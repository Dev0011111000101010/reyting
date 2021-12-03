var $ = jQuery;

jQuery(function ($) {

    const autoCompleteJS = new autoComplete({
        selector: ".sf-field-search",
        placeHolder: "Search for Food...",
        data: {
            src: [
                { "food": "Sauce - Thousand Island" },
                { "food": "Wild Boar - Tenderloin" },
                { "food": "Goat - Whole Cut" }
            ],
            keys: ["food"],
            cache: true
        },
        resultsList: {
            element: (list, data) => {
                if (!data.results.length) {
                    // Create "No Results" message element
                    const message = document.createElement("div");
                    // Add class to the created element
                    message.setAttribute("class", "no_result");
                    // Add message text content
                    message.innerHTML = `<span>Результатів не знайдено<span>`;
                    // Append message element to the results list
                    list.prepend(message);
                }
            },
            noResults: true,
        },
        resultItem: {
            highlight: {
                render: true
            }
        }
    });

    $('.sf-field-search').addEventListener("selection", function (event) {
        // "event.detail" carries the autoComplete.js "feedback" object
        const feedback = event.detail.query;

        $.ajax({
            type: "POST",
            url: ajaxurl,
            data: {
                action : 'save_this_search',
                search_name: feedback
            },
            success: function(data) {
                console.log(data);
            }
        });
    });

    $('body').on('click', function (e) {
        if (!$('.home-filter.visible').length || $(e.target).closest('#filter-btn').length) return;

        if ($(e.target).closest('.home-filter.visible').length === 0) {
            console.log('hide filter');
            hideFilter();
        }

    })

    $('#filter-btn').on('click', function () {
        $(".home-filter").toggleClass('visible');
    })

    $('.sf-field-tag>h4').append('<span onclick="hideFilter();" class="filter-close"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"><path d="M193.94 256L296.5 153.44l21.15-21.15c3.12-3.12 3.12-8.19 0-11.31l-22.63-22.63c-3.12-3.12-8.19-3.12-11.31 0L160 222.06 36.29 98.34c-3.12-3.12-8.19-3.12-11.31 0L2.34 120.97c-3.12 3.12-3.12 8.19 0 11.31L126.06 256 2.34 379.71c-3.12 3.12-3.12 8.19 0 11.31l22.63 22.63c3.12 3.12 8.19 3.12 11.31 0L160 289.94 262.56 392.5l21.15 21.15c3.12 3.12 8.19 3.12 11.31 0l22.63-22.63c3.12-3.12 3.12-8.19 0-11.31L193.94 256z"/></svg></span>')

    $(document).on("sf:ajaxfinish", ".searchandfilter", function () {
        console.log("ajax complete");
        $('.sf-level-0').each(function () {
            toggleHasChildren($(this));
        })
    });

    $('.sf-level-0').each(function () {
        toggleHasChildren($(this));
    })

    $('body').on('mouseover', '.sf-level-0', function () {
        if (window.matchMedia('(min-width: 981px)').matches) {
            $(this).addClass('active')
        }
    })
    $('body').on('mouseout', '.sf-level-0', function () {
        if (window.matchMedia('(min-width: 981px)').matches) {
            $(this).removeClass('active')
        }
    })
    $('body').on('click', '.sf-label-checkbox', function (e) {
        e.preventDefault();
        $(this).closest('.sf-level-0').toggleClass('toggled');
        // let destination = $(this).offset().top + 56;
        // $('html').animate({ scrollTop: destination }, 300);
        $('.clear-filter').remove();
        $(this).closest('.sf-level-0').append('<a href="" class="clear-filter">Очистить</a>')
        $(this).closest('.sf-level-0').find('.children').slideToggle('fast');
        $(this).parent().siblings().closest('.sf-level-0').removeClass('toggled').find('.children').slideUp();
    })
    $('body').on('click', '.clear-filter', function (e) {
        e.preventDefault();
        $('.search-filter-reset').click()
        return false;
    })

    $('body').on('click', '.filter-close', function () {
        console.log('.filter-close');
        $('.home-filter').removeClass('visible');
    })


    if (localStorage.getItem('strana_golosovanii')) {
        let name = JSON.parse(localStorage.getItem('strana_golosovanii'));
        $("#country_selector").countrySelect({
            defaultCountry: name.iso2,
        });
        $(`.sf-field-taxonomy-strana_golosovaniia label:contains(${name.name})`).prev().click();
    } else {
        $("#country_selector").countrySelect({});
    }

    $('#country_selector').on('change input', function (e) {

        let name = $('#country_selector').countrySelect("getSelectedCountryData");
        if (name.iso2 == 'reset') {
            console.log('reset')
            $(`.sf-field-taxonomy-strana_golosovaniia input`).prop('checked', false);
            location.reload();
        } else {
            localStorage.setItem('strana_golosovanii', JSON.stringify(name));
            $(`.sf-field-taxonomy-strana_golosovaniia input`).prop('checked', false);
            $(`.sf-field-taxonomy-strana_golosovaniia label:contains(${name.name})`).prev().click();
        }
    })

    if ($('.taxonomy-countries').length) {
        $('.taxonomy-countries .category-list').prepend(
            `<div class="child-list-category">
<span class="parent-category all-countries-checker" onclick="checkAllCountries(event);">Все страны</span>
</div>`
        );
        $('.parent-category:not(.all-countries-checker):not(.europe-countries-checker)').on('click', function () {
            if ($(this).hasClass('active')) {
                console.log('has class active')
                $(this).closest('.child-list-category').find('input[type=checkbox]').prop("checked", false);
            } else {
                console.log('doesn`t have class active')
                $(this).closest('.child-list-category').find('input[type=checkbox]').prop("checked", true);
            }
            $(this).toggleClass('active')
        })
    }

    $('.modal-previewfile').on('hidden.bs.modal', function (e) {
        $(e.target).find('.post-thumb').remove();
    })

    // $('.sf-field-taxonomy-court_region select').on('change', function () {
    //     let current = $(this).find('option:selected').text();
    //     $(`.sf-field-taxonomy-court_region_name .chosen-results li`).removeAttr('style');
    //
    //     $('.sf-field-taxonomy-court_region_name .chosen-drop').trigger('click')
    //
    //     console.log($('.sf-field-taxonomy-court_region_name ul.chosen-results li'));
    //
    //
    //     $('.sf-field-taxonomy-court_region_name ul.chosen-results li.sf-level-0').each(function () {
    //         if($(this).text() == current) {
    //             $(this).nextUntil('.sf-level-0').each(function () {
    //                 $(this).addClass('visible')
    //             })
    //         }else {
    //             $(this).nextUntil('.sf-level-0').each(function () {
    //                 console.log($(this))
    //                 $(this).remove();
    //             })
    //         }
    //     })
    // })

    $('.basic-search .sf-field-submit').before('<a href="?professional=true">Професійний пошук</a>');
    $('.extended-search .sf-field-submit').before('<a href="'+window.location.origin+'" >Стандартний пошук</a>');

})

jQuery(window).ready(function () {
    // jQuery('.sf-field-taxonomy-decisions_branch:nth-child(2) select').select2();
    // jQuery('.sf-field-taxonomy-decisions_branch select').select2('open');
    // jQuery('.sf-field-taxonomy-decisions_branch select').select2('close');
})

function toggleHasChildren(tthis) {
    if (tthis.find('ul').length) tthis.addClass('has-children');
}

function hideFilter() {
    $('.home-filter').removeClass('visible');
}