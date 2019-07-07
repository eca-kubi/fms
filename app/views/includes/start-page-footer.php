<?php
?>
<script src="<?php echo URL_ROOT; ?>/public/assets/js/jquery.min.js"></script>
<script src="<?php echo URL_ROOT; ?>/public/assets/js/jquery-toast.min.js"></script>
<script src="<?php echo URL_ROOT; ?>/public/assets/js/read-more-less.js"></script>

<script>
    $(function () {
        $("[data-url]").on('click', function () {
            window.location.href = $(this).data('url');
        });
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
