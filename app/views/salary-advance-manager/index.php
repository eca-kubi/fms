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
<!-- /.wrapper -->
<?php require_once APP_ROOT . '\templates\x-kendo-templates\x-kendo-templates.php'; ?>
<?php require_once APP_ROOT . '\views\includes\scripts.php'; ?>

<?php
/** @var string $request_number */
?>
<script>
    let universal = {
        requestNumber: "<?php echo $request_number ?>",
        currencySymbol: "<?php echo CURRENCY_GHS; ?>",
        currentDepartment: "<?php echo $current_user->department; ?>",
        currentDepartmentID: <?php echo $current_user->department_id; ?>,
        isFinanceOfficer: Boolean("<?php echo isFinanceOfficer($current_user->user_id); ?>"),
        isHr: Boolean("<?php echo isCurrentHR($current_user->user_id) ?>"),
        isFmgr: Boolean("<?php echo isCurrentFmgr($current_user->user_id) ?>"),
        isGM: Boolean("<?php echo isCurrentGM($current_user->user_id) ?>"),
        isManager: Boolean("<?php echo isCurrentManager($current_user->user_id) ?>")
    };
    let $salaryAdvanceGrid;
    let salaryAdvanceDataSource;
    $(document).ready(function () {
        URL_ROOT = $('#url_root').val();
        kendo.culture().numberFormat.currency.symbol = 'GH₵';
        $salaryAdvanceGrid = $('#salary_advance');
        salaryAdvanceDataSource = new kendo.data.DataSource({
            filter: [
                {
                    field: "date_raised", operator: "gte", value: new Date(firstDayOfMonth)
                },
                {
                    field: 'date_raised', operator: "lte", value: new Date(lastDayOfMonth)
                }],
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
                this.cancelChanges();
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
                        request_number: {
                            editable: false
                        },
                        name: {
                            editable: false
                        },
                        date_raised: {
                            type: 'date',
                            editable: false
                        },
                        amount_requested: {
                            type: 'number',
                            nullable: true,
                            validation: {
                                min: 0,
                                required: true
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
                        gm_approval: {editable: false, nullable: true, type: "boolean"},
                        gm_approval_date: {editable: false, nullable: true, type: "date"},
                        gm_comment: {editable: false, type: "string"},
                        gm_id: {type: "number"},
                        fmgr_approval: {
                            nullable: true,
                            type: 'boolean',
                            editable: false
                        },
                        amount_payable: {
                            type: 'number',
                            nullable: true,
                            validation: {
                                required: true,
                                min: '0'
                            },
                            editable: false
                        },
                        amount_approved: {
                            nullable: true,
                            type: 'number',
                            validation: {
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
                            type: "number", editable: false, nullable: true,
                        },
                        percentage: {
                            type: "number",
                            editable: false
                        },
                        amount_requested_is_percentage: {
                            type: 'boolean',
                            defaultValue: true
                        },
                        basic_salary: {
                            type: "number"
                        },
                        finance_officer_id: {type: "number", nullable: true},
                        finance_officer_comment: {type: "string", editable: false}
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
            toolbar: [{name: "excel", template: $("#exportToExcel").html()}],
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
            editable: 'popup',
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
            cancel: function (e) {
                let extRadioButtonGroup = e.container.find("[data-role=extradiobuttongroup]");
                extRadioButtonGroup.each(function () {
                    let element = $(this);
                    let tooltip = element.data('kendoTooltip');
                    tooltip.destroy();
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
                pageSizes: [20, 40, 60, 80, 100],
                buttonCount: 5
            },
            columnResizeHandleWidth: 30,
            columns: [
                {
                    command: [
                        {
                            name: "edit",
                            text: "Edit",
                            iconClass: {edit: "k-icon k-i-edit"},
                            className: "badge badge-success btn k-button text-black border"
                        },
                        {name: "print", template: $("#printButton").html()}
                    ],
                    headerAttributes: {class: "title"},
                    title: "Action",
                    width: 190
                },
                {
                    field: "request_number",
                    title: "Request Number",
                    width: 230,
                    headerAttributes: {
                        "class": "title"
                    },
                    filterable: {cell: {showOperators: false}},
                },
                {
                    field: "name",
                    filterable: {cell: {showOperators: false}},
                    headerAttributes: {class: "title"},
                    title: "Employee",
                    width: 290
                },
                {
                    field: 'department',
                    title: 'Department',
                    headerAttributes: {
                        class: "title"
                    },
                    width: 260,
                    filterable: {cell: {showOperators: false}}
                },
                {
                    field: 'percentage',
                    title: 'Amount in Percentage',
                    headerAttributes: {
                        class: "title"
                    },
                    width: 180,
                    format: "{0:#\\%}",
                    filterable: false,
                    hidden: true
                },
                {
                    field: 'amount_requested',
                    title: 'Amount in Figures',
                    width: 180,
                    headerAttributes: {
                        "class": "title"
                    },
                    format: "{0:c}",
                    filterable: false,
                },
                {
                    field: 'date_raised',
                    title: 'Date Raised',
                    headerAttributes: {
                        class: "title"
                    },
                    width: 450,
                    groupHeaderTemplate: "Date Raised: #= kendo.toString(kendo.parseDate(value), 'dddd dd MMM, yyyy') #",
                    filterable: {cell: {template: dateRangeFilter}},
                    format: "{0:dddd dd MMM, yyyy}",
                    nullable: true
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
                    groupHeaderTemplate: "HoD Approval: #= value===null? 'Pending' : (value? 'Approved' : 'Rejected') # | Total: #= count #",
                    aggregates: ["count"],
                    filterable: false
                },
                {
                    field: 'hod_comment',
                    title: 'HoD Comment',
                    hidden: false,
                    nullable: true,
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
                    field: 'hod_approval_date',
                    title: 'HoD Approval Date ',
                    headerAttributes: {
                        "class": "title"
                    },
                    width: 200,
                    groupHeaderTemplate: "Date Raised: #= value ? kendo.toString(kendo.parseDate(value), 'dddd dd MMM, yyyy') : '' #",
                    hidden: false,
                    format: "{0:dddd dd MMM, yyyy}",
                    filterable: false,
                    nullable: true
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
                    groupHeaderTemplate: "HR Approval: #= value===null? 'Pending' : (value? 'Approved' : 'Rejected') # |  Total: #= count #",
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
                    width: 200,
                    filterable: false,
                    nullable: true
                },
                {
                    field: 'amount_payable',
                    title: 'Amount Payable',
                    format: "{0:c}",
                    headerAttributes: {
                        "class": "title"
                    },
                    groupHeaderTemplate: "Amount Payable: #= value?  kendo.format('{0:c}', value) : 'Pending' #",
                    aggregates: ["max", "min"],
                    width: 200,
                    filterable: false,
                    nullable: true
                },
                {
                    field: 'hr_approval_date',
                    title: 'HR Approval Date ',
                    headerAttributes: {
                        "class": "title"
                    },
                    width: 200,
                    groupHeaderTemplate: "HR Approval Date: #= value ? kendo.toString(kendo.parseDate(value), 'dddd dd MMM, yyyy') : '' #",
                    hidden: false,
                    filterable: false,
                    format: "{0:dddd dd MMM, yyyy}",
                    nullable: true
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
                    groupHeaderTemplate: "GM Approval: #= value=== null 'Pending' : ( value? 'Approved': 'Rejected' )# | Total: #= count #",
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
                    filterable: false,
                    nullable: true
                },
                {
                    field: 'gm_approval_date',
                    title: 'GM Approval Date ',
                    headerAttributes: {
                        "class": "title"
                    },
                    width: 200,
                    groupHeaderTemplate: "GM's Approval Date: #= value ? kendo.toString(kendo.parseDate(value), 'dddd dd MMM, yyyy') : '' #",
                    filterable: false,
                    format: "{0:dddd dd MMM, yyyy}",
                    nullable: true
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
                    groupHeaderTemplate: "Fin Mgr Approval: #= value? 'Approved' : (value===null? 'Pending' : 'Rejected') # |  Total: #=count #",
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
                    nullable: true,
                    filterable: false
                },
                {
                    field: 'amount_approved',
                    title: 'Amount Approved',
                    nullable: true,
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
                    width: 200,
                    groupHeaderTemplate: "Fin Mgr Approval Date: #= value ? kendo.toString(kendo.parseDate(value), 'dddd dd MMM, yyyy') : '' #",
                    hidden: false,
                    filterable: false,
                    format: "{0:dddd dd MMM, yyyy}",
                    nullable: true
                },
                {
                    field: 'amount_received',
                    title: 'Amount Received',
                    nullable: true,
                    attributes: {
                        class: 'amount_received'
                    },
                    headerAttributes: {
                        "class": "title"
                    },
                    width: 200,
                    groupHeaderTemplate: "Amount Received: #: kendo.format('{0:c}', value) #",
                    filterable: false,
                    format: "{0:c}"
                },
                {
                    field: 'received_by',
                    title: 'Received By',
                    hidden: false,
                    headerAttributes: {
                        "class": "title"
                    },
                    width: 200,
                    groupHeaderTemplate: "Received By: #:  value #",
                    filterable: false,
                    nullable: true
                },
                {
                    field: 'date_received',
                    title: 'Date Received',
                    hidden: false,
                    nullable: true,
                    headerAttributes: {
                        "class": "title"
                    },
                    width: 450,
                    groupHeaderTemplate: "Date Received: #= value? kendo.toString(kendo.parseDate(value), 'dddd dd MMM, yyyy') : 'Pending' #",
                    filterable: {
                        cell: {
                            template: dateRangeFilter
                        }
                    },
                    format: "{0:dddd dd MMM, yyyy}"
                },
                {
                    field: 'finance_officer_comment',
                    title: 'Fin Officer Comment',
                    hidden: false,
                    editor: textAreaEditor,
                    headerAttributes: {
                        "class": "title"
                    },
                    attributes: {
                        class: 'comment'
                    },
                    width: 200,
                    nullable: true,
                    filterable: false
                }
            ],
            detailTemplate: kendo.template($("#detailTemplate").html()),
            dataSource: salaryAdvanceDataSource,
            dataBound: function (e) {
                let grid = e.sender;
                let data = grid.dataSource.data();
                let dataSource = grid.dataSource;
                $.each(data, function (i, row) {
                    $('tr[data-uid="' + row.uid + '"] ').attr('data-id-salary-advance', row['id_salary_advance']).find(".print-it").attr("href", URL_ROOT + "/salary-advance/print/" + row["request_number"]);
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

                if (!currentRowSelected && universal.requestNumber) {
                    selectGridRow(universal.requestNumber, grid, dataSource, 'request_number');
                }
                if (!firstLoadDone) {
                    firstLoadDone = true;
                    filterDate(new Date(firstDayOfMonth), new Date(lastDayOfMonth), "date_raised");
                }
            },
            detailInit: function (e) {
                let colSize = e.sender.content.find('colgroup col').length;
                e.detailRow.find('.k-hierarchy-cell').hide();
                e.detailCell.attr('colspan', colSize);
            },
            detailExpand: onDetailExpand,
            detailCollapse: onDetailCollapse,
            beforeEdit: function (e) {
                window.grid_uid = e.model.uid; // uid of current editing row
                e.model.fields.amount_requested.editable = false;
                e.model.fields.percentage.editable = false;
                e.model.fields.hod_comment.editable = e.model.fields.hod_approval.editable = e.model.hod_approval === null && e.model.department_id === universal.currentDepartmentID;
                e.model.fields.amount_payable.editable = e.model.fields.hr_comment.editable = e.model.fields.hr_approval.editable = universal.isHr && e.model.hr_approval === null && (e.model.hod_approval !== null || e.model.department_id === universal.currentDepartmentID);
                e.model.fields.gm_approval.editable = e.model.fields.gm_comment.editable = e.model.gm_approval === null && e.model.hr_approval !== null;
                e.model.fields.amount_approved.editable = e.model.fields.fmgr_comment.editable = e.model.fields.fmgr_approval.editable = universal.isFmgr && e.model.fmgr_approval === null && e.model.gm_approval !== null;
            },
            edit: function (e) {
                let nameLabelField = e.container.find('.k-edit-label:eq(1), .k-edit-field:eq(1)');
                let departmentLabelField = e.container.find('.k-edit-label:eq(2), .k-edit-field:eq(2)');
                let percentageLabelField = e.container.find('.k-edit-label:eq(3), .k-edit-field:eq(3)');
                let amountRequestedLabelField = e.container.find('.k-edit-label:eq(4), .k-edit-field:eq(4)');
                let hodApprovalLabelField = e.container.find('.k-edit-label:eq(6), .k-edit-field:eq(6)');
                let hodCommentLabelField = e.container.find('.k-edit-label:eq(7), .k-edit-field:eq(7)');
                let hrApprovalLabelField = e.container.find('.k-edit-label:eq(9), .k-edit-field:eq(9)');
                let hrCommentLabelField = e.container.find('.k-edit-label:eq(10), .k-edit-field:eq(10)');
                let amountPayableLabelField = e.container.find('.k-edit-label:eq(11), .k-edit-field:eq(11)');
                let gmApprovalLabelField = e.container.find('.k-edit-label:eq(13), .k-edit-field:eq(13)');
                let gmCommentLabelField = e.container.find('.k-edit-label:eq(14), .k-edit-field:eq(14)');
                let fmgrApprovalLabelField = e.container.find('.k-edit-label:eq(16), .k-edit-field:eq(16)');
                let fmgrCommentLabelField = e.container.find('.k-edit-label:eq(17), .k-edit-field:eq(17)');
                let amountApprovedLabelField = e.container.find('.k-edit-label:eq(18), .k-edit-field:eq(18)');
                let amountReceivedLabelField = e.container.find('.k-edit-label:eq(20), .k-edit-field:eq(20)');
                let receivedByLabelField = e.container.find('.k-edit-label:eq(21), .k-edit-field:eq(21)');
                let dateReceivedLabelField = e.container.find('.k-edit-label:eq(22), .k-edit-field:eq(22)');

                e.container.find('.k-edit-label, .k-edit-field').addClass("pt-2").toggle(false);

                // e.container.find('.k-edit-field .k-checkbox').parent().removeClass('pt-2');
                // Toggleability
                nameLabelField.toggle();
                departmentLabelField.toggle();
                percentageLabelField.toggle();
                amountRequestedLabelField.toggle();
                hodApprovalLabelField.toggle();
                hrApprovalLabelField.toggle();
                gmApprovalLabelField.toggle();
                fmgrApprovalLabelField.toggle();

                amountPayableLabelField.toggle(Boolean(e.model.hr_approval));
                amountApprovedLabelField.toggle(Boolean(e.model.fmgr_approval));
                hodCommentLabelField.toggle(e.model.hod_approval !== null || e.model.department_id === universal["currentDepartmentID"]);
                hrCommentLabelField.toggle(e.model.hr_approval !== null || (e.model.hod_approval !== null && universal["isHr"]));
                gmCommentLabelField.toggle(e.model.gm_approval !== null || (e.model.hr_approval !== null && universal["isGm"]));
                fmgrCommentLabelField.toggle(e.model.fmgr_approval !== null || (e.model.gm_approval !== null && universal["isFmgr"]));
                receivedByLabelField.toggle(e.model.received_by !== null || universal["isFinanceOfficer"]);
                amountReceivedLabelField.toggle(e.model.received_by !== null || universal["isFinanceOfficer"]);
                dateReceivedLabelField.toggle(e.model.date_received !== null || universal["isFinanceOfficer"]);

                // Validations
                hodCommentLabelField.find('.k-textbox').attr('data-required-msg', 'HoD Comment is required!').attr('rows', '6');
                hrCommentLabelField.find('.k-textbox').attr('data-required-msg', 'HR Comment is required!').attr('rows', '6');
                amountPayableLabelField.find('.k-input').attr('data-required-msg', 'Amount Payable is required!');
                gmCommentLabelField.find('.k-textbox').attr('data-required-msg', 'GM Comment is required!').attr('rows', '6');
                fmgrCommentLabelField.find('.k-textbox').attr('data-required-msg', 'Fin Mgr Comment is required!').attr('rows', '6');
                amountApprovedLabelField.find('.k-input').attr('data-required-msg', 'Amount Approved is required!');
                amountReceivedLabelField.find('.k-input').attr('data-required-msg', 'Amount Received is required!');
                receivedByLabelField.find('.k-input').attr('data-required-msg', 'This field is required!');

                let extRadioButtonGroup = e.container.find("[data-role=extradiobuttongroup]");
                let updateButton = e.container.find('.k-grid-update');
                extRadioButtonGroup.each(function () {
                    let element = $(this);
                    let tooltip = element.data('kendoTooltip');
                    updateButton.click(function (e) {
                        if (element.data('kendoExtRadioButtonGroup').value() == null) {
                            tooltip.show(element);
                            e.preventDefault();
                        }
                    });
                });

                e.container.data('kendoWindow').bind('deactivate', function () {
                    let data = $salaryAdvanceGrid.getKendoGrid().dataSource.data();
                    $.each(data, function (i, row) {
                        $('tr[data-uid="' + row.uid + '"] ').attr('data-id-salary-advance', row['id_salary_advance']).find(".print-it").attr("href", URL_ROOT + "/salary-advance/print/" + row["id_salary_advance"]);
                    });
                    $(".print-it").printPage();
                });

                let title = $(e.container).parent().find(".k-window-title");
                let update = $(e.container).parent().find(".k-grid-update");
                let cancel = $(e.container).parent().find(".k-grid-cancel");
                $(title).text('');
                $(update).html('<span class="k-icon k-i-check"></span>OK');
                $(cancel).html('<span class="k-icon k-i-cancel"></span>Cancel');
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
            filter: "td.comment",
            position: "top",
            content: function (e) {
                e.sender.popup.element.css("visibility", "hidden");
                let text = $(e.target).text();
                if (text) e.sender.popup.element.css("visibility", "visible");
                return text;
            }
        }).data("kendoTooltip");

        /*       $salaryAdvanceGrid.on("click", ".action-edit", function (e) {
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
               });*/

        $salaryAdvanceGrid.data("kendoGrid").bind("dataBound", onDataBound);
    });

</script>
</body>
</html>