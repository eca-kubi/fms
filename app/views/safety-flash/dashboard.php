<?php require_once APP_ROOT . '\views\includes\safety-flash\header.php'; ?>
<?php require_once APP_ROOT . '\views\includes\safety-flash\navbar.php'; ?>
<?php require_once APP_ROOT . '\views\includes\safety-flash\sidebar.php'; ?>
<input id="url_root" type="hidden" value="<?php echo URL_ROOT; ?>">
<!-- .content-wrapper -->
<div class="content-wrapper animated fadeInRight" style="margin-top: <?php echo NAVBAR_MT; ?>">
    <!-- .content-header-->
    <section class="content-header d-none">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>
                        <?php echo APP_NAME; ?>
                    </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active">
                            <a href="#">Dashboard</a>
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </section>
    <!-- /.content-header-->
    <!-- content -->
    <section class="content">
        <div class="container-fluid">

        </div>
    </section>
    <!-- /.content -->
    <!-- footer -->
    <?php require_once APP_ROOT . '\views\includes\footer.php'; ?>
    <!-- footer -->
</div>
<!-- .content-wrapper -->
</div>
<!-- /.wrapper -->
<?php require_once APP_ROOT . '\views\includes\safety-flash\scripts.php'; ?>
</body>
</html>