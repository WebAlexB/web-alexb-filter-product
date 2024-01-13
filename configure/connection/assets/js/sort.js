$(function ($) {
    let filter = $('.alba-filter-item'),
        sorter = $('.alba-sort-item');
    function isDesktop() {``
        return $(window).width() > 990;
    }

    $(".title-sort").click(function () {
        let category_id = $('.sort-action').attr("data-category");
        let sort = '';

        $(this).siblings().removeClass('checked-sort').removeAttr('data-checked', 'checked');
        $(this).toggleClass('checked-sort');

        $(this).hasClass('checked-sort') ? sort = $(this).attr("data-slug") : sort = '';

        $.ajax({
            url: sort_form.url,
            type: 'post',
            data: {
                action: "sort_form",
                sort: sort,
                category_id: category_id
            },
            success: function (data) {
                $(".response").html(data);
                $("html:not(:animated),body:not(:animated)").animate({scrollTop: $('.filter-product')}, 800);
                $('.filter-dropdown').siblings().removeClass('active-filter');
                $(".products").addClass('filter-resual-products');
                $('#jas-header').removeClass('dark-content');
                $("body").removeClass("scroll-body");

                isDesktop() ?
                    filter.css({'transform': 'translateY(100%)'}):
                    filter.css({'transform': 'translateX(-100%)'});

                isDesktop() ?
                    sorter.css({'transform': 'translateY(100%)'}):
                    sorter.css({'transform': 'translateX(-100%)'});
            },
            error: function (error) {
                console.log(error);
            }
        });
    });
});




