<?php ?>
<input type="hidden" id="url_root" value="<?php echo URL_ROOT; ?>">

<script src="<?php echo URL_ROOT; ?>/public/assets/js/jquery.min.js"></script>
<script src="<?php echo URL_ROOT; ?>/public/assets/js/jquery-toast.min.js"></script>
<script src="<?php echo URL_ROOT; ?>/public/assets/kinetic/kinetic.js"></script>
<script src="<?php echo URL_ROOT; ?>/public/assets/jquery-scrollto/jquery.scrollTo.min.js"></script>
<script src="<?php echo URL_ROOT; ?>/public/assets/enjoyhint-master/enjoyhint.js"></script>
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
            $('.tag-coming-soon').addClass('badge-light flash slow animated')
            $('.fa-check').removeClass('d-none').addClass('rotateInUpLeft text-success text-bold').parent().addClass('text-success');
        }
    });
    let enjoyhint_instance = new EnjoyHint({});
    $(function () {
        $("[data-url]").on('click', function () {
            window.location.href = $(this).data('url');
        });
        /*$('.section-1')[0].addEventListener('animationend', () => {
            let enjoyhint_script_steps = [
                {
                    ' .go-back' : 'Click on Forms to go back.',
                    'skipButton' : {text: 'OK'}
                }
            ];
            enjoyhint_instance.set(enjoyhint_script_steps);
            enjoyhint_instance.run();
        });*/
        setTimeout(() => $.unblockUI(), 1000);
    });
</script>
