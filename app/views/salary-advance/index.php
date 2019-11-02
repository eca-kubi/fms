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
</div>
<!.. /.wrapper -->
<?php require_once APP_ROOT . '\templates\x-kendo-templates\x-kendo-templates.php'; ?>
<?php require_once APP_ROOT . '\views\includes\scripts.php'; ?>

<?php
$user = new User($current_user->user_id);
$universal = new stdClass();
$universal->currency_symbol = CURRENCY_GHS;
$universal->hr_comment_editable = $universal->isHr = getCurrentHR() === $current_user->user_id;
$universal->fgmr_comment_editable = $universal->isFmgr = getCurrentFgmr() === $current_user->user_id;
/** @var int|null $select_row_id */
$universal->select_row_id = $select_row_id;
$universal->has_active_application = hasActiveApplication($current_user->user_id);
$universal->basic_salary = $user->basic_salary;
?>
<!--suppress HtmlUnknownTarget -->
<script>
    /*Error constants*/
    const dbg_turn_off_disable_add_button = true;
    const ERROR_UNSPECIFIED_ERROR = 'E_1000';
    const ERROR_AN_APPLICATION_ALREADY_EXISTS = 'E_1001';
    const ERROR_APPLICATION_ALREADY_REVIEWED = 'E_1002';
    let universal = JSON.parse(`<?php echo json_encode($universal, JSON_THROW_ON_ERROR, 512); ?>`);
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
            filter: [{field: "date_raised", operator: "gte", value: new Date(firstDayOfMonth)}, {
                field: 'date_raised',
                operator: "lte",
                value: new Date(lastDayOfMonth)
            }],
            pageSize: 20,
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
                    return response.errors;
                },
                parameterMap: function (data, operation) {
                    if (operation !== "read") {
                        return kendo.stringify(data);
                    }
                }
            },
            error: function (e) {
                if (e.errors[0]['code'] === ERROR_AN_APPLICATION_ALREADY_EXISTS) {
                    disableGridAddButton();
                }
                salaryAdvanceDataSource.cancelChanges();
                toastError(e.errors[0]['message']);
            },
            requestEnd: function (e) {
                if (e.type === 'update' && e.response.length > 0 || e.type === 'create' && e.response.length > 0) {
                    toastSuccess('Success', 5000);
                }
            },
            schema: {
                model: {
                    id: "id_salary_advance",
                    fields: {
                        date_raised: {
                            type: 'date',
                            editable: false
                        },
                        amount_requested: {
                            type: 'number',
                            validation: { //set validation rules
                                min: 0,
                                required: true
                            },
                            editable: true
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
                            nullable: true,
                            type: 'boolean',
                            editable: false
                        },
                        hod_approval: {
                            nullable: true,
                            type: 'boolean',
                            editable: false
                        },
                        hod_approval_date: {
                            type: "date",
                            editable: false
                        },
                        fmgr_approval: {
                            nullable: true,
                            type: 'boolean',
                            editable: false

                        },
                        amount_payable: {
                            type: 'number',
                            nullable: true,
                            validation: { //set validation rules
                                required: true,
                                min: '0'
                            },
                            editable: false
                        },
                        amount_approved: {
                            nullable: true,
                            type: 'number',
                            validation: { //set validation rules
                                required: true,
                                min: '0'
                            },
                            editable: false
                        },
                        received_by: {
                            nullable: true,
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
                            type: 'number',
                            nullable: true
                        },
                        raised_by_secretary: {
                            type: 'boolean'
                        },
                        user_id: {type: 'number'},
                        department_id: {
                            type: 'number', editable: false, nullable: true
                        },
                        raised_by_id: {type: "number"},
                        amount_received: {
                            type: "number", editable: false, nullable: true,
                        },
                        percentage: {
                            type: "number",
                            defaultValue: 10,
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
                        basic_salary: {
                            type: "number",
                            defaultValue: universal["basic_salary"]
                        }
                    }
                },
                parse: function (data) {
                    $.each(data, function (idx, elem) {
                        elem.date_raised = moment(elem.date_raised).format("YYYY-MM-DD");
                        elem.date_received = moment(elem.date_received).format("YYYY-MM-DD");
                    });
                    return data;
                }
            }
        });

        $salaryAdvanceGrid.kendoGrid({
            autoFitColumn: true,
            selectable: true,
            mobile: true,
            noRecords: true,
            navigatable: true,
            persistSelection: true,
            toolbar: [{
                name: "create",
                text: "Request Salary Advance",
                iconClass: "k-icon k-i-add text-primary"
            }, {name: "excel", iconClass: "text-primary", template: $("#exportToExcel").html()}],
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
                    row.cells[5].value = dataItem.hod_approval == null ? 'Pending' : (dataItem.hod_approval ? 'Approved' : 'Rejected');
                    row.cells[8].value = dataItem.hr_approval == null ? 'Pending' : (dataItem.hr_approval ? 'Approved' : 'Rejected');
                    row.cells[12].value = dataItem.fmgr_approval == null ? 'Pending' : (dataItem.fmgr_approval ? 'Approved' : 'Rejected');

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
            columnMenu: false,
            sortable: true,
            groupable: false,
            height: 520,
            resizable: true,
            scrollable: true,
            columnResizeHandleWidth: 30,
            pageable: {
                alwaysVisible: false,
                pageSizes: [20, 40, 60, 80, 100],
                buttonCount: 5
            },
            columns: [
                {
                    attributes: {class: "action"},
                    command: [{
                        name: "edit",
                        text: "Edit",
                        iconClass: {edit: "k-icon k-i-edit"},
                        className: "badge badge-success btn k-button text-black border"
                    },
                        {name: "print", template: $("#printButton").html()}],
                    headerAttributes: {class: "title"},
                    title: "Action",
                    width: 190
                },
                {
                    field: 'date_raised',
                    title: 'Date Raised',
                    headerAttributes: {
                        "class": "title"
                    },
                    width: 450,
                    groupHeaderTemplate: "Date Raised: #= kendo.toString(kendo.parseDate(value), 'dddd dd MMM, yyyy') #",
                    filterable: {
                        cell: {
                            template: dateRangeFilter
                        }
                    },
                    format: "{0:dddd dd MMM, yyyy}"
                },
                {
                    field: 'percentage',
                    title: 'Amount in Percentage',
                    template: function (dataItem) {
                        return "<span>" + (dataItem.percentage ? kendo.toString(dataItem.percentage, '#\\%') : '') + "</span>"
                    },
                    headerAttributes: {
                        "class": "title"
                    },
                    width: 250,
                    groupHeaderTemplate: "Amount in Percentage: #= value? value + '%' : '' #",
                    aggregates: ["max", "min"],
                    format: "{0:#\\%}",
                    filterable: false
                },
                {
                    field: 'amount_requested',
                    title: 'Amount in Figures',
                    width: 250,
                    editor: editNumberWithoutSpinners,
                    headerAttributes: {
                        "class": "title"
                    },
                    groupHeaderTemplate: "Amount in Figures: #=  value ? kendo.format('{0:c}', value) : ''#",
                    aggregates: ["max", "min", "count"],
                    format: "{0:c}",
                    filterable: false
                },
                {
                    field: 'hod_approval',
                    title: 'HoD Approval',
                    editor: approvalEditor,
                    template: function (dataItem) {
                        return "<span>" + (dataItem.hod_approval === null ? '<i class="fa fa-warning text-yellow"></i>  Pending' : dataItem.hod_approval ? '<i class="fa fa-check text-success"></i> Approved' : '<i class="fa fa-warning text-danger"></i> Rejected') + "</span>"
                    },
                    headerAttributes: {
                        "class": "title"
                    },
                    width: 200,
                    groupHeaderTemplate: "HoD Approved: #= value===null? 'Pending' : (value? 'Approved' : 'Rejected') # | Total: #= count #",
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
                        return "<span>" + hod_comment + "</span>"
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
                        return "<span>" + date + "</span>";
                    },
                    width: 200,
                    groupHeaderTemplate: "Date Raised: #= value ? kendo.toString(kendo.parseDate(value), 'dddd dd MMM, yyyy') : '' #",
                    hidden: false,
                    filterable: false

                },
                {
                    field: 'hr_approval',
                    title: 'HR Approval',
                    editor: approvalEditor,
                    template: function (dataItem) {
                        return "<span>" + (dataItem.hr_approval === null ? '<i class="fa fa-warning text-yellow"></i>  Pending' : dataItem.hr_approval ? '<i class="fa fa-check text-success"></i> Approved' : '<i class="fa fa-warning text-danger"></i> Rejected') + "</span>"
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
                        return `<span>${hr_comment}</span>`
                    },
                    width: 200,
                    filterable: false

                },
                {
                    field: 'amount_payable',
                    title: 'Amount Payable',
                    template: function (dataItem) {
                        if (dataItem.amount_payable == null) return "";
                        return "<span>" + (kendo.format('{0:c}', dataItem.amount_payable)) + "</span>"
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
                        return "<span>" + date + "</span>";
                    },
                    width: 200,
                    groupHeaderTemplate: "HR Approval Date: #= value ? kendo.toString(kendo.parseDate(value), 'dddd dd MMM, yyyy') : '' #",
                    hidden: false,
                    filterable: false

                },
                {
                    field: 'fmgr_approval',
                    title: 'Fin. Mgr. Approval',
                    editor: approvalEditor,
                    template: function (dataItem) {
                        return "<span>" + (dataItem.fmgr_approval === null ? '<i class="fa fa-warning text-yellow"></i>  Pending' : dataItem.fmgr_approval ? '<i class="fa fa-check text-success"></i> Approved' : '<i class="fa fa-warning text-danger"></i> Rejected') + "</span>"
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
                        return "<span>" + fmgr_comment + "</span>"
                    },
                    filterable: false

                },
                {
                    field: 'amount_approved',
                    title: 'Amount Approved',
                    template: function (dataItem) {
                        if (dataItem.amount_approved == null) return "";
                        return "<span>" + (kendo.format('{0:c}', dataItem.amount_approved)) + "</span>"
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
                        return "<span>" + date + "</span>";
                    },
                    width: 200,
                    groupHeaderTemplate: "Finance Mgr. Approval Date: #= value ? kendo.toString(kendo.parseDate(value), 'dddd dd MMM, yyyy') : '' #",
                    hidden: false,
                    filterable: false

                },
                {
                    field: 'amount_received',
                    title: 'Amount Received',
                    template: function (dataItem) {
                        if (dataItem.amount_received == null) return "";
                        return "<span>" + kendo.format('{0:c}', dataItem.amount_received) + "</span>";

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
                        return dataItem.received_by ? "<span>" + dataItem.received_by + "</span>" : ""
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
                        return "<span>" + date + "</span>";
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
                }
            ],
            detailTemplate: kendo.template($("#detailTemplate").html()),
            dataSource: salaryAdvanceDataSource,
            dataBinding: function () {
                //let no = (this.dataSource.page() - 1) * this.dataSource.pageSize();
            },
            dataBound: function (e) {
                let grid = e.sender;
                let dataSource = this.dataSource;
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
                if (universal["has_active_application"]) {
                    disableGridAddButton();
                }
                if (!currentRowSelected && universal['select_row_id']) {
                    selectGridRow(universal["select_row_id"], grid, dataSource, 'id_salary_advance');
                }
                if (!firstLoadDone) {
                    firstLoadDone = true;
                    filterDate(new Date(firstDayOfMonth), new Date(lastDayOfMonth), "date_raised");
                }
            },
            detailInit: function (e) {
                let grid = $salaryAdvanceGrid.data("kendoGrid");
                let masterRow = e.detailRow.prev('tr.k-master-row');
                let dataItem = grid.dataItem(masterRow);
                let colSize = e.sender.content.find('colgroup col').length;
                e.detailRow.find(".print-it").attr("href", URL_ROOT + "/salary-advance/print/" + dataItem["id_salary_advance"]);
                $(".print-it").printPage();
                e.detailRow.find('.k-hierarchy-cell').hide();
                e.detailCell.attr('colspan', colSize);
            },
            detailExpand: onDetailExpand,
            detailCollapse: onDetailCollapse,
            beforeEdit: function (e) {
                window.grid_uid = e.model.uid; // uid of current editing row
                let grid = $salaryAdvanceGrid.data("kendoGrid");
                /* e.model.fields["percentage"].editable  = e.model.hod_approval == null && e.model.hr_approval == null && e.model.fmgr_approval == null;
                 e.model.amount_requested = parseFloat((e.model.percentage / 100) * e.model.basic_salary).toFixed(2);
                 e.model.dirty = true;
                 e.model.dirtyFields["amount_requested"] = true;
                 kendoFastReDrawRow(grid, grid.currentRow(), e.model);*/
            },
            edit: function (e) {
                let percentageLabelField = e.container.find('.k-edit-label:eq(1), .k-edit-field:eq(1) ');
                let amountRequestedLabelField = e.container.find(".k-edit-label:eq(2), .k-edit-field:eq(2)");
                let amountRequestedNumericTextBox = amountRequestedLabelField.find('input[data-role="numerictextbox"]').data('kendoNumericTextBox');
                let amountRequestedPercentageNumericTextBox = percentageLabelField.find('input[data-role="numerictextbox"]').data('kendoNumericTextBox');
                // let radioButtonGroup = $('<div class="editor-field"><input type="radio" name="toggleAmountRequested" id="percentageRadio" class="k-radio" checked="checked" > <label class="k-radio-label" for="percentageRadio" >Percentage</label><input type="radio" name="toggleAmountRequested" id="figureRadio" class="k-radio"> <label class="k-radio-label" for="figureRadio">Figure</label></div>');
                amountRequestedPercentageNumericTextBox.bind("change", function (e) {
                    let model = salaryAdvanceDataSource.getByUid(grid_uid);
                    let grid = $salaryAdvanceGrid.data("kendoGrid");
                    let amountRequested = parseFloat((this.value() / 100) * model.basic_salary).toFixed(2);
                    amountRequestedNumericTextBox.value(amountRequested);
                    amountRequestedNumericTextBox.trigger("change");
                    //kendoFastReDrawRow(grid, grid.currentRow(), model)
                });
                amountRequestedNumericTextBox.enable(false);
                if (e.model.isNew() || e.model.fields.percentage.editable) {
                    amountRequestedNumericTextBox.value((e.model.percentage / 100) * e.model.basic_salary);
                    amountRequestedNumericTextBox.trigger("change");
                }
                // Toggle visibility off for all editor fields and labels
                e.container.find('.k-edit-label, .k-edit-field').addClass("pt-2").toggle(false);
                amountRequestedLabelField.toggle(true);
                percentageLabelField.toggle(true);
                amountRequestedLabelField.find('input').attr('min', '0');
                percentageLabelField.find('input').attr('min', 10).attr('max', 30).attr('data-min-msg', 'Amount Requested must be at least 10% of net salary!').attr('data-max-msg', 'Amount Requested must not exceed 30% of net salary!');
                percentageLabelField.find('.k-input').attr('data-required-msg', 'A percentage is required!');
                e.container.data('kendoWindow').bind('activate', function () {
                    if (e.model.fields["percentage"].editable) {
                        amountRequestedPercentageNumericTextBox.focus();
                    }
                });

                /*if (e.model.fields["percentage"].editable) {
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
                        amountRequestedPercentageNumericTextBox.element.attr("required", "required");
                        amountRequestedPercentageNumericTextBox.wrapper.next('.k-invalid-msg').removeClass('d-none');
                        amountRequestedNumericTextBox.element.removeAttr("required");
                        amountRequestedNumericTextBox.wrapper.next('.k-invalid-msg').addClass('d-none');
                    });

                    radioButtonGroup.on('click', '#figureRadio', function () {
                        e.model.amount_requested_is_percentage = false;
                        amountRequestedPercentageNumericTextBox.enable(false);
                        amountRequestedNumericTextBox.enable();
                        amountRequestedNumericTextBox.focus();
                        amountRequestedPercentageNumericTextBox.element.removeAttr("required");
                        amountRequestedPercentageNumericTextBox.wrapper.next('.k-invalid-msg').addClass('d-none');
                        amountRequestedNumericTextBox.element.attr("required", "required");
                        amountRequestedNumericTextBox.wrapper.next('.k-invalid-msg').removeClass('d-none');
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
                    percentageLabelField.find('.k-input').attr('data-required-msg', 'A percentage is required!');
                    amountRequestedLabelField.find('.k-input').attr('data-required-msg', 'An is required');
                }*/

                /*  if (!e.model.isNew()) {
                      e.container.find('.k-edit-label:eq(3), .k-edit-field:eq(3)').toggle(e.model.hod_approval !== null); // hod approval
                      e.container.find('.k-edit-label:eq(4), .k-edit-field:eq(4)').toggle(e.model.hod_approval !== null); // hod comment
                      e.container.find('.k-edit-label:eq(6), .k-edit-field:eq(6)').toggle(true); // hr approval
                      e.container.find('.k-edit-label:eq(7), .k-edit-field:eq(7)').toggle(e.model.hr_approval !== null); // hr comment
                      e.container.find('.k-edit-label:eq(8), .k-edit-field:eq(8)').toggle(e.model.hr_approval !== null); // toggle visibility for amount payable
                      e.container.find('.k-edit-label:eq(10), .k-edit-field:eq(10)').toggle(true); // toggle visibility for fmgr approval
                      e.container.find('.k-edit-label:eq(11), .k-edit-field:eq(11)').toggle(e.model.fmgr_approval !== null); // toggle visibility for fmgr comment
                      e.container.find('.k-edit-label:eq(12), .k-edit-field:eq(12)').toggle(e.model.fmgr_approval !== null); // toggle visibility for amount approved
                      e.container.find('.k-edit-label:eq(14), .k-edit-field:eq(14)').toggle(Boolean(e.model.amount_received)); // toggle visibility for amount received
                      e.container.find('.k-edit-label:eq(15), .k-edit-field:eq(15)').toggle(Boolean(e.model.received_by)); // toggle visibility for received by
                      e.container.find('.k-edit-label:eq(16), .k-edit-field:eq(16)').toggle(Boolean(e.model.date_received)); // toggle visibility for date received // kendo grid has date set to today by default
                  } else {
                      amountRequestedLabelField.toggle(true);
                      percentageLabelField.toggle(true);
                  }*/

                let title = $(e.container).parent().find(".k-window-title");
                let update = $(e.container).parent().find(".k-grid-update");
                let cancel = $(e.container).parent().find(".k-grid-cancel");
                $(title).text('');
                $(update).html('<span class="k-icon k-i-check"></span>OK');
                $(cancel).html('<span class="k-icon k-i-cancel"></span>Cancel');
            }
        });

        kGridAddButton = $('.k-grid-add');
        $salaryAdvanceTooltip = $salaryAdvanceGrid.kendoTooltip({
            filter: "td.comment", //this filter selects the second column's cells
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
        /*
                $salaryAdvanceGrid.data('kendoGrid').thead.kendoTooltip({
                    filter: "th.title",
                    position: 'top',
                    content: function (e) {
                        let target = e.target; // element for which the tooltip is shown
                        return $(target).text();
                    }
                });*/

        $salaryAdvanceGrid.on("click", ".action-edit", function (e) {
            let grid = $salaryAdvanceGrid.data("kendoGrid");
            let target = $(e.currentTarget);
            let currentRow;
            if (target.hasClass('in-detail-row')) {
                currentRow = target.closest('tr.k-detail-row').prev('tr.k-master-row');
            } else {
                currentRow = grid.currentRow();
            }
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
        };

        $salaryAdvanceGrid.data("kendoGrid").bind("dataBound", onDataBound);
        $salaryAdvanceGrid.data("kendoGrid").bind("filter", function (e) {
            toggleDateFilterBtn(e)
        });
    });

    toggleAmountRequested = function () {
        if (this.id === 'percentageRadio') {

        }
    };

    function disableGridAddButton() {
        if (!dbg_turn_off_disable_add_button)
            kGridAddButton.attr('disabled', 'disabled')
                .attr("title", "You have an active salary advance request for this month!")
                .removeClass("k-grid-add")
                .addClass("k-state-disabled k-grid-add-disabled")
                .removeAttr("href");
    }

    function enableGridAddButton() {
        kGridAddButton.removeAttr('disabled').removeAttr("title")
            .addClass('k-grid-add')
            .removeClass('k-state-disabled k-grid-add-disabled')
            .attr('href', '#');
    }
</script>
</body>
</html>