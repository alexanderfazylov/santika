/**
 * Created by KURT on 20.05.14.
 */
$(document).on('change', '#linecategory-line_id', function () {
    var shop_id = $('#linecategory-shop_id').val();
    var line_category_id = $(this).attr('line_category_id');
    $.ajax({
        url: '/admin/line-category/category-without-line',
        type: "POST",
        dataType: "json",
        data: {
            shop_id: shop_id,
            line_category_id: line_category_id
        },
        success: function (data) {
            if (data.status == 'success') {
                var category_select = $('#linecategory-category_id');
                category_select.find('option').remove();
                $.each(data.categories, function (index, category) {
                    var option = $('<option>').val(category.id).text(category.name);
                    option.appendTo(category_select);
//                    $('<option>').val('999').text('999').appendTo(category_select);
                })
            }
        }
    })
});


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
                category_select.find('option').remove();
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