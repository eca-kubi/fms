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
$universal->hr_comment_editable = $universal->isHr = getCurrentHR() === $current_user->user_id;
$universal->fgmr_comment_editable = $universal->isFmgr = getCurrentFgmr() === $current_user->user_id;
/** @var string $bulk_request_number */
$universal->bulkRequestNumber = $bulk_request_number ?: '';
$universal->isSecretary = isAssignedAsSecretary($current_user->user_id);
$universal->isHR = isCurrentHR($current_user->user_id);
$universal->isFmgr = isCurrentFmgr($current_user->user_id);
$universal->isGM = isCurrentGM($current_user->user_id);
$universal->currentDepartment = $current_user->department;
$universal->currentDepartmentID = $current_user->department_id;
$universal->isCurrentManager = isCurrentManager($current_user->user_id);

?>
<script>
    let universal = JSON.parse(`<?php echo json_encode($universal, JSON_THROW_ON_ERROR, 512); ?>`);
    let $salaryAdvanceGrid;
    let salaryAdvanceDataSource;
    const ERROR_AN_APPLICATION_ALREADY_EXISTS = 'E_1001';
    $(document).ready(function () {
        URL_ROOT = $('#url_root').val();
        kendo.culture().numberFormat.currency.symbol = 'GH₵';
        $salaryAdvanceGrid = $('#salary_advance');
        salaryAdvanceDataSource = new kendo.data.DataSource({
            width: 'auto',
            pageSize: 20,
            batch: false,
            transport: {
                read: {
                    url: URL_ROOT + "/salary-advance-bulk-requests-ajax/index/" + universal["bulkRequestNumber"],
                    dataType: "json",
                    data: {bulk_request_number: universal["bulkRequestNumber"]}
                },
                update: {
                    url: URL_ROOT + "/salary-advance-bulk-requests-ajax/update/",
                    type: 'post',
                    dataType: 'json',
                    contentType: "application/json"
                },
                destroy: {
                    url: URL_ROOT + "/salary-advance-bulk-requests-ajax/destroy/",
                    type: 'post',
                    dataType: 'json'
                },
                create: {
                    url: URL_ROOT + "/salary-advance-bulk-requests-ajax/create",
                    type: 'post',
                    dataType: 'json'
                },
                parameterMap: function (data, type) {
                    return JSON.stringify(data);
                },
                errors: function (response) {
                    return response.errors;
                }
            },
            error: function (e) {
                toastError(e.errors ? e.errors[0]['message'] : "The page failed to load some required assets.");
                this.cancelChanges();
            },
            requestEnd: function (e) {
                if ((e.type === 'update' || e.type === 'create') && e.response.length > 0) {
                    toastSuccess('Success', 5000);
                }
            },
            change: function (e) {
                if (this.hasChanges()) {
                    $('.k-grid-cancel-changes, .k-grid-save-changes').removeClass('d-none');
                }
            },
            schema: {
                model: {
                    id: "id_salary_advance",
                    fields: {
                        amount_approved: {
                            editable: false,
                            nullable: true,
                            type: "number",
                            validation: {min: "0", required: true}
                        },
                        amount_payable: {
                            editable: false,
                            nullable: true,
                            type: "number",
                            validation: {min: "0", required: true}
                        },
                        amount_received: {editable: false, nullable: true, type: "number"},
                        amount_requested: {
                            editable: false,
                            nullable: true,
                            type: "number",
                            validation: {min: 0, required: true}
                        },
                        basic_salary: {editable: false, type: "number"},
                        bulk_request_number: {nullable: true, type: "string", editable: false},
                        date_raised: {editable: false, type: "date"},
                        date_received: {editable: false, nullable: true, type: "date"},
                        department: {editable: false, type: "string"},
                        department_id: {editable: false, type: "number"},
                        department_ref: {editable: false},
                        employee: {defaultValue: {}},
                        fmgr_approval: {editable: false, nullable: true, type: "boolean"},
                        fmgr_approval_date: {editable: false, nullable: true, type: "date"},
                        fmgr_comment: {editable: false, type: "string"},
                        fmgr_id: {type: "number"},
                        gm_approval: {editable: false, nullable: true, type: "boolean"},
                        gm_approval_date: {editable: false, nullable: true, type: "date"},
                        gm_comment: {editable: false, type: "string"},
                        gm_id: {type: "number"},
                        hod_approval: {editable: false, nullable: true, type: "boolean"},
                        hod_approval_date: {editable: false, nullable: true, type: "date"},
                        hod_comment: {editable: false, type: "string"},
                        hod_id: {type: "number"},
                        hr_approval: {editable: false, nullable: true, type: "boolean"},
                        hr_approval_date: {editable: false, nullable: true, type: "date"},
                        hr_comment: {editable: false, type: "string"},
                        hr_id: {type: "number"},
                        is_bulk_request: {defaultValue: true, type: "boolean"},
                        name: {from: "employee.name", editable: false},
                        percentage: {
                            editable: false,
                            nullable: true,
                            type: "number",
                            validation: {max: 30, min: 10, required: true}
                        },
                        raised_by_id: {type: "number"},
                        raised_by_secretary: {type: "boolean"},
                        received_by: {editable: false, nullable: true, type: "string"},
                        user_id: {type: "number"}
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
            toolbar: kendo.template($('#toolbarTemplate_Bulk_Requests').html()),
            filter: function (e) {
                toggleDateFilterBtn(e);
            },
            excel: {
                fileName: "Salary Advance Export.xlsx",
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
            editable: "popup",
            save: function (e) {
                let extRadioButtonGroup = e.container.find("[data-role=extradiobuttongroup]");
                extRadioButtonGroup.each(function () {
                    let element = $(this);
                    let tooltip = element.data('kendoTooltip');
                    if (element.data("kendoExtRadioButtonGroup").value() == null) {
                        e.preventDefault();
                        tooltip.show(element);
                    }
                });
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
            groupable: true,
            height: 520,
            resizable: true,
            scrollable: true,
            persistSelection: true,
            pageable: {
                alwaysVisible: false,
                pageSizes: [5, 10, 15, 20],
                buttonCount: 5
            },
            columnResizeHandleWidth: 30,
            columns: [
                {
                    attributes: {class: "action"},
                    command: [{
                        name: "edit", text: "Edit", iconClass: {edit: "k-icon k-i-edit"},
                        className: "action-edit badge badge-success btn k-button text-black border",
                        visible: function (dataItem) {
                            return (dataItem.hod_approval === null && universal["isSecretary"]) || !universal["isSecretary"];
                        }
                    },
                        {name: "print", template: $("#printButton").html()}],
                    headerAttributes: {class: "title"},
                    title: "Action",
                    width: 190
                },
                {
                    editor: dropDownEditor,
                    field: "name",
                    filterable: {cell: {showOperators: false}},
                    headerAttributes: {class: "title"},
                    title: "Employee",
                    width: 250
                },
                {
                    field: 'department',
                    title: 'Department',
                    headerAttributes: {
                        "class": "title"
                    },
                    width: 260,
                    filterable: {
                        //  ui: departmentFilter,
                        cell: {
                            showOperators: false
                        }
                    },
                    hidden: universal["isSecretary"]
                },
                {
                    title: "Request Number",
                    width: 250,
                    field: "bulk_request_number",
                    filterable: {cell: {showOperators: false}},
                    headerAttributes: {class: "title"}
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
                    width: 180,
                    groupHeaderTemplate: "Amount in Percentage: #= value? value + '%' : '' #",
                    aggregates: ["max", "min"],
                    format: "{0:#\\%}",
                    filterable: false
                },
                {
                    field: 'amount_requested',
                    title: 'Amount in Figures',
                    width: 180,
                    template: function (dataItem) {
                        return "<span>" + (dataItem.amount_requested ? kendo.format('{0:c}', dataItem.amount_requested) : '') + "</span>"
                    },
                    headerAttributes: {
                        "class": "title"
                    },
                    groupHeaderTemplate: "Amount in Figures: #=  value ? kendo.format('{0:c}', value) : ''#",
                    aggregates: ["max", "min", "count"],
                    format: "{0:c}",
                    filterable: false,
                    hidden: universal["isSecretary"]
                },
                {
                    field: 'date_raised',
                    title: 'Date Raised',
                    headerAttributes: {
                        "class": "title"
                    },
                    width: 450,
                    groupHeaderTemplate: "Date Raised: #= kendo.toString(kendo.parseDate(value), 'dddd dd MMM, yyyy h:mm:ss tt') #",
                    filterable: {
                        cell: {
                            template: dateRangeFilter
                        }
                    },
                    format: "{0:dddd dd MMM, yyyy}"
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
                    groupHeaderTemplate: "HoD Approved: #= value? 'Yes' : 'No' # | Total: #= count #",
                    aggregates: ["count"],
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
                    title: 'HoD Approval Date ',
                    headerAttributes: {
                        "class": "title"
                    },
                    width: 200,
                    groupHeaderTemplate: "Date Raised: #= value ? kendo.toString(kendo.parseDate(value), 'dddd dd MMM, yyyy h:mm:ss tt') : '' #",
                    hidden: false,
                    format: "{0:dddd dd MMM, yyyy}",
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
                    title: 'HR Approval Date ',
                    headerAttributes: {
                        "class": "title"
                    },
                    template: function (dataItem) {
                        let date = dataItem.hr_approval_date ? kendo.toString(kendo.parseDate(dataItem.hr_approval_date), 'dddd dd MMM, yyyy') : '';
                        return "<span>" + date + "</span>";
                    },
                    width: 200,
                    groupHeaderTemplate: "HR Approval Date: #= value ? kendo.toString(kendo.parseDate(value), 'dddd dd MMM, yyyy h:mm:ss tt') : '' #",
                    hidden: false,
                    filterable: false

                },
                {
                    field: 'gm_approval',
                    title: 'GM Approval',
                    editor: approvalEditor,
                    template: function (dataItem) {
                        return "<span>" + (dataItem.gm_approval === null ? '<i class="fa fa-warning text-yellow"></i>  Pending' : dataItem.gm_approval ? '<i class="fa fa-check text-success"></i> Approved' : '<i class="fa fa-warning text-danger"></i> Rejected') + "</span>"
                    },
                    headerAttributes: {
                        "class": "title"
                    },
                    width: 200,
                    //groupHeaderTemplate: "GM Approval: #= value=== null 'Pending' : 'No' # | Total: #= count #",
                    aggregates: ["count"],
                    filterable: false
                },
                {
                    field: 'gm_comment',
                    title: 'GM Comment',
                    editor: textAreaEditor,
                    headerAttributes: {
                        "class": "title"
                    },
                    attributes: {
                        class: 'comment'
                    },
                    width: 200,
                    filterable: false
                },
                {
                    field: 'gm_approval_date',
                    title: 'GM Approval Date ',
                    headerAttributes: {
                        "class": "title"
                    },
                    width: 200,
                    groupHeaderTemplate: "GM's Approval Date: #= value ? kendo.toString(kendo.parseDate(value), 'dddd dd MMM, yyyy h:mm:ss tt') : '' #",
                    filterable: false,
                    format: "{0:dddd dd MMM, yyyy}"

                },
                {
                    field: 'fmgr_approval',
                    title: 'Fin Mgr Approval',
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
                    title: 'Fin Mgr Comment',
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
                    title: 'Fin Mgr Approval Date ',
                    headerAttributes: {
                        "class": "title"
                    },
                    template: function (dataItem) {
                        let date = dataItem.fmgr_approval_date ? kendo.toString(kendo.parseDate(dataItem.fmgr_approval_date), 'dddd dd MMM, yyyy') : '';
                        return "<span>" + date + "</span>";
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
            detailTemplate: kendo.template($("#detailTemplate_Secretary").html()),
            dataSource: salaryAdvanceDataSource,
            dataBound: function (e) {
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
                filterRow.find('input:eq(2)').attr('placeholder', 'Search...');

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
                $(".print-it").printPage();
                e.detailRow.find('.k-hierarchy-cell').hide();
                e.detailCell.attr('colspan', colSize);
            },
            detailExpand: onDetailExpand,
            detailCollapse: onDetailCollapse,
            beforeEdit: function (e) {
                window.grid_uid = e.model.uid; // uid of current editing row
                e.model.fields["percentage"].editable = (universal["isSecretary"] && e.model.hod_approval === null) || (universal["isFmgr"] && e.model.fmgr_approval === null);
                e.model.fields["hod_approval"].editable = universal["isCurrentManager"] && (e.model.hod_approval === null);
                e.model.fields["hr_approval"].editable = universal["isHR"] && (e.model.hr_approval === null) && e.model.hod_approval !== null;
                e.model.fields["gm_approval"].editable = universal["isGM"] && (e.model.gm_approval === null)  && e.model.hr_approval !== null ;
                e.model.fields["fmgr_approval"].editable = universal["isFmgr"] && (e.model.fmgr_approval === null) && e.model.gm_approval !== null;
            },
            edit: function (e) {
                e.container.find(".k-edit-label, .k-edit-field").addClass("pt-2").toggle(false);
                let nameLabelField = e.container.find(".k-edit-label:eq(0), .k-edit-field:eq(0)");
                let departmentLabelField = e.container.find(".k-edit-label:eq(1), .k-edit-field:eq(1)");
                let requestNumberLabelField = e.container.find(".k-edit-label:eq(2), .k-edit-field:eq(2)");
                let percentageLabelField = e.container.find(".k-edit-label:eq(3), .k-edit-field:eq(3)");
                let hodApprovalLabelField = e.container.find(".k-edit-label:eq(6), .k-edit-field:eq(6)");
                let hrApprovalLabelField = e.container.find(".k-edit-label:eq(9), .k-edit-field:eq(9)");
                let amountPayableLabelField = e.container.find(".k-edit-label:eq(11), .k-edit-field:eq(11)");
                let gmApprovalLabelField = e.container.find(".k-edit-label:eq(13), .k-edit-field:eq(13)");
                let fmgrApprovalLabelField = e.container.find(".k-edit-label:eq(16), .k-edit-field:eq(16)");

                nameLabelField.toggle();
                requestNumberLabelField.toggle();
                percentageLabelField.toggle();
                departmentLabelField.toggle();
                if (!universal["isSecretary"]) {
                    hodApprovalLabelField.toggle();
                    hrApprovalLabelField.toggle();
                    gmApprovalLabelField.toggle();
                    fmgrApprovalLabelField.toggle();
                }
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

        $('.k-grid-cancel-changes').click(function (e) {
            $('.k-grid-cancel-changes, .k-grid-save-changes').addClass('d-none')
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
        $salaryAdvanceGrid.data("kendoGrid").bind("filter", function (e) {
            toggleDateFilterBtn(e)
        });
    });

</script>
</body>
</html>