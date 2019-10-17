<?php
$universal = new stdClass();
$universal->hr_comment_editable = getCurrentHR() == $current_user->user_id;
$universal->fgmr_comment_editable = getCurrentFgmr() == $current_user->user_id;
$universal->has_active_application = hasActiveApplication($current_user->user_id);
/** @var int $select_row_id */
$universal->select_row_id = $select_row_id;
?>
<?php require_once APP_ROOT . '\views\includes\header.php'; ?>
<?php require_once APP_ROOT . '\views\includes\navbar.php'; ?>
<?php require_once APP_ROOT . '\views\includes\sidebar.php'; ?>
<input id="url_root" type="hidden" value="<?php echo URL_ROOT; ?>">
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
    <!-- footer -->
    <?php require_once APP_ROOT . '\views\includes\footer.php'; ?>
    <!-- footer -->
</div>
</div>
<!-- /.wrapper -->
<?php require_once APP_ROOT . '\views\includes\scripts.php'; ?>
<script>
    /*Error constants*/
    const dbg_turn_off_disable_add_button = true;
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
        URL_ROOT = $('#url_root').val();
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
                    let row = sheet.rows[rowIndex];
                    let dataItem = {
                        hod_approval: row.cells[5].value,
                        fmgr_approval: row.cells[12].value,
                        hr_approval: row.cells[8].value
                    };
                    row.cells[5].value = dataItem.hod_approval == null? 'Pending': (dataItem.hod_approval? 'Approved': 'Rejected');
                    row.cells[8].value = dataItem.hr_approval == null? 'Pending': (dataItem.hr_approval? 'Approved': 'Rejected');
                    row.cells[12].value = dataItem.fmgr_approval == null? 'Pending': (dataItem.fmgr_approval? 'Approved': 'Rejected');

                    // alternating row colors
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
                extra: false,
                mode: "row",
                messages: {
                    info: "",
                    selectedItemsFormat: ""
                }
            },
            columnMenu: true,
            sortable: true,
            //groupable: true,
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
                    width: 450,
                    groupHeaderTemplate: "Date Raised: #= kendo.toString(kendo.parseDate(value), 'dddd dd MMM, yyyy h:mm:ss tt') #",
                    filterable: {
                        cell: {
                            template: dateRangeFilter
                        }
                    }
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
                    format: "{0:#\\%}",
                    filterable: false
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
                    format: "{0:c}",
                    filterable: false
                },
                {
                    field: 'hod_approval',
                    title: 'HoD Approval',
                    editor: approvalEditor,
                    template: function (dataItem) {
                        return "<span title='HoD Approved: " + (dataItem.hod_approval === null ? 'Pending' : dataItem.hod_approval ? 'Yes' : 'No') + "'>" + (dataItem.hod_approval === null ? 'Pending' : dataItem.hod_approval ? 'Yes' : 'No') + "</span>"
                    },
                    headerAttributes: {
                        "class": "title"
                    },
                    width: 200,
                    groupHeaderTemplate: "HoD Approved: #= value? 'Yes' : 'No' # | Total: #= count #",
                    aggregates: ["count"],
                    /*filterable:{
                        ui: function(element){
                            element.kendoDropDownList({
                                dataSource: [{ value: 1, text: "Approved" }, { value: 0, text: "Rejected" }],
                                //optionLabel: "--Select--",
                                dataTextField: "text",
                                dataValueField: "value",
                            });
                        }
                    }*/
                    filterable: false
                },
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
                    filterable: false
                },
                {
                    field: 'hod_approval_date',
                    title: 'HoD. Approval Date ',
                    headerAttributes: {
                        "class": "title"
                    },
                    template: function (dataItem) {
                        let date = dataItem.hod_approval_date ? kendo.toString(kendo.parseDate(dataItem.hod_approval_date), 'dddd dd MMM, yyyy') : '';
                        return "<span title='HoD Approval Date: " + date + "'>" + date + "</span>";
                    },
                    width: 200,
                    groupHeaderTemplate: "Date Raised: #= value ? kendo.toString(kendo.parseDate(value), 'dddd dd MMM, yyyy h:mm:ss tt') : '' #",
                    hidden: false,
                    filterable: false

                },
                {
                    field: 'hr_approval',
                    title: 'HR Approval',
                    editor: approvalEditor,
                    template: function (dataItem) {
                        return "<span title='HR Approved: " + (dataItem.hr_approval === null ? 'Pending' : dataItem.hr_approval ? 'Yes' : 'No') + "'>" + (dataItem.hr_approval === null ? 'Pending' : dataItem.hr_approval ? 'Yes' : 'No') + "</span>"
                    },
                    headerAttributes: {
                        "class": "title"
                    },
                    groupHeaderTemplate: "HR Approved: #= value? 'Yes' : 'No' # |  Total: #= count #",
                    aggregates: ["count"],
                    width: 200,
                    filterable: false

                },
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
                    width: 200,
                    filterable: false

                },
                {
                    field: 'amount_payable',
                    title: 'Amount Payable',
                    template: function (dataItem) {
                        return "<span title='Amount Payable: " + (kendo.format('{0:c}', dataItem.amount_payable)) + "'>" + (kendo.format('{0:c}', dataItem.amount_payable)) + "</span>"
                    },
                    format: "{0:c}",
                    headerAttributes: {
                        "class": "title"
                    },
                    groupHeaderTemplate: "Amount Payable: #= value?  kendo.format('{0:c}', value) : 'Pending' #",
                    aggregates: ["max", "min"],
                    width: 200,
                    filterable: false

                },
                {
                    field: 'hr_approval_date',
                    title: 'HR. Approval Date ',
                    headerAttributes: {
                        "class": "title"
                    },
                    template: function (dataItem) {
                        let date = dataItem.hr_approval_date ? kendo.toString(kendo.parseDate(dataItem.hr_approval_date), 'dddd dd MMM, yyyy') : '';
                        return "<span title='HR Approval Date: " + date + "'>" + date + "</span>";
                    },
                    width: 200,
                    groupHeaderTemplate: "HR Approval Date: #= value ? kendo.toString(kendo.parseDate(value), 'dddd dd MMM, yyyy h:mm:ss tt') : '' #",
                    hidden: false,
                    filterable: false

                },
                {
                    field: 'fmgr_approval',
                    title: 'Fin. Mgr. Approval',
                    editor: approvalEditor,
                    template: function (dataItem) {
                        return "<span title='Approved by Finance Mgr.: " + (dataItem.fmgr_approval === null ? 'Pending' : dataItem.fmgr_approval ? 'Yes' : 'No') + "'>" + (dataItem.fmgr_approval === null ? 'Pending' : dataItem.fmgr_approval ? 'Yes' : 'No') + "</span>"
                    },
                    headerAttributes: {
                        "class": "title"
                    },
                    width: 200,
                    groupHeaderTemplate: "Finance Manager Approved: #= value? 'Yes' : 'No' # |  Total: #=count #",
                    aggregates: ["count"],
                    filterable: false

                },
                {
                    field: 'fmgr_comment',
                    title: 'Fin. Mgr. Comment',
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
                    },
                    filterable: false

                },
                {
                    field: 'amount_approved',
                    title: 'Amount Approved',
                    template: function (dataItem) {
                        return "<span title='Amount Approved: " + (kendo.format('{0:c}', dataItem.amount_approved)) + "'>" + (kendo.format('{0:c}', dataItem.amount_approved)) + "</span>"
                    },
                    headerAttributes: {
                        "class": "title"
                    },
                    groupHeaderTemplate: "Amount Approved: #= value?  kendo.format('{0:c}', value): 'Pending' #",
                    aggregates: ["max", "min"],
                    format: "{0:c}",
                    width: 200,
                    filterable: false

                },
                {
                    field: 'fmgr_approval_date',
                    title: 'Fin. Mgr. Approval Date ',
                    headerAttributes: {
                        "class": "title"
                    },
                    template: function (dataItem) {
                        let date = dataItem.fmgr_approval_date ? kendo.toString(kendo.parseDate(dataItem.fmgr_approval_date), 'dddd dd MMM, yyyy') : '';
                        return "<span title='Finance Mgr. Approval Date: " + date + "'>" + date + "</span>";
                    },
                    width: 200,
                    groupHeaderTemplate: "Finance Mgr. Approval Date: #= value ? kendo.toString(kendo.parseDate(value), 'dddd dd MMM, yyyy h:mm:ss tt') : '' #",
                    hidden: false,
                    filterable: false

                },
                {
                    field: 'amount_received',
                    title: 'Amount Received',
                    template: function (dataItem) {
                        return "<span title='Amount Received: " + kendo.format('{0:c}', dataItem.amount_received) + "'>" + kendo.format('{0:c}', dataItem.amount_received) + "</span>";

                        // return dataItem.amount_received ? "<span title='Amount Received: " + kendo.format('{0:c}', dataItem.amount_received) + "'>" + kendo.format('{0:c}', dataItem.amount_received) + "</span>" : "<span title='0' " + universal.currency_symbol + ">0" + universal.currency_symbol + "</span>"
                    },
                    attributes: {
                        class: 'amount_received'
                    },
                    headerAttributes: {
                        "class": "title"
                    },
                    width: 200,
                    groupHeaderTemplate: "Amount Received: #: kendo.format('{0:c}', value) #",
                    filterable: false

                },
                {
                    field: 'received_by',
                    title: 'Received By',
                    hidden: false,
                    template: function (dataItem) {
                        return dataItem.received_by ? "<span title='Received by: " + dataItem.received_by + "'>" + dataItem.received_by + "</span>" : "<span title='Pending'>Pending</span>"
                    },
                    headerAttributes: {
                        "class": "title"
                    },
                    width: 200,
                    groupHeaderTemplate: "Received By: #:  value #",
                    filterable: false

                },
                {
                    field: 'date_received',
                    title: 'Date Received',
                    hidden: false,
                    template: function (dataItem) {
                        let date = dataItem.date_received ? kendo.toString(kendo.parseDate(dataItem.date_received), 'dddd dd MMM, yyyy') : '';
                        return "<span title='Date Received: " + date + "'>" + date + "</span>";
                    },
                    headerAttributes: {
                        "class": "title"
                    },
                    width: 450,
                    groupHeaderTemplate: "Date Received: #= value? kendo.toString(kendo.parseDate(value), 'dddd dd MMM, yyyy') : 'Pending' #",
                    filterable: {
                        cell: {
                            template: dateRangeFilter
                        }
                    }
                },
                {
                    template: "<span class='text-center action-tools'>" +
                        "<span class='col' title='Edit'><a href='javascript:' class='text-black action-edit'><i class='fa fa-pencil'></i></a></span>" +
                        "<span class='col d-none' title='Delete'><a href='javascript:' class='text-danger action-delete'><i class='fas fa-trash-alt'></i></a></span>" +
                        "<span class='col d-none' title='More Info'><a href='javascript:' class='text-primary action-more-info'><i class='fas fa-info-circle'></i></a></span>" +
                        "<span class='col' title='Print'><a href='\\#' class='text-primary action-print print-it' target='_blank'><i class='fas fa-print'></i></a></span>" +
                        "</span>",
                    width: 100,
                    title: "Action",
                    headerAttributes: {
                        "class": "title"
                    },
                    /*locked: true,
                    lockable: true*/
                },
            ],
            detailTemplate: kendo.template(summaryTemplate),
            dataSource: salaryAdvanceDataSource,
            dataBinding: function () {
                //let no = (this.dataSource.page() - 1) * this.dataSource.pageSize();
            },
            dataBound: function (e) {
                let grid = e.sender;
                let data = grid.dataSource.data();
                $.each(data, function (i, row) {
                    $('tr[data-uid="' + row.uid + '"] ').attr('data-id-salary-advance', row['id_salary_advance']).find(".print-it").attr("href", URL_ROOT + "/salary-advance/print/" + row["id_salary_advance"]);
                });
                $(".print-it").printPage();
                let headingRow = $salaryAdvanceGrid.find('thead tr[role=row]');
                headingRow.find('th.k-hierarchy-cell').hide();
                headingRow.find('th.k-hierarchy-cell').next('th').attr('colspan', 2);
                let filterRow = $salaryAdvanceGrid.find('thead tr.k-filter-row');
                filterRow.find('th.k-hierarchy-cell').hide();
                filterRow.find('th.k-hierarchy-cell').next('th').attr('colspan', 2);
                const {has_active_application} = universal;
                if (has_active_application) {
                    //disableGridAddButton();
                }
                let selectRow = $salaryAdvanceGrid.find(`tr[data-id-salary-advance=${universal['select_row_id']}]`);
                grid.select(selectRow);
                selectRow.find('.action-more-info').click();
            },
            detailInit: function (e) {
                let grid = $salaryAdvanceGrid.data("kendoGrid");
                let masterRow = e.detailRow.prev('tr.k-master-row');
                let dataItem = grid.dataItem(masterRow);
                e.detailRow.find(".print-it").attr("href", URL_ROOT + "/salary-advance/print/" + dataItem["id_salary_advance"]);
                $(".print-it").printPage();
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

        $salaryAdvanceGrid.on("click", ".action-edit", function (e) {
            let grid = $salaryAdvanceGrid.data("kendoGrid");
            let target = $(e.currentTarget);
            let currentRow;
            if (target.hasClass('in-detail-row')) {
                currentRow = target.closest('tr.k-detail-row').prev('tr.k-master-row');
            } else {
                currentRow = grid.currentRow();
            }
            let dataItem = grid.dataItem(currentRow);
            grid.editRow(currentRow);
            let actionTools = target.parent('.action-tools');
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
        if (!dbg_turn_off_disable_add_button)
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
</body>
</html>