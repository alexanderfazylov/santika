/**
 * Created by KURT on 20.05.14.
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
 * @param color_id
 * @param upload_tmp
 * @param upload_name
 */
function savePhoto(product_id, color_id, upload_tmp, upload_name) {
    $.ajax({
        url: '/admin/product/add-photo',
        type: "POST",
        dataType: "json",
        data: {
            product_id: product_id,
            color_id: color_id,
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
    return false;
});

$(document).on('click', '.cancel-product_price', function () {
    $(this).parents('tr').removeClass('tr-edit');
    return false;
});

$(document).on('click', '.save-product_price', function () {
    var $tr = $(this).parents('tr');
    var price_id = $('#price_id').val();
    var product_id = $tr.data('product_id');
    var color_id = $tr.data('color_id');
    var cost_eur = $tr.find('input[name="cost_eur"]').val();
    $.ajax({
        url: '/admin/price-product/save-ajax',
        type: "POST",
        dataType: "json",
        data: {
            price_id: price_id,
            product_id: product_id,
            color_id: color_id,
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
    return false;
});

$(document).on('click', '.delete-product_price', function () {
    var $tr = $(this).parents('tr');
    var price_id = $('#price_id').val();
    var product_id = $tr.data('product_id');
    var color_id = $tr.data('color_id');
    $.ajax({
        url: '/admin/price-product/delete-ajax',
        type: "POST",
        dataType: "json",
        data: {
            price_id: price_id,
            product_id: product_id,
            color_id: color_id
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
    return false;
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
    return false;
});

$(document).on('click', '.save-photo_gallery_sort', function () {
    var sort_index = 0;
    var sort = {};
    var $divs = $('#photo_sorter').find('> li > div');
    $.each($divs, function (index, element) {
        var id = $(element).attr('photo_gallery-id');
        sort[id] = sort_index;
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
    return false;
});

/**
 * Функционал для работы с фотогалереей КОНЕЦ
 */

/**
 * Функционал для работы с загрузчиком файлов НАЧАЛО
 */
$(document).on('click', '.delete-tmp-file', function () {
    $(this).siblings('.tmp-file-path').val('');
    $(this).siblings('.tmp-file-name').val('');
    $(this).siblings('.tmp-file').html('');
    $(this).addClass('hidden');
    return false;
});
$(document).on('click', '.delete-uploaded-file', function () {
    $(this).siblings('.uploaded-file-id').val('');
    $(this).siblings('.uploaded-file').html('');
    $(this).addClass('hidden');
    return false;
});
/**
 * Функционал для работы с загрузчиком файлов КОНЕЦ
 */


/**
 * Специальное удаление, с провкеркой ajax ответа НАЧАЛО
 */
$(document).on('click', '.custom-delete', function () {
    var url = $(this).attr('href');
    $.ajax({
        url: url,
        dataType: 'json',
        type: 'post',
        data: {},
        success: function (data) {
            if (data.status == 'success') {
                window.location.reload();
            } else {
                alertMessages(data);
            }
        }
    });
    return false;
});
/**
 * Специальное удаление, с провкеркой ajax ответа КОНЕЦ
 */