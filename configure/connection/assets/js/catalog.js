$ = jQuery;
$(function ($) {
    let filter = $('.alba-filter-item'),
        sorter = $('.alba-sort-item'),
        filterDropdown = $('.filter-dropdown');

        function isDesktop() {
            return $(window).width() > 990;
        }

    filterDropdown.click(function (e) {
        let sorterToggler = $('#title-sort'),
            filterToggler = $('#title-filter');
            function isMenuOpened(menuToggler) {
              return menuToggler.hasClass('active-filter')
            }

        $(this).toggleClass('active-filter');
        $("body").addClass("scroll-body");
        $('#jas-header').addClass('dark-content');

        if(isDesktop()) {
            if($(e.target).closest(filterDropdown).hasClass('sorter')) {
                isMenuOpened(sorterToggler) ?
                    sorter.css({'transform': 'translateY(0)'}) :
                    sorter.css({'transform': 'translateY(100%)'});
            }
            if($(e.target).closest(filterDropdown).hasClass('filter')) {
                isMenuOpened(filterToggler) ?
                    filter.css({'transform': 'translateY(0)'}) :
                    filter.css({'transform': 'translateY(100%)'})
            }
        } else {
            if($(e.target).closest(filterDropdown).hasClass('sorter')){
                isMenuOpened(sorterToggler) ?
                    sorter.css({  'transform': 'translateX(0)'}):
                    sorter.css({'transform': 'translateX(-100%)'});

            }
            if($(e.target).closest(filterDropdown).hasClass('filter')) {
                isMenuOpened(filterToggler) ?
                    filter.css({'transform': 'translateX(0)'}):
                    filter.css({'transform': 'translateX(-100%)'});
            }
        }
    });

    function onFilterSortClose() {
        filterDropdown.siblings().removeClass('active-filter');
        $('#jas-header').removeClass('dark-content');
        $("body").removeClass("scroll-body");
    }
    $('.cross-filter').click(function () {
        onFilterSortClose();

        isDesktop() ?
            filter.css({ 'transform': 'translateY(100%)' }):
            filter.css({ 'transform': 'translateX(-100%)'});
    });

    $('.mobile-sort-close').click(function () {
        onFilterSortClose();

        isDesktop() ?
            sorter.css({ 'transform': 'translateY(100%)' }):
            sorter.css({'transform': 'translateX(-100%)'});
    });

    let n1 = 0;
    let n2 = 0;
    let n3 = 0;
    let n4 = 0;
    let n5 = 0;
    let data_slug_type = [];
    let data_slug_color = [];
    let data_slug_size = [];
    let data_slug_material = [];
    let data_slug_gender = [];

    $(".filter-typeproduct").click(function () {
        const $this = $(this);
        if ($this.hasClass('checked-type')) {
            const dataSlug = $this.attr("data-slug");
            const index = data_slug_type.indexOf(dataSlug);
            if (index !== -1) {
                data_slug_type.splice(index, 1);
            }
            $(".number-attr-1").text(--n1);
            $this.removeClass('checked-type');
            $this.removeAttr('data-checked', 'checked');
        } else {
            const dataSlug = $this.attr("data-slug");
            data_slug_type.push(dataSlug);
            $(".number-attr-1").text(++n1);
            $this.addClass('checked-type');
            $this.attr('data-checked', 'checked');
        }
    });

    $(".filter-color").click(function () {
        const $this = $(this);
        if ($this.hasClass('checked-color')) {
            const dataSlug = $this.attr("data-slug");
            const index = data_slug_color.indexOf(dataSlug);
            if (index !== -1) {
                data_slug_color.splice(index, 1);
            }
            $(".number-attr-2").text(--n2);
            $this.removeClass('checked-color');
            $this.removeAttr('data-checked', 'checked');
        } else {
            const dataSlug = $this.attr("data-slug");
            data_slug_color.push(dataSlug);
            $(".number-attr-2").text(++n2);
            $this.addClass('checked-color');
            $this.attr('data-checked', 'checked');
        }
    });

    $(".filter-size").click(function () {
        const $this = $(this);
        if ($this.hasClass('checked-size')) {
            const dataSlug = $this.attr("data-slug");
            const index = data_slug_size.indexOf(dataSlug);
            if (index !== -1) {
                data_slug_size.splice(index, 1);
            }
            $(".number-attr-3").text(--n3);
            $this.removeClass('checked-size');
            $this.removeAttr('data-checked', 'checked');
        } else {
            const dataSlug = $this.attr("data-slug");
            data_slug_size.push(dataSlug);
            $(".number-attr-3").text(++n3);
            $this.addClass('checked-size');
            $this.attr('data-checked', 'checked');
        }
    });

    $(".filter-material").click(function () {
        if ($(this).hasClass('checked-material')) {
            $(".number-attr-4").text((--n4));
            $(this).removeClass('checked-material');
            $(this).removeAttr('data-checked', 'checked');
            data_slug_material = [];
        } else {
            $(".number-attr-4").text((++n4));
            $(this).addClass('checked-material');
            $(this).attr('data-checked', 'checked');
            let material_product = $('.result-material').find('[data-checked=checked]')
            $(material_product).each(function (i) {
                data_slug_material[i] = $(this).attr("data-slug");
            });
        }
    });

    $(".filter-gender").click(function () {
        const $this = $(this);
        if ($this.hasClass('checked-gender')) {
            const dataSlug = $this.attr("data-slug");
            const index = data_slug_gender.indexOf(dataSlug);
            if (index !== -1) {
                data_slug_gender.splice(index, 1);
            }
            $(".number-attr-5").text(--n5);
            $this.removeClass('checked-gender');
            $this.removeAttr('data-checked', 'checked');
        } else {
            const dataSlug = $this.attr("data-slug");
            data_slug_gender.push(dataSlug);
            $(".number-attr-5").text(++n5);
            $this.addClass('checked-gender');
            $this.attr('data-checked', 'checked');
        }
    });

    $(".remove-filters").click(function () {
        location.reload();
        $(".filter-color").removeAttr('data-checked', 'checked').removeClass('checked-color');
        $(".filter-size").removeAttr('data-checked', 'checked').removeClass('checked-size');
        $(".filter-typeproduct").removeAttr('data-checked', 'checked').removeClass('checked-type');
        $(".filter-material").removeAttr('data-checked', 'checked').removeClass('checked-material');
        $(".filter-gender").removeAttr('data-checked', 'checked').removeClass('checked-gender');
        $(".products").removeClass('filter-resual-products');
        $('#jas-header').removeClass('dark-content');
        $(".response").empty();
    });

    $(".apply-filters").click(function () {
        let category_id = $('.alba-filter-content').attr("data-category");
        if ($(".title-filters").hasClass('sticky-filter')) {
            $(".title-filters").removeClass('sticky-filter');
        }
        jQuery.ajax({
            url: filter_form.url,
            type: 'post',
            data: {
                action: "filter_form",
                data_slug_size: data_slug_size,
                data_slug_color: data_slug_color,
                data_slug_type: data_slug_type,
                data_slug_gender: data_slug_gender,
                data_slug_material: data_slug_material,
                category_id: category_id
            },
            success: function (data) {
                onFilterSortClose();
                $(".response").html(data);
                $("html:not(:animated),body:not(:animated)").animate({scrollTop: $('.filter-product')}, 800);
                $(".site-main").toggleClass('filter-resual-full');
                $(".products").addClass('filter-resual-products');

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





