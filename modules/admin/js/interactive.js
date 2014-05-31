/**
 * интреактивное фото НАЧАЛО
 */
var pointCollection = new InteractiveCollection();

function InteractiveCollection() {
    var points = [];
    var deleted_ids = [];

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
        $div.append($label).append(' ').append($delete);
        $('.interactive-products').append($div);
    };

    this.remove = function (array_index) {
        var point = points[array_index];
        point.object.remove();
        $('div[array_index="' + array_index + '"]').remove();
        if (point.id != null) {
            deleted_ids.push(point.id);
        }
        delete points[array_index];
    };

    this.getPoints = function () {
        return points;
    };
    this.getDeletedIds = function () {
        return deleted_ids;
    };
    this.getSaveData = function () {
        var data = [];
        $.each(points, function (index, point) {
            if (typeof point == 'undefined') {
                return;
            }
            var poin_array = {};
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
    this.clear = function () {
        points = [];
        deleted_ids = [];
        $('.interactive-products').html('');
        $('.interactive-owner').find('.point').remove();
    }
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
            .attr('title', that.product_name)
        ;

    $owner.append($div);
    that.object = $div;
    that.object.draggable({
            containment: $owner,
            start: function (event, ui) {
//                $('.product-name').text(that.product_name);
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
//        $('.product-name').text(that.product_name);
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

function refreshInteractiveProducts() {
    var interactive_id = $('#interactive-id').val();

    $.ajax({
        url: '/admin/interactive/get-products',
        type: "POST",
        dataType: "json",
        data: {
            interactive_id: interactive_id
        },
        success: function (data) {
            if (data.status == 'success') {

                pointCollection.clear();
                pointCollection.addArray(data.products);
            }
        }
    });
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

$(document).on('click', '.save-interactive-product', function () {
    var points = pointCollection.getSaveData();
    var deleted_ids = pointCollection.getDeletedIds();
    if (points.length == 0 && deleted_ids.length == 0) {
        alert('Нет данных для сохранения');
        return false;
    }
    $.ajax({
        url: '/admin/interactive/save-products',
        type: "POST",
        dataType: "json",
        data: {
            InteractiveProduct: points,
            InteractiveProductDeleted: deleted_ids
        },
        success: function (data) {

            if (data.status == 'success') {
                alert('Сохранено');
                refreshInteractiveProducts();
            } else {
                alertMessages(data);
            }
        }
    });
    return false;
});

/**
 * интреактивное фото КОНЕЦ
 */
