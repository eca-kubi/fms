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
        <input name="uploadedFile" id="excelUpload" type="file"/>
        <div class="demo-hint">You can only upload <strong>Excel</strong> files.</div>
    </div>
</div>
<div id="exchangeRateWindow" style="display: none">
    <form action="<?php URL_ROOT ?>\salary-advance\exchange-rate" id="exchangeRateForm" method="get">
        <div class="k-content">
            <h4>Enter Exchange Rate</h4>
            <label for="exchangeRateInput" class="mr-2">1 USD = </label><input name="exchange_rate" id="exchangeRateInput" type="text" data-required-msg="Exchange Rate is required!" required/>
            <span class="k-invalid-msg" data-for="exchangeRateInput"></span>
            <input type="submit" class="success btn btn-success" value="Submit" id="exchangeRateSubmitButton">
        </div>
    </form>
</div>