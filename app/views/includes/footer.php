<footer class="font-raleway w3-tiny d-none main-footer" style="z-index: 1100">
    <div class="col-8 float-left text-left">
        <strong>
            &copy; <?php echo year(now()) ?> -
            <a href="<?php echo site_url('about'); ?>">Developed By Adamus IT</a>.
        </strong>
    </div>
    <div class="float-right col-4 text-right">
        <b>Version 2.0</b>
    </div>
</footer>
<input type="hidden" id="url_root" value="<?php echo URL_ROOT; ?>">

<div id="uploadSalariesWindow" style="display: none">
    <div class="k-content">
        <h4>Upload Salaries</h4>
        <input name="uploadedFile" id="excelUpload" type="file"/>
        <div class="demo-hint">You can only upload <strong>Excel</strong> files.</div>
    </div>
</div>
<div id="exchangeRateWindow" style="display: none">
    <form action="<?php URL_ROOT ?>\salary-advance\exchange-rate" id="exchangeRateForm" method="get">
        <div class="k-content">
            <h4>Enter Exchange Rate</h4>
            <label for="exchangeRateInput" class="mr-2">1 USD = </label><input name="exchange_rate" id="exchangeRateInput" type="text" data-required-msg="Exchange Rate is required!" required/>
            <span class="k-invalid-msg" data-for="exchangeRateInput"></span>
            <input type="submit" class="success btn btn-success" value="Submit" id="exchangeRateSubmitButton">
        </div>
    </form>
</div>



<?php
//modal('stop_change');
//modal('select_gm');
//modal('select_mgr');
modal('change_password');
?>

</div>
<!-- ./wrapper -->
<input type="hidden" value="<?php echo URL_ROOT ?>" id="url_root"/>
<script src="<?php echo URL_ROOT; ?>/public/assets/js/jquery.min.js"></script>
<script src="<?php echo URL_ROOT; ?>/public/assets/js/blockui.js"></script>
<script>
    $.blockUI({
        message: '<i class="fa fa-spinner w3-spin" style="font-size:32px"></i>',
        css: {
            padding: 0,
            margin: 0,
            width: '0%',
            top: '40%',
            left: '50%',
            textAlign: 'center',
            color: '#000',
            border: '0',
            backgroundColor: 'rgba(0,0,0,0.5)',
            cursor: 'default'
        },
        overlayCSS: {
            backgroundColor: '#000',
            opacity: 0.0,
            cursor: 'default'
        },
        onUnblock: function () {
            $('.hide-on-init').removeClass('invisible');
            setTimeout(function () {
                $('.completed:not(.fa)').addClass('text-success fadeIn text-bold');
                $('.completed .fa').removeClass('d-none').addClass('rotateInUpLeft text-success text-bold');
                $('.incomplete').addClass('text-danger text-bold').removeClass('text-dark');
            }, 1000)
        }
    });
    /*$('.blockable').block({
        message: null,
        overlayCSS: {
            backgroundColor: '#000',
            opacity: 0.0,
            cursor: 'default'
        },
    });*/
</script>
<!--<script src="<?php /*echo URL_ROOT; */ ?>/public/assets/js/jquery.ui.widget.js"></script>
-->
<!--<script src="<?php /*echo URL_ROOT; */ ?>/public/assets/js/jquery.iframe-transport.js"></script>
-->
<!--<script src="<?php /*echo URL_ROOT; */ ?>/public/assets/js/jquery.fileupload.js"></script>
-->
<!--<script src="<?php /*echo URL_ROOT; */?>/public/assets/js/hop.js"></script>
-->
<script src="<?php echo URL_ROOT; ?>/public/assets/js/jquery-toast.min.js"></script>
<script src="<?php echo URL_ROOT; ?>/public/assets/js/bootstrap.bundle.min.js"></script>
<script src="<?php echo URL_ROOT ?>/public/assets/js/shards.min.js"></script>
<script src="<?php echo URL_ROOT; ?>/public/assets/js/adminlte.min.js"></script>
<script src="<?php echo URL_ROOT; ?>/public/assets/js/adminlte-2.js"></script>
<script src="<?php echo URL_ROOT; ?>/public/assets/js/moment.js"></script>
<!--<script src="<?php /*echo URL_ROOT; */ ?>/public/assets/js/bizniz.js"></script>
-->
<!--<script src="<?php /*echo URL_ROOT; */ ?>/public/assets/js/moment-business-with-holidays.js"></script>
-->
<!--<script src="<?php /*echo URL_ROOT; */ ?>/public/assets/js/moment-holiday.js"></script>
-->
<!--<script src="<?php /*echo URL_ROOT; */ ?>/public/assets/js/easter.js"></script>
-->
<!--<script src="<?php /*echo URL_ROOT; */ ?>/public/assets/js/ghana.js"></script>
-->
<!--<script src="<?php /*echo URL_ROOT; */ ?>/public/assets/js/jquery.daterange.js"></script>
-->
<script src="<?php echo URL_ROOT; ?>/public/assets/js/jquery.scrollTo.js"></script>
<script src="<?php echo URL_ROOT; ?>/public/assets/js/letter-avatar.js"></script>
<script src="<?php echo URL_ROOT; ?>/public/assets/bootstrap-validator/validator.min.js"></script>
<script src="<?php echo URL_ROOT; ?>/public/assets/bootstrap-select/boostrap-select.min.js"></script>
<!--<script src="<?php /*echo URL_ROOT; */ ?>/public/assets/js/pignose.calendar.js"></script>
-->
<script src="<?php echo URL_ROOT; ?>/public/assets/listjs/listjs.min.js"></script>
<script src="<?php echo URL_ROOT; ?>/public/assets/js/kendo/kendo.all.min.js"></script>
<script src="<?php echo URL_ROOT; ?>/public/assets/js/jQuery-printPage/jquery.printPage.js"></script>

<?php echo $page?? ''; ?>
<script>
    /// <reference path='../../assets/ts/kendo.all.d.ts' />
    /// <reference path='../../assets/typescript/moment.d.ts' />

    //let moment_format = 'DD/MM/YYYY';

    let URL_ROOT = "<?php echo URL_ROOT; ?>";
    let form_submit_count = 0;
    let CMS_FORM_ID = 0;
    let lists = [];
    //let ANIMATE_FLASH = 'animated flash card infinite';
    //=============================================================
    // Daterangepicker Plugin
    /*let date_rangepicker_options = {
        singleDate: true,
        showShortcuts: false,
        autoClose: true,
        customArrowPrevSymbol: '<i class="fa fa-arrow-circle-left"></i>',
        customArrowNextSymbol: '<i class="fa fa-arrow-circle-right"></i>',
        singleMonth: true,
        monthSelect: true,
        yearSelect: true,
        format: 'ddd, MMM D, YYYY',
        //yearSelect: [1900, moment().get('year')]
        //startDate: '1900-01-01'
    };*/

    $(function () {
        $('.print-it').printPage();
        //let NAV_BAR_HEIGHT = $('.navbar-fixed').height();
        //$('.content-wrapper').css('margin-top', NAV_BAR_HEIGHT + 'px');
        //CMS_FORM_ID = $('#cms_form_id').val();
        //moment.modifyHolidays.add('Ghana');
        //checkHODAssignment();
        $('.modal').on('shown.bs.modal', (e) => {
            let $this = $(e.currentTarget);
            $this.find('input[type=text]:first').trigger('focus');
        });

        $('form[data-toggle=validator] input[type=text]:not([readonly]):first').trigger('focus');

        $("[id='*_form']").validator().on('submit', (e) => {
            if (e.isDefaultPrevented()) {
                // handle the invalid form...
            } else {
                // everything looks good!
            }
        });

        $('.search-button').on('click',function () {
            let $this = $(this);
            $this.parents('.box').boxWidget('expand');
            setTimeout(() => {
                $this.siblings('ul').find('input').trigger('focus');
            }, 200)
        });

        $(window).on('resize',function () {
            $('.content-wrapper').css('margin-top', $('.navbar-fixed').height() + 'px');
        });

        initList('active');
        initList('closed');
        initList('rejected');
        //$(document).ajaxStart($.blockUI).ajaxStop($.unblockUI);

        $('form').on('submit',function () {
            let form_is_valid = this.checkValidity();
            if (form_is_valid) {
                form_submit_count++;
                if (form_submit_count > 1) {
                    return false;
                }
            }
        });
        //=============================================================

        $('.bs-select')
            .selectpicker({
                liveSearch: true,
                virtualization: true,
                showTick: false,
                showContent: false
            })
            .on('loaded.bs.select show.bs.select', function () {
                $('.replace-multiple-select').remove();
                $('.multiple-hidden.bs-select').removeClass('d-none');
                $('.bs-select .dropdown-menu').addClass('p-0 rounded-0');
                $('.bs-select button').addClass('w3-hover-none rounded-0 w3-transparent w3-border');
                $('.bootstrap-select').addClass('exclude-hover');
                $('.bs-select').attr('tabindex', 1);
            });

        // '/cms-forms/add'
        $('#change_type').on('changed.bs.select', function () {
            if ($(this).has('option[value=Other]:selected').length) {
                $('#other_type').removeClass('d-none');
                $('[name=other_type]').attr('required', true);
                $('#add_cms_form').validator('update');
                $('#button_container').removeClass('col-sm-6').addClass('w-100');
            } else {
                $('#other_type').addClass('d-none');
                $('[name=other_type]').removeAttr('required');
                $('#add_cms_form').validator('update');
                $('#button_container').addClass('col-sm-6').removeClass('w-100');
            }
        });

        $('.section').on('shown.bs.collapse', function () {
            $(this).parent().find('.fa').eq(0).removeClass('fa-plus-circle').addClass('fa-minus-circle');
            resizeTables();
            return false;
        })
            .on('hidden.bs.collapse', function () {
                $(this).parent().find('.fa').eq(0).removeClass('fa-minus-circle').addClass('fa-plus-circle');
                return false;
            });

        // '/cms-forms/hod-assesment'
        $('[name=hod_approval]').on('change', function () {
            if ($(this).val() === "approved") {
                $('#hod_ref_num').removeClass('d-none');
                $('.gm.form-group').removeClass('d-none');
                $('[name=hod_ref_num], [name=gm_id]').attr('required', true);
                $('#hod_assessment_form').validator('update');
            } else {
                $('#hod_ref_num').addClass('d-none');
                $('.gm.form-group').addClass('d-none');
                $("[name=hod_ref_num], [name=gm_id]").attr('required', false);
                $('#hod_assessment_form').validator('update');
            }
        });

        // fix column width for tables in collapse
        $('.hide-child').removeClass('show').trigger('hidden.bs.collapse');

        $('.bs-searchbox >input').attr('data-validate', false);

    });

    window.addEventListener("load", function () {
        $('.content').removeClass('d-none invisible');
        $('footer').removeClass('d-none');

        $(document).on('click', '.add-input', addFormGroup);
        $(document).on('click', '.remove-input', removeFormGroup);
        $(document).on('show.bs.modal', '#stopProcess', (e) => {
            let href = $(e.relatedTarget).attr('data-href');
            $('#stopProcess .btn-primary').attr('data-href', href);
        });

        $(document).on('click', '#stopProcess .btn-primary', stopChangeProcess);

        let prevScrollpos = window.pageYOffset;
        window.onscroll = function () {
            let navbar = $('.navbar-fixed');
            let currentScrollPos = window.pageYOffset;
            if (prevScrollpos > currentScrollPos) {
                navbar.animate({top: '0'},'fast')
            } else {
                navbar.animate({top: '-60px'},'fast')
            }
            prevScrollpos = currentScrollPos;
        };

        setTimeout(()=>$.unblockUI(), 1000);

        console.log("All resources finished loading!");
    });

    function displayTour( tourOption) {
        if (!window.localStorage(tourOption) ){

        }
    }

    function executeOnAppFirstRun() {
        displayTour()
    }

    /*function isOffDay(date) {
        return bizniz.isWeekendDay(date);
    }

    function getWeekendDays(startMoment, endMoment) {
        startMoment.toDate();
        endMoment.toDate();
        return Math.abs(bizniz.default.weekendDaysBetween(s, e));
    }

    function getWeekDays(startMoment, endMoment) {
        startMoment.toDate();
        endMoment.toDate();
        return Math.abs(bizniz.default.weekDaysBetween(s, e));
    }

    function getUrlParam(parameter, defaultvalue) {
        let url_parameter = defaultvalue;
        if (window.location.href.indexOf(parameter) > -1) url_parameter = getUrlVars()[parameter];
        return url_parameter;
    }

    function getUrlVars() {
        let vars = {};
        window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function (m, key, value) {
            vars[key] = value;
        });
        return vars;
    }

    function nextWeekDay(moment_date) {
        return moment(moment_date.toDate(), moment_format).weekday(8);
    }

    function nextWorkingDay(moment_start_date) {
        /!*do {
            bizniz.default.addWeekDays(moment_day.toDate(), 1);
        } while (!moment_day.isHoliday());*!/
        return moment(moment_start_date.toDate(), moment_format);
    }

    function getDays(moment_start_date, moment_resume_date) {
        let holidays = moment_start_date.holidaysBetween(moment_resume_date);
        let holiday_count = holidays ? holidays.length : 0;
        let days_applied_for = moment_start_date.businessDiff(moment_resume_date);
        return {
            'holiday_count': holiday_count,
            'days_applied_for': days_applied_for
        }
    }*/

    /*function capitalize(s) {
        if (typeof s !== 'string') return '';
        return s.split(/[\s,-.]+/).map(function (line) {
            line = line[0].toUpperCase() + line.substr(1);
            return line;
        }).join(" ");
    }*/

    function initList(id) {
        let options = {
            valueNames: ['title', 'ref_num', 'originator', 'date_raised', 'change_type'],
            searchClass: 'search',
            item: `<div class="col-sm-5 mx-sm-5">
        <dl>
            <dt></dt><dd class="ref_num"></dd>
            <dt></dt><dd class="title"></dd>
            <dt></dt><dd class="originator"></dd>
            <dt></dt><dd class="date_raised"></dd>
            <dt></dt><dd class="change_type"></dd>
            <dt></dt><dd></dd>
        </dl>
        </div>`
        };

        lists['list_' + id] = new List('list_container_' + id, options);
    }

    function searchList(element) {
        let $this = $(element);
        let list = lists[$this.attr('data-list-id')];
        let keyword = $this.val();
        list.search(keyword);
    }

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
        $('form[data-toggle=validator]').validator('update')

    };

    let removeFormGroup = function () {
        let $formGroup = $(this).closest('.input-group');
        let $multipleFormGroup = $formGroup.closest('.multiple-form-group');

        let $lastFormGroupLast = $multipleFormGroup.find('.input-group:last');
        if ($multipleFormGroup.data('max') >= countFormGroup($multipleFormGroup)) {
            $lastFormGroupLast.find('.add-input').removeClass('cursor-disabled');
        }
        $formGroup.remove();
        $('form[data-toggle=validator]').validator('update')
    };

    let countFormGroup = function ($form) {
        return $form.find('.input-group').length;
    };

    let stopChangeProcess = function () {
        //let redirect = window.location.href;
        window.location.href = $(this).attr('data-href');
    };

    let checkHODAssignment = function () {
        let hod = $('#hod_id');
        if (hod.length === 0) return;
        if (hod.val() === '') {
            $.toast({
                // heading: 'warning',
                text: 'A Manager must be assigned!',
                position: 'top-center',
                stack: false,
                hideAfter: false,
                icon: 'error',
                showHideTransition: 'plain'
            });
            return false;
        }
    };

</script>
</body>
</html>
