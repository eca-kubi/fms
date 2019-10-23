<script type="text/x-kendo-template" id="detailTemplate">
    <div class="salary-advance-details">
        <ul style="list-style: none">
            <li><label>Action:</label><span class='text-center action-tools'>
                        <span title=''><a href='javascript:'
                                          class='action-edit badge badge-success btn k-button text-black in-detail-row border'><i
                                    class='k-icon k-i-edit'></i>Review</a></span>
                        <span class=' d-none' title=''><a href='javascript:'
                                                          class='text-danger action-delete in-detail-row'><i
                                    class='fas fa-trash-alt'></i></a></span>
                        <span class=' d-none' title=''><a href='javascript:'
                                                          class='action-more-info in-detail-row'><i
                                    class='fas fa-info-circle'></i></a></span>
                        <span title=''><a href='\\#'
                                          class='text-black action-print print-it in-detail-row badge badge-primary btn k-button border'
                                          target='_blank'><i class='k-icon k-i-printer'></i>Print</a></span>
                        </span></li>
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