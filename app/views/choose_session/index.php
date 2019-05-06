<!DOCTYPE html>
<html lang="">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php /** @var string $title */
        echo $title; ?></title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="<?php echo URL_ROOT; ?>/public/favicon.ico" type="image/x-icon"/>
    <link rel="stylesheet" href="<?php echo URL_ROOT; ?>/public/assets/css/bootstrap.css"/>
    <link rel="stylesheet" href="<?php echo URL_ROOT; ?>/public/assets/css/adminlte.css"/>
    <link rel="stylesheet" href="<?php echo URL_ROOT; ?>/public/assets/css/all.css"/>

</head>

<body class="hold-transition sidebar-mini sidebar-collapse">

<div class="wrapper">

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header d-none">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h4>Services Management System (SMS)</h4>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item">
                                <a href="/sms">Services</a>
                            </li>

                        </ol>
                    </div>
                </div>
            </div>
            <!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content invisible">
            <div class="container-fluid fa col-12 d-none">
                <div class="navigation m-2 ">
                    <a href="#" onclick="window.history.back(); return false;"
                       class="btn btn-default btn-lg w3-hover-text-grey btn-sm"><i
                                class="fa fa-angle-double-left  mr-1"></i>
                        Go Back
                    </a>
                </div>
                <div class="box m-0 d-none">
                    <div class="box-header with-border">
                        <h3 class="box-title text-bold"></h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse">
                                <i class="fa fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body font-weight-normal">
                        <?php flash('flash'); ?>
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">
                    </div>
                    <!-- /.box-footer-->
                </div>
            </div>
            <!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
</div>
<!-- ./wrapper -->
<?php require APP_ROOT . '\views\modals\choose_session_modal.php'; ?>
<script src="<?php echo URL_ROOT; ?>/public/assets/js/jquery.min.js"></script>
<script src="<?php echo URL_ROOT; ?>/public/assets/js/bootstrap.bundle.js"></script>
<script src="<?php echo URL_ROOT; ?>/public/assets/js/letter-avatar.js"></script>

<script>
    $(window).on('load', function () {
        LetterAvatar.transform();
        $('#choose_session_modal').modal('show');
    });
</script>
</body>

</html>