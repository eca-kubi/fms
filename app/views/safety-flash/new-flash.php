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
            <div id="flashForm" class="px-sm-5 mx-sm-5">
                <div class="text-right">
                    <img class="col-2 w3-hide-small" src="<?php echo URL_ROOT ?>/public/assets/images/adamus.jpg"
                         alt="">
                    <img class="col-12 w3-hide-medium w3-hide-large"
                         src="<?php echo URL_ROOT ?>/public/assets/images/adamus.jpg" alt="">
                    <div class="font-italic font-weight-bold">NZEMA GOLD OPERATIONS</div>
                </div>
                <div class="bg-white border border-secondary" style="padding: 0.1rem;">
                    <div class="bg-white" style="border: 6px solid black; padding: 0.1rem">
                        <div class="bg-yellow-gradient border border-secondary font-weight-bold h4 mb-0 text-center text-white">
                            <div class="d-inline-block bg-blue-active p-2">HSSEC Incident Alert</div>
                        </div>
                    </div>
                </div>
                <div class="font-weight-bold w3-text-amber pl-sm-4">
                    This is NOT an investigation report. It is an alert to a significant incident that has taken place
                    on this operation. The information below is
                    a preliminary assessment of an incident and is not a formal review of the accident.
                </div>
                <div class="table-responsive mt-1">
                    <table class="table table-bordered border-secondary text-black font-raleway">
                        <tr>
                            <td class="w-25 text-bold">Incident/Alert Title</td>
                            <td class="w-75"></td>
                        </tr>
                        <tr>
                            <td class="w-25 text-bold">Type of Alert</td>
                            <td class="w-75"></td>
                        </tr>
                        <tr>
                            <td class="w-25 text-bold">Date of Incident</td>
                            <td class="w-75"></td>
                        </tr>
                        <tr>
                            <td class="w-25 text-bold">Type of Alert</td>
                            <td class="w-75"></td>
                        </tr>
                        <tr>
                            <td class="w-25 text-bold">Issued By</td>
                            <td class="w-75"></td>
                        </tr>
                        <tr>
                            <td class="w-25 text-bold">Location/Department</td>
                            <td class="w-75"></td>
                        </tr>
                        <tr class="text-center">
                            <td colspan="2"><span class="text-bold">Incident Description</span><br></td>
                        </tr>
                        <tr class="text-center">
                            <td colspan="2"><span class="text-bold">Probable Immediate/ Basic Causes/ Contributing Factors</span><br></td>
                        </tr>
                        <tr class="text-center">
                            <td colspan="2"><span class="text-bold">Immedia Actions Taken after Incident</span><br></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </section>
    <!-- /.content -->
</div>
<!-- .content-wrapper -->
<!-- footer -->
<?php require_once APP_ROOT . '\views\includes\footer.php'; ?>
<!-- footer -->
</div>
<!-- /.wrapper -->
<?php require_once APP_ROOT . '\views\includes\safety-flash\flash-form.php'; ?>

<?php require_once APP_ROOT . '\views\includes\safety-flash\scripts.php'; ?>
</body>
</html>