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
$universal->hr_comment_editable = getCurrentHR() == $current_user->user_id;
$universal->fgmr_comment_editable = $universal->amount_requested_editable = getCurrentFgmr() == $current_user->user_id;
$universal->has_active_application = hasActiveApplication($current_user->user_id);
?>
<!--suppress HtmlUnknownTarget -->
<script>
    /*Error constants*/
    const ERROR_UNSPECIFIED_ERROR = 'E_1000';
    const ERROR_AN_APPLICATION_ALREADY_EXISTS = 'E_1001';
    const ERROR_APPLICATION_ALREADY_REVIEWED = 'E_1002';
    let universal = JSON.parse(`<?php echo json_encode($universal); ?>`);
    let kGridAddButton;
    let $salaryAdvanceGrid;
    let salaryAdvanceDataSource;
    let $salaryAdvanceTooltip;
    $(document).ready(function () {
        //$(document).on('click', '#percentageRadio', toggleAmountRequested);
        kendo.culture().numberFormat.currency.symbol = 'GH₵';
        $salaryAdvanceGrid = $("#salary_advance");
        $salaryAdvanceGrid.on('change', "input[name=employee]", function (e) {
            //let select = $(this).data("kendoDropDownList");
        });
        // '/salary-advance-ajax/'
        salaryAdvanceDataSource = new kendo.data.DataSource({
            pageSize: 5,
            transport: {
                read: {
                    url: URL_ROOT + "/salary-advance-ajax/",
                    type: "post",
                    dataType: "json"
                },
                update: {
                    url: URL_ROOT + "/salary-advance-ajax/update/",
                    type: 'post',
                    dataType: 'json'
                },
                destroy: {
                    url: URL_ROOT + "/salary-advance-ajax/destroy/",
                    type: 'post',
                    dataType: 'json'
                },
                create: {
                    url: URL_ROOT + "/salary-advance-ajax/create",
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
                if (e.errors['code'] === ERROR_AN_APPLICATION_ALREADY_EXISTS) {
                    // Disable Grid Add Button
                    disableGridAddButton();
                }
                toastError(e.errors['message']);
                salaryAdvanceDataSource.cancelChanges();
            },
            requestEnd: function (e) {
                if (e.type === 'update' && !e.response[0].success) {
                    e.response[0].reason ? toastError(e.response[0].reason) : toastError('An error occurred!');
                } else if (e.type === 'update' && e.response[0].success) {
                    toastSuccess('Success', 5000);
                }
                if (e.type === 'create' && e.response[0].success) {
                    toastSuccess('Success', 5000);
                    if (e.response[0]['has_active_application']) {
                        disableGridAddButton();
                    } else {
                        enableGridAddButton();
                    }
                }
                if (e.type === 'destroy' && e.response.success) {
                    toastSuccess('Success', 5000);
                    universal.has_active_application = e.response['has_active_application'];
                    if (universal.has_active_application) {
                        disableGridAddButton();
                    } else {
                        enableGridAddButton();
                    }
                } else if (e.type === 'destroy' && !e.response.success) {
                    e.response.reason ? toastError(e.response.reason) : toastError('An error occurred!');
                    salaryAdvanceDataSource.cancelChanges();
                }
            },
            schema: {
                model: {
                    id: "id_salary_advance",
                    fields: {
                        /*name: {
                            editable: false,
                            from: "employee.name"
                        },
                        employee: {
                            defaultValue: {}
                        },*/
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
                            editable: false
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
                            editable: false,
                            nullable: true
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
            toolbar: ["create", "excel"],
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
                /*{
                    field: 'name',
                    title: 'Employee',
                    editor: dropDownEditor,
                    template: function (dataItem) {
                        return "<span title='" + dataItem.name + "'>" + dataItem.name + "</span>";
                    },
                    width: "15%",
                    headerAttributes: {
                        "class": "title"
                    }
                },*/
               /* {
                    field: 'department',
                    title: 'Department',
                    template: function (dataItem) {
                        return "<span title='" + dataItem.department + "'>" + dataItem.department + "</span>";
                    },
                    headerAttributes: {
                        "class": "title"
                    },
                    filterable: {
                        ui: departmentFilter
                    }
                },*/
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
                    groupHeaderTemplate: "Amount Requested in Percentage: #= value? value + '%' : '' #",
                    aggregates: ["max", "min"],
                    format: "{0:#\\%}"
                },
                {
                    field: 'amount_requested',
                    title: 'Amount Requested in Figures',
                    width: '10%',
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
                            }
                        },
                        {
                            field: 'hod_approval',
                            title: 'Approved by HoD?',
                            editor: customBoolEditor,
                            template: function (dataItem) {
                                let hod_approval = dataItem.hod_approval ? dataItem.hod_approval : '';
                                return "<span title='HoD Approved: " + (hod_approval ? 'Yes' : 'No') + "'>" + (hod_approval ? 'Yes' : 'No') + "</span>"
                            },
                            headerAttributes: {
                                "class": "title"
                            },
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
                            }
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
                            aggregates: ["count"]
                        },
                        {
                            field: 'amount_payable',
                            title: 'Amount Payable',
                            template: function (dataItem) {
                                return "<span title='Amount Payable: " + (dataItem.amount_payable ? kendo.toString('GH₵ ' + kendo.format('{0:n}', dataItem.amount_payable)) : '') + "'>" + (dataItem.amount_payable ? kendo.toString('GH₵ ' + kendo.format('{0:n}', dataItem.amount_payable)) : '') + "</span>"
                            },
                            headerAttributes: {
                                "class": "title"
                            },
                            groupHeaderTemplate: "Amount Payable: #= value? kendo.toString('GH₵ ' + kendo.format('{0:n}', value)) : 'Pending' #",
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
                                let fmgr_approval = dataItem.fmgr_approval_date ? dataItem.fmgr_approval : '';
                                return "<span title='Approved by Finance Mgr.: " + (fmgr_approval ? 'Yes' : 'No') + "'>" + (fmgr_approval ? 'Yes' : 'No') + "</span>"
                            },
                            headerAttributes: {
                                "class": "title"
                            },
                            groupHeaderTemplate: "Finance Manager Approved: #= value? 'Yes' : 'No' # |  Total: #=count #",
                            aggregates: ["count"]
                        },
                        {
                            field: 'amount_approved',
                            title: 'Amount Approved',
                            template: function (dataItem) {
                                return "<span title='Amount Approved: " + (dataItem.amount_approved ? kendo.toString('GH₵ ' + kendo.format('{0:n}', dataItem.amount_approved)) : '') + "'>" + (dataItem.amount_approved ? kendo.toString('GH₵ ' + kendo.format('{0:n}', dataItem.amount_approved)) : '') + "</span>"
                            },
                            headerAttributes: {
                                "class": "title"
                            },
                            groupHeaderTemplate: "Amount Approved: #= value? kendo.toString('GH₵ ' + kendo.format('{0:n}', value)): 'Pending' #",
                            aggregates: ["max", "min"]
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
                            groupHeaderTemplate: "Finance Mgr. Approval Date: #= kendo.toString(kendo.parseDate(value), 'dddd dd MMM, yyyy h:mm:ss tt') #",
                            hidden: true
                        }
                    ],
                },
                {
                    field: 'amount_received',
                    title: 'Amount Received',
                    template: function (dataItem) {
                        return dataItem.amount_received ? "<span title='Amount Received: " + kendo.toString('GH₵ ' + kendo.format('{0:n}', dataItem.amount_received)) + "'>" + kendo.toString('GH₵ ' + kendo.format('{0:n}', dataItem.amount_received)) + "</span>" : "<span title='Pending'>Pending</span>"
                    },
                    width: '10%',
                    attributes: {
                        class: 'amount_received'
                    },
                    headerAttributes: {
                        "class": "title"
                    },
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
                    headerAttributes: {
                        "class": "title"
                    },
                    groupHeaderTemplate: "Date Received: #= value? kendo.toString(kendo.parseDate(value), 'dddd dd MMM, yyyy') : 'Pending' #",
                },
                {
                    template: "<span class='text-center action-tools'>" +
                        "<span class='col' title='Edit'><a href='\\#' class='text-black action-edit'><i class='fa fa-pencil'></i></a></span>" +
                        "<span class='col' title='Delete'><a href='\\#' class='text-danger action-delete'><i class='fas fa-trash-alt'></i></a></span><span class='col' title='More Info'><a href='\\#' class='text-primary action-more-info'><i class='fas fa-info-circle'></i></a></span>" +
                        "<span class='col' title='Print'><a href='\\#' class='text-primary action-print print-it' target='_blank'><i class='fas fa-print'></i></a></span>" +
                        "</span>",
                    width: "10%",
                    title: "Action"
                },
            ],
            detailTemplate: kendo.template(`
    <div class="">
        <b>Date Raised</b>: #= kendo.toString(kendo.parseDate(date_raised), 'dddd dd MMM, yyyy') #</br>
        <b>Amount Requested in Figures</b>:#=  amount_requested? kendo.toString('GH₵ ' + kendo.format('{0:n}', amount_requested)) : '' #</br>
        <b>Amount Requested in Percentage </b>:#=  percentage? percentage + '%' : '' #</br>
        <b>Approved by HoD?</b> #= hod_approval? 'Yes' : 'No' #</br>
        #=hod_approval_date? '<b>HoD Approval Date: </b>' + kendo.toString(kendo.parseDate(hod_approval_date), 'dddd dd MMM, yyyy')+'</br>': '' #
        <b>Approved by HR? </b> #= hr_approval? 'Yes' : 'No' # </br>
        #=hr_approval_date? '<b>HR Approval Date: </b>' + kendo.toString(kendo.parseDate(hr_approval_date), 'dddd dd MMM, yyyy')+'</br>': '' #
        <b >Amount Payable </b>: #= amount_payable? kendo.toString('GH₵ ' + kendo.format('{0:n}', amount_payable)) : 'Pending' #</br>
        <b>Approved by Finance Manager? </b> #= fmgr_approval? 'Yes' : 'No' # </br>
        #=fmgr_approval_date? '<b>Finance Mgr. Approval Date: </b>' + kendo.toString(kendo.parseDate(fmgr_approval_date), 'dddd dd MMM, yyyy')+'</br>': '' #
        <b>Amount Approved </b>: #= amount_approved? kendo.toString('GH₵ ' + kendo.format('{0:n}', amount_approved)) : 'Pending' #</br>
        <b>Amount Received </b>: #= amount_received? kendo.toString('GH₵ ' + kendo.format('{0:n}', amount_received)) : 'Pending' #</br>
        #=date_received? '<b>Date Received: </b>' + kendo.toString(kendo.parseDate(date_received), 'dddd dd MMM, yyyy')+'</br>': '' #
        #=received_by? '<b>Received by: </b>' + received_by  +'</br>': '' #
    </div>
   `),
            dataSource: salaryAdvanceDataSource,
            dataBinding: function () {
                //let no = (this.dataSource.page() - 1) * this.dataSource.pageSize();
            },
            dataBound: function () {
                let grid = $salaryAdvanceGrid.data('kendoGrid');
                grid.hideColumn("name");
                grid.hideColumn("department");
                let data = grid.dataSource.data();
                $.each(data, function (i, row) {
                    $('tr[data-uid="' + row.uid + '"] ').find(".print-it").attr("href", URL_ROOT + "/salary-advance/print/" + row["id_salary_advance"]);
                });
                $(".print-it").printPage();
                //kGridAddButton = $(".k-grid-add");
                const {has_active_application} = universal;
                if (has_active_application) {
                    disableGridAddButton();
                }
                /*let len = $salaryAdvanceGrid.find("tbody tr").length;
                for(let i=0;i<len ; i++)
                {
                    let model = grid.dataSource.at(i);

                }*/
            },
            beforeEdit: function (e) {
                e.model.fields["percentage"].editable = e.model.fields['amount_requested'].editable = !Boolean(e.model.hod_approval || e.model.fmgr_approval || e.model.hr_approval);
            },
            edit: function (e) {
                let percentageLabelField = e.container.find('.k-edit-label:eq(1), .k-edit-field:eq(1) ');
                let amountRequestedLabelField = e.container.find(".k-edit-label:eq(2), .k-edit-field:eq(2)");
                let amountRequestedNumericTextBox = amountRequestedLabelField.find('input[data-role="numerictextbox"]').data('kendoNumericTextBox');
                let amountRequestedPercentageNumericTextBox = percentageLabelField.find('input[data-role="numerictextbox"]').data('kendoNumericTextBox');
                let radioButtonGroup = $('<div class="editor-field"><input type="radio" name="toggleAmountRequested" id="percentageRadio" class="k-radio" checked="checked" > <label class="k-radio-label" for="percentageRadio" >Percentage</label><input type="radio" name="toggleAmountRequested" id="figureRadio" class="k-radio"> <label class="k-radio-label" for="figureRadio">Figure</label></div>');

                // Toggle visibility off for all editor fields and labels
                e.container.find('.k-edit-label, .k-edit-field').addClass("pt-2").toggle(false);

                if (e.model.isNew() || e.model.fields["percentage"].editable) {
                    amountRequestedLabelField.toggle(true);
                    percentageLabelField.toggle(true);

                    amountRequestedLabelField.find('input').attr('min', '0');
                    percentageLabelField.find('input').attr('min', 10).attr('max', 30).attr('data-min-msg', 'Amount Requested must be at least 10% of net salary!').attr('data-max-msg', 'Amount Requested must not exceed 30% of net salary!');

                    percentageLabelField.find('label').html('Amount Requested <br><small class="text-danger text-bold">Enter as Percentage (10% - 30%)</small>');
                    amountRequestedLabelField.find('label').html('Amount Requested <br> <small class="text-danger text-bold" > Enter as Figure</small>');

                    radioButtonGroup.insertAfter(e.container.find('.k-edit-form-container').children('[data-container-for=amount_requested]'));
                    radioButtonGroup.on('click', '#percentageRadio', function () {
                        e.model.amount_requested_is_percentage = true;
                        amountRequestedNumericTextBox.enable(false);
                        amountRequestedPercentageNumericTextBox.enable();
                        amountRequestedPercentageNumericTextBox.focus();
                    });

                    radioButtonGroup.on('click', '#figureRadio', function () {
                        e.model.amount_requested_is_percentage = false;
                        amountRequestedPercentageNumericTextBox.enable(false);
                        amountRequestedNumericTextBox.enable();
                        amountRequestedNumericTextBox.focus();
                    });

                    if (e.model.amount_requested_is_percentage) {
                        amountRequestedPercentageNumericTextBox.focus();
                        amountRequestedNumericTextBox.enable(false);
                        radioButtonGroup.find('#percentageRadio').attr('checked', 'checked');
                    } else {
                        amountRequestedNumericTextBox.focus();
                        amountRequestedPercentageNumericTextBox.enable(false);
                        radioButtonGroup.find('#figureRadio').attr('checked', 'checked');
                    }

                    e.container.data('kendoWindow').bind('activate', function () {
                        if (e.model.amount_requested_is_percentage) {
                            amountRequestedPercentageNumericTextBox.focus();
                        } else {
                            amountRequestedNumericTextBox.focus();
                        }
                    });
                } else {
                    percentageLabelField.toggle(Boolean(e.model.amount_requested_is_percentage)).find('label').html('Amount Requested');
                    amountRequestedLabelField.toggle(!Boolean(e.model.amount_requested_is_percentage)).find('label').html('Amount Requested');
                }
             /*   e.container.find('.k-edit-label:eq(1), .k-edit-field:eq(1)').toggle(Boolean(e.model.percentage) || Boolean(e.model.amount_requested_is_percentage)); // toggle visibility for amount requested in percentage
                e.container.find('.k-edit-label:eq(2), .k-edit-field:eq(2)').toggle(Boolean(e.model.amount_requested)); // toggle visibility for amount requested in figures*/
                e.container.find('.k-edit-label:eq(8), .k-edit-field:eq(8)').toggle(Boolean(e.model.hr_approval)); // toggle visibility for amount payable
                e.container.find('.k-edit-label:eq(12), .k-edit-field:eq(12)').toggle(Boolean(e.model.fmgr_approval)); // toggle visibility for amount approved
                e.container.find('.k-edit-label:eq(14), .k-edit-field:eq(14)').toggle(Boolean(e.model.amount_received)); // toggle visibility for amount received
                e.container.find('.k-edit-label:eq(15), .k-edit-field:eq(15)').toggle(Boolean(e.model.received_by)); // toggle visibility for received by
                e.container.find('.k-edit-label:eq(16), .k-edit-field:eq(16)').toggle(Boolean(e.model.date_received)); // toggle visibility for date received // kendo grid has date set to today by default
            }
        });
        kGridAddButton = $('.k-grid-add');
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

        // kGridAddButton
        //     .removeClass("k-grid-add")
        //     .addClass("k-state-disabled k-grid-add-disabled")
        //     .removeAttr("href").click(function () {
        //     toastError("You already have an active salary advance application for the month of " + moment()["format"]("MMMM") + "!");
        // });

        $salaryAdvanceGrid.data('kendoGrid').thead.kendoTooltip({
            filter: "th.title",
            position: 'top',
            content: function (e) {
                let target = e.target; // element for which the tooltip is shown
                return $(target).text();
            }
        });

        $salaryAdvanceGrid.on("click", ".action-edit", function () {
            let grid = $salaryAdvanceGrid.data("kendoGrid");
            //let currentRow = grid.currentRow();
            //let dataItem = grid.dataItem(currentRow);
            let row = $(this).closest("tr");
            let $this = $(this);
            let actionTools = $this.closest('.action-tools');
            grid.editRow(row);
            actionTools.html("<span class='col'><a href='#' class='text-success action-confirm-edit'><i class='fa fa-check'></i></a></span>" +
                "<span class='col'><a href='#' class='text-black action-cancel-edit'><i class='k-icon k-i-cancel'></i></a></span>");
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

        $salaryAdvanceGrid.on('click', '.k-grid-add-disabled', function () {
            toastError("You already have an active salary advance application for the month of " + moment()["format"]("MMMM") + "!");
        });

        kendo.ui.Grid.fn["currentRow"] = function () {
            //this will only work if grid is navigatable
            let cell = this.current();
            if (cell) {
                return cell.closest('tr')[0];
            }
            //following will only work if grid is selectable, it will get the 1st row only for multiple selection
            if (this.options.selectable !== false)
                return this.select()[0];
            return null;
        }
    });

    toggleAmountRequested = function () {
        if (this.id === 'percentageRadio') {

        }
    };

    function disableGridAddButton() {
        kGridAddButton.attr('disabled', 'disabled')
            .removeClass("k-grid-add")
            .addClass("k-state-disabled k-grid-add-disabled")
            .removeAttr("href");
    }

    function enableGridAddButton() {
        kGridAddButton.removeAttr('disabled')
            .addClass('k-grid-add')
            .removeClass('k-state-disabled k-grid-add-disabled')
            .attr('href', '#');
    }
</script>