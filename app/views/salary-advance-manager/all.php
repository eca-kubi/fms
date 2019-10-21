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
$universal = new stdClass();
$universal->currency_symbol = CURRENCY_GHS;
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
        URL_ROOT = $('#url_root').val();
        kendo.culture().numberFormat.currency.symbol = 'GH₵';
        $salaryAdvanceGrid = $('#salary_advance');
        $salaryAdvanceGrid.on('change', "input[name=employee]", function (e) {
            //let select = $(this).data("kendoDropDownList");
        });
        salaryAdvanceDataSource = new kendo.data.DataSource({
            width: 'auto',
            pageSize: 20,
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
            toolbar: kendo.template($('#toolbarTemplate').html()),
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
            save: function (e) {
                let extRadioButtonGroup = e.container.find("[data-role=extradiobuttongroup]");
                if (extRadioButtonGroup.data("kendoExtRadioButtonGroup").value() == null) {
                    e.preventDefault();
                    extRadioButtonGroup.data("kendoTooltip").show(extRadioButtonGroup);
                }
            },
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
            persistSelection: true,
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
                    width: 260,
                    headerAttributes: {
                        "class": "title"
                    },
                    filterable: {
                        cell: {
                            showOperators: false
                        }
                    }
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
                    width: 260,
                    filterable: {
                        //  ui: departmentFilter,
                        cell: {
                            showOperators: false
                        }
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
                        return "<span title='HoD Approved: " + (dataItem.hod_approval === null ? 'Pending' : dataItem.hod_approval ? 'Approved' : 'Rejected') + "'>" + (dataItem.hod_approval === null ? 'Pending' : dataItem.hod_approval ? 'Approved' : 'Rejected') + "</span>"
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
                        "<span class='col' title='Review'><a href='javascript:' class='text-black action-edit btn k-button badge badge-success  border'><i class='k-icon k-i-edit'></i>Review</a></span>" +
                        "<span class='col d-none' title=''><a href='javascript:' class='text-danger action-delete'><i class='fas fa-trash-alt'></i></a></span>" +
                        "<span class='col d-none' title=''><a href='javascript:' class='text-primary action-more-info'><i class='fas fa-info-circle'></i></a></span>" +
                        "<span class='col' title='Print'><a href='\\#' class='text-black action-print print-it badge badge-primary btn k-button border' target='_blank'><i class='k-icon k-i-printer'></i>Print</a></span>" +
                        "</span>",
                    width: 250,
                    title: "Action",
                    headerAttributes: {
                        "class": "title"
                    },
                    attributes: {
                        class: 'action'
                    },
                    /*locked: true,
                    lockable: true*/
                },
            ],
            detailTemplate: kendo.template($("#detailTemplate").html()),
            dataSource: salaryAdvanceDataSource,
            dataBinding: function () {
                //let no = (this.dataSource.page() - 1) * this.dataSource.pageSize();
            },
            dataBound: function (e) {
                //let grid = $salaryAdvanceGrid.data('kendoGrid');
                let grid = e.sender;
                let data = grid.dataSource.data();
                let dataSource = grid.dataSource;
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
                filterRow.find('input:first').attr('placeholder', 'Search...');
                filterRow.find('input:eq(1)').attr('placeholder', 'Search...');

                if (!currentRowSelected && universal['select_row_id']) {
                    selectGridRow(universal["select_row_id"], grid, dataSource, 'id_salary_advance');
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
                // Editability
                /*e.model.fields.amount_requested.editable = !e.model.fmgr_approval && universal['isFmgr'];
                e.model.fields.percentage.editable = !e.model.fmgr_approval && universal['isFmgr'];*/
                e.model.fields.amount_requested.editable = false;
                e.model.fields.percentage.editable = false;
                e.model.fields.hod_comment.editable = Boolean(e.model["hod_comment_editable"]) && !Boolean(e.model.hod_approval);
                e.model.fields.hod_approval.editable = e.model["hod_approval_editable"] && !e.model.hod_approval;
                e.model.fields.amount_payable.editable = universal['isHr'] && !Boolean(e.model.hr_approval);
                e.model.fields.hr_approval.editable = universal['isHr'] && !Boolean(e.model.hr_approval_date);
                e.model.fields.hr_comment.editable = universal['isHr'] && !Boolean(e.model.hr_comment);
                e.model.fields.amount_approved.editable = universal['isFmgr'] && !Boolean(e.model.fmgr_approval_date);
                e.model.fields.fmgr_approval.editable = universal['isFmgr'] && !Boolean(e.model.fmgr_approval_date) && Boolean(e.model.hr_approval);
                e.model.fields.fmgr_comment.editable = Boolean(e.model["fmgr_comment_editable"]);
            },
            edit: function (e) {
                let nameLabelField = e.container.find('.k-edit-label:eq(0), .k-edit-field:eq(0)');
                let departmentLabelField = e.container.find('.k-edit-label:eq(1), .k-edit-field:eq(1)');
                let percentageLabelField = e.container.find('.k-edit-label:eq(3), .k-edit-field:eq(3)');
                let amountRequestedLabelField = e.container.find('.k-edit-label:eq(4), .k-edit-field:eq(4)');
                let hodApprovalLabelField = e.container.find('.k-edit-label:eq(5), .k-edit-field:eq(5)');
                let hodCommentLabelField = e.container.find('.k-edit-label:eq(6), .k-edit-field:eq(6)');
                let hrApprovalLabelField = e.container.find('.k-edit-label:eq(8), .k-edit-field:eq(8)');
                let hrCommentLabelField = e.container.find('.k-edit-label:eq(9), .k-edit-field:eq(9)');
                let amountPayableLabelField = e.container.find('.k-edit-label:eq(10), .k-edit-field:eq(10)');
                let fmgrApprovalLabelField = e.container.find('.k-edit-label:eq(12), .k-edit-field:eq(12)');
                let fmgrCommentLabelField = e.container.find('.k-edit-label:eq(13), .k-edit-field:eq(13)');
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
                departmentLabelField.toggle(true);
                amountRequestedLabelField.toggle(!e.model.amount_requested_is_percentage /*|| universal['isFmgr']*/);
                percentageLabelField.toggle(e.model.amount_requested_is_percentage /*|| universal['isFmgr']*/);
                hodApprovalLabelField.toggle(true);
                hodCommentLabelField.toggle(Boolean(e.model["hod_comment_editable"]) || Boolean(e.model.hod_comment));
                hrApprovalLabelField.toggle(true);
                hrCommentLabelField.toggle(e.model["hr_comment_editable"] || Boolean(e.model.hr_comment));
                fmgrApprovalLabelField.toggle(true);
                fmgrCommentLabelField.toggle(e.model["fmgr_comment_editable"] || Boolean(e.model.fmgr_comment));
                amountPayableLabelField.toggle(Boolean(universal['isHr']) || Boolean(e.model.hr_approval));
                amountApprovedLabelField.toggle((Boolean(universal['isFmgr']) && Boolean(e.model.hr_approval)) || Boolean(e.model.fmgr_approval)); // toggle visibility for amount approved
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
                let extRadioButtonGroup = e.container.find("[data-role=extradiobuttongroup]");
                let updateButton = e.container.find('.k-grid-update');
                let tooltip = extRadioButtonGroup.data('kendoTooltip');
                updateButton.click(function (e) {
                    if (extRadioButtonGroup.data('kendoExtRadioButtonGroup').value() == null) {
                        tooltip.show(extRadioButtonGroup);
                        e.preventDefault();
                    }
                });

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

        $salaryAdvanceGrid.find('#department').kendoDropDownList({
            dataSource: new kendo.data.DataSource({
                transport: {
                    read: {
                        url: URL_ROOT + '/departments-ajax/',
                        dataType: "json"
                    }
                }
            }),
            filter: "contains",
            optionLabel: "All",
            change: function () {
                let value = this.value();
                if (value) {
                    $salaryAdvanceGrid.data("kendoGrid").dataSource.filter({
                        field: "department",
                        operator: "eq",
                        value: this.value()
                    });
                } else {
                    $salaryAdvanceGrid.data("kendoGrid").dataSource.filter({});
                }
            }
        });

        $salaryAdvanceGrid.find('#names').keyup(function () {
            $salaryAdvanceGrid.data("kendoGrid").dataSource.filter({
                logic: "or",
                filters: [
                    {
                        field: "name",
                        operator: "contains",
                        value: $(this).val()
                    },
                ]
            });
        });

        $salaryAdvanceTooltip = $salaryAdvanceGrid.kendoTooltip({
            filter: "td:not('.k-detail-cell'):not('.action')", //this filter selects the second column's cells
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
            let target = $(e.currentTarget);
            let currentRow;
            if (target.hasClass('in-detail-row')) {
                currentRow = target.closest('tr.k-detail-row').prev('tr.k-master-row');
            } else {
                currentRow = grid.currentRow();
            }
            let dataItem = grid.dataItem(currentRow);
            let errorMsg = '';
            if (dataItem['hod_approval_editable'] || (dataItem['hr_approval_editable'] && dataItem.hod_approval) || (dataItem['fmgr_approval_editable'] && dataItem.hr_approval)) {
                /*let row = $(this).closest("tr.k-master-row");
                let $this = $(this);
                let actionTools = $this.closest('.action-tools');*/
                grid.editRow(currentRow);
                /*actionTools.html("<span class='col'><a href='#' class='text-success action-confirm-edit'><i class='fa fa-check'></i></a></span>" +
                    "<span class='col'><a href='#' class='text-black action-cancel-edit'><i class='k-icon k-i-cancel'></i></a></span>");
*/
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

        $salaryAdvanceGrid.data("kendoGrid").bind("dataBound", onDataBound);
    });

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
</body>
</html>