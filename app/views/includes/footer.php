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
<script src="<?php echo URL_ROOT; ?>/public/assets/jquery-ui/jquery-ui.min.js"></script>
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
<script src="<?php echo URL_ROOT; ?>/public/assets/js/bootstrap.bundle.js"></script>
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
<script src="<?php echo URL_ROOT; ?>/public/assets/bootstrap-select/bootstrap-select.js"></script>
<script src="<?php echo URL_ROOT; ?>/public/assets/bootstrap-validator/validator.js"></script>
<!--<script src="<?php /*echo URL_ROOT; */ ?>/public/assets/js/pignose.calendar.js"></script>
-->
<script src="<?php echo URL_ROOT; ?>/public/assets/listjs/listjs.min.js"></script>
<script src="<?php echo URL_ROOT; ?>/public/assets/kendo-ui/kendo.all.min.js"></script>
<script src="<?php echo URL_ROOT; ?>/public/assets/pako-deflate/pako-deflate.min.js"></script>
<script src="<?php echo URL_ROOT; ?>/public/assets/jszip/jszip.min.js"></script>
<script src="<?php echo URL_ROOT; ?>/public/assets/pdfjs/pdf.js"></script>
<script src="<?php echo URL_ROOT; ?>/public/assets/js/jQuery-printPage/jquery.printPage.js"></script>
<script src="<?php echo URL_ROOT; ?>/public/assets/enjoyhint/enjoyhint.js"></script>
<script src="<?php echo URL_ROOT; ?>/public/assets/export-as-pdf/export-as-pdf.js"></script>

<?php loadCustomScripts($custom_scripts?? ['/']); ?>

</body>
</html>
