/**
 * Created by KURT on 20.05.14.
 */

/**
 * интреактивное фото НАЧАЛО
 */
/**
 * @TODO подумать над видимостью переменной
 */
var pointCollection = new InteractiveCollection();

function InteractiveCollection() {
    var points = [];

    this.addArray = function (points) {
        $.each(points, function (index, point_array) {
            var point = InteractivePoint(point_array);
            pointCollection.add(point);

        })
    };
    this.add = function (point) {
        points.push(point);
        var array_index = points.indexOf(point);
        $delete = $('<a></a>')
            .attr('href', '#')
            .addClass('interactive-remove-product')
            .text('Удалить');
        $label = $('<label></label>')
            .text(point.product_name);
        $div = $('<div></div>')
            .attr('array_index', array_index);
        $div.append($label).append($delete);
        $('.interactive-products').append($div);
    };

    this.remove = function (array_index) {
        var point = points[array_index];
        point.object.remove();
        $('div[array_index="' + array_index + '"]').remove();
        delete points[array_index];
    };

    this.getPoints = function () {
        return points;
    };
    this.getSaveData = function () {
        var data = [];
        $.each(points, function (index, point) {
            var poin_array = [];
            poin_array['id'] = point.id;
            poin_array['interactive_id'] = $('#interactive-id').val();
            poin_array['product_id'] = point.product_id;
            poin_array['left'] = point.getLeftPercent();
            poin_array['top'] = point.getTopPercent();
            poin_array['id'] = point.id;
            data.push(poin_array);
        });
        return data;
    };
}
function InteractivePoint(options) {
    var $owner = $('.interactive-owner');
    var that = {};//this;
    that.id = options.id;
    that.product_id = options.product_id;
    that.product_name = options.product_name;
    that.left_percent = options.left_percent;
    that.left = $owner.width() * that.left_percent / 100;
    that.top_percent = options.top_percent;
    that.top = $owner.height() * that.top_percent / 100;

    var $div = $('<div></div>')
            .attr('id', 'point_' + that.id)
            .attr('class', 'point')
            .css('left', that.left)
            .css('top', that.top)
            .css('background-color', 'red')
        ;

    $owner.append($div);
    that.object = $div;
    that.object.draggable({
            containment: $owner,
            start: function (event, ui) {
                $('.product-name').text(that.product_name);
            },
            stop: function (event, ui) {
//                var left_percent = 100 * ui.position.left / $owner.width();
//                var top_percent = 100 * ui.position.top / $owner.height();
//                $('.left-percent').text(left_percent);
//                $('.top-percent').text(top_percent);
            }
        }
    );
    that.object.click(function () {
        $('.product-name').text(that.product_name);
    });

    that.getObject = function () {
        return that.object;
    };
    that.getLeftPercent = function () {
        return  100 * that.object.position().left / $owner.width();
    };

    that.getTopPercent = function () {
        return  100 * that.object.position().top / $owner.height();
    };


    return that;
}

$(document).on('click', '.interactive-add-product', function () {
    var $select = $('#interactive_product-product_id');
    var $selected = $select.find(':selected');
    if ($selected.val() == "") {
        return;
    }
    var point = InteractivePoint({
        id: null,
        product_id: $selected.val(),
        product_name: $selected.text(),
        left_percent: 50,
        top_percent: 50
    });
    pointCollection.add(point);
    $select.val("");
});

$(document).on('click', '.interactive-remove-product', function () {
    var array_index = $(this).parent().attr('array_index');
    pointCollection.remove(array_index);
});

function getInteractiveProducts(interactive_id) {
    $.ajax({
        url: '/admin/interactive/get-products',
        type: "POST",
        dataType: "json",
        data: {
            interactive_id: interactive_id
        },
        success: function (data) {
            if (data.status == 'success') {
                pointCollection.addArray(data.products);
            }
        }
    });
}

$(document).on('click', '.save-interactive-product', function () {
    var points = pointCollection.getSaveData();
    $.ajax({
        url: '/admin/interactive/save-products',
        type: "POST",
        dataType: "json",
        data: {
            points: points
        },
        success: function () {

        }
    });
    return false;
});

/**
 * интреактивное фото КОНЕЦ
 */

/**
 * Выводит сообщения об ошибках
 * data.message - одна ошибка
 * data.messages - массив ошибок
 * @param data
 */
function alertMessages(data) {

    if (typeof data.message != "undefined") {
        alert(data.message);
    }
    if (typeof data.messages != "undefined") {
        $.each(data.messages, function (index, message) {
            alert(message);
        });
    }
}

/**
 * Сохраняет фото товара из временной папки
 * @param product_id
 * @param upload_tmp
 * @param upload_name
 */
function savePhoto(product_id, upload_tmp, upload_name) {
    $.ajax({
        url: '/admin/product/add-photo',
        type: "POST",
        dataType: "json",
        data: {
            product_id: product_id,
            upload_tmp: upload_tmp,
            upload_name: upload_name
        },
        success: function (data) {
            if (data.status == 'success') {
                $li = $('<li></li>').html(data.img);
                $('#photo_sorter').append($li);
            } else {
                alertMessages(data);
            }
        }
    });
}

/* редактоирование товара */
$(document).on('change', '#product-line_ids', function () {
    var shop_id = $('#product-shop_id').val();
    var line_ids = $(this).val();

    $.ajax({
        url: '/admin/category/by-line-ids',
        type: "POST",
        dataType: "json",
        data: {
            shop_id: shop_id,
            line_ids: line_ids
        },
        success: function (data) {
            if (data.status == 'success') {
                var category_select = $('#product-category_id');
                category_select.find('option[value!=""]').remove();
                $.each(data.categories, function (index, category) {
                    $('<option>')
                        .val(category.id)
                        .text(category.name)
                        .appendTo(category_select);
                })
            }
        }
    })
});
/* редактоирование категории */
$(document).on('change', '#category-line_ids', function () {
    var shop_id = $('#category-shop_id').val();
    var line_ids = $(this).val();
    var skip_id = $(this).attr('skip_id');

    $.ajax({
        url: '/admin/category/by-line-ids',
        type: "POST",
        dataType: "json",
        data: {
            shop_id: shop_id,
            line_ids: line_ids,
            skip_id: skip_id
        },
        success: function (data) {
            if (data.status == 'success') {
                var parent_select = $('#category-parent_id');
                parent_select.find('option[value!=""]').remove();
                $.each(data.categories, function (index, category) {
                    $('<option>')
                        .val(category.id)
                        .text(category.name)
                        .appendTo(parent_select);
                })
            }
        }
    })
});

/**
 * Функционал редактирования стоимости товаров НАЧАЛО
 */
$(document).on('click', '.edit-product_price', function () {
    $(this).parents('tr').addClass('tr-edit');
});

$(document).on('click', '.cancel-product_price', function () {
    $(this).parents('tr').removeClass('tr-edit');
});

$(document).on('click', '.save-product_price', function () {
    var $tr = $(this).parents('tr');
    var price_id = $('#price_id').val();
    var product_id = $tr.data('product_id');
    var cost_eur = $tr.find('input[name="cost_eur"]').val();
    $.ajax({
        url: '/admin/price-product/save-ajax',
        type: "POST",
        dataType: "json",
        data: {
            price_id: price_id,
            product_id: product_id,
            cost_eur: cost_eur
        },
        success: function (data) {
            if (data.status == 'success') {
                $tr.removeClass('tr-edit');
                $tr.find('.cost_eur').text(cost_eur);
                $tr.find('.cost_rub').text(data.cost_rub);
            } else {
                alertMessages(data);
            }
        }

    });
});

$(document).on('click', '.delete-product_price', function () {
    var $tr = $(this).parents('tr');
    var price_id = $('#price_id').val();
    var product_id = $tr.data('product_id');
    $.ajax({
        url: '/admin/price-product/delete-ajax',
        type: "POST",
        dataType: "json",
        data: {
            price_id: price_id,
            product_id: product_id
        },
        success: function (data) {
            if (data.status == 'success') {
                $tr.removeClass('tr-edit');
                $tr.find('.cost_eur').text('0.00');
                $tr.find('.cost_rub').text('0.00');
                $tr.find('input[name="cost_eur"]').val('0.00');
            } else {
                alertMessages(data);
            }
        }

    });
});

/**
 * Функционал редактирования стоимости товаров КОНЕЦ
 */

/**
 * Функционал для работы с фотогалереей НАЧАЛО
 */

$(document).on('click', '.delete-photo_gallery', function () {
    var $li = $(this).parents('li');
    var $div = $li.find('> div');
    var photo_gallery_id = $div.attr('photo_gallery-id');
    $.ajax({
        url: '/admin/product/delete-photo',
        type: "POST",
        dataType: "json",
        data: {
            photo_gallery_id: photo_gallery_id
        },
        success: function (data) {
            if (data.status == 'success') {
                $li.remove();
            } else {
                alertMessages(data);
            }
        }

    });
});

$(document).on('click', '.save-photo_gallery_sort', function () {
    var sort_index = 0;
    var sort = [];
    var $divs = $('#photo_sorter').find('> li > div');
    $.each($divs, function (index, element) {
        sort[$(element).attr('photo_gallery-id')] = sort_index;
        sort_index++;
    });
    $.ajax({
        url: '/admin/product/save-photo-sort',
        type: "POST",
        dataType: "json",
        data: {
            sort: sort
        },
        success: function (data) {
            if (data.status == 'success') {
                alert('Сохранено');
            } else {
                alertMessages(data);
            }
        }

    });
});

/**
 * Функционал для работы с фотогалереей КОНЕЦ
 */