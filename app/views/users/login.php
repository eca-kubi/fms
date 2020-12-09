<?php
/**
 *@var LoginViewModel $view_model
 */

use ViewModels\LoginViewModel;

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge"/>
    <title>
        <?php echo $view_model->title; ?>
    </title>
<!--    <script src="<?php /*echo URL_ROOT; */?>/public/assets/js/jquery.min.js"></script>
-->
    <link rel="icon" href="<?php echo URL_ROOT; ?>/public/favicon.ico" type="image/x-icon"/>
<!--    <link rel="stylesheet" href="<?php /*echo URL_ROOT; */?>/public/assets/css/AdminLTE.min.css"/>
-->
<!--    <link rel="stylesheet" href="<?php /*echo URL_ROOT; */?>/public/assets/css/critical.css"/>
-->
<!--<link rel="stylesheet" href="<?php /*echo URL_ROOT; */?>/public/assets/css/bootstrap.min.css"/>
-->
    <link rel="stylesheet" href="<?php echo URL_ROOT; ?>/public/assets/css/fonts.css"/>
<!--    <link rel="stylesheet" href="<?php /*echo URL_ROOT; */?>/public/assets/css/font-awesome-pro/css/all.min.css"/>
-->
<!--    <link rel="stylesheet" href="<?php /*echo URL_ROOT; */?>/public/custom-assets/css/custom.css"/>
-->

    <style>
        :root{--blue:#007bff;--indigo:#6610f2;--purple:#6f42c1;--pink:#e83e8c;--red:#dc3545;--orange:#fd7e14;--yellow:#ffc107;--green:#28a745;--teal:#20c997;--cyan:#17a2b8;--white:#fff;--gray:#6c757d;--gray-dark:#343a40;--primary:#007bff;--secondary:#6c757d;--success:#28a745;--info:#17a2b8;--warning:#ffc107;--danger:#dc3545;--light:#f8f9fa;--dark:#343a40;--breakpoint-xs:0;--breakpoint-sm:576px;--breakpoint-md:768px;--breakpoint-lg:992px;--breakpoint-xl:1200px;--font-family-sans-serif:-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,"Noto Sans",sans-serif,"Apple Color Emoji","Segoe UI Emoji","Segoe UI Symbol","Noto Color Emoji";--font-family-monospace:SFMono-Regular,Menlo,Monaco,Consolas,"Liberation Mono","Courier New",monospace}*,::after,::before{box-sizing:border-box}html{font-family:sans-serif;line-height:1.15;-webkit-text-size-adjust:100%}body{margin:0;font-family:-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,"Noto Sans",sans-serif,"Apple Color Emoji","Segoe UI Emoji","Segoe UI Symbol","Noto Color Emoji";font-size:1rem;font-weight:400;line-height:1.5;color:#212529;text-align:left;background-color:#fff}h1,h4,h5,h6{margin-top:0;margin-bottom:.5rem}p{margin-top:0;margin-bottom:1rem}small{font-size:80%}a{color:#007bff;text-decoration:none;background-color:transparent}button{border-radius:0}button,input{margin:0;font-family:inherit;font-size:inherit;line-height:inherit}button,input{overflow:visible}button{text-transform:none}[type=button],[type=submit],button{-webkit-appearance:button}[type=button]::-moz-focus-inner,[type=submit]::-moz-focus-inner,button::-moz-focus-inner{padding:0;border-style:none}fieldset{min-width:0;padding:0;margin:0;border:0}::-webkit-file-upload-button{font:inherit;-webkit-appearance:button}h1,h4,h5,h6{margin-bottom:.5rem;font-weight:500;line-height:1.2}h1{font-size:2.5rem}h4{font-size:1.5rem}h5{font-size:1.25rem}h6{font-size:1rem}small{font-size:80%;font-weight:400}.row{display:-ms-flexbox;display:flex;-ms-flex-wrap:wrap;flex-wrap:wrap;margin-right:-15px;margin-left:-15px}.col,.col-10,.col-md-1,.col-md-10,.col-md-11,.col-md-12,.col-md-2,.col-md-3,.col-md-4,.col-md-5,.col-md-6,.col-md-7,.col-md-8,.col-md-9,.col-sm-1,.col-sm-10,.col-sm-11,.col-sm-12,.col-sm-2,.col-sm-3,.col-sm-4,.col-sm-5,.col-sm-6,.col-sm-7,.col-sm-8,.col-sm-9,.col-xl-1,.col-xl-10,.col-xl-11,.col-xl-12,.col-xl-2,.col-xl-3,.col-xl-4,.col-xl-5,.col-xl-6,.col-xl-7,.col-xl-8,.col-xl-9{position:relative;width:100%;padding-right:15px;padding-left:15px}.col{-ms-flex-preferred-size:0;flex-basis:0;-ms-flex-positive:1;flex-grow:1;max-width:100%}.col-10{-ms-flex:0 0 83.333333%;flex:0 0 83.333333%;max-width:83.333333%}@media (min-width:576px){.col-sm-1{-ms-flex:0 0 8.333333%;flex:0 0 8.333333%;max-width:8.333333%}.col-sm-2{-ms-flex:0 0 16.666667%;flex:0 0 16.666667%;max-width:16.666667%}.col-sm-3{-ms-flex:0 0 25%;flex:0 0 25%;max-width:25%}.col-sm-4{-ms-flex:0 0 33.333333%;flex:0 0 33.333333%;max-width:33.333333%}.col-sm-5{-ms-flex:0 0 41.666667%;flex:0 0 41.666667%;max-width:41.666667%}.col-sm-6{-ms-flex:0 0 50%;flex:0 0 50%;max-width:50%}.col-sm-7{-ms-flex:0 0 58.333333%;flex:0 0 58.333333%;max-width:58.333333%}.col-sm-8{-ms-flex:0 0 66.666667%;flex:0 0 66.666667%;max-width:66.666667%}.col-sm-9{-ms-flex:0 0 75%;flex:0 0 75%;max-width:75%}.col-sm-10{-ms-flex:0 0 83.333333%;flex:0 0 83.333333%;max-width:83.333333%}.col-sm-11{-ms-flex:0 0 91.666667%;flex:0 0 91.666667%;max-width:91.666667%}.col-sm-12{-ms-flex:0 0 100%;flex:0 0 100%;max-width:100%}}@media (min-width:768px){.col-md-1{-ms-flex:0 0 8.333333%;flex:0 0 8.333333%;max-width:8.333333%}.col-md-2{-ms-flex:0 0 16.666667%;flex:0 0 16.666667%;max-width:16.666667%}.col-md-3{-ms-flex:0 0 25%;flex:0 0 25%;max-width:25%}.col-md-4{-ms-flex:0 0 33.333333%;flex:0 0 33.333333%;max-width:33.333333%}.col-md-5{-ms-flex:0 0 41.666667%;flex:0 0 41.666667%;max-width:41.666667%}.col-md-6{-ms-flex:0 0 50%;flex:0 0 50%;max-width:50%}.col-md-7{-ms-flex:0 0 58.333333%;flex:0 0 58.333333%;max-width:58.333333%}.col-md-8{-ms-flex:0 0 66.666667%;flex:0 0 66.666667%;max-width:66.666667%}.col-md-9{-ms-flex:0 0 75%;flex:0 0 75%;max-width:75%}.col-md-10{-ms-flex:0 0 83.333333%;flex:0 0 83.333333%;max-width:83.333333%}.col-md-11{-ms-flex:0 0 91.666667%;flex:0 0 91.666667%;max-width:91.666667%}.col-md-12{-ms-flex:0 0 100%;flex:0 0 100%;max-width:100%}}@media (min-width:1200px){.col-xl-1{-ms-flex:0 0 8.333333%;flex:0 0 8.333333%;max-width:8.333333%}.col-xl-2{-ms-flex:0 0 16.666667%;flex:0 0 16.666667%;max-width:16.666667%}.col-xl-3{-ms-flex:0 0 25%;flex:0 0 25%;max-width:25%}.col-xl-4{-ms-flex:0 0 33.333333%;flex:0 0 33.333333%;max-width:33.333333%}.col-xl-5{-ms-flex:0 0 41.666667%;flex:0 0 41.666667%;max-width:41.666667%}.col-xl-6{-ms-flex:0 0 50%;flex:0 0 50%;max-width:50%}.col-xl-7{-ms-flex:0 0 58.333333%;flex:0 0 58.333333%;max-width:58.333333%}.col-xl-8{-ms-flex:0 0 66.666667%;flex:0 0 66.666667%;max-width:66.666667%}.col-xl-9{-ms-flex:0 0 75%;flex:0 0 75%;max-width:75%}.col-xl-10{-ms-flex:0 0 83.333333%;flex:0 0 83.333333%;max-width:83.333333%}.col-xl-11{-ms-flex:0 0 91.666667%;flex:0 0 91.666667%;max-width:91.666667%}.col-xl-12{-ms-flex:0 0 100%;flex:0 0 100%;max-width:100%}}.form-control{display:block;width:100%;height:calc(1.5em + .75rem + 2px);padding:.375rem .75rem;font-size:1rem;font-weight:400;line-height:1.5;color:#495057;background-color:#fff;background-clip:padding-box;border:1px solid #ced4da;border-radius:.25rem}.form-control::-ms-expand{background-color:transparent;border:0}.form-control::-webkit-input-placeholder{color:#6c757d;opacity:1}.form-control::-moz-placeholder{color:#6c757d;opacity:1}.form-control:-ms-input-placeholder{color:#6c757d;opacity:1}.form-control::-ms-input-placeholder{color:#6c757d;opacity:1}.form-group{margin-bottom:1rem}.form-row{display:-ms-flexbox;display:flex;-ms-flex-wrap:wrap;flex-wrap:wrap;margin-right:-5px;margin-left:-5px}.form-row>[class*=col-]{padding-right:5px;padding-left:5px}.btn{display:inline-block;font-weight:400;color:#212529;text-align:center;vertical-align:middle;background-color:transparent;border:1px solid transparent;padding:.375rem .75rem;font-size:1rem;line-height:1.5;border-radius:.25rem}.btn-secondary{color:#fff;background-color:#6c757d;border-color:#6c757d}.fade:not(.show){opacity:0}.alert{position:relative;padding:.75rem 1.25rem;margin-bottom:1rem;border:1px solid transparent;border-radius:.25rem}.close{float:right;font-size:1.5rem;font-weight:700;line-height:1;color:#000;text-shadow:0 1px 0 #fff;opacity:.5}button.close{padding:0;background-color:transparent;border:0;-webkit-appearance:none;-moz-appearance:none;appearance:none}.modal{position:fixed;top:0;left:0;z-index:1050;display:none;width:100%;height:100%;overflow:hidden;outline:0}.modal-dialog{position:relative;width:auto;margin:.5rem}.modal.fade .modal-dialog{-webkit-transform:translate(0,-50px);transform:translate(0,-50px)}.modal-content{position:relative;display:-ms-flexbox;display:flex;-ms-flex-direction:column;flex-direction:column;width:100%;background-color:#fff;background-clip:padding-box;border:1px solid rgba(0,0,0,.2);border-radius:.3rem;outline:0}.modal-header{display:-ms-flexbox;display:flex;-ms-flex-align:start;align-items:flex-start;-ms-flex-pack:justify;justify-content:space-between;padding:1rem 1rem;border-bottom:1px solid #dee2e6;border-top-left-radius:.3rem;border-top-right-radius:.3rem}.modal-header .close{padding:1rem 1rem;margin:-1rem -1rem -1rem auto}.modal-title{margin-bottom:0;line-height:1.5}.modal-body{position:relative;-ms-flex:1 1 auto;flex:1 1 auto;padding:1rem}.modal-footer{display:-ms-flexbox;display:flex;-ms-flex-align:center;align-items:center;-ms-flex-pack:end;justify-content:flex-end;padding:1rem;border-top:1px solid #dee2e6;border-bottom-right-radius:.3rem;border-bottom-left-radius:.3rem}.modal-footer>:not(:first-child){margin-left:.25rem}.modal-footer>:not(:last-child){margin-right:.25rem}@media (min-width:576px){.modal-dialog{max-width:500px;margin:1.75rem auto}}.bg-dark{background-color:#343a40!important}.d-none{display:none!important}.d-block{display:block!important}@media (min-width:768px){.d-md-none{display:none!important}.d-md-block{display:block!important}}.float-left{float:left!important}.float-right{float:right!important}.m-1{margin:.25rem!important}.mt-2{margin-top:.5rem!important}.mb-2{margin-bottom:.5rem!important}.mt-5{margin-top:3rem!important}.mr-5{margin-right:3rem!important}.py-0{padding-top:0!important}.py-0{padding-bottom:0!important}.p-2{padding:.5rem!important}.pt-2{padding-top:.5rem!important}.m-auto{margin:auto!important}.mx-auto{margin-right:auto!important}.mx-auto{margin-left:auto!important}@media (min-width:768px){.pb-md-2{padding-bottom:.5rem!important}}.text-left{text-align:left!important}.text-center{text-align:center!important}.text-uppercase{text-transform:uppercase!important}.text-capitalize{text-transform:capitalize!important}.font-weight-normal{font-weight:400!important}.font-weight-bold{font-weight:700!important}.text-white{color:#fff!important}.text-danger{color:#dc3545!important}
        html,body{height:100%}body{font-family:'Source Sans Pro','Helvetica Neue',Helvetica,Arial,sans-serif;font-weight:400;overflow-x:hidden;overflow-y:auto}h1,h4,h5,h6{font-family:'Source Sans Pro',sans-serif}a{color:#3c8dbc}.form-control{border-radius:0;box-shadow:none;border-color:#d2d6de}.form-control::-moz-placeholder,.form-control:-ms-input-placeholder,.form-control::-webkit-input-placeholder{color:#bbb;opacity:1}.form-control:not(select){-webkit-appearance:none;-moz-appearance:none;appearance:none}.btn{border-radius:3px;-webkit-box-shadow:none;box-shadow:none;border:1px solid transparent}.modal{background:rgba(0,0,0,0.3)}.modal-content{border-radius:0;-webkit-box-shadow:0 2px 3px rgba(0,0,0,0.125);box-shadow:0 2px 3px rgba(0,0,0,0.125);border:0}@media (min-width:768px){.modal-content{-webkit-box-shadow:0 2px 3px rgba(0,0,0,0.125);box-shadow:0 2px 3px rgba(0,0,0,0.125)}}.modal-header{border-bottom-color:#f4f4f4}.modal-footer{border-top-color:#f4f4f4}.text-purple{color:#605ca8!important}*{-webkit-box-sizing:border-box;box-sizing:border-box}body{padding:0;margin:0}#notfound{position:relative;height:100vh}#notfound .notfound-bg{position:absolute;width:100%;height:100%;background-size:cover}#notfound .notfound-bg:after{content:'';position:absolute;width:100%;height:100%;background-color:rgba(0,0,0,0.25)}#notfound .notfound{position:absolute;left:50%;top:50%;-webkit-transform:translate(-50%,-50%);-ms-transform:translate(-50%,-50%);transform:translate(-50%,-50%)}#notfound .notfound:after{content:'';position:absolute;left:50%;top:50%;-webkit-transform:translate(-50%,-50%);-ms-transform:translate(-50%,-50%);transform:translate(-50%,-50%);width:100%;height:600px;background-color:rgba(255,255,255,0.7);-webkit-box-shadow:0 0 0 30px rgba(255,255,255,0.7) inset;box-shadow:0 0 0 30px rgba(255,255,255,0.7) inset;z-index:-1}.notfound{max-width:600px;width:100%;text-align:center;padding:30px;line-height:1.4}.notfound .notfound-404{position:relative;height:200px}.notfound .notfound-404 h1{font-family:'Passion One',cursive;position:absolute;left:50%;top:50%;-webkit-transform:translate(-50%,-50%);-ms-transform:translate(-50%,-50%);transform:translate(-50%,-50%);font-size:220px;margin:0;color:#222225;text-transform:uppercase}input{font-family:'Muli',sans-serif;width:100%;height:40px;padding:3px 15px;color:#fff;font-weight:400;font-size:18px;background:#222225;border:none}.notfound a{font-family:'Muli',sans-serif;display:inline-block;font-weight:400;text-decoration:none;background-color:transparent;text-transform:uppercase;font-size:14px}@media only screen and (max-width:480px){.notfound .notfound-404{height:146px}.notfound .notfound-404 h1{font-size:146px}}:root{--blue:#007bff;--indigo:#6610f2;--purple:#6f42c1;--pink:#e83e8c;--red:#dc3545;--orange:#fd7e14;--yellow:#ffc107;--green:#28a745;--teal:#20c997;--cyan:#17a2b8;--white:#fff;--gray:#6c757d;--gray-dark:#343a40;--primary:#007bff;--secondary:#6c757d;--success:#28a745;--info:#17a2b8;--warning:#ffc107;--danger:#dc3545;--light:#f8f9fa;--dark:#343a40;--breakpoint-xs:0;--breakpoint-sm:576px;--breakpoint-md:768px;--breakpoint-lg:992px;--breakpoint-xl:1200px;--font-family-sans-serif:-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,"Noto Sans",sans-serif,"Apple Color Emoji","Segoe UI Emoji","Segoe UI Symbol","Noto Color Emoji";--font-family-monospace:SFMono-Regular,Menlo,Monaco,Consolas,"Liberation Mono","Courier New",monospace}*,::after,::before{box-sizing:border-box}html{font-family:sans-serif;line-height:1.15;-webkit-text-size-adjust:100%}body{margin:0;font-family:-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,"Noto Sans",sans-serif,"Apple Color Emoji","Segoe UI Emoji","Segoe UI Symbol","Noto Color Emoji";font-size:1rem;font-weight:400;line-height:1.5;color:#212529;text-align:left;background-color:#fff}h1,h4,h5,h6{margin-top:0;margin-bottom:.5rem}p{margin-top:0;margin-bottom:1rem}small{font-size:80%}a{color:#007bff;text-decoration:none;background-color:transparent}button{border-radius:0}button,input{margin:0;font-family:inherit;font-size:inherit;line-height:inherit}button,input{overflow:visible}button{text-transform:none}[type=button],[type=submit],button{-webkit-appearance:button}[type=button]::-moz-focus-inner,[type=submit]::-moz-focus-inner,button::-moz-focus-inner{padding:0;border-style:none}fieldset{min-width:0;padding:0;margin:0;border:0}::-webkit-file-upload-button{font:inherit;-webkit-appearance:button}h1,h4,h5,h6{margin-bottom:.5rem;font-weight:500;line-height:1.2}h1{font-size:2.5rem}h4{font-size:1.5rem}h5{font-size:1.25rem}h6{font-size:1rem}small{font-size:80%;font-weight:400}.row{display:-ms-flexbox;display:flex;-ms-flex-wrap:wrap;flex-wrap:wrap;margin-right:-15px;margin-left:-15px}.col,.col-10,.col-sm-12{position:relative;width:100%;padding-right:15px;padding-left:15px}.col{-ms-flex-preferred-size:0;flex-basis:0;-ms-flex-positive:1;flex-grow:1;max-width:100%}.col-10{-ms-flex:0 0 83.333333%;flex:0 0 83.333333%;max-width:83.333333%}@media (min-width:576px){.col-sm-12{-ms-flex:0 0 100%;flex:0 0 100%;max-width:100%}}.form-control{display:block;width:100%;height:calc(1.5em + .75rem + 2px);padding:.375rem .75rem;font-size:1rem;font-weight:400;line-height:1.5;color:#495057;background-color:#fff;background-clip:padding-box;border:1px solid #ced4da;border-radius:.25rem}.form-control::-ms-expand{background-color:transparent;border:0}.form-control::-webkit-input-placeholder{color:#6c757d;opacity:1}.form-control::-moz-placeholder{color:#6c757d;opacity:1}.form-control:-ms-input-placeholder{color:#6c757d;opacity:1}.form-control::-ms-input-placeholder{color:#6c757d;opacity:1}.form-group{margin-bottom:1rem}.form-row{display:-ms-flexbox;display:flex;-ms-flex-wrap:wrap;flex-wrap:wrap;margin-right:-5px;margin-left:-5px}.form-row>[class*=col-]{padding-right:5px;padding-left:5px}.btn{display:inline-block;font-weight:400;color:#212529;text-align:center;vertical-align:middle;background-color:transparent;border:1px solid transparent;padding:.375rem .75rem;font-size:1rem;line-height:1.5;border-radius:.25rem}.btn-secondary{color:#fff;background-color:#6c757d;border-color:#6c757d}.fade:not(.show){opacity:0}.close{float:right;font-size:1.5rem;font-weight:700;line-height:1;color:#000;text-shadow:0 1px 0 #fff;opacity:.5}button.close{padding:0;background-color:transparent;border:0;-webkit-appearance:none;-moz-appearance:none;appearance:none}.modal{position:fixed;top:0;left:0;z-index:1050;display:none;width:100%;height:100%;overflow:hidden;outline:0}.modal-dialog{position:relative;width:auto;margin:.5rem}.modal.fade .modal-dialog{-webkit-transform:translate(0,-50px);transform:translate(0,-50px)}.modal-content{position:relative;display:-ms-flexbox;display:flex;-ms-flex-direction:column;flex-direction:column;width:100%;background-color:#fff;background-clip:padding-box;border:1px solid rgba(0,0,0,.2);border-radius:.3rem;outline:0}.modal-header{display:-ms-flexbox;display:flex;-ms-flex-align:start;align-items:flex-start;-ms-flex-pack:justify;justify-content:space-between;padding:1rem 1rem;border-bottom:1px solid #dee2e6;border-top-left-radius:.3rem;border-top-right-radius:.3rem}.modal-header .close{padding:1rem 1rem;margin:-1rem -1rem -1rem auto}.modal-title{margin-bottom:0;line-height:1.5}.modal-body{position:relative;-ms-flex:1 1 auto;flex:1 1 auto;padding:1rem}.modal-footer{display:-ms-flexbox;display:flex;-ms-flex-align:center;align-items:center;-ms-flex-pack:end;justify-content:flex-end;padding:1rem;border-top:1px solid #dee2e6;border-bottom-right-radius:.3rem;border-bottom-left-radius:.3rem}.modal-footer>:not(:first-child){margin-left:.25rem}.modal-footer>:not(:last-child){margin-right:.25rem}@media (min-width:576px){.modal-dialog{max-width:500px;margin:1.75rem auto}}.bg-dark{background-color:#343a40!important}.d-none{display:none!important}.d-block{display:block!important}@media (min-width:768px){.d-md-none{display:none!important}.d-md-block{display:block!important}}.float-left{float:left!important}.float-right{float:right!important}.m-1{margin:.25rem!important}.mt-2{margin-top:.5rem!important}.mb-2{margin-bottom:.5rem!important}.mt-5{margin-top:3rem!important}.mr-5{margin-right:3rem!important}.py-0{padding-top:0!important}.py-0{padding-bottom:0!important}.p-2{padding:.5rem!important}.pt-2{padding-top:.5rem!important}.mx-auto{margin-right:auto!important}.mx-auto{margin-left:auto!important}@media (min-width:768px){.pb-md-2{padding-bottom:.5rem!important}}.text-left{text-align:left!important}.text-center{text-align:center!important}.text-uppercase{text-transform:uppercase!important}.text-capitalize{text-transform:capitalize!important}.font-weight-normal{font-weight:400!important}.font-weight-bold{font-weight:700!important}.text-white{color:#fff!important}::-ms-reveal,::-ms-clear{display:none!important}body{scroll-behavior:smooth;height:auto;min-height:100%}html{height:auto;min-height:100%;overflow:hidden}input:not([type="hidden"]):not([type="submit"]):not([type="reset"]):not(button){border-radius:0!important}.with-errors{color:#dc3545}.font-passion-one{font-family:'Passion One',sans-serif}.bg-nzema-mine{background-image:url('/public/assets/images/bgs/Nzema-mine.jpg');background-attachment:fixed;background-size:cover;background-position:center}html{-ms-overflow-style:none}:-moz-submit-invalid{box-shadow:none}:-moz-ui-invalid{box-shadow:none}@media (min-width:425px) and (max-width:767px){p{font-size:x-small}}
    button.close {display: none}
    </style>
</head>
<body class="bg-nzema-mine">
<div id="notfound">
    <div class="notfound-bg"></div>
    <div class="notfound">
        <div class="notfound-404">
            <div>
                <h1 class="d-md-block d-none" style="font-size: 50px">FMS Login</h1>
                <h4 class="pt-2">Form Management System</h4>
            </div>
            <h5 class="font-passion-one d-md-none text-uppercase text-center">CMS Login</h5>
        </div>
        <?php  flash('flash_login') ?>
        <!-- .col-10 -->
        <div class="col-10 mx-auto">
            <!-- .row -->
            <div class="row">
                <div class="col mb-2">
                    <div id="user_login">
                        <form action="<?php echo site_url('users/login/' . $view_model->redirect_url) ?>"
                              enctype="multipart/form-data"
                              method="post" role="form"
                              data-toggle="validator">
                            <fieldset class="py-0 text-left fa font-weight-normal col-sm-12 p-2">
                                <small class="font-weight-bold text-purple text-center d-block mb-2">
                                    Password is case sensitive.
                                </small>
                                <div class="form-group form-row">
                                    <div class="col-sm-12">
                                        <input type="text" id="staff_id" name="staff_id"
                                               class="<?php //echo !empty($payload['post']->staff_id_err)? 'border-danger-4': '' ?>"
                                               placeholder="USERNAME" aria-describedby="helpId"
                                               value="<?php echo $view_model->staff_id? : '' ?>"
                                               required/>
                                        <small class="with-errors help-block d-block">
                                            <?php //echo isset($payload['post']->staff_id_err)? $payload['post']->staff_id_err: '' ?>
                                        </small>
                                    </div>
                                </div>
                                <div class="form-group form-row">
                                    <div class="col-sm-12">
                                        <input type="password" placeholder="PASSWORD" name="password"
                                               class="<?php //echo !empty($payload['post']->password_err)? 'border-danger-4': '' ?>"
                                               aria-describedby="helpId"
                                               required/>
                                        <small class="with-errors help-block d-block">
                                            <?php //echo !empty($payload['post']->password_err)? $payload['post']->password_err: '' ?>
                                        </small>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn bg-dark text-white w3-btn text-uppercase">Submit
                                    </button>
                                    <a class="float-right mt-2" id="forgot_password" href="#"
                                       data-target="#forgot_password_modal" data-toggle="modal" style="color: #222225;">
                                        Forgot Password?
                                    </a>
                                </div>
                            </fieldset>
                        </form>
                    </div>
                </div>
            </div>
            <!-- /.row -->
        </div>
        <!-- /.col-10 -->

        <div class="text-capitalize mt-5 pb-md-2">
            <a class="mr-5" href="<?php echo HOST; ?>">
                <i class="fa fa-window"></i> Adamus Apps
            </a>
            <a class="" href="<?php echo INTRANET; ?>" target="_blank">
                <i class="fa fa-cloud"></i> Intranet
            </a>
        </div>
    </div>
</div>
<!-- Forgot Password Modal -->
<div class="modal fade" id="forgot_password_modal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content fa font-weight-normal">
            <div class="modal-header">
                <h6 class="modal-title" id="modelTitleId">Forgot Password?</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="" id="forgot_password_user_login">
                    <form method="post" action="<?php echo site_url('users/forgot-password'); ?>"
                          id="forgot_password_form" role="form">
                        <p>Send us your email and we'll reset it for you!</p>
                        <div class="form-group">
                            <input type="email" class="form-control" name="email" placeholder="Email" required/>
                            <small class="with-errors help-block m-1"></small>
                        </div>
                    </form>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" form="forgot_password_form" class="btn btn-secondary float-left">Submit</button>
                <a href="#user_login" data-dismiss="modal">Wait, I remember it now!</a>
            </div>
        </div>
    </div>
</div>
<!--<script src="<?php /*echo URL_ROOT; */?>/public/assets/js/bootstrap.bundle.js"></script>
-->
<script>
    document.addEventListener('DOMContentLoaded', (event) => {
        document.getElementById('staff_id').focus()
    })
</script>
</body>
</html>
