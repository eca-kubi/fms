<?php /** @var array $payload */ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $payload['title']; ?></title>
    <link href="<?php echo URL_ROOT; ?>/public/assets/fontastic/styles.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo URL_ROOT; ?>/public/assets/css/w3.css"/>
    <link rel="stylesheet" href="<?php echo URL_ROOT; ?>/public/assets/css/adminlte.css"/>
    <link rel="stylesheet" href="<?php echo URL_ROOT; ?>/public/assets/css/adminlte-miscellaneous.css"/>
    <link rel="stylesheet" href="<?php echo URL_ROOT; ?>/public/assets/css/box-widget.css"/>
    <link rel="stylesheet" media="all" href="<?php echo URL_ROOT; ?>/public/assets/css/font-awesome.css"/>
    <link rel="stylesheet" media="all" href="<?php echo URL_ROOT; ?>/public/assets/css/all.css"/>
    <link rel="stylesheet" media="all" href="<?php echo URL_ROOT; ?>/public/assets/css/v4-shims.min.css"/>
    <link rel="stylesheet" href="<?php echo URL_ROOT ?>/public/assets/css/shards.min.css">
    <link rel="stylesheet" href="<?php echo URL_ROOT; ?>/public/assets/css/fonts.css"/>
    <link rel="stylesheet" href="<?php echo URL_ROOT; ?>/public/assets/css/jquery-toast.min.css"/>

    <link rel="stylesheet"
          href="<?php echo URL_ROOT; ?>/public/custom-assets/css/custom.css?<?php echo microtime(); ?>"/>
    <style>
        section {
            padding-top: 4rem;
            padding-bottom: 5rem;
            background-color: #f1f4fa;
        }

        .wrap {
            display: flex;
            background: white;
            padding: 1rem 1rem 1rem 1rem;
            border-radius: 0.5rem;
            box-shadow: 7px 7px 30px -5px rgba(0, 0, 0, 0.1);
            margin-bottom: 2rem;
        }

        .wrap:hover {
            background: linear-gradient(135deg, #F0E68C 0%, #DAA520 100%);
            color: white;
        }

        .ico-wrap {
            margin: auto;
        }

        .mbr-iconfont {
            font-size: 4.5rem !important;
            color: #313131;
            margin: 1rem;
            padding-right: 1rem;
        }

        .vcenter {
            margin: auto;
        }

        .mbr-section-title3 {
            text-align: left;
        }

        h2 {
            margin-top: 0.5rem;
            margin-bottom: 0.5rem;
        }

        .display-5 {
            font-family: 'Source Sans Pro', sans-serif;
            font-size: 1.4rem;
        }

        .mbr-bold {
            font-weight: 700;
        }

        p {
            padding-top: 0.5rem;
            padding-bottom: 0.5rem;
            line-height: 25px;
        }

        .display-6 {
            font-family: 'Source Sans Pro', sans-serif;
            font-size: 1rem
        }

        .wrap:hover {
            cursor: default;
        }

    </style>
</head>
<body>
<section>
    <div class="container">
        <div class="row mbr-justify-content-center">
            <div class="col-lg-6 mbr-col-md-10"  data-toggle="list" data-list='["<a href=\"<?php echo site_url("salary-advance") ?>\">Salary Advance</a>"]'>
                <div class="wrap">
                    <div class="ico-wrap">
                        <span class="mbr-iconfont fa-money fa"></span>
                    </div>
                    <div class="text-wrap vcenter">
                        <h2 class="mbr-fonts-style mbr-bold mbr-section-title3 display-5">Finance <span></span></h2>
                        <p class="mbr-fonts-style text1 mbr-text display-6">
                            <span>Salary Advance, etc... <span class="invisible">Coming soon</span> </span>
                            <span class="invisible">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum</span>
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
                            <span class="invisible">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum</span>
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
                            <span class="invisible">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum</span>
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
                            <span class="invisible">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum</span>
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 mbr-col-md-10 coming-soon">
                <div class="wrap">
                    <div class="ico-wrap">
                        <span class="mbr-iconfont fa-user-shield fa"></span>
                    </div>
                    <div class="text-wrap vcenter">
                        <h2 class="mbr-fonts-style mbr-bold mbr-section-title3 display-5">HSE <span></span></h2>
                        <p class="mbr-fonts-style text1 mbr-text display-6">
                            <span>All HSE-related forms. (Coming soon)</span>
                            <span class="invisible">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum</span>
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
                            <span class="invisible">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum</span>
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
