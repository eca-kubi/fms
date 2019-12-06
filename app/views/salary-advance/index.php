<?php require_once APP_ROOT . '\views\includes\header.php'; ?>
<?php require_once APP_ROOT . '\views\includes\navbar.php'; ?>
<?php require_once APP_ROOT . '\views\includes\sidebar.php'; ?>
<!-- .content-wrapper -->
<div class="content-wrapper animated fadeInRight" style="margin-top: <?php echo NAVBAR_MT; ?>">
    <!-- content -->
    <section class="content">
        <div class="box-group" id="box_group">
            <div class="box collapsed">
                <div class="box-header">
                    <h5>
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
    let MIN_PERCENTAGE = <?php echo MIN_PERCENTAGE ?>;
    let MAX_PERCENTAGE = <?php echo MAX_PERCENTAGE ?>;
    let kGridAddButton;
    $(document).ready(function () {
        $salaryAdvanceGrid = $("#salary_advance");
        dataSource = new kendo.data.DataSource({
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
            error: dataSourceError,
            requestEnd: function (e) {
                if (e.type === 'update' && e.response.length > 0 || e.type === 'create' && e.response.length > 0) {
                    toastSuccess('Success', 5000);
                }
                $.get(URL_ROOT + "/salary-advance-ajax/has-active-salary-advance", {}, null, "json").done(function (hasActiveSalaryAdvance) {
                    universal.hasActiveApplication = hasActiveSalaryAdvance;
                    if (hasActiveSalaryAdvance) disableGridAddButton();
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
                            validation: Configurations.validations.minMaxAmount,
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
                        },
                        finance_officer_comment: {
                            editable: false
                        }
                    }
                }
            }
        });
        grid = $salaryAdvanceGrid.kendoGrid($.extend({}, Configurations.grid.options, {
            columns: [
                {
                    command: [
                        {
                            name: "custom_edit",
                            text: "Edit",
                            iconClass: "k-icon k-i-edit",
                            className: "badge badge-success btn k-button text-black border k-grid-custom-edit",
                            click: function () {
                                let currentRow = grid.currentRow();
                                let id_salary_advance;
                                selectedRowId = id_salary_advance = currentRow.attr("data-id-salary-advance");
                                grid.select(currentRow);
                                grid.content.lockscroll(true);
                                grid.dataSource.read().then(function () {
                                    grid.editRow(grid.table.find("tr[data-id-salary-advance=" + id_salary_advance + "]"));
                                });
                            }
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
                    filterable: {cell: {showOperators: false, suggestionOperator: "contains", operator: "contains"}},
                },
                {
                    field: 'amount_requested',
                    title: 'Amount Requested',
                    width: 180,
                    headerAttributes: {
                        "class": "title"
                    },
                    format: "{0:c}",
                    filterable: false,
                    editor: editNumberWithoutSpinners
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
                    nullable: true,
                    editor: editNumberWithoutSpinners
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
                    groupHeaderTemplate: "GM Approval: #= value=== null? 'Pending' : ( value? 'Approved': 'Rejected' )# | Total: #= count #",
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
                    format: "{0:c}",
                    width: 200,
                    filterable: false,
                    editor: editNumberWithoutSpinners
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
                    filterable: false,
                    format: "{0:c}",
                    editor: editNumberWithoutSpinners
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
                    nullable: true,
                    editor: employeesEditor
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
                    groupHeaderTemplate: "Date Received: #= value? kendo.toString(kendo.parseDate(value), 'dddd dd MMM, yyyy') : '' #",
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
            toolbar: [
                {
                    name: "create",
                    text: "Request Salary Advance",
                    iconClass: "k-icon k-i-add text-primary"
                },
                {name: "excel", template: $("#exportToExcel").html()}
            ],
            beforeEdit: function (e) {
                window.grid_uid = e.model.uid; // uid of current editing row
                e.model.fields.amount_requested.editable = e.model.hod_approval === null;
            },
            edit: function (e) {
                grid.content.lockscroll(true);
                let amountRequestedLabelField = e.container.find(".k-edit-label:eq(1), .k-edit-field:eq(1)");
                let amountRequestedNumericTextBox = amountRequestedLabelField.find('input[data-role="numerictextbox"]').data('kendoNumericTextBox');

                // Toggle visibility off for all editor fields and labels
                e.container.find('.k-edit-label, .k-edit-field').addClass("pt-2").toggle(false);
                amountRequestedLabelField.toggle(true);

                e.container.data('kendoWindow').bind('activate', function () {
                    if (e.model.fields.amount_requested.editable) {
                        amountRequestedNumericTextBox.focus();
                    }
                });

                e.container.getKendoWindow().bind('close', function () {
                    let data = grid.dataSource.data();
                    $.each(data, function (i, row) {
                        $('tr[data-uid="' + row.uid + '"] ').attr('data-id-salary-advance', row['id_salary_advance'])
                            .attr('data-request-number', row.request_number)
                            .find(".print-it").attr("href", URL_ROOT + "/salary-advance/print/" + row["id_salary_advance"]);
                    });
                    $(".print-it").printPage();
                    setTimeout(function () {
                        grid.content.lockscroll(false);
                    }, 1)
                });

                e.container.on("keypress", ".k-input", function (e) {
                    if (e.which === 13)
                        $(this).blur().next("input").focus();
                });

                grid.editable.validatable._errorTemplate = (function anonymous(data
                ) {
                    let $kendoOutput;
                    $kendoOutput = '<div class="k-widget k-tooltip k-tooltip-validation row mt-2"><span class="k-icon k-i-info d-inline col"> </span><span class="col">' + (data.message) + '</span><span class="k-callout k-callout-n"></span></div>';
                    return $kendoOutput;
                });
            },
            excelExport: function (e) {
                let sheet = e.workbook.sheets[0];
                sheet.columns[0].autoWidth = false;
                for (let rowIndex = 1; rowIndex < sheet.rows.length; rowIndex++) {
                    let row = sheet.rows[rowIndex];
                    if (row.type === "data") {
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
                }
            }
        }, {dataSource: dataSource})).getKendoGrid();
        kGridAddButton = $('.k-grid-add');
        documentReady();
    });
</script>
</body>
</html>