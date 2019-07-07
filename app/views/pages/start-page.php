<?php /** @var array $payload */ ?>
<!DOCTYPE html>
<html lang="en">
<?php require_once APP_ROOT.'\views\includes\start-page-header.php'; ?>
<body>
<section class="animated fadeInLeft">
    <div class="container">
            <div class="row">
                <span class="m-1 ml-auto">
                    <a href="<?php echo site_url("users/logout") ?>" class="btn btn-pill btn-primary"><i class="fa fa-lock"></i>&nbsp;Sign me out</a>
                </span>
            </div>
        <div class="row mbr-justify-content-center">
            <div class="col-lg-6 mbr-col-md-10" data-url="<?php echo site_url('start-page/finance') ?>">
                <div class="wrap">
                    <div class="ico-wrap">
                        <span class="mbr-iconfont fa-money fa"></span>
                    </div>
                    <div class="text-wrap vcenter">
                        <h2 class="mbr-fonts-style mbr-bold mbr-section-title3 display-5">Finance <span></span></h2>
                        <p class="mbr-fonts-style text1 mbr-text display-6">
                            <span>Salary Advance, etc... <span class="invisible">Coming soon</span> </span>
                            <span class="d-none">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum</span>
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 mbr-col-md-10 coming-soon">
                <div class="wrap">
                    <div class="ico-wrap">
                        <span class="mbr-iconfont fa-users fa"></span>
                    </div>
                    <div class="text-wrap vcenter">
                        <h2 class="mbr-fonts-style mbr-bold mbr-section-title3 display-5">HR
                            <span></span>
                        </h2>
                        <p class="mbr-fonts-style text1 mbr-text display-6">
                            <span>All HR-related forms. (Coming soon)</span>
                            <span class="d-none">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum</span>
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 mbr-col-md-10 coming-soon">
                <div class="wrap">
                    <div class="ico-wrap">
                        <span class="mbr-iconfont fa-wpforms fa"></span>
                    </div>
                    <div class="text-wrap vcenter">
                        <h2 class="mbr-fonts-style mbr-bold mbr-section-title3 display-5">Admin
                            <span></span>
                        </h2>
                        <p class="mbr-fonts-style text1 mbr-text display-6">
                            <span>All Admin-related forms. (Coming soon)</span>
                            <span class="d-none">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum</span>
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 mbr-col-md-10 coming-soon">
                <div class="wrap">
                    <div class="ico-wrap">
                        <span class="mbr-iconfont fa-windows fa"></span>
                    </div>
                    <div class="text-wrap vcenter">
                        <h2 class="mbr-fonts-style mbr-bold mbr-section-title3 display-5">IT <span></span></h2>
                        <p class="mbr-fonts-style text1 mbr-text display-6">
                            <span>All IT-related forms. (Coming soon)</span>
                            <span class="d-none">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum</span>
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 mbr-col-md-10" data-toggle="list" data-list='["<a href=\"<?php echo HOST . "/nearmiss" ?>\">Near Miss</a>"]'>
                <div class="wrap">
                    <div class="ico-wrap">
                        <span class="mbr-iconfont fa-user-shield fa"></span>
                    </div>
                    <div class="text-wrap vcenter">
                        <h2 class="mbr-fonts-style mbr-bold mbr-section-title3 display-5">HSE <span></span></h2>
                        <p class="mbr-fonts-style text1 mbr-text display-6">
                            <span>Near Miss, etc.<span class="d-none">Lorem Ipsum is simply dummy text of the</span></span>
                            <span class="d-none">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum</span>
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 mbr-col-md-10 coming-soon">
                <div class="wrap">
                    <div class="ico-wrap">
                        <span class="mbr-iconfont fontastic-mine-truck"></span>
                    </div>
                    <div class="text-wrap vcenter">
                        <h2 class="mbr-fonts-style mbr-bold mbr-section-title3 display-5">Mining <span></span></h2>
                        <p class="mbr-fonts-style text1 mbr-text display-6">
                            <span>All Mining-related forms. (Coming soon)</span>
                            <span class="d-none">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum</span>
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 mbr-col-md-10 coming-soon">
                <div class="wrap">
                    <div class="ico-wrap">
                        <span class="mbr-iconfont fontastic-engineering"></span>
                    </div>
                    <div class="text-wrap vcenter">
                        <h2 class="mbr-fonts-style mbr-bold mbr-section-title3 display-5">Engineering <span></span></h2>
                        <p class="mbr-fonts-style text1 mbr-text display-6">
                            <span>All Engineering-related forms. (Coming soon)</span>
                            <span class="d-none">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum</span>
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 mbr-col-md-10 coming-soon">
                <div class="wrap">
                    <div class="ico-wrap">
                        <span class="mbr-iconfont fontastic-robot"></span>
                    </div>
                    <div class="text-wrap vcenter">
                        <h2 class="mbr-fonts-style mbr-bold mbr-section-title3 display-5">Processing <span></span></h2>
                        <p class="mbr-fonts-style text1 mbr-text display-6">
                            <span>All Processing-related forms. (Coming soon)</span>
                            <span class="d-none">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script src="<?php echo URL_ROOT; ?>/public/assets/js/jquery.min.js"></script>
<script src="<?php echo URL_ROOT; ?>/public/assets/js/jquery-toast.min.js"></script>

<script>
    $(function () {
        $("[data-toggle=list]").on('click', function () {
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
        });

        $("[data-url]").on('click', function () {
            window.location.href = $(this).data('url');
        });

        $('.coming-soon').on('click', function () {
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
        });

    });
</script>
</body>
</html>
