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
            error: function (e) {
                grid.dataSource.cancelChanges();
                grid.dataSource.read();
                if (e.status === "parsererror") {
                    toastError("Some required assets on this page failed to load");
                    return;
                }
                toastError(e.errors[0]['message']);
            },
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
        grid = $salaryAdvanceGrid.kendoGrid($.extend({}, Configurations.grid.options, {dataSource: dataSource}, {
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
                let amountRequestedLabelField = e.container.find(".k-edit-label:eq(3), .k-edit-field:eq(3)");
                let amountRequestedNumericTextBox = amountRequestedLabelField.find('input[data-role="numerictextbox"]').data('kendoNumericTextBox');

                // Toggle visibility off for all editor fields and labels
                e.container.find('.k-edit-label, .k-edit-field').addClass("pt-2").toggle(false);
                amountRequestedLabelField.toggle(true);

                e.container.data('kendoWindow').bind('activate', function () {
                    if (e.model.fields.amount_requested.editable) {
                        amountRequestedNumericTextBox.focus();
                    }
                });
            }
        })).getKendoGrid();
        kGridAddButton = $('.k-grid-add');
        documentReady();
    });
</script>
</body>
</html>