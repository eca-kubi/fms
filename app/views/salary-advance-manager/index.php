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
/** @var int $select_row_id */
$universal->select_row_id = $select_row_id;
$universal->isGm = isCurrentGM($current_user->user_id);
$universal->currentDepartment = $current_user->department;
$universal->currentDepartmentID = $current_user->department_id;
$universal->isFinanceOfficer = isFinanceOfficer($current_user->user_id);
?>
<!--suppress HtmlUnknownTarget -->
<script>
    let universal = JSON.parse(`<?php echo json_encode($universal, JSON_THROW_ON_ERROR, 512); ?>`);
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
                            type: "number", editable: false, nullable: true,
                        },
                        percentage: {
                            type: "number",
                            nullable: true,
                            validation: {
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
                            type: "number"
                        },
                        finance_officer_id: {type: "number", nullable: true},
                        finance_officer_comment: {type: "string", editable: false}
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
            toolbar: kendo.template($('#toolbarTemplate').html()),
            filter: function (e) {
                toggleDateFilterBtn(e);
            },
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
                    template: function (dataItem) {
                        return "<span>" + (dataItem.percentage ? kendo.toString(dataItem.percentage, '#\\%') : '') + "</span>"
                    },
                    width: 180,
                    //groupHeaderTemplate: "Amount in Percentage: #= value? value + '%' : '' #",
                    aggregates: ["max", "min"],
                    format: "{0:#\\%}",
                    filterable: false
                },
                {
                    field: 'amount_requested',
                    title: 'Amount in Figures',
                    width: 180,
                    editor: editNumberWithoutSpinners,
                    headerAttributes: {
                        "class": "title"
                    },
                    //groupHeaderTemplate: "Amount in Figures: #=  value ? kendo.format('{0:c}', value) : ''#",
                    aggregates: ["max", "min", "count"],
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
                    groupHeaderTemplate: "HoD Approved: #= value===null? 'Pending' : (value? 'Approved' : 'Rejected') # | Total: #= count #",
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
                    groupHeaderTemplate: "Fin Mgr Approval: #= value? 'Yes' : (value===null? 'Pending' : 'No') # |  Total: #=count #",
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
                }
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
                if (!firstLoadDone) {
                    firstLoadDone = true;
                    filterDate(new Date(firstDayOfMonth), new Date(lastDayOfMonth), "date_raised");
                }
            },
            detailInit: function (e) {
                //let grid = $salaryAdvanceGrid.data("kendoGrid");
                //let masterRow = e.detailRow.prev('tr.k-master-row');
                //let dataItem = grid.dataItem(masterRow);
                let colSize = e.sender.content.find('colgroup col').length;
                //e.detailRow.find(".print-it").attr("href", URL_ROOT + "/salary-advance/print/" + dataItem["id_salary_advance"]);
               // $(".print-it").printPage();
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
                e.model.fields.hod_approval.editable = e.model["hod_approval_editable"] && e.model.hod_approval == null;
                e.model.fields.hod_comment.editable = e.model["hod_comment_editable"] && e.model.hod_approval == null;
                e.model.fields.hr_approval.editable = universal['isHr'] && e.model.hr_approval == null;
                e.model.fields.hr_comment.editable = universal['isHr'] && e.model.hr_approval == null;
                e.model.fields.amount_payable.editable = universal['isHr'] && e.model.hr_approval == null;
                e.model.fields.fmgr_approval.editable = universal['isFmgr'] && e.model.fmgr_approval == null;
                e.model.fields.fmgr_comment.editable = universal['isFmgr'] && e.model.fmgr_approval == null;
                e.model.fields.amount_approved.editable = universal['isFmgr'] && e.model.hr_approval == null;
            },
            edit: function (e) {
                let nameLabelField = e.container.find('.k-edit-label:eq(0), .k-edit-field:eq(0)');
                let departmentLabelField = e.container.find('.k-edit-label:eq(1), .k-edit-field:eq(1)');
                let percentageLabelField = e.container.find('.k-edit-label:eq(2), .k-edit-field:eq(2)');
                let amountRequestedLabelField = e.container.find('.k-edit-label:eq(3), .k-edit-field:eq(3)');
                let hodApprovalLabelField = e.container.find('.k-edit-label:eq(5), .k-edit-field:eq(5)');
                let hodCommentLabelField = e.container.find('.k-edit-label:eq(6), .k-edit-field:eq(6)');
                let hrApprovalLabelField = e.container.find('.k-edit-label:eq(8), .k-edit-field:eq(8)');
                let hrCommentLabelField = e.container.find('.k-edit-label:eq(9), .k-edit-field:eq(9)');
                let amountPayableLabelField = e.container.find('.k-edit-label:eq(10), .k-edit-field:eq(10)');
                let gmApprovalLabelField = e.container.find('.k-edit-label:eq(12), .k-edit-field:eq(12)');
                let gmCommentLabelField = e.container.find('.k-edit-label:eq(13), .k-edit-field:eq(13)');
                let fmgrApprovalLabelField = e.container.find('.k-edit-label:eq(15), .k-edit-field:eq(15)');
                let fmgrCommentLabelField = e.container.find('.k-edit-label:eq(16), .k-edit-field:eq(16)');
                let amountApprovedLabelField = e.container.find('.k-edit-label:eq(17), .k-edit-field:eq(17)');
                let amountReceivedLabelField = e.container.find('.k-edit-label:eq(19), .k-edit-field:eq(19)');
                let receivedByLabelField = e.container.find('.k-edit-label:eq(20), .k-edit-field:eq(20)');
                let dateReceivedLabelField = e.container.find('.k-edit-label:eq(21), .k-edit-field:eq(21)');

                /* let amountRequestedNumericTextBox = amountRequestedLabelField.find('input[data-role="numerictextbox"]').data('kendoNumericTextBox');
                 let amountRequestedPercentageNumericTextBox = percentageLabelField.find('input[data-role="numerictextbox"]').data('kendoNumericTextBox');
                 let amountPayableNumericTextBox = amountPayableLabelField.find('input[data-role="numerictextbox"]').data('kendoNumericTextBox');
                 let radioButtonGroup = $('<div class="k-edit-field"><input type="radio" name="toggleAmountRequested" id="percentageRadio" class="k-radio" checked="checked" > <label class="k-radio-label" for="percentageRadio" >Percentage</label><input type="radio" name="toggleAmountRequested" id="figureRadio" class="k-radio"> <label class="k-radio-label" for="figureRadio">Figure</label></div>');
 */
                // Hod Approval RadiobuttonGroup initialisation
                //e.container.find("#approvalRadioButtonGroup" + "_hod_approval").find()
                // Toggle visibility off for all editor fields and labels
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

               /* // Edit Labels
                if (!e.model.fields.amount_requested.editable /!*|| !universal['isFmgr']*!/) {
                    // This Label means Amount Requested and Percentage fields will not be edited
                    percentageLabelField.find('label').html('Amount Requested <br><small class="text-danger text-bold">(10% to 30% of Salary)</small>');
                    amountRequestedLabelField.find('label').html('Amount Requested <br> <small class="text-danger text-bold" ></small>');
                } else {
                    // Editing is enabled
                    percentageLabelField.find('label').html('Amount Requested <br><small class="text-danger text-bold">Enter as Percentage (10% - 30%)</small>');
                    amountRequestedLabelField.find('label').html('Amount Requested <br> <small class="text-danger text-bold" > Enter as Figure</small>');
                }
*/
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

              /*  e.container.data('kendoWindow').bind('activate', function () {
                    if (e.model.fields.percentage.editable) {
                        amountRequestedPercentageNumericTextBox.focus();
                    }
                });*/

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

        /*        $salaryAdvanceGrid.data('kendoGrid').thead.kendoTooltip({
                    filter: "th.title",
                    position: 'top',
                    content: function (e) {
                        let target = e.target; // element for which the tooltip is shown
                        return $(target).text();
                    }
                });*/

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
        $salaryAdvanceGrid.data("kendoGrid").bind("filter", function (e) {
            toggleDateFilterBtn(e)
        });
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