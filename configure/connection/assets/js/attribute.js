jQuery(document).ready(function ($) {
    let colors;

    function initCheckedColor() {
        if (Cookies.get('name') === undefined) {
            console.log('yes');
        } else {
            colors = Cookies.get('name').split(',');
            if (Array.isArray(colors)) {
                colors.forEach(function (color) {
                    $('.result-color .filter-color').each(function () {
                        if (color === $(this).attr("data-slug")) {
                            $(this).addClass('checked-color');
                        }
                    });
                })
            } else {
                colors = Cookies.get('name');
                $('.result-color .filter-color').each(function () {
                    if (colors === $(this).attr("data-slug")) {
                        $(this).addClass('checked-color');
                    }
                });
            }
        }
    }

    function removeArrDuplicates(array) {
        const uniqueArr = [];

        for (let i = 0; i < array.length; i++) {
            if (uniqueArr.indexOf(array[i]) === -1) {
                uniqueArr.push(array[i]);
            }
        }
        return uniqueArr;
    }

    initCheckedColor();
    let attrs = ['typeproduct', 'color', 'gender', 'size'];
    attrs.forEach(function (attr) {
        onClickAttr(attr);
    });
    let attr_product = [];

    function onClickAttr(attribute) {
        let element = '.filter-' + attribute,
            checked = 'checked-' + attribute + '-2';

        $(element).click(function (e) {
            e.stopPropagation();
            e.preventDefault();
            if ($(this).hasClass(checked)) {
                let current = $(this);
                current.removeClass(checked);
                attrs.forEach(function (attr) {
                    let element = '.filter-' + attr;
                    if (attr !== attribute) {
                        $(element).show();
                        attr_product = attr_product.filter(function (item) {
                            return item.type !== current.attr("data-slug");
                        })
                        console.log(attr_product)
                    } else {
                        let element = '.result-' + attr;
                        $(element).removeAttr('data-checked', 'checked');
                    }
                });
            } else {
                $(this).addClass(checked);
                let element = '.result-' + attribute;
                $(element).find('[data-checked=checked]').each(function (i) {
                    let item = {
                        attribute: attribute,
                        type: $(this).attr("data-slug"),
                    }
                    attr_product.push(item);
                });
            }

            jQuery.ajax({
                url: attribute_form.url,
                type: 'post',
                dataType: 'json',
                data: {
                    action: "attribute_form",
                    attr_product: attr_product
                },
                success: function (data) {
                    if (data.length === 0) {
                        attrs.forEach(function (attr) {
                            let element = '.filter-' + attr;
                                $(element).show();
                        });
                    } else {
                        attrs.forEach(function (attr) {
                            let element = '.filter-' + attr,
                                checked = 'checked-' + attr + '-2';
                            if (attr !== attribute ) {
                                $(element).hide();
                            }
                            data.forEach(function (key) {
                                attrs.forEach(function (attr) {

                                    let elementClass = '.filter-' + attr;
                                    if (attr !== attribute) {
                                        $(elementClass).hide();
                                    }
                                    $(elementClass).each(function (index, el) {
                                        if (key[attr] === $(el).attr("data-slug") ) {
                                            $(el).show(data);
                                        }
                                    });
                                })
                            });
                        });
                    }
                    initCheckedColor();
                },
                error: function (errorThrown) {
                    console.log(errorThrown);
                }
            });
        });
    }
});
