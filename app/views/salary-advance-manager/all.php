<?php require_once APP_ROOT . '\views\includes\header.php'; ?>
<?php require_once APP_ROOT . '\views\includes\navbar.php'; ?>
<?php require_once APP_ROOT . '\views\includes\sidebar.php'; ?>
<!-- .content-wrapper -->
<div class="content-wrapper animated fadeInRight" style="margin-top: <?php echo NAVBAR_MT; ?>">
    <!-- .content-header-->
    <section class="content-header d-none">
        <!-- .container-fluid -->
        <div class="container-fluid">
            <!-- .row -->
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
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- /.content-header-->
    <!-- content -->
    <section class="content">
        <div class="box-group" id="box_group">
            <div class="box collapsed">
                <div class="box-header">
                    <h5>
                        <?php flash('flash_all'); ?>
                    </h5>
                    <h3 class="box-title text-bold d-none"></h3>
                    <div class="box-tools pull-right d-none">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse">
                            <i class="fa fa-minus"></i>
                        </button>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body table-responsive">
                    <div class="salary-advance" id="salary_advance"></div>
                </div>
                <!-- /.box-body -->
                <div class="box-footer d-none"></div>
                <!-- /.box-footer-->
            </div>
        </div>
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<?php require_once APP_ROOT . '\views\includes\footer.php'; ?>
<?php
$universal = new stdClass();
$universal->hr_comment_editable = $universal->isHr = getCurrentHR() == $current_user->user_id;
$universal->fgmr_comment_editable = $universal->isFmgr = getCurrentFgmr() == $current_user->user_id;
/** @var int $select_row_id */
$universal->select_row_id = $select_row_id;
?>
<!--suppress HtmlUnknownTarget -->
<script>
    let universal = JSON.parse(`<?php echo json_encode($universal); ?>`);
    let $salaryAdvanceGrid;
    let salaryAdvanceDataSource;
    $(document).ready(function () {
        kendo.culture().numberFormat.currency.symbol = 'GH₵';
        $salaryAdvanceGrid = $('#salary_advance');
        $salaryAdvanceGrid.on('change', "input[name=employee]", function (e) {
            //let select = $(this).data("kendoDropDownList");
        });
        salaryAdvanceDataSource = new kendo.data.DataSource({
            width: 'auto',
            pageSize: 5,
            transport: {
                read: {
                    url: URL_ROOT + "/salary-advance-manager-ajax/",
                    type: "post",
                    dataType: "json"
                },
                update: {
                    url: URL_ROOT + "/salary-advance-manager-ajax/update/",
                    type: 'post',
                    dataType: 'json'
                },
                destroy: {
                    url: URL_ROOT + "/salary-advance-manager-ajax/destroy/",
                    type: 'post',
                    dataType: 'json'
                },
                create: {
                    url: URL_ROOT + "/salary-advance-manager-ajax/create",
                    type: 'post',
                    dataType: 'json'
                },
                errors: function (response) {
                    //console.log("errors as function", response.errors[0]);
                    return response.errors;
                }
                /* parameterMap: function (options, operation) {
                     if (operation !== "read" && options.models) {
                         return {models: kendo.stringify(options.models)};
                     }
                 }*/
            },
            error: function (e) {
                //console.log("error event handler", e.errors[0]);
                toastError(e.errors[0]);
                salaryAdvanceDataSource.cancelChanges();
            },
            requestEnd: function (e) {
                /*if (e.type === 'read' && e.response) {
                    let grid = $salaryAdvanceGrid.data('kendoGrid');
                }*/
                if (e.type === 'update' && !e.response[0].success) {
                    e.response[0].reason ? toastError(e.response[0].reason) : toastError('An error occurred!');
                } else if (e.type === 'update' && e.response[0].success) {
                    toastSuccess('Success', 5000);
                }
                if (e.type === 'create' && e.response[0].success) {
                    toastSuccess('Success', 5000);
                }
                if (e.type === 'destroy' && e.response[0].success) {
                    toastSuccess('Success', 5000);
                } else if (e.type === 'destroy' && !e.response[0].success) {
                    //e.response[0].reason ? toastError(e.response[0].reason) : toastError('An error occurred!');
                    //salaryAdvanceDataSource.cancelChanges();
                }
            },
            schema: {
                model: {
                    id: "id_salary_advance",
                    fields: {
                        name: {
                            editable: false,
                            from: "employee.name"
                        },
                        employee: {
                            defaultValue: {}
                        },
                        date_raised: {
                            type: 'date',
                            editable: false
                        },
                        amount_requested: {
                            type: 'number',
                            // a defaultValue will not be assigned (default value is false)
                            nullable: true,
                            validation: { //set validation rules
                                min: 0,
                                //required: true
                            }
                        },
                        hod_comment: {
                            type: 'string',
                            editable: false
                        },
                        hr_comment: {
                            type: 'string',
                            editable: false
                        },
                        fmgr_comment: {
                            type: 'string',
                            editable: false
                        },
                        department: {
                            type: 'string',
                            editable: false
                        },
                        hr_approval: {
                            type: 'boolean',
                            editable: false,
                            nullable: true,
                        },
                        hod_approval: {
                            type: 'boolean',
                            editable: false
                        },
                        hod_approval_date: {
                            type: "date",
                            editable: false
                        },
                        fmgr_approval: {
                            type: 'boolean',
                            editable: false

                        },
                        amount_payable: {
                            type: 'number',
                            validation: { //set validation rules
                                required: true,
                                min: '0'
                            },
                            editable: false
                        },
                        amount_approved: {
                            type: 'number',
                            validation: { //set validation rules
                                required: true,
                                min: '0'
                            },
                            editable: false
                        },
                        received_by: {
                            type: 'string',
                            editable: false
                        },
                        fmgr_approval_date: {
                            type: 'date',
                            editable: false
                        },
                        hr_approval_date: {
                            type: 'date',
                            editable: false
                        },
                        date_received: {
                            type: 'date',
                            editable: false
                        },
                        department_ref: {
                            editable: false
                        },
                        hr_id: {
                            type: 'number'
                        },
                        hod_id: {
                            type: 'number'
                        },
                        fmgr_id: {
                            type: 'number'
                        },
                        raised_by_secretary: {
                            type: 'boolean'
                        },
                        user_id: {type: 'number'},
                        department_id: {
                            type: 'number', editable: false
                        },
                        raised_by_id: {type: "number"},
                        amount_received: {
                            type: "number", editable: false
                        },
                        percentage: {
                            type: "number",
                            // a defaultValue will not be assigned (default value is false)
                            nullable: true,
                            validation: { //set validation rules
                                min: 10,
                                max: 30,
                                required: true
                            },
                        },
                        amount_requested_is_percentage: {
                            type: 'boolean',
                            defaultValue: true
                        },
                    }
                }
            }
        });

        $salaryAdvanceGrid.kendoGrid({
            autoFitColumn: true,
            selectable: true,
            mobile: true,
            noRecords: true,
            navigatable: true,
            toolbar: ["excel"],
            excel: {
                fileName: "Salary Advance Export.xlsx",
                //proxyURL: "https://demos.telerik.com/kendo-ui/service/export",
                filterable: true
            },
            excelExport: function (e) {
                let sheet = e.workbook.sheets[0];
                sheet.columns[0].autoWidth = false;
                for (let rowIndex = 1; rowIndex < sheet.rows.length; rowIndex++) {
                    if (rowIndex % 2 === 0) {
                        let row = sheet.rows[rowIndex];
                        for (let cellIndex = 0; cellIndex < row.cells.length; cellIndex++) {
                            //row.cells[cellIndex].fontName = "Poppins";
                        }
                    }
                }
            },
            editable: 'popup',
            filterable: {
                operators: {
                    date: {
                        gte: "From Date",
                        lte: "To Date"
                    },
                    string: {
                        startswith: "Starts with",
                        eq: "Is equal to",
                        neq: "Is not equal to",
                        contains: "Contains"
                    }
                }
            },
            columnMenu: true,
            sortable: true,
            groupable: true,
            height: 520,
            resizable: true,
            scrollable: true,
            pageable: {
                alwaysVisible: false,
                pageSizes: [5, 10, 15, 20],
                buttonCount: 5
            },
            columns: [
                {
                    field: 'name',
                    title: 'Employee',
                    editor: dropDownEditor,
                    template: function (dataItem) {
                        return "<span title='" + dataItem.name + "'>" + dataItem.name + "</span>";
                    },
                    width: 200,
                    headerAttributes: {
                        "class": "title"
                    },
                   /* locked: true,
                    lockable: true*/
                },
                {
                    field: 'department',
                    title: 'Department',
                    template: function (dataItem) {
                        return "<span title='" + dataItem.department + "'>" + dataItem.department + "</span>";
                    },
                    headerAttributes: {
                        "class": "title"
                    },
                    width: 200,
                    filterable: {
                        ui: departmentFilter
                    }
                },
                {
                    field: 'date_raised',
                    title: 'Date Raised',
                    template: function (dataItem) {
                        let date = kendo.toString(kendo.parseDate(dataItem.date_raised), 'dddd dd MMM, yyyy');
                        return "<span title='Date Raised: " + date + "'>" + date + "</span>";
                    },
                    headerAttributes: {
                        "class": "title"
                    },
                    width: 200,
                    groupHeaderTemplate: "Date Raised: #= kendo.toString(kendo.parseDate(value), 'dddd dd MMM, yyyy h:mm:ss tt') #"
                },
                {
                    field: 'percentage',
                    title: 'Amount Requested in Percentage',
                    template: function (dataItem) {
                        return "<span title='Amount Requested in Percentage: " + (dataItem.percentage ? kendo.toString(dataItem.percentage, '#\\%') : '') + "'>" + (dataItem.percentage ? kendo.toString(dataItem.percentage, '#\\%') : '') + "</span>"
                    },
                    headerAttributes: {
                        "class": "title"
                    },
                    width: 250,
                    groupHeaderTemplate: "Amount Requested in Percentage: #= value? value + '%' : '' #",
                    aggregates: ["max", "min"],
                    format: "{0:#\\%}"
                },
                {
                    field: 'amount_requested',
                    title: 'Amount Requested in Figures',
                    width: 250,
                    template: function (dataItem) {
                        return "<span title='Amount Requested: " + (dataItem.amount_requested ? kendo.format('{0:c}', dataItem.amount_requested) : '') + "'>" + (dataItem.amount_requested ? kendo.format('{0:c}', dataItem.amount_requested) : '') + "</span>"
                    },
                    headerAttributes: {
                        "class": "title"
                    },
                    groupHeaderTemplate: "Amount Requested in Figures: #=  value ? kendo.format('{0:c}', value) : ''#",
                    aggregates: ["max", "min", "count"],
                    format: "{0:c}"
                },
                {
                    title: 'HoD',
                    headerAttributes: {
                        "class": "title font-weight-bold"
                    },
                    columns: [
                        {
                            field: 'hod_comment',
                            title: 'HoD Comment',
                            hidden: false,
                            editor: textAreaEditor,
                            headerAttributes: {
                                "class": "title"
                            },
                            attributes: {
                                class: 'comment'
                            },
                            template: function (dataItem) {
                                let hod_comment = dataItem.hod_comment ? dataItem.hod_comment : '';
                                return "<span title='HoD Comment: " + hod_comment + "'>" + hod_comment + "</span>"
                            },
                            width: 200,
                        },
                        {
                            field: 'hod_approval',
                            title: 'Approved by HoD?',
                            editor: approvalEditor,
                            template: function (dataItem) {
                                let hod_approval = dataItem.hod_approval ? dataItem.hod_approval : '';
                                return "<span title='HoD Approved: " + (hod_approval ? 'Yes' : 'No') + "'>" + (hod_approval ? 'Yes' : 'No') + "</span>"
                            },
                            headerAttributes: {
                                "class": "title"
                            },
                            width: 200,
                            groupHeaderTemplate: "HoD Approved: #= value? 'Yes' : 'No' # | Total: #= count #",
                            aggregates: ["count"]
                        },
                        {
                            field: 'hod_approval_date',
                            title: 'HoD. Approval Date ',
                            headerAttributes: {
                                "class": "title"
                            },
                            template: function (dataItem) {
                                let date = kendo.toString(kendo.parseDate(dataItem.hod_approval_date), 'dddd dd MMM, yyyy');
                                return "<span title='HoD Approval Date: " + date + "'>" + date + "</span>";
                            },
                            width: 200,
                            groupHeaderTemplate: "Date Raised: #= kendo.toString(kendo.parseDate(value), 'dddd dd MMM, yyyy h:mm:ss tt') #",
                            hidden: true
                        }
                    ],
                },
                {
                    title: 'HR',
                    headerAttributes: {
                        "class": "title font-weight-bold"
                    },
                    columns: [
                        {
                            field: 'hr_comment',
                            title: 'HR Comment',
                            editor: textAreaEditor,
                            hidden: false,
                            headerAttributes: {
                                "class": "title"
                            },
                            attributes: {
                                class: 'comment'
                            },
                            template: function (dataItem) {
                                let hr_comment = dataItem.hr_comment ? dataItem.hr_comment : '';
                                return `<span title='HR Comment: ${hr_comment}'>${hr_comment}</span>`
                            },
                            width: 200
                        },
                        {
                            field: 'hr_approval',
                            title: 'Approved by HR?',
                            editor: customBoolEditor,
                            template: function (dataItem) {
                                let hr_approval = dataItem.hr_approval ? dataItem.hr_approval : '';
                                return "<span title='HR Approved: " + (hr_approval ? 'Yes' : 'No') + "'>" + (hr_approval ? 'Yes' : 'No') + "</span>"
                            },
                            headerAttributes: {
                                "class": "title"
                            },
                            groupHeaderTemplate: "HR Approved: #= value? 'Yes' : 'No' # |  Total: #= count #",
                            aggregates: ["count"],
                            width: 200
                        },
                        {
                            field: 'amount_payable',
                            title: 'Amount Payable',
                            template: function (dataItem) {
                                return "<span title='Amount Payable: " + (dataItem.amount_payable ? kendo.format('{0:c}', dataItem.amount_payable) : '') + "'>" + (dataItem.amount_payable ? kendo.format('{0:c}', dataItem.amount_payable) : '') + "</span>"
                            },
                            format: "{0:c}",
                            headerAttributes: {
                                "class": "title"
                            },
                            groupHeaderTemplate: "Amount Payable: #= value?  kendo.format('{0:c}', value) : 'Pending' #",
                            aggregates: ["max", "min"]
                        },
                        {
                            field: 'hr_approval_date',
                            title: 'HR. Approval Date ',
                            headerAttributes: {
                                "class": "title"
                            },
                            template: function (dataItem) {
                                let date = kendo.toString(kendo.parseDate(dataItem.hr_approval_date), 'dddd dd MMM, yyyy');
                                return "<span title='HR Approval Date: " + date + "'>" + date + "</span>";
                            },
                            width: 200,
                            groupHeaderTemplate: "HR Approval Date: #= kendo.toString(kendo.parseDate(value), 'dddd dd MMM, yyyy h:mm:ss tt') #",
                            hidden: true
                        }
                    ],
                },
                {
                    title: 'Finance Mgr.',
                    headerAttributes: {
                        "class": "title font-weight-bold"
                    },
                    columns: [
                        {
                            field: 'fmgr_comment',
                            title: 'Finance Mgr. Comment',
                            hidden: false,
                            editor: textAreaEditor,
                            headerAttributes: {
                                "class": "title"
                            },
                            attributes: {
                                class: 'comment'
                            },
                            width: 200,
                            template: function (dataItem) {
                                let fmgr_comment = dataItem.fmgr_comment ? dataItem.fmgr_comment : '';
                                return "<span title='Finance Mgr. Comment: " + fmgr_comment + "'>" + fmgr_comment + "</span>"
                            }
                        },
                        {
                            field: 'fmgr_approval',
                            title: 'Approved by Finance Mgr.?',
                            editor: customBoolEditor,
                            template: function (dataItem) {
                                let fmgr_approval = dataItem.fmgr_approval ? dataItem.fmgr_approval : '';
                                return "<span title='Approved by Finance Mgr.: " + (fmgr_approval ? 'Yes' : 'No') + "'>" + (fmgr_approval ? 'Yes' : 'No') + "</span>"
                            },
                            headerAttributes: {
                                "class": "title"
                            },
                            width: 200,
                            groupHeaderTemplate: "Finance Manager Approved: #= value? 'Yes' : 'No' # |  Total: #=count #",
                            aggregates: ["count"]
                        },
                        {
                            field: 'amount_approved',
                            title: 'Amount Approved',
                            template: function (dataItem) {
                                return "<span title='Amount Approved: " + (dataItem.amount_approved ? kendo.format('{0:c}', dataItem.amount_approved) : '') + "'>" + (dataItem.amount_approved ? kendo.format('{0:c}', dataItem.amount_approved) : '') + "</span>"
                            },
                            headerAttributes: {
                                "class": "title"
                            },
                            groupHeaderTemplate: "Amount Approved: #= value?  kendo.format('{0:c}', value): 'Pending' #",
                            aggregates: ["max", "min"],
                            format: "{0:c}"
                        },
                        {
                            field: 'fmgr_approval_date',
                            title: 'Finance Mgr. Approval Date ',
                            headerAttributes: {
                                "class": "title"
                            },
                            template: function (dataItem) {
                                let date = kendo.toString(kendo.parseDate(dataItem.fmgr_approval_date), 'dddd dd MMM, yyyy');
                                return "<span title='Finance Mgr. Approval Date: " + date + "'>" + date + "</span>";
                            },
                            width: 200,
                            groupHeaderTemplate: "Finance Mgr. Approval Date: #= kendo.toString(kendo.parseDate(value), 'dddd dd MMM, yyyy h:mm:ss tt') #",
                            hidden: true
                        }
                    ],
                },
                {
                    field: 'amount_received',
                    title: 'Amount Received',
                    template: function (dataItem) {
                        return dataItem.amount_received ? "<span title='Amount Received: " + kendo.format('{0:c}', dataItem.amount_received) + "'>" + kendo.format('{0:c}', dataItem.amount_received) + "</span>" : "<span title='Pending'>Pending</span>"
                    },
                    attributes: {
                        class: 'amount_received'
                    },
                    headerAttributes: {
                        "class": "title"
                    },
                    width: 200,
                    groupHeaderTemplate: "Amount Received: #: kendo.format('{0:c}', value) #",
                },
                {
                    field: 'received_by',
                    title: 'Received By',
                    hidden: true,
                    template: function (dataItem) {
                        return dataItem.received_by ? "<span title='Received by: " + dataItem.received_by + "'>" + dataItem.received_by + "</span>" : "<span title='Pending'>Pending</span>"
                    },
                    headerAttributes: {
                        "class": "title"
                    },
                    width: 200,
                    groupHeaderTemplate: "Received By: #:  value #",
                },
                {
                    field: 'date_received',
                    title: 'Date Received',
                    hidden: true,
                    template: function (dataItem) {
                        let date = dataItem.date_received ? kendo.toString(kendo.parseDate(dataItem.date_received), 'dddd dd MMM, yyyy') : 'Pending';
                        return "<span title='Date Received: " + date + "'>" + date + "</span>";
                    },
                    "class": "title",
                    width: 200,
                    groupHeaderTemplate: "Date Received: #= value? kendo.toString(kendo.parseDate(value), 'dddd dd MMM, yyyy') : 'Pending' #"
                },
                {
                    template: "<span class='text-center action-tools'>" +
                        "<span class='col' title='Edit'><a href='javascript:' class='text-black action-edit'><i class='fa fa-pencil'></i></a></span>" +
                        "<span class='col d-none' title='Delete'><a href='javascript:' class='text-danger action-delete'><i class='fas fa-trash-alt'></i></a></span>" +
                        "<span class='col d-none' title='More Info'><a href='javascript:' class='text-primary action-more-info'><i class='fas fa-info-circle'></i></a></span>" +
                        "<span class='col' title='Print'><a href='\\#' class='text-primary action-print print-it' target='_blank'><i class='fas fa-print'></i></a></span>" +
                        "</span>",
                    width: 100,
                    title: "",
                    locked: true,
                    lockable: true
                },
            ],
            //detailTemplate: kendo.template(summaryTemplate),
            dataSource: salaryAdvanceDataSource,
            dataBinding: function () {
                //let no = (this.dataSource.page() - 1) * this.dataSource.pageSize();
            },
            dataBound: function (e) {
                //let grid = $salaryAdvanceGrid.data('kendoGrid');
                let grid = e.sender;
                let data = grid.dataSource.data();
                $.each(data, function (i, row) {
                    $('tr[data-uid="' + row.uid + '"] ').attr('data-id-salary-advance', row['id_salary_advance']).find(".print-it").attr("href", URL_ROOT + "/salary-advance/print/" + row["id_salary_advance"]);
                });
                $(".print-it").printPage();
                if (!(universal['isHr'] || universal['isFmgr'])) {
                    grid.hideColumn('department');
                }
                let selectRow = $salaryAdvanceGrid.find(`tr[data-id-salary-advance=${universal['select_row_id']}]`);
                grid.select(selectRow);
                selectRow.find('.action-more-info').click();
            },
            beforeEdit: function (e) {
                window.grid_uid = e.model.uid; // uid of current editing row
                // Editability
                /*e.model.fields.amount_requested.editable = !e.model.fmgr_approval && universal['isFmgr'];
                e.model.fields.percentage.editable = !e.model.fmgr_approval && universal['isFmgr'];*/
                e.model.fields.amount_requested.editable = false;
                e.model.fields.percentage.editable = false;
                e.model.fields.hod_comment.editable = Boolean(e.model["hod_comment_editable"]) && !Boolean(e.model.hod_approval);
                e.model.fields.hod_approval.editable = e.model["hod_approval_editable"] && !e.model.hod_approval;
                e.model.fields.amount_payable.editable = universal['isHr'] && !Boolean(e.model.hr_approval);
                e.model.fields.hr_approval.editable = universal['isHr'] && !Boolean(e.model.hr_approval);
                e.model.fields.hr_comment.editable = universal['isHr'];
                e.model.fields.amount_approved.editable = universal['isFmgr'] && !Boolean(e.model.fmgr_approval);
                e.model.fields.fmgr_approval.editable = universal['isFmgr'] && !Boolean(e.model.fmgr_approval);
                e.model.fields.fmgr_comment.editable = universal['isFmgr'];
            },
            edit: function (e) {
                let nameLabelField = e.container.find('.k-edit-label:eq(0), .k-edit-field:eq(0)');
                let percentageLabelField = e.container.find('.k-edit-label:eq(3), .k-edit-field:eq(3)');
                let amountRequestedLabelField = e.container.find('.k-edit-label:eq(4), .k-edit-field:eq(4)');
                let hodCommentLabelField = e.container.find('.k-edit-label:eq(5), .k-edit-field:eq(5)');
                let hodApprovalLabelField = e.container.find('.k-edit-label:eq(6), .k-edit-field:eq(6)');
                let hrCommentLabelField = e.container.find('.k-edit-label:eq(8), .k-edit-field:eq(8)');
                let hrApprovalLabelField = e.container.find('.k-edit-label:eq(9), .k-edit-field:eq(9)');
                let amountPayableLabelField = e.container.find('.k-edit-label:eq(10), .k-edit-field:eq(10)');
                let fmgrCommentLabelField = e.container.find('.k-edit-label:eq(12), .k-edit-field:eq(12)');
                let fmgrApprovalLabelField = e.container.find('.k-edit-label:eq(13), .k-edit-field:eq(13)');
                let amountApprovedLabelField = e.container.find('.k-edit-label:eq(14), .k-edit-field:eq(14)');
                let amountReceivedLabelField = e.container.find('.k-edit-label:eq(16), .k-edit-field:eq(16)');
                let receivedByLabelField = e.container.find('.k-edit-label:eq(17), .k-edit-field:eq(17)');
                let dateReceivedLabelField = e.container.find('.k-edit-label:eq(18), .k-edit-field:eq(18)');

                /* let amountRequestedNumericTextBox = amountRequestedLabelField.find('input[data-role="numerictextbox"]').data('kendoNumericTextBox');
                 let amountRequestedPercentageNumericTextBox = percentageLabelField.find('input[data-role="numerictextbox"]').data('kendoNumericTextBox');
                 let amountPayableNumericTextBox = amountPayableLabelField.find('input[data-role="numerictextbox"]').data('kendoNumericTextBox');
                 let radioButtonGroup = $('<div class="k-edit-field"><input type="radio" name="toggleAmountRequested" id="percentageRadio" class="k-radio" checked="checked" > <label class="k-radio-label" for="percentageRadio" >Percentage</label><input type="radio" name="toggleAmountRequested" id="figureRadio" class="k-radio"> <label class="k-radio-label" for="figureRadio">Figure</label></div>');
 */
                // Hod Approval RadiobuttonGroup initialisation
                //e.container.find("#approvalRadioButtonGroup" + "_hod_approval").find()
                // Toggle visibility off for all editor fields and labels
                e.container.find('.k-edit-label, .k-edit-field').addClass("pt-2").toggle(false);

                e.container.find('.k-edit-field .k-checkbox').parent().removeClass('pt-2');
                // Toggleability
                nameLabelField.toggle(true);
                amountRequestedLabelField.toggle(!e.model.amount_requested_is_percentage /*|| universal['isFmgr']*/);
                percentageLabelField.toggle(e.model.amount_requested_is_percentage /*|| universal['isFmgr']*/);
                hodCommentLabelField.toggle(Boolean(e.model["hod_comment_editable"]));
                hodApprovalLabelField.toggle(Boolean(e.model["hod_approval_editable"]));
                hrApprovalLabelField.toggle(universal['isHr']);
                hrCommentLabelField.toggle(!Boolean(e.model.hr_approval) && universal['isHr']);
                fmgrApprovalLabelField.toggle(!Boolean(e.model.fmgr_approval) && universal['isFmgr']);
                fmgrCommentLabelField.toggle(!Boolean(e.model.fmgr_approval) && universal['isFmgr']);
                amountPayableLabelField.toggle(Boolean(universal['isHr']) || Boolean(e.model.hr_approval));
                amountApprovedLabelField.toggle(Boolean(universal['isFmgr']) || Boolean(e.model.fmgr_approval)); // toggle visibility for amount approved
                amountReceivedLabelField.toggle(Boolean(e.model.amount_received)); // toggle visibility for amount received
                receivedByLabelField.toggle(Boolean(e.model.received_by)); // toggle visibility for received by
                dateReceivedLabelField.toggle(Boolean(e.model.date_received)); // toggle visibility for date received

                // Edit Labels
                if (!e.model.fields.amount_requested.editable /*|| !universal['isFmgr']*/) {
                    // This Label means Amount Requested and Percentage fields will not be edited
                    percentageLabelField.find('label').html('Amount Requested <br><small class="text-danger text-bold">(10% to 30% of Salary)</small>');
                    amountRequestedLabelField.find('label').html('Amount Requested <br> <small class="text-danger text-bold" ></small>');
                } else {
                    // Editing is enabled
                    percentageLabelField.find('label').html('Amount Requested <br><small class="text-danger text-bold">Enter as Percentage (10% - 30%)</small>');
                    amountRequestedLabelField.find('label').html('Amount Requested <br> <small class="text-danger text-bold" > Enter as Figure</small>');
                }

                // Validations
                hodCommentLabelField.find('.k-textbox').attr('data-required-msg', 'HoD Comment is required!').attr('rows', '6');
                hrCommentLabelField.find('.k-textbox').attr('data-required-msg', 'HR Comment is required!').attr('rows', '6');
                amountPayableLabelField.find('.k-input').attr('data-required-msg', 'Amount Payable is required');
                fmgrCommentLabelField.find('.k-textbox').attr('data-required-msg', 'Finance Mgr. Comment is required!').attr('rows', '6');
                amountApprovedLabelField.find('.k-input').attr('data-required-msg', 'Amount Approved is required');
            }
        });

        $salaryAdvanceGrid.data('kendoGrid').thead.kendoTooltip({
            filter: "th.title",
            position: 'top',
            content: function (e) {
                let target = e.target; // element for which the tooltip is shown
                return $(target).text();
            }
        });

        $salaryAdvanceTooltip = $salaryAdvanceGrid.kendoTooltip({
            filter: "td:not('.k-detail-cell')", //this filter selects the second column's cells
            position: "top",
            content: function (e) {
                // hide popup as default action
                e.sender.popup.element.css("visibility", "hidden");
                let text = $(e.target).text();
                if (text) e.sender.popup.element.css("visibility", "visible");
                return text;
            }
        }).data("kendoTooltip");

        $salaryAdvanceGrid.on("click", ".action-edit", function (e) {
            let grid = $salaryAdvanceGrid.data("kendoGrid");
            let currentRow = grid.currentRow();
            let dataItem = grid.dataItem(currentRow);
            let errorMsg = '';
            if (dataItem['hod_approval_editable'] || (dataItem['hr_approval_editable'] && dataItem.hod_approval) || (dataItem['fmgr_approval_editable'] && dataItem.hr_approval)) {
                let row = $(this).closest("tr");
                let $this = $(this);
                let actionTools = $this.closest('.action-tools');
                grid.editRow(row);
                actionTools.html("<span class='col'><a href='#' class='text-success action-confirm-edit'><i class='fa fa-check'></i></a></span>" +
                    "<span class='col'><a href='#' class='text-black action-cancel-edit'><i class='k-icon k-i-cancel'></i></a></span>");

            } else {
                if (!dataItem.hod_approval) {
                    errorMsg = 'HoD must approve it first!'
                } else if (!dataItem.hr_approval) {
                    errorMsg = 'HR must approve it first!'
                }
                e.preventDefault();
                $.toast({
                    // heading: '<u>Information</u>',
                    text: '<b class="text-bold"><i class="fa fa-warning text-warning"></i> <span>' + errorMsg + ' </span></b>',
                    //icon: 'warning',
                    loader: false,        // Change it to false to disable loader
                    loaderBg: '#9EC600',  // To change the background
                    position: 'top-center',
                    stack: 1,
                    hideAfter: false
                });
            }
        });

        $salaryAdvanceGrid.on("click", ".action-cancel-edit", function () {
            //let row = $(this).closest("tr");
            let $this = $(this);
            let actionTools = $this.closest('.action-tools');
            actionTools.html("<span class='col' title='Edit'><a href='#' class='text-black action-edit'><i class='fa fa-pencil'></i></a></span>" +
                "<span class='col' title='Delete'><a href='#' class='text-danger action-delete'><i class='fas fa-trash-alt'></i></a></span>" +
                "<span class='col' title='More Info'><a href='#' class='text-primary action-more-info'><i class='fas fa-info-circle'></i></a></span>" +
                "</span>");
            $salaryAdvanceGrid.data("kendoGrid").cancelChanges();
        });

        $salaryAdvanceGrid.on("click", ".action-confirm-edit", function () {
            //let row = $(this).closest("tr");
            let $this = $(this);
            let actionTools = $this.closest('.action-tools');
            actionTools.html("<span class='col' title='Edit'><a href='#' class='text-black action-edit'><i class='fa fa-pencil'></i></a></span>" +
                "<span class='col' title='Delete'><a href='#' class='text-danger action-delete'><i class='fas fa-trash-alt'></i></a></span>" +
                "<span class='col' title='More Info'><a href='#' class='text-primary action-more-info'><i class='fas fa-info-circle'></i></a></span>" +
                "</span>");
            $salaryAdvanceGrid.data("kendoGrid").saveChanges();
        });

        $salaryAdvanceGrid.on("click", ".action-delete", function () {
            let row = $(this).closest("tr");
            $salaryAdvanceGrid.data("kendoGrid").removeRow(row);
        });

        $salaryAdvanceGrid.on("click", ".action-more-info", function () {
            let row = $(this).closest("tr");
            row.find('.k-hierarchy-cell>a').click();
        });
    });
</script>
