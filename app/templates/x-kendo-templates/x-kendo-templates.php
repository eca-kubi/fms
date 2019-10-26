<script type="text/x-kendo-template" id="detailTemplate">
    <div class="salary-advance-details">
        <ul style="list-style: none">
            <li><label>Action:</label>
                <span class='text-center action-tools'>
                        <span><a href='javascript:' class='action-edit badge badge-success btn k-button text-black in-detail-row border'><i class='k-icon k-i-edit'></i>Review</a></span>
                        <span class='d-none'><a href='javascript:' class='text-danger action-delete in-detail-row'><i class='fas fa-trash-alt'></i></a></span>
                        <span class='d-none'><a href='javascript:' class='action-more-info in-detail-row'><i class='fas fa-info-circle'></i></a></span>
                        <span title=''><a href='\\#' class='text-black action-print print-it in-detail-row badge badge-primary btn k-button border' target='_blank'><i class='k-icon k-i-printer'></i>Print</a></span>
                </span>
            </li>
            <li class="d-none"><label>Date Raised:</label><span>#= kendo.toString(kendo.parseDate(date_raised), 'dddd dd MMM, yyyy') #</span></li>
            <li class="#= amount_requested == null? 'd-none' : '' #"><label>Amount Requested:</label> <span>#= kendo.format('{0:c}', amount_requested) #</span></li>
            <li class="#= percentage? '' : 'd-none' #"><label>Amount Requested:</label> <span>#= percentage +'% of Salary' #</span></li>
            <li><label>HoD Approval:</label><span> #= hod_approval == null? '<i class="text-yellow fa fa-warning"></i> <span> Pending</span>' : (hod_approval? '<i class="text-success fa fa-check"></i><span> Approved</span>' : '<i class="text-danger fa fa-warning"></i><span> Rejected</span>') #</span></li>
            <li class="#= hod_approval == null? 'd-none' : '' #"><label>HoD Approval Date:</label><span>#= kendo.toString(kendo.parseDate(hod_approval_date), 'dddd dd MMM, yyyy')#</span></li>
            <li class="#= hod_approval == null? 'd-none' : '' #"><label>HoD's Comment:</label><span>#= hod_comment #</span></li>
            <li><label>HR Approval:</label><span> #= hr_approval == null? '<i class="text-yellow fa fa-warning"></i> <span> Pending</span>' : (hr_approval? '<i class="text-success fa fa-check"></i><span> Approved</span>' : '<i class="text-danger fa fa-warning"></i><span> Rejected</span>') #</span></li>
            <li class="#= hr_approval == null? 'd-none' : '' #"><label>HR Approval Date:</label><span>#= kendo.toString(kendo.parseDate(hr_approval_date), 'dddd dd MMM, yyyy') #</span></li>
            <li class="#= hr_approval == null? 'd-none' : '' #"><label>HR's Comment:</label><span>#= hr_comment #</span></li>
            <li class="#= hr_approval == null? 'd-none' : '' #"><label>Amount Payable:</label> <span>#= kendo.toString('GH₵ ' + kendo.format('{0:n}', amount_payable))#</span></li>
            <li><label>Finance Manager Approval:</label><span> #= fmgr_approval == null? '<i class="text-yellow fa fa-warning"></i> <span> Pending</span>' : (fmgr_approval? '<i class="text-success fa fa-check"></i><span> Approved</span>' : '<i class="text-danger fa fa-warning"></i><span> Rejected</span>') #</span></li>
            <li class="#= fmgr_approval == null? 'd-none' : '' #"><label>Finance Manager Approval Date:</label><span>#=  kendo.toString(kendo.parseDate(fmgr_approval_date), 'dddd dd MMM, yyyy')#</span></li>
            <li class="#= fmgr_approval == null? 'd-none' : '' #"><label>Finance Mgr's Comment:</label><span>#= fmgr_comment #</span></li>
            <li class="#= fmgr_approval == null? 'd-none' : '' #"><label>Amount Approved:</label> <span>#= kendo.toString('GH₵ ' + kendo.format('{0:n}', amount_approved)) #</span></li>
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

<script type="text/x-kendo-template" id="toolbarTemplate_Secretary">
    <div>
        <span>
        <a role="button" class="k-button k-button-icontext k-grid-add" href="\\#"><span class="k-icon k-i-plus text-primary"></span>Request Salary Advance</a>
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
            <li class="#= amount_requested == null? 'd-none' : '' #"><label>Amount Requested:</label> <span>#= kendo.format('{0:c}', amount_requested) #</span></li>
            <li class="#= percentage? '' : 'd-none' #"><label>Amount Requested:</label> <span>#= percentage +'% of Salary' #</span></li>
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

            <li><label>Finance Manager Approval:</label><span> #= fmgr_approval == null? '<i class="text-yellow fa fa-warning"></i> <span> Pending</span>' : (fmgr_approval? '<i class="text-success fa fa-check"></i><span> Approved</span>' : '<i class="text-danger fa fa-warning"></i><span> Rejected</span>') #</span></li>
            <li class="#= fmgr_approval == null? 'd-none' : '' #"><label>Finance Manager Approval Date:</label><span>#=  kendo.toString(kendo.parseDate(fmgr_approval_date), 'dddd dd MMM, yyyy')#</span></li>
            <li class="#= fmgr_approval == null? 'd-none' : '' #"><label>Finance Mgr's Comment:</label><span>#= fmgr_comment #</span></li>
            <li class="#= fmgr_approval == null? 'd-none' : '' #"><label>Amount Approved:</label> <span>#= kendo.toString('GH₵ ' + kendo.format('{0:n}', amount_approved)) #</span></li>
            <li class="#= received_by? '' : 'd-none' #"><label>Amount Received:</label><span>#=  kendo.toString('GH₵ ' + kendo.format('{0:n}', amount_received)) #</span></li>
            <li class="#= received_by? '' : 'd-none' #"><label>Date Received:</label> <span>#= kendo.toString(kendo.parseDate(date_received), 'dddd dd MMM,  yyyy')#</span></li>
            <li class="#= received_by? '' : 'd-none' #"><label>Received By:</label> <span>#= received_by#</span></li>
        </ul>
    </div>
</script>