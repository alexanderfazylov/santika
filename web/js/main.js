/**
 * Created by KURT on 20.05.14.
 */
//$(document).on('change', '#linecategory-line_id', function () {
//    var shop_id = $('#linecategory-shop_id').val();
//    var line_category_id = $(this).attr('line_category_id');
//    $.ajax({
//        url: '/admin/line-category/category-without-line',
//        type: "POST",
//        dataType: "json",
//        data: {
//            shop_id: shop_id,
//            line_category_id: line_category_id
//        },
//        success: function (data) {
//            if (data.status == 'success') {
//                var category_select = $('#linecategory-category_id');
//                category_select.find('option').remove();
//                $.each(data.categories, function (index, category) {
//                    var option = $('<option>').val(category.id).text(category.name);
//                    option.appendTo(category_select);
////                    $('<option>').val('999').text('999').appendTo(category_select);
//                })
//            }
//        }
//    })
//});


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

$(document).on('change', '.datepicker-with-alt', function (e) {
    $('#toField').val(e.format('mm/dd/yyyy'));
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
                if (typeof data.message != "undefined") {
                    alert(data.message);
                }
                if (typeof data.messages != "undefined") {
                    $.each(data.messages, function (index, message) {
                        alert(message);
                    });
                }
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
                if (typeof data.message != "undefined") {
                    alert(data.message);
                }
                if (typeof data.messages != "undefined") {
                    $.each(data.messages, function (index, message) {
                        alert(message);
                    });
                }
            }
        }

    });
});


/**
 * Функционал редактирования стоимости товаров КОНЕЦ
 */