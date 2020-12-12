/// <reference path='../../assets/ts/kendo.all.d.ts' />
/// <reference path='../../assets/typescript/moment.d.ts' />
// Custom script:  visitor-access-form/

let URL_ROOT = '<?php echo URL_ROOT; ?>';
let lists = [];



function notify(message, type = 'success') {
    let notification = $('<span class="notification" />').appendTo('body').kendoNotification({
        position: {
            right: 0,
            top: 0
        }
    }).data('kendoNotification');
    notification.show(message, type)
}

$(function () {
    $('.print-it').printPage();

    $('[data-toggle="tooltip"]').tooltip()

    $('.modal').on('shown.bs.modal', (e) => {
        let $this = $(e.currentTarget);
        $this.find('input[type=text]:first').trigger('focus');
    });

    $('.modal.draggable>.modal-dialog').draggable({
        cursor: 'move',
        handle: '.modal-header'
    });
    $('.modal.draggable>.modal-dialog>.modal-content>.modal-header').css('cursor', 'move');

    $('form[data-toggle=validator]')
        .validator()
        .on('validate.bs.validator', function (e) {
            //console.log(e.relatedTarget.name)
        })
        .on('submit', function (e) {
            if (e.isDefaultPrevented()) {
                // handle the invalid form...
                $(this).addClass('submitted')
            } else {
                // everything looks good!
            }
        })

    $("input[type=checkbox].require-at-least-one").on('change', function () {
        var $this = $(this);
        var requiredElements = $this.parents('form').find('.require-at-least-one');
        if (requiredElements.is(':checked')) {
            requiredElements.removeAttr('required');
            $this.parents('form').validator('validate');
        } else {
            requiredElements.attr('required', 'true');
        }
    }).trigger('change');

    $('form.require-at-least-one[data-toggle=validator]').validator().on('submit', function (e) {
        var requiredElements = $(this).find('.require-at-least-one');

        if (e.isDefaultPrevented()) {
            // handle the invalid form...

            if (!requiredElements.is(':checked')) {
                var enjoyhint_instance = new EnjoyHint({});

                enjoyhint_instance.set([{
                    selector: '.modal .form-check.require-at-least-one:first',
                    description: 'Please select at least one checkbox',
                    event: 'click',
                    showSkip: false
                }])
                enjoyhint_instance.run();
            } else {
                this.submit();
            }
        } else {
            this.submit();
        }
    })

    $('form[data-toggle=validator] input[type=text]:not([readonly]):first').trigger('focus');

    /*  $('.search-button').on('click', function () {
          let $this = $(this);
          setTimeout(function() { $('input.search').trigger('focus') }, 1500);
          //$this.siblings('ul').find('input.search').trigger('focus');
      });*/

    $(window).on('resize', function () {
        $('.content-wrapper').css('margin-top', $('.navbar-fixed').height() + 'px');
    });

    initList('pending_approvals');
    initList('completed_approvals');
    //initList('rejected');

    $('.bs-select')
        .selectpicker({
            mobile: false,
            iconBase: 'fa',
            windowPadding: 0,
            style: 'rounded-0',
            styleBase: 'form-control',
            selectOnTab: true,
            //liveSearch: true,
            liveSearchNormalize: true,
            virtualization: true,
            showTick: false,
            showContent: false
        })
        .on('loaded.bs.select', function () {
            let select = $(this);
            let bsSelect = $(this).parent('.bs-select');
            $('.bs-select .dropdown-menu').addClass('rounded-0');
            bsSelect.addClass('exclude-hover')
                .find('button')
                .on('blur', function () {
                    //bsSelect.find('select')
                    select.trigger('blur')
                })
        })
        .on('changed.bs.select', function () {
            let $this = $(this);
            let parentSibling = $this.parents('.col-sm-6').next('.col-sm-6.other-option');
            if ($this.has('option[value=Other]:selected').length) {
                parentSibling.removeClass('d-none');
                parentSibling.find('input').attr('required', 'true');
            } else {
                parentSibling.addClass('d-none');
                parentSibling.find('input').removeAttr('required');
            }
        });

    $('#arrival_date').on('change', function () {
        $('#departure_date').attr('min', $(this).val())
    });

    $('#departure_date').on('change', function () {
        $('#arrival_date').attr('max', $(this).val())
    });

    $('.section')
        .on('shown.bs.collapse', function () {
            $(this).parent()
                .find('.fa').eq(0).removeClass('fa-plus-circle').addClass('fa-minus-circle');
            return false;
        }).on('hidden.bs.collapse', function () {
        $(this).parent().find('.fa').eq(0).removeClass('fa-minus-circle').addClass('fa-plus-circle');
        return false;
    });

    // fix column width for tables in collapse
    $('.hide-child').removeClass('show').trigger('hidden.bs.collapse');

    $('.bs-searchbox >input').attr('data-validate', '');
});

window.addEventListener("load", function () {
    $('.content, footer').removeClass('d-none invisible');

    $(document).on('click', '.add-input', addFormGroup);
    $(document).on('click', '.remove-input', null, removeFormGroup);

    $(document).on('show.bs.modal', '#cancelRequest', (e) => {
        let href = $(e.relatedTarget).attr('data-href');
        $('#cancelRequest .btn-primary').attr('href', href);
    });

    let prevScrollpos = window.pageYOffset;

    window.onscroll = function () {
        let navbar = $('.navbar-fixed');
        let currentScrollPos = window.pageYOffset;
        if (prevScrollpos > currentScrollPos) {
            navbar.animate({top: '0'}, 'fast')
        } else {
            navbar.animate({top: '-60px'}, 'fast')
        }
        prevScrollpos = currentScrollPos;
    };

    setTimeout(() => $.unblockUI(), 1000);

    console.log("All resources finished loading!");
});

function initList(id) {
    let options = {
        valueNames: ['document_id', 'department', 'originator', 'date_raised', 'date_completed'],
        searchClass: 'search',
        pagination: true,
        page: 10,
        item: `<li class="col-sm-5 mx-auto">
        <dl>
            <dt></dt><dd class="document_id"></dd>
            <dt></dt><dd class="department"></dd>
            <dt></dt><dd class="originator"></dd>
            <dt></dt><dd class="date_raised"></dd>
            <dt></dt><dd class="date_completed"></dd>
            <dt></dt><dd></dd>
        </dl>
        </li>`
    };

    lists['list_' + id] = new List('list_container_' + id, options);
}

/*function searchList(element) {
    let $this = $(element);
    let list = lists[$this.attr('data-list-id')];
    let keyword = $this.val();
    list.search(keyword);
}*/

let addFormGroup = function () {
    let $inputGroup = $(this).closest('.input-group');
    let $multipleFormGroup = $inputGroup.closest('.multiple-form-group');
    let $lastInputGroupLast = $multipleFormGroup.find('.input-group:last');

    if ($multipleFormGroup.data('max') <= countFormGroup($multipleFormGroup)) {
        $lastInputGroupLast.find('.add-input').addClass('cursor-disabled');
        $.toast({
            text: 'You can only add up to three files!',
            position: 'top-center',
            stack: false,
            hideAfter: false,
            icon: 'warning',
            showHideTransition: 'plain'
        });
        return;
    }
    let $inputGroupClone = $inputGroup.clone();
    $(this)
        .toggleClass('add-input remove-input')
        .html('<i class="fas fa-minus-square text-danger"></i>');
    $inputGroup.addClass('mb-1');
    $inputGroupClone.find('input').val('');
    $inputGroupClone.insertAfter($inputGroup);
    //$('form[data-toggle=validator]').validator('update')
};

let removeFormGroup = function () {
    let $formGroup = $(this).closest('.input-group');
    let $multipleFormGroup = $formGroup.closest('.multiple-form-group');

    let $lastFormGroupLast = $multipleFormGroup.find('.input-group:last');
    if ($multipleFormGroup.data('max') >= countFormGroup($multipleFormGroup)) {
        $lastFormGroupLast.find('.add-input').removeClass('cursor-disabled');
    }
    $formGroup.remove();
    //$('form[data-toggle=validator]').validator('update')
};

let countFormGroup = function ($form) {
    return $form.find('.input-group').length;
};

let stopChangeProcess = function () {
    //let redirect = window.location.href;
    window.location.href = $(this).attr('data-href');
};

toastError = function f(message, timeout = 3000) {
    $.toast({
// heading: '<u>Information</u>',
        text: `<b class="text-bold row"><i class="fa fa-warning text-warning p-1"></i> <span class="text-danger">${message}</span></b>`,
//icon: 'warning',
        loader: false,        // Change it to false to disable loader
        loaderBg: '#9EC600',  // To change the background
        position: 'mid-center',
        stack: 1,
        hideAfter: timeout,
        beforeShow: function (element) {
            element.addClass("bg-white card")
        },
        afterShown(element) {
            //$(".dimbackground-curtain").remove();
            //element.dimBackground({darkness: 0.6});
        },
        afterHidden(element) {
            //element.undim();
        }
    });
};

toastSuccess = function f(message, timeout = 1000) {
    $.toast({
// heading: '<u>Information</u>',
        text: `<b class="text-bold text-danger"><i class="fa fa-check text-success p-1"></i> <span class="text-success">${message}</span></b>`,
//icon: 'warning',
        loader: false,        // Change it to false to disable loader
        loaderBg: '#9EC600',  // To change the background
        position: 'top-center',
        stack: 1,
        hideAfter: timeout,
        beforeShow: function (element) {
            element.addClass("bg-white card")
        },
        afterShown(element) {
            //$(".dimbackground-curtain").remove();
            //element.dimBackground({darkness: 0.6});
        },
        afterHidden(element) {
            //element.undim();
        }
    });
};
