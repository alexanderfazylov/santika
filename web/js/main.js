/**
 * Товар на интерактивной фотографии
 * @param options
 * @param data_index
 * @constructor
 */
function InteractivePoint(options, data_index) {
    var $owner = $('#blueimp-gallery').find('.slide[data-index="' + data_index + '"]');
    var $image = $owner.find('img');
    var image_position = $image.position();
    var that = {};//this;
    that.id = options.id;
    that.product_id = options.product_id;
    that.product_name = options.product_name;
    that.left_percent = options.left_percent;
    that.left = image_position.left + $image.width() * that.left_percent / 100;
    that.top_percent = options.top_percent;
    that.top = image_position.top + $image.height() * that.top_percent / 100;
    /**
     * @TODO обьновлять положение точек при изменении размера окна
     */
    var $div = $('<div></div>')
            .attr('id', 'point_' + that.id)
//            .attr('class', 'point')
            .attr('class', 'pop')
            .css('left', that.left)
            .css('top', that.top)
        ;
    /**
     * @TODO доделать fabcybox
     */
    $div.data('fancybox-href', '#quick');
    $div.hover(function () {
        updateFancyBox(options.product_id, options.line_url);
    });
    $div.fancybox({
        beforeShow: function () {
            $('#blueimp-gallery').find('.play-pause')[0].click();
        },
        afterClose: function () {
            $('#blueimp-gallery').find('.play-pause')[0].click();
        },
        overlayColor: '#000'
    });
    $owner.append($div);
}
/**
 * Отображение инфы о товаре на интерактивной фотографии
 * @param product
 */
var product_info_cache = [];

function updateFancyBox(product_id, line_url) {
    if (product_id in product_info_cache) {
        setFancyBoxData(product_info_cache[product_id]);
    } else {
        $.ajax({
            url: '/catalog/product-info',
            type: "GET",
            dataType: "json",
            data: {
                product_id: product_id,
                line_url: line_url
            },
            success: function (data) {
                if (data.status == 'success') {
                    product_info_cache[product_id] = data.product;
                    setFancyBoxData(data.product);
                } else {

                }
            }
        });
    }
}
function setFancyBoxData(product) {
    var $owner = $('.product-info');
    $owner.find('.product-photo').attr('src', product.photo);
    $owner.find('.product-link').attr('href', product.link);
    $owner.find('.product-name').text(product.name);
    $owner.find('.product-article').text(product.article);
    $owner.find('.product-lwh').text(product.lwh);
    $owner.find('.product-color').text(product.color);
    $owner.find('.product-description').text(product.description);
    $owner.find('.product-price').text(product.price);
    $owner.find('.product-country').text(product.country);
    $owner.find('.product-installation').text(product.installation);
}

/**
 * анимация на странице
 */
$(function () {
    $('.app-index #slideshow').fadeSlideShow({
        height: 460,
        interval: 9000,
        autoplay: true
    });
});

/**
 * листалка товаров на главной странице
 */
$(function () {
    $(".app-index .b-carusel").each(function () {
        $(this).find('> .gallery').jCarouselLite({
            btnNext: $(this).find('> .nav .next')[0],
            btnPrev: $(this).find('> .nav .prev')[0],
            visible: 10
        });
    });
});

/**
 * select'ы на странице каталога
 */
$(function () {
    $("select").selectbox();
});


/**
 * Интерьерные фото
 */
$(function () {
    $("a.pop").fancybox({
        overlayColor: '#000'
    });
});

$(function () {
    $('.app-collection #slideshow').fadeSlideShow({
        interval: 9000,
        autoplay: true,
        NextElementText: 'Далее',
        PrevElementText: 'Назад'
    });
});


/**
 *  листалка на странице товара
 */
$(function () {
    $(".app-product .b-carusel").each(function () {
        $(this).find('> .gallery').jCarouselLite({
            btnNext: $(this).find('> .nav .next')[0],
            btnPrev: $(this).find('> .nav .prev')[0],
            visible: 5
        });
    });
});
/**
 *  листалка на странице товара
 */
//$(function () {
//    $('.app-product #slideshow').fadeSlideShow({
//        height: 460,
//        interval: 9000,
//        autoplay: true
//    });
//});

/**
 * Отображение меню
 */
$(function () {
    $('.menu').click(function () {
        $('.b-panel-r').toggleClass('menu-visible');
        return false;
    });
});


/**
 * Выпадающее меню на странице товаров линии
 */
$(function () {
    $(".app-rzd .b-rzd__menu .gallery").each(function () {
        var visible = 7;
        var circular = true;
        if ($(this).find('li').size() < visible) {
            visible = $(this).find('li').size();
            circular = false;
            $(this).find('> .nav').addClass('hidden');
        }
        $(this).parents('.b-carusel').addClass('preload-carusel');
        $(this).jCarouselLite({
            btnNext: $(this).find('> .nav .next')[0],
            btnPrev: $(this).find('> .nav .prev')[0],
            visible: visible,
            circular: circular
        });
        $(this).parents('.b-carusel').removeClass('preload-carusel');
    });

    $(document).on('mouseleave', '.b-rzd__menu', function () {
        //скрываем все меню
        $('.b-rzd__menu .b-carusel').addClass('hidden');
        $('.b-rzd__menu .active').removeClass('active');
    });
    $(document).on('mouseenter', '.b-rzd__menu li', function () {
        //отображаем дочерние элементы  выбранного пункта меню

        var object_type = $(this).attr('object_type');
        var object_id = $(this).attr('object_id');
        var hide_level = $(this).attr('hide_level');

        $(this).siblings().removeClass('active');
        //т.к. из-за листалки у нас будет несколько клонгов элемента, то отметим их все активными
        $(this).parent().find('[object_type="' + object_type + '"][object_id="' + object_id + '"]').addClass('active');

        $('.' + hide_level).addClass('hidden');
        $('div[parent_type="' + object_type + '"][parent_id="' + object_id + '"]').removeClass('hidden');
    });
});