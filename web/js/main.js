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
    $div.data('fancybox-href','#quick');
    $div.hover(function () {
        showProductInfo(options);
    });
    $div.fancybox({
        overlayColor : '#000'
    });
    $owner.append($div);
}
/**
 * Отображение инфы о товаре на интерактивной фотографии
 * @param product
 */
function showProductInfo(product) {
    var $owner = $('.product-info');
    $owner.find('.product-photo').attr('src', product.photo);
    $owner.find('.product-link').attr('href', product.link);
    $owner.find('.product-name').text(product.name);
    $owner.find('.product-article').text(product.article);
    $owner.find('.product-lwh').text(product.lwh);
    $owner.find('.product-color').text(product.color);
    $owner.find('.product-description').text(product.description);
//    $('#quick').removeClass('hidden');
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
    $(".app-index .gallery").jCarouselLite({
        //mouseWheel: true,
        btnNext: ".next",
        btnPrev: ".prev",
        visible: 10
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
    $(".app-product .gallery").jCarouselLite({
        btnNext: ".next",
        btnPrev: ".prev",
        visible: 5
    });
});
/**
 *  листалка на странице товара
 */
$(function () {
    $('.app-product #slideshow').fadeSlideShow({
        height: 460,
        interval: 9000,
        autoplay: true
    });
});

/**
 * Отображение меню
 */
$(function () {
    $('.menu').click(function(){
        $('.b-panel-r').toggleClass('menu-visible');
        return false;
    });
});