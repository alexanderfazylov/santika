/**
 * Created by KURT on 20.05.14.
 */
$(document).on('change', '#linecategory-line_id', function () {
    var shop_id = $('#linecategory-shop_id').val();
    var line_id = $(this).val();
    $.ajax({
        url: '/admin/linecategory/category-without-line',
        type: "POST",
        dataType: "json",
        data: {
            shop_id: shop_id,
            line_id: line_id
        },
        success: function (data) {
            if (data.status == 'success') {
                var line_select = $('#linecategory-line_id');
                line_select.find('option').remove();
                $.each(data.categories, function (index, category) {
                    var option = $('<option>').val(category.id).text(category.name);
                    option.appendTo(line_select);
                })
            }
        }
    })
});