<?php ?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php /** @var array $payload */
        echo $payload['title']; ?></title>
<link href="<?php echo URL_ROOT; ?>/public/assets/fontastic/styles.css" rel="stylesheet">
<link rel="stylesheet" href="<?php echo URL_ROOT; ?>/public/assets/css/w3.css"/>
<link rel="stylesheet" href="<?php echo URL_ROOT; ?>/public/assets/css/animate.css"/>
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
        margin: 1px;
    }

    .mbr-iconfont {
        font-size: 4.5rem !important;
        color: #313131;
        margin: 1rem;
        padding-right: 1rem;
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

    .desc {
        border-bottom: 0.5rem solid #b2dba0;
        border-top: 0.5rem solid #b2dba0;
    }
    
    .vcenter {
        margin: auto;
    }
</style>
</head>