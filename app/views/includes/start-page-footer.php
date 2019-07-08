<?php
?>
<script src="<?php echo URL_ROOT; ?>/public/assets/js/jquery.min.js"></script>
<script src="<?php echo URL_ROOT; ?>/public/assets/js/tether.min.js"></script>
<script src="<?php echo URL_ROOT; ?>/public/assets/js/jquery-toast.min.js"></script>
<script src="<?php echo URL_ROOT; ?>/public/assets/js/jquery.shortify.js"></script>
<script src="<?php echo URL_ROOT; ?>/public/assets/js/jquery.collapser.min.js"></script>


<script>
    $(function () {
        $("[data-url]").on('click', function () {
            window.location.href = $(this).data('url');
        });

        /*$(".more").shortify({
            char_limit: 25,
            position:  "tooltip-bottom",
            ellipsis: '...'
        });*/

        $('.more').collapser({
            target: 'next',
            mode: 'words',
            speed: 'slow',
            truncate: 10,
            ellipsis: '...',
            effect: 'fade',
            controlBtn: '',
            showText: 'Show more',
            hideText: 'Hide text',
            showClass: 'show-class',
            hideClass: 'hide-class',
            atStart: 'hide',
            lockHide: false,
            dynamic: false,
            changeText: false,
            beforeShow: null,
            afterShow: null,
            beforeHide: null,
            afterHide: null
        });
/*        new Tether({
            element: '.tag-coming-soon',
            target: '.tag-target',
            attachment: 'top left',
            targetAttachment: 'top right',
            constraints: [{
                to: 'window',
                attachment: 'together'
            }]
        });*/

        /*$("[data-toggle=list]").on('click', function () {
            let list = $(this).data('list');
            $.toast({
                heading: 'Click on a link below:',
                icon: 'info',
                loader: false,        // Change it to false to disable loader
                loaderBg: '#9EC600',  // To change the background
                position: 'top-center',
                stack: 1,
                hideAfter: false,
                text: list
            })
        });*/
        /*$('.coming-soon').on('click', function () {
            $.toast({
                heading: 'Information',
                text: 'Coming soon!',
                icon: 'info',
                loader: false,        // Change it to false to disable loader
                loaderBg: '#9EC600',  // To change the background
                position: 'top-center',
                stack: 1,
                hideAfter: false
            })
        });*/

    });
</script>
