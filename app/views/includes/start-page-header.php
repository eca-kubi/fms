<?php
/**
 * @var StartPageViewModel $view_model;
 */

use ViewModels\StartPageViewModel;

?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="Description" content="Start page for Adamus staff portal.">
    <title>
        <?php echo $view_model->title; ?></title>

    <link rel="preload" href="<?php echo URL_ROOT . '/public/assets/fontastic/fonts/fontastic.woff' ?>" as="font" type="font/woff" crossorigin>

    <link href="<?php echo URL_ROOT; ?>/public/assets/fontastic/critical.css" type="text/css"  rel="stylesheet" as="style"
          media="none" onload="if(media!=='all')media='all'" crossorigin>

    <noscript>
        <link href="<?php echo URL_ROOT; ?>/public/assets/fontastic/critical.css" rel="stylesheet">
    </noscript>

    <link rel="preload" as="font" href="<?php echo URL_ROOT; ?>/public/assets/css/font-awesome-free/webfonts/fa-regular-400.woff2" type="font/woff2" crossorigin>
    <link rel="preload" as="font" href="<?php echo URL_ROOT; ?>/public/assets/css/font-awesome-free/webfonts/fa-solid-900.woff2" type="font/woff2" crossorigin>
    <link rel="stylesheet" as="style" type="text/css" media="none" href="<?php echo URL_ROOT; ?>/public/assets/css/font-awesome-free/css/critical.css" onload="if(media!=='all')media='all'"/>

    <link rel="preload" as="font" href="<?php echo URL_ROOT; ?>/public/assets/css/font-awesome-pro/webfonts/fa-light-300.woff2" type="font/woff2" crossorigin>
    <link rel="preload" as="font" href="<?php echo URL_ROOT; ?>/public/assets/css/font-awesome-pro/webfonts/fa-solid-900.woff2" type="font/woff2" crossorigin>
    <link rel="preload" as="font" href="<?php echo URL_ROOT; ?>/public/assets/css/font-awesome-pro/webfonts/fa-regular-400.woff2" type="font/woff2" crossorigin>
    <link rel="stylesheet" as="style" type="text/css"  media="none" href="<?php echo URL_ROOT; ?>/public/assets/css/font-awesome-pro/css/critical.min.css" onload="if(media!=='all')media='all'"/>

    <noscript>
        <link rel="stylesheet" as="style" media="all" href="<?php echo URL_ROOT; ?>/public/assets/css/font-awesome-free/css/critical.css" onload="rel='stylesheet'"/>
        <link rel="stylesheet" as="style" media="all" href="<?php echo URL_ROOT; ?>/public/assets/css/font-awesome-pro/css/critical.min.css" onload="rel='stylesheet'"/>
    </noscript>

<!--    <link rel="stylesheet" href="<?php /*echo URL_ROOT; */?>/public/assets/css/w3.css"/>-->
    <!--    <link rel="stylesheet" href="<?php /*echo URL_ROOT; */?>/public/assets/css/animate.min.css"/> -->
    <!--    <link rel="stylesheet" href="<?php /*echo URL_ROOT; */?>/public/assets/css/adminlte.css"/> -->
<!--    <link rel="stylesheet" href="<?php /*echo URL_ROOT; */?>/public/assets/css/adminlte-miscellaneous.css"/> -->
    <!--    <link rel="stylesheet" href="<?php /*echo URL_ROOT; */?>/public/assets/css/box-widget.css"/> -->
    <!--    <link rel="stylesheet" media="all" href="<?php /*echo URL_ROOT; */?>/public/assets/css/fa-animate.css"/>-->
<!--    <link rel="stylesheet" href="<?php /*echo URL_ROOT; */?>/public/assets/css/fonts.css"/>
-->
    <!--    <link rel="stylesheet" href="<?php /*echo URL_ROOT; */?>/public/assets/css/tooltips.css"/>-->
    <!--    <link rel="stylesheet" href="<?php /*echo URL_ROOT; */?>/public/assets/css/jquery-toast.min.css"/> -->

<!--    <link rel="stylesheet" media="all" href="<?php /*echo URL_ROOT; */?>/public/assets/css/font-awesome-free/css/v4-shims.min.css"/> -->
    <!--    <link rel="stylesheet" href="<?php /*echo URL_ROOT */?>/public/assets/css/shards.min.css"> -->
<!--    <link rel="stylesheet" href="<?php /*echo URL_ROOT */?>/public/assets/enjoyhint-master/enjoyhint.css">-->
<!--    <link rel="stylesheet" href="<?php /*echo URL_ROOT; */?>/public/custom-assets/css/custom.css?<?php /*echo microtime(); */?>"/> -->
    <style>
        html{box-sizing:border-box}*,*:before,*:after{box-sizing:inherit}html{-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%}body{margin:0}section{display:block}a{background-color:transparent;-webkit-text-decoration-skip:objects}svg:not(:root){overflow:hidden}::-webkit-input-placeholder{color:inherit;opacity:0.54}::-webkit-file-upload-button{-webkit-appearance:button;font:inherit}html,body{font-family:Verdana,sans-serif;font-size:15px;line-height:1.5}html{overflow-x:hidden}h2{font-size:30px}h2{font-family:"Segoe UI",Arial,sans-serif;font-weight:400;margin:10px 0}a{color:inherit}.w3-text-amber{color:#ffc107!important}.w3-text-black{color:#000!important}
        html{height:100%}body{display:flex;flex-direction:column}section{padding-top:0.5rem;background-color:#f1f4fa;flex-grow:1}.wrap{display:flex;background:white;padding:1rem 1rem 1rem 1rem;border-radius:0.5rem;box-shadow:7px 7px 30px -5px rgba(0,0,0,0.1);margin-bottom:1rem}.ico-wrap{margin:1px}.mbr-iconfont{font-size:4.5rem!important;color:#313131;margin:1rem;padding-right:1rem}.mbr-section-title3{text-align:left}h2{margin-top:0.5rem;margin-bottom:0.5rem}.display-5{font-family:'Source Sans Pro',sans-serif;font-size:1.4rem}.mbr-bold{font-weight:700}p{padding-top:0.5rem;padding-bottom:0.5rem;line-height:25px}.display-6{font-family:'Source Sans Pro',sans-serif;font-size:1rem}.tag-coming-soon{position:absolute;right:10px;border-radius:.625rem .625rem 0 0}
        :root{--blue: #007bff;--indigo: #6610f2;--purple: #6f42c1;--pink: #e83e8c;--red: #dc3545;--orange: #fd7e14;--yellow: #ffc107;--green: #28a745;--teal: #20c997;--cyan: #17a2b8;--white: #ffffff;--gray: #6c757d;--gray-dark: #343a40;--primary: #007bff;--secondary: #6c757d;--success: #28a745;--info: #17a2b8;--warning: #ffc107;--danger: #dc3545;--light: #f8f9fa;--dark: #343a40;--breakpoint-xs: 0;--breakpoint-sm: 576px;--breakpoint-md: 768px;--breakpoint-lg: 992px;--breakpoint-xl: 1200px;--font-family-sans-serif: "Source Sans Pro", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol";--font-family-monospace: SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace}*,*::before,*::after{box-sizing:border-box}html{font-family:sans-serif;line-height:1.15;-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%;-ms-overflow-style:scrollbar}@-ms-viewport{width:device-width}section{display:block}body{margin:0;font-family:"Source Sans Pro",-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif,"Apple Color Emoji","Segoe UI Emoji","Segoe UI Symbol";font-size:1rem;font-weight:400;line-height:1.5;color:#212529;text-align:left;background-color:#ffffff}h2{margin-top:0;margin-bottom:0.5rem}p{margin-top:0;margin-bottom:1rem}a{color:#007bff;text-decoration:none;background-color:transparent;-webkit-text-decoration-skip:objects}svg:not(:root){overflow:hidden}input{margin:0;font-family:inherit;font-size:inherit;line-height:inherit}input{overflow:visible}::-webkit-file-upload-button{font:inherit;-webkit-appearance:button}h2,.h3{margin-bottom:0.5rem;font-family:inherit;font-weight:500;line-height:1.2;color:inherit}h2{font-size:2rem}.h3{font-size:1.75rem}.container{width:100%;padding-right:7.5px;padding-left:7.5px;margin-right:auto;margin-left:auto}@media (min-width:576px){.container{max-width:540px}}@media (min-width:768px){.container{max-width:720px}}@media (min-width:992px){.container{max-width:960px}}@media (min-width:1200px){.container{max-width:1140px}}.row{display:flex;flex-wrap:wrap;margin-right:-7.5px;margin-left:-7.5px}.col-lg-4{position:relative;width:100%;min-height:1px;padding-right:7.5px;padding-left:7.5px}@media (min-width:992px){.col-lg-4{flex:0 0 33.333333%;max-width:33.333333%}}.btn{display:inline-block;font-weight:400;text-align:center;white-space:nowrap;vertical-align:middle;border:1px solid transparent;padding:0.375rem 0.75rem;font-size:1rem;line-height:1.5;border-radius:0.25rem}.btn-primary{color:#ffffff;background-color:#007bff;border-color:#007bff;box-shadow:0 1px 1px rgba(0,0,0,0.075)}.badge{display:inline-block;padding:0.25em 0.4em;font-size:75%;font-weight:700;line-height:1;text-align:center;white-space:nowrap;vertical-align:baseline;border-radius:0.25rem}.badge-pill{padding-right:0.6em;padding-left:0.6em;border-radius:10rem}.border{border:1px solid #dee2e6!important}.d-none{display:none!important}.d-block{display:block!important}.mb-0{margin-bottom:0!important}.m-1{margin:0.25rem!important}.m-2{margin:0.5rem!important}.mb-2{margin-bottom:0.5rem!important}.ml-auto{margin-left:auto!important}.text-success{color:#28a745!important}.invisible{visibility:hidden!important}html,body{min-height:100%;overflow-x:hidden}.text-sm{font-size:0.875rem}
        :root{--blue:#007bff;--indigo:#674eec;--purple:#8445f7;--pink:#ff4169;--red:#c4183c;--orange:#fb7906;--yellow:#ffb400;--green:#17c671;--teal:#1adba2;--cyan:#00b8d8;--white:#fff;--gray:#868e96;--gray-dark:#343a40;--primary:#007bff;--secondary:#5A6169;--success:#17c671;--info:#00b8d8;--warning:#ffb400;--danger:#c4183c;--light:#e9ecef;--dark:#212529;--breakpoint-xs:0;--breakpoint-sm:576px;--breakpoint-md:768px;--breakpoint-lg:992px;--breakpoint-xl:1200px;--font-family-sans-serif:-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif;--font-family-monospace:"Roboto Mono",Menlo,Monaco,Consolas,"Liberation Mono","Courier New",monospace}@media (max-width:575.98px){html{font-size:15px}}body{font-size:1rem;font-weight:300;color:#5a6169;background-color:#fff}a{color:#007bff;text-decoration:none}h2{margin-top:0;margin-bottom:.5rem}.h3{display:block}.h3,h2{margin-bottom:.75rem;font-family:Poppins,-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif;font-weight:400;color:#212529}h2{font-size:2.441rem;letter-spacing:-.0625rem;line-height:2.25rem}.h3{font-size:1.953rem;line-height:2.25rem}p{margin-bottom:1.75rem}.btn{font-weight:300;font-family:Poppins,-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif;border:1px solid transparent;padding:.75rem 1.25rem;font-size:.875rem;line-height:1.125;border-radius:.375rem}.btn.btn-pill{border-radius:50px}.btn-primary{color:#fff;border-color:#007bff;background-color:#007bff;box-shadow:none}.badge{padding:.375rem .5rem;font-size:75%;font-weight:500;font-family:-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif;color:#fff;border-radius:.375rem}.badge-pill{padding-right:.5rem;padding-left:.5rem;border-radius:10rem}.badge-outline-secondary{background:0 0;border:1px solid #5a6169;color:#5a6169}.border{border:1px solid #becad6!important}.text-success{color:#17c671!important}.text-black{color:#000}
        @-webkit-keyframes fadeInRight{0%{opacity:0;-webkit-transform:translate3d(100%,0,0);transform:translate3d(100%,0,0)}to{opacity:1;-webkit-transform:translateZ(0);transform:translateZ(0)}}@keyframes fadeInRight{0%{opacity:0;-webkit-transform:translate3d(100%,0,0);transform:translate3d(100%,0,0)}to{opacity:1;-webkit-transform:translateZ(0);transform:translateZ(0)}}.fadeInRight{-webkit-animation-name:fadeInRight;animation-name:fadeInRight}.animated{-webkit-animation-duration:1s;animation-duration:1s;-webkit-animation-fill-mode:both;animation-fill-mode:both}@media (prefers-reduced-motion:reduce),(print){.animated{-webkit-animation-duration:1ms!important;animation-duration:1ms!important;-webkit-animation-iteration-count:1!important;animation-iteration-count:1!important}}
        .fa.fa-sign-out:before{content:"\f2f5"}.fa.fa-money{font-family:"Font Awesome 5 Pro";font-weight:400}.fa.fa-money:before{content:"\f3d1"}.fa.fa-user-o{font-family:"Font Awesome 5 Pro";font-weight:400}.fa.fa-user-o:before{content:"\f007"}
        ::-ms-reveal,::-ms-clear{display:none!important}body{scroll-behavior:smooth;height:auto;min-height:100%}html{height:auto;min-height:100%;overflow:hidden}.wrap{white-space:normal}html{-ms-overflow-style:none}:-moz-submit-invalid{box-shadow:none}:-moz-ui-invalid{box-shadow:none}@media (min-width:425px) and (max-width:767px){p{font-size:x-small}}.wrap{height:200px}.badge{font-size:85%}
        .text-black{color:#111111!important}.text-sm{font-size:12px}
        html {
            height: 100vh;
            min-height: 100%;
            overflow: hidden;
        }
        html:hover {
            overflow: auto;
        }

    </style>
</head>
<body>
<section class="animated fadeInRight m-2 border section-1"
         style="height: auto;background-image: url(<?php echo URL_ROOT; ?>/public/assets/images/bgs/background.png)">
    <div class="container">
        <div class="row">
            <span class="m-l"><a href="javascript:window.history.back();"
                                 class="btn btn-pill btn-primary <?php echo isset($sub_page) ? '' : 'd-none'; ?> "><i
                            class="fa fa-caret-left"></i> Go Back</a></span>
            <span class="m-1 ml-auto">
                    <a href="<?php echo site_url('users/logout') ?>" class="btn btn-pill btn-primary logout"><i
                                class="fa fa-sign-out"></i>&nbsp;Logout</a>
            </span>
        </div>
        <div class="row mb-2" style="border-bottom: 4px solid #eedaa8!important">
            <span class="h3">
              <i class="fal fa-folder-open"></i> <?php echo $view_model->sub_page? "<a href='" . site_url('start-page') . "' class='w3-text-black'>Forms</a>" . " / <span class='w3-text-gray'>$view_model->sub_page</span> " : 'Forms' ?>
            </span>
        </div>
