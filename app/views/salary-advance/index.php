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
        basicSalary: "<?php echo $current_user->basic_salary; ?>",
        currencySymbol: "<?php echo CURRENCY_GHS; ?>",
        currentDepartment: "<?php echo $current_user->department ?>",
        currentDepartmentID: <?php echo $current_user->department_id; ?>,
        hasActiveApplication: Boolean("<?php echo hasActiveApplication($current_user->user_id) ?>"),
        isFinanceOfficer: Boolean("<?php echo isFinanceOfficer($current_user->user_id); ?>"),
        isHr: Boolean("<?php echo isCurrentHR($current_user->user_id) ?>"),
        isFmgr: Boolean("<?php echo isCurrentFmgr($current_user->user_id) ?>"),
        isGM: Boolean("<?php echo isCurrentGM($current_user->user_id) ?>"),
        isManager: Boolean("<?php echo isCurrentManager($current_user->user_id) ?>"),
        requestNumber: "<?php echo $request_number ?>"
    };
    const DEBUG_MODE = false;
    const ERROR_UNSPECIFIED_ERROR = 'E_1000';
    const ERROR_APPLICATION_ALREADY_REVIEWED = 'E_1002';
    let MIN_PERCENTAGE = <?php echo MIN_PERCENTAGE ?>;
    let MAX_PERCENTAGE = <?php echo MAX_PERCENTAGE ?>;
    let kGridAddButton;
    let $salaryAdvanceGrid;
    let salaryAdvanceDataSource;
    let $salaryAdvanceTooltip;
    $(document).ready(function () {
        URL_ROOT = $('#url_root').val();
        kendo.culture().numberFormat.currency.symbol = 'GH₵';
        $salaryAdvanceGrid = $("#salary_advance");
        salaryAdvanceDataSource = new kendo.data.DataSource({
            /*filter: [
                {
                    field: "date_raised", operator: "gte", value: new Date(firstDayOfMonth)
                },
                {
                    field: 'date_raised', operator: "lte", value: new Date(lastDayOfMonth)
                }],*/
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
                salaryAdvanceDataSource.cancelChanges();
                salaryAdvanceDataSource.read();
                if (e.status === "parsererror") {
                    toastError("Some required assets on this page failed to load");
                    return;
                }
                toastError(e.errors[0]['message']);
                if (e.errors[0]['code'] === ERROR_AN_APPLICATION_ALREADY_EXISTS) {
                    disableGridAddButton();
                }
            },
            requestEnd: function (e) {
                if (e.type === 'update' && e.response.length > 0 || e.type === 'create' && e.response.length > 0) {
                    toastSuccess('Success', 5000);
                }
                $.get(URL_ROOT + "/salary-advance-ajax/has-active-salary-advance", {}, null, "json").done(function (hasActiveSalaryAdvance) {
                    universal.hasActiveApplication = hasActiveSalaryAdvance
                });
            },
            schema: {
                model: {
                    id: "id_salary_advance",
                    fields: {
                        date_raised: {
                            type: 'date',
                            editable: false
                        },
                        request_number: {
                            editable: false
                        },
                        amount_requested: {
                            type: 'number',
                            validation: {
                                required: function (input) {
                                    if (input.attr("name") === "amount_requested") {
                                        input.attr("data-required-msg", "Enter an amount.");
                                        return input.val() !== "";
                                    }
                                    return true;
                                },
                                min: function (input) {
                                    if (input.attr("name") === "amount_requested") {
                                        let grid = $salaryAdvanceGrid.getKendoGrid();
                                        let model = grid.dataSource.getByUid(grid_uid);
                                        input.attr("data-min-msg", "Amount must be more than 10% of salary.");
                                        return 0.1 * model.basic_salary <= kendo.parseFloat(input.val());
                                    }
                                    return true;
                                },
                                max: function (input) {
                                    if (input.attr("name") === "amount_requested") {
                                        let grid = $salaryAdvanceGrid.getKendoGrid();
                                        let model = grid.dataSource.getByUid(grid_uid);
                                        input.attr("data-max-msg", "Amount must not exceed 30% of salary.");
                                        return 0.30 * model.basic_salary >= kendo.parseFloat(input.val());
                                    }
                                    return true;
                                }
                            },
                            nullable: true,
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
                        gm_approval: {editable: false, nullable: true, type: "boolean"},
                        gm_approval_date: {editable: false, nullable: true, type: "date"},
                        gm_comment: {editable: false, type: "string"},
                        gm_id: {type: "number", editable: false},
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
                                min: 0
                            },
                            editable: false
                        },
                        amount_approved: {
                            nullable: true,
                            type: 'number',
                            validation: {
                                required: true,
                                min: 0
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
                            editable: false,
                            nullable: true
                        },
                        hr_approval_date: {
                            type: 'date',
                            editable: false,
                            nullable: true
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
                            type: "number", editable: false, nullable: true
                        },
                        percentage: {
                            type: "number", editable: false
                        },
                        basic_salary: {
                            type: "number",
                            defaultValue: universal.basicSalary,
                            editable: false
                        }
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
            persistSelection: true,
            toolbar: [{
                name: "create",
                text: "Request Salary Advance",
                iconClass: "k-icon k-i-add text-primary"
            }, {name: "excel", template: $("#exportToExcel").html()}],
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
                        hod_approval: row.cells[3].value,
                        hr_approval: row.cells[6].value,
                        gm_approval: row.cells[10].value,
                        fmgr_approval: row.cells[13].value
                    };
                    row.cells[3].value = dataItem.hod_approval == null ? 'Pending' : (dataItem.hod_approval ? 'Approved' : 'Rejected');
                    row.cells[6].value = dataItem.hr_approval == null ? 'Pending' : (dataItem.hr_approval ? 'Approved' : 'Rejected');
                    row.cells[10].value = dataItem.gm_approval == null ? 'Pending' : (dataItem.gm_approval ? 'Approved' : 'Rejected');
                    row.cells[13].value = dataItem.fmgr_approval == null ? 'Pending' : (dataItem.fmgr_approval ? 'Approved' : 'Rejected');
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
                        name: "custom_edit",
                        text: "Edit",
                        iconClass: {edit: "k-icon k-i-edit"},
                        className: "badge badge-success btn k-button text-black border k-grid-custom-edit",
                        click: function (e) {
                            let grid = $salaryAdvanceGrid.getKendoGrid();
                            salaryAdvanceDataSource.read().then(function () {
                                grid.editRow(grid.currentRow());
                            });
                        }
                    },
                        {name: "print", template: $("#printButton").html()}],
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
                    field: 'date_raised',
                    title: 'Date Raised',
                    headerAttributes: {
                        "class": "title"
                    },
                    width: 450,
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
                    width: 180,
                    format: "{0:#\\%}",
                    filterable: false,
                    hidden: true
                },
                {
                    field: 'amount_requested',
                    title: 'Amount Requested',
                    width: 180,
                    editor: editNumberWithoutSpinners,
                    headerAttributes: {
                        "class": "title"
                    },
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
                    nullable: true,
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
                    hidden: false,
                    filterable: false,
                    nullable: true,
                    format: "{0:dddd dd MMM, yyyy}"
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
                    hidden: false,
                    filterable: false,
                    nullable: true,
                    format: "{0:dddd dd MMM, yyyy}"
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
                    hidden: false,
                    filterable: false,
                    nullable: true,
                    format: "{0:dddd dd MMM, yyyy}"
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
                    filterable: false
                },
                {
                    field: 'received_by',
                    title: 'Received By',
                    hidden: false,
                    nullable: true,
                    headerAttributes: {
                        "class": "title"
                    },
                    width: 200,
                    filterable: false
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
                let dataSource = this.dataSource;
                let data = grid.dataSource.data();
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
                with (universal) {
                    if (hasActiveApplication) {
                        disableGridAddButton();
                    }
                    if (!currentRowSelected && requestNumber) {
                        selectGridRow(requestNumber, grid, dataSource, 'request_number');
                    }
                    if (!firstLoadDone) {
                        firstLoadDone = true;
                        if (requestNumber)
                            filterString(requestNumber, 'request_number');
                    }
                }
            },
            detailInit: function (e) {
                let grid = $salaryAdvanceGrid.data("kendoGrid");
                let masterRow = e.detailRow.prev('tr.k-master-row');
                let dataItem = grid.dataItem(masterRow);
                let colSize = e.sender.content.find('colgroup col').length;
                e.detailRow.find(".print-it").attr("href", URL_ROOT + "/salary-advance/print/" + dataItem["request_number"]);
                $(".print-it").printPage();
                e.detailRow.find('.k-hierarchy-cell').hide();
                e.detailCell.attr('colspan', colSize);
            },
            detailExpand: onDetailExpand,
            detailCollapse: onDetailCollapse,
            beforeEdit: function (e) {
                window.grid_uid = e.model.uid; // uid of current editing row
                e.model.fields.amount_requested.editable = e.model.hod_approval === null;
            },
            edit: function (e) {
                let validator = this.editable.validatable;
                let amountRequestedLabelField = e.container.find(".k-edit-label:eq(3), .k-edit-field:eq(3)");
                let amountRequestedNumericTextBox = amountRequestedLabelField.find('input[data-role="numerictextbox"]').data('kendoNumericTextBox');

                validator._errorTemplate = (function anonymous(data
                ) {
                    let $kendoOutput, $kendoHtmlEncode = kendo.htmlEncode;
                    with (data) {
                        $kendoOutput = '<div class="k-widget k-tooltip k-tooltip-validation mt-2"><span class="k-icon k-i-warning"> </span><span class="mr-4">' + (message) + '</span><span class="k-callout k-callout-n"></span></div>';
                    }
                    return $kendoOutput;
                });

                // Toggle visibility off for all editor fields and labels
                e.container.find('.k-edit-label, .k-edit-field').addClass("pt-2").toggle(false);

                amountRequestedLabelField.toggle(true);

                e.container.on("keypress", ".k-input", function (e) {
                    if (e.which === 13)
                        $(this).blur().next("input").focus();
                });

                e.container.data('kendoWindow').bind('activate', function () {
                    if (e.model.fields["amount_requested"].editable) {
                        amountRequestedNumericTextBox.focus();
                    }
                });

                e.container.data('kendoWindow').bind('deactivate', function () {
                    let data = $salaryAdvanceGrid.getKendoGrid().dataSource.data();
                    $.each(data, function (i, row) {
                        $('tr[data-uid="' + row.uid + '"] ').attr('data-id-salary-advance', row['id_salary_advance']).find(".print-it").attr("href", URL_ROOT + "/salary-advance/print/" + row["request_number"]);
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

        $salaryAdvanceGrid.on("click", ".action-delete", function () {
            let row = $(this).closest("tr");
            $salaryAdvanceGrid.data("kendoGrid").removeRow(row);
        });

        $salaryAdvanceGrid.on('click', '.k-grid-add-disabled', function () {
            toastError($(this).attr("data-title"));
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
        /*$salaryAdvanceGrid.data("kendoGrid").bind("filter", function (e) {
            toggleDateFilterBtn(e)
        });*/
    });

    toggleAmountRequested = function () {
        if (this.id === 'percentageRadio') {

        }
    };

    function disableGridAddButton() {
        let errorMessage = moment() > moment(30, "DD") ? "Applications cannot be accepted after 10 days into the month! <br> <span>Please try again next month.</span>" : "You have an active salary advance request for this month!";
        if (!DEBUG_MODE)
            kGridAddButton.attr('disabled', 'disabled')
                .attr("data-title", errorMessage)
                .attr('title', moment() > moment(30, "DD") ? "Applications cannot be accepted after 10 days into the month! Please try again next month." : "You have an active salary advance request for this month!")
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