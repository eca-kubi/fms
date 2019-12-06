<footer class="font-raleway w3-tiny d-none main-footer" style="z-index: 1100">
    <div class="col-8 float-left text-left">
        <strong>
            &copy; 2019 -
            <a href="<?php echo site_url('about'); ?>">Developed By Adamus IT</a>.
        </strong>
    </div>
    <div class="float-right col-4 text-right">
        <b>Version 2.0</b>
    </div>
</footer>
<input type="hidden" id="url_root" value="<?php echo URL_ROOT; ?>">

<div id="uploadSalariesWindow" style="display: none">
    <div class="k-content">
        <h4>Upload Salaries</h4>
        <input name="uploadedFile" id="excelUpload" type="file" />
        <div class="demo-hint">You can only upload <strong>Excel</strong> files.</div>
    </div>
    <!--<form action="<?php /*echo URL_ROOT . '/salary-advance/upload-salaries' */?>">
        <div class="form-group"><label for="excelUpload"><input type="file" id="excelUpload"></label></div>
    </form>-->
</div>