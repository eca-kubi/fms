
<script src="<?php echo URL_ROOT; ?>/public/assets/js/jQuery/jquery-3.5.1.min.js"></script>
<!--<script src="<?php /*echo URL_ROOT; */?>/public/assets/js/jquery-toast.min.js"></script>
--><!--<script src="<?php /*echo URL_ROOT; */?>/public/assets/kinetic/kinetic.js"></script>
--><!--<script src="<?php /*echo URL_ROOT; */?>/public/assets/jquery-scrollto/jquery.scrollTo.min.js"></script>
--><!--<script src="<?php /*echo URL_ROOT; */?>/public/assets/enjoyhint-master/enjoyhint.js"></script>
--><!--<script src="<?php /*echo URL_ROOT; */?>/public/assets/js/blockui.js"></script>
--><script>
    $(function () {
        $("[data-url]").on('click', function () {
            window.location.href = $(this).data('url');
        });

        $('a.child').on('click', function (e) {
            e.stopPropagation();
        })
    });
</script>
