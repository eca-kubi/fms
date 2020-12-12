<?php
$current_user = getUserSession();
$is_secretary = isAssignedAsSecretary($current_user->user_id);
?>

<script type="text/x-kendo-template" id="detailTemplate">
    <div class="salary-advance-details">
        <ul style="list-style: none">
            <li class="d-none"><label>Date Raised:</label><span>#= kendo.toString(kendo.parseDate(date_raised), 'dddd dd MMM, yyyy') #</span></li>
            <li class="#= amount_requested == null? 'd-none' : '' #"><label>Amount Requested:</label> <span>#= kendo.format('{0:c}', amount_requested) #</span></li>
            <li class="d-none"><label>Amount in Percentage:</label> <span>#= percentage +'% of Salary' #</span></li>
            <li><label>HoD Approval:</label><span> #= hod_approval == null? '<i class="text-yellow fa fa-warning"></i> <span> Pending</span>' : (hod_approval? '<i class="text-success fa fa-check"></i><span> Approved</span>' : '<i class="text-danger fa fa-warning"></i><span> Rejected</span>') #</span></li>
            <li class="#= hod_approval === null? 'd-none' : '' #"><label>HoD Approval Date:</label><span>#= kendo.toString(kendo.parseDate(hod_approval_date), 'dddd dd MMM, yyyy')#</span></li>
            <li class="#= hod_approval === null? 'd-none' : '' #"><label>HoD's Comment:</label><span class="comment">#= hod_comment #</span></li>
            <li><label>HR Approval:</label><span> #= hr_approval == null? '<i class="text-yellow fa fa-warning"></i> <span> Pending</span>' : (hr_approval? '<i class="text-success fa fa-check"></i><span> Approved</span>' : '<i class="text-danger fa fa-warning"></i><span> Rejected</span>') #</span></li>
            <li class="#= hr_approval === null? 'd-none' : '' #"><label>HR Approval Date:</label><span>#= kendo.toString(kendo.parseDate(hr_approval_date), 'dddd dd MMM, yyyy') #</span></li>
            <li class="#= hr_approval === null? 'd-none' : '' #"><label>HR's Comment:</label><span>#= hr_comment #</span></li>
            <li class="#= hr_approval === true? '' : 'd-none' #"><label>Amount Payable:</label> <span>#= kendo.toString('GH₵ ' + kendo.format('{0:n}', amount_payable))#</span></li>
            <li><label>GM Approval:</label><span> #= gm_approval == null? '<i class="text-yellow fa fa-warning"></i> <span> Pending</span>' : (gm_approval? '<i class="text-success fa fa-check"></i><span> Approved</span>' : '<i class="text-danger fa fa-warning"></i><span> Rejected</span>') #</span></li>
            <li class="#= gm_approval === null? 'd-none' : '' #"><label>GM Approval Date:</label><span>#= kendo.toString(kendo.parseDate(gm_approval_date), 'dddd dd MMM, yyyy') #</span></li>
            <li class="#= gm_approval === null? 'd-none' : '' #"><label>GM's Comment:</label><span>#= gm_comment #</span></li>

            <li><label>Fin Mgr Approval:</label><span> #= fmgr_approval == null? '<i class="text-yellow fa fa-warning"></i> <span> Pending</span>' : (fmgr_approval? '<i class="text-success fa fa-check"></i><span> Approved</span>' : '<i class="text-danger fa fa-warning"></i><span> Rejected</span>') #</span></li>
            <li class="#= fmgr_approval === null? 'd-none' : '' #"><label>Fin Mgr Approval Date:</label><span>#=  kendo.toString(kendo.parseDate(fmgr_approval_date), 'dddd dd MMM, yyyy')#</span></li>
            <li class="#= fmgr_approval === null? 'd-none' : '' #"><label>Fin Mgr's Comment:</label><span>#= fmgr_comment #</span></li>
            <li class="#= fmgr_approval === true? '' : 'd-none' #"><label>Amount Approved:</label> <span>#= kendo.toString('GH₵ ' + kendo.format('{0:n}', amount_approved)) #</span></li>
            <li class="#= received_by? '' : 'd-none' #"><label>Amount Received:</label><span>#=  kendo.toString('GH₵ ' + kendo.format('{0:n}', amount_received)) #</span></li>
            <li class="#= received_by? '' : 'd-none' #"><label>Date Received:</label> <span>#= kendo.toString(kendo.parseDate(date_received), 'dddd dd MMM,  yyyy')#</span></li>
            <li class="#= received_by? '' : 'd-none' #"><label>Received By:</label> <span>#= received_by#</span></li>
        </ul>
    </div>
</script>

<script type="text/x-kendo-template" id="toolbarTemplate">
    <div class="row">
    <span><a role="button" class="k-button k-button-icontext k-grid-excel" href="\\#"><span
                    class="k-icon k-i-file-excel"></span>Export to Excel</a></span>
        <!--<span class="toolbar-department-filter mx-1" title="Filter by Department">
            <label class="category-label mt-2 mt-sm-0" for="department">Search by Department</label>
            <input type="search" id="department" style="width: 150px"/>
        </span>
        <span class="toolbar-name-filter mx-1">
            <label class="category-label mt-2 mt-sm-0" for="names">Search by Names</label>
            <input type="search" id="names" class="border p-2 bg-gray-light"
                   style="width: 150px; border-radius: 3px!important;"/>
        </span>-->
    </div>
</script>

<script type="text/x-kendo-template" id="toolbarTemplate_Bulk_Requests">
    <div>
        <span>
        <a role="button" class="k-button k-button-icontext <?php echo $is_secretary? : 'd-none'; ?>" href="<?php echo URL_ROOT ?>\/salary-advance\/new-bulk-request"><span class="k-icon k-i-aggregate-fields text-primary"></span>New Bulk Request</a>
        </span>
    <span class="float-lg-right">
        <a role="button" class="k-button k-button-icontext k-grid-excel" href="\\#">
            <span class="k-icon k-i-file-excel text-success"></span>Export to Excel</a>
    </span>
        <span>
        <a role="button" class="k-button k-button-icontext k-grid-cancel-changes d-none" href="\\#"><span class="k-icon k-i-cancel text-warning"></span>Cancel</a>
    </span>
        <span>
        <a role="button" class="k-button k-button-icontext k-grid-save-changes d-none" href="\\#"><span class="k-icon k-i-check-circle text-success"></span>Submit Changes</a>
    </span>
    </div>
</script>

<script type="text/x-kendo-template" id="toolbarTemplate_New_Bulk_Request">
    <div>
        <span>
        <a role="button" class="k-button k-button-icontext k-grid-add" href="<?php echo URL_ROOT ?>\/salary-advance\/new-bulk-request"><span class="k-icon k-i-add text-primary"></span>Append</a>
        </span>
        <span>
        <a role="button" class="k-button k-button-icontext k-grid-cancel-changes d-none" href="\\#"><span class="k-icon k-i-cancel text-warning"></span>Cancel</a>
    </span>
        <span>
        <a role="button" class="k-button k-button-icontext k-grid-save-changes d-none" href="\\#"><span class="k-icon k-i-check-circle text-success"></span>Submit New Request</a>
    </span>
        <span class="float-lg-right">
        <a role="button" class="k-button k-button-icontext k-grid-excel" href="\\#">
            <span class="k-icon k-i-file-excel text-success"></span>Export to Excel</a>
    </span>
    </div>
</script>

<script type="text/x-kendo-template" id="detailTemplate_Secretary">
    <div class="salary-advance-details">
        <ul style="list-style: none">
            <li class="d-none"><label>Action:</label>
                <span class='text-center action-tools'>
                        <span><a href='javascript:' class='action-edit badge badge-success btn k-button text-black in-detail-row border'><i class='k-icon k-i-edit'></i>Review</a></span>
                        <span class='d-none'><a href='javascript:' class='text-danger action-delete in-detail-row'><i class='fas fa-trash-alt'></i></a></span>
                        <span class='d-none'><a href='javascript:' class='action-more-info in-detail-row'><i class='fas fa-info-circle'></i></a></span>
                        <span title=''><a href='\\#' class='text-black action-print print-it in-detail-row badge badge-primary btn k-button border' target='_blank'><i class='k-icon k-i-printer'></i>Print</a></span>
                </span>
            </li>
            <li class="d-none"><label>Date Raised:</label><span>#= kendo.toString(kendo.parseDate(date_raised), 'dddd dd MMM, yyyy') #</span></li>
            <li class="#= amount_requested === null || universal.isSecretary? 'd-none' : '' #"><label>Amount in Figures:</label> <span>#= kendo.format('{0:c}', amount_requested) #</span></li>
            <li class="#= percentage? '' : 'd-none' #"><label>Amount in Percentage:</label> <span>#= percentage +'% of Salary' #</span></li>
            <li><label>HoD Approval:</label><span> #= hod_approval == null? '<i class="text-yellow fa fa-warning"></i> <span> Pending</span>' : (hod_approval? '<i class="text-success fa fa-check"></i><span> Approved</span>' : '<i class="text-danger fa fa-warning"></i><span> Rejected</span>') #</span></li>
            <li class="#= hod_approval == null? 'd-none' : '' #"><label>HoD Approval Date:</label><span>#= kendo.toString(kendo.parseDate(hod_approval_date), 'dddd dd MMM, yyyy')#</span></li>
            <li class="#= hod_approval == null? 'd-none' : '' #"><label>HoD's Comment:</label><span>#= hod_comment #</span></li>
            <li><label>HR Approval:</label><span> #= hr_approval == null? '<i class="text-yellow fa fa-warning"></i> <span> Pending</span>' : (hr_approval? '<i class="text-success fa fa-check"></i><span> Approved</span>' : '<i class="text-danger fa fa-warning"></i><span> Rejected</span>') #</span></li>
            <li class="#= hr_approval == null? 'd-none' : '' #"><label>HR Approval Date:</label><span>#= kendo.toString(kendo.parseDate(hr_approval_date), 'dddd dd MMM, yyyy') #</span></li>
            <li class="#= hr_approval == null? 'd-none' : '' #"><label>HR's Comment:</label><span>#= hr_comment #</span></li>
            <li class="#= hr_approval == null? 'd-none' : '' #"><label>Amount Payable:</label> <span>#= kendo.toString('GH₵ ' + kendo.format('{0:n}', amount_payable))#</span></li>
            <li><label>GM Approval:</label><span> #= gm_approval == null? '<i class="text-yellow fa fa-warning"></i> <span> Pending</span>' : (gm_approval? '<i class="text-success fa fa-check"></i><span> Approved</span>' : '<i class="text-danger fa fa-warning"></i><span> Rejected</span>') #</span></li>
            <li class="#= gm_approval == null? 'd-none' : '' #"><label>GM Approval Date:</label><span>#= kendo.toString(kendo.parseDate(gm_approval_date), 'dddd dd MMM, yyyy') #</span></li>
            <li class="#= gm_approval == null? 'd-none' : '' #"><label>GM's Comment:</label><span>#= gm_comment #</span></li>

            <li><label>Fin Mgr Approval:</label><span> #= fmgr_approval == null? '<i class="text-yellow fa fa-warning"></i> <span> Pending</span>' : (fmgr_approval? '<i class="text-success fa fa-check"></i><span> Approved</span>' : '<i class="text-danger fa fa-warning"></i><span> Rejected</span>') #</span></li>
            <li class="#= fmgr_approval == null? 'd-none' : '' #"><label>Fin Mgr Approval Date:</label><span>#=  kendo.toString(kendo.parseDate(fmgr_approval_date), 'dddd dd MMM, yyyy')#</span></li>
            <li class="#= fmgr_approval == null? 'd-none' : '' #"><label>Fin Mgr's Comment:</label><span>#= fmgr_comment #</span></li>
            <li class="#= fmgr_approval == null? 'd-none' : '' #"><label>Amount Approved:</label> <span>#= kendo.toString('GH₵ ' + kendo.format('{0:n}', amount_approved)) #</span></li>
            <li class="#= received_by? '' : 'd-none' #"><label>Amount Received:</label><span>#=  kendo.toString('GH₵ ' + kendo.format('{0:n}', amount_received)) #</span></li>
            <li class="#= received_by? '' : 'd-none' #"><label>Date Received:</label> <span>#= kendo.toString(kendo.parseDate(date_received), 'dddd dd MMM,  yyyy')#</span></li>
            <li class="#= received_by? '' : 'd-none' #"><label>Received By:</label> <span>#= received_by#</span></li>
        </ul>
    </div>
</script>

<script  type="text/x-kendo-template" id="destroyButton">
    <span class='col m-1'><a class='btn btn-destroy k-grid-delete w3-hover-red k-button badge badge-danger border text-bold text-white'><i class='k-icon k-i-trash'></i> DELETE</a></span>
</script>

<script  type="text/x-kendo-template" id="printButton">
    <span class='col'><a href='\\#' class='action-print print-it badge badge-primary btn k-button border text-bold text-white' target='_blank'><i class='k-icon k-i-printer'></i> PRINT</a></span>
</script>

<script  type="text/x-kendo-template" id="editButton">
    <span class="col"><a class="action-edit badge badge-success btn k-button text-black border"><i class="k-icon k-i-edit"></i>Review</a></span>
</script>

<script  type="text/x-kendo-template" id="exportToExcel">
    <span class="float-lg-right">
        <a role="button" class="k-button k-button-icontext k-grid-excel" href="\\#">
            <span class="k-icon k-i-file-excel text-success"></span>Export to Excel</a>
    </span>
</script>

<script id="disabledListItem" type="text/x-kendo-template">
    <span class="#: has_active_application ? 'k-state-disabled cursor-disabled': ''#" title="#: has_active_application ? 'This employee has an active application!': ''#">
       #: name #
    </span>
</script>

<script id="employeeDropDownListTemplate" type="text/x-kendo-template">
    # if (data.has_active_application) {#
    <span class="k-state-disabled" title="This employee has already been selected!">#: data.name#</span>
    # } else { #
    <span>#:data.name#</span>
   # }#
</script>

<script id="tooltipTemplate" type="text/x-kendo-template"><span class="k-widget k-tooltip k-tooltip-validation"><span class="k-icon k-warning"></span>#=message#</span></script>

<script id="groupByColumnsTemplate" type="text/x-kendo-template">
    <label for="groupByColumnsSelect">Group By Columns</label>
    <select id="groupByColumnsSelect" multiple="multiple" data-placeholder="Select columns...">
        <option>HoD Approval</option>
        <option>HR Approval</option>
        <option>GM Approval</option>
        <option>FMgr Approval</option>
        <option>Request Number</option>
        <option>Date Raised</option>
    </select>
</script>
