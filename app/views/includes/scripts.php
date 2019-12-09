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
    $('.blockable').block({
        message: null,
        overlayCSS: {
            backgroundColor: '#000',
            opacity: 0.0,
            cursor: 'default'
        },
    });
</script>
<script src="<?php echo URL_ROOT; ?>/public/assets/js/jquery-toast.min.js"></script>
<script src="<?php echo URL_ROOT; ?>/public/assets/js/jquery.lockscroll.min.js"></script>
<script src="<?php echo URL_ROOT; ?>/public/assets/js/bootstrap.bundle.min.js"></script>
<script src="<?php echo URL_ROOT ?>/public/assets/js/shards.min.js"></script>
<script src="<?php echo URL_ROOT; ?>/public/assets/js/adminlte.min.js"></script>
<script src="<?php echo URL_ROOT; ?>/public/assets/js/adminlte-2.js"></script>
<script src="<?php echo URL_ROOT; ?>/public/assets/js/moment.js"></script>
<script src="<?php echo URL_ROOT; ?>/public/assets/js/jquery.scrollTo.js"></script>
<script src="<?php echo URL_ROOT; ?>/public/assets/js/letter-avatar.js"></script>
<script src="<?php echo URL_ROOT; ?>/public/assets/js/jszip.min.js"></script>
<script src="<?php echo URL_ROOT; ?>/public/assets/js/kendo/kendo.all.min.js"></script>
<script src="<?php echo URL_ROOT; ?>/public/assets/js/kendo/kendo.web.plugins.radiobuttongroup.js"></script>
<script src="<?php echo URL_ROOT; ?>/public/assets/js/jQuery-printPage/jquery.printPage.js"></script>
<script src="<?php echo URL_ROOT; ?>/public/assets/js/jquery.dim-background.min.js"></script>
<script src="<?php echo URL_ROOT; ?>/public/assets/js/tour.js"></script>
<script src="<?php echo URL_ROOT; ?>/public/assets/js/enjoyhint.js"></script>
<script src="<?php echo URL_ROOT; ?>/public/assets/js/node-bundle.js"></script>
<script src="<?php echo URL_ROOT; ?>/public/custom-assets/js/custom.js"></script>