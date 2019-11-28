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
            requestNumber: "<?php echo $request_number ?>",
            currencySymbol: "<?php echo CURRENCY_GHS; ?>",
            currentDepartment: "<?php echo $current_user->department; ?>",
            currentDepartmentID: <?php echo $current_user->department_id; ?>,
            isFinanceOfficer: Boolean("<?php echo isFinanceOfficer($current_user->user_id); ?>"),
            isHr: Boolean("<?php echo isCurrentHR($current_user->user_id) ?>"),
            isFmgr: Boolean("<?php echo isCurrentFmgr($current_user->user_id) ?>"),
            isGM: Boolean("<?php echo isCurrentGM($current_user->user_id) ?>"),
            isManager: Boolean("<?php echo isCurrentManager($current_user->user_id) ?>"),
            isSecretary: Boolean("<?php echo isSecretary($current_user->user_id); ?>")
        },
        MIN_PERCENTAGE = <?php echo MIN_PERCENTAGE ?>,
        MAX_PERCENTAGE = <?php echo MAX_PERCENTAGE ?>;
    $(document).ready(function () {
        $salaryAdvanceGrid = $('#salary_advance');
        dataSource = new kendo.data.DataSource({
            pageSize: 20,
            transport: {
                read: {
                    url: URL_ROOT + "/salary-advance-single-requests-ajax/",
                    type: "post",
                    dataType: "json"
                },
                update: {
                    url: URL_ROOT + "/salary-advance-single-requests-ajax/update/",
                    type: 'post',
                    dataType: 'json'
                },
                destroy: {
                    url: URL_ROOT + "/salary-advance-single-requests-ajax/destroy/",
                    type: 'post',
                    dataType: 'json'
                },
                create: {
                    url: URL_ROOT + "/salary-advance-single-requests-ajax/create",
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
            requestEnd: onRequestEnd,
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
                            editable: false
                        },
                        hod_comment: {
                            type: 'string',
                            editable: false,
                            validation: Configurations.validations.minMaxAmount
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
                        gm_approval: {
                            editable: false,
                            nullable: true,
                            type: "boolean"
                        },
                        gm_approval_date: {editable: false, nullable: true, type: "date"},
                        gm_comment: {
                            editable: false, type: "string", validation: Configurations.validations.minMaxAmount,
                        },
                        gm_id: {type: "number", editable: false},
                        fmgr_approval: {
                            nullable: true,
                            type: 'boolean',
                            editable: false
                        },
                        amount_payable: {
                            type: 'number',
                            nullable: true,
                            validation: Configurations.validations.minMaxAmount,
                            editable: false
                        },
                        amount_approved: {
                            nullable: true,
                            type: 'number',
                            validation: Configurations.validations.minMaxAmount,
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
                            type: 'number', editable: false
                        },
                        hod_id: {
                            type: 'number', editable: false
                        },
                        fmgr_id: {
                            type: 'number', editable: false
                        },
                        raised_by_secretary: {
                            type: 'boolean', editable: false
                        },
                        user_id: {type: 'number', editable: false},
                        department_id: {
                            type: 'number', editable: false
                        },
                        raised_by_id: {type: "number", editable: false},
                        amount_received: {
                            type: "number", editable: false, nullable: true
                        },
                        percentage: {
                            type: "number",
                            editable: false
                        },
                        amount_requested_is_percentage: {
                            type: 'boolean', editable: false
                        },
                        basic_salary: {
                            type: "number", editable: false
                        },
                        finance_officer_id: {type: "number", nullable: true, editable: false},
                        finance_officer_comment: {
                            type: "string", editable: false, validation: Configurations.validations.minMaxAmount,
                        }
                    }
                }
            }
        });
        grid = $salaryAdvanceGrid.kendoGrid($.extend({}, Configurations.grid.options, {dataSource: dataSource}, {
            groupable: true,
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
            beforeEdit: onBeforeEdit,
            edit: onEdit,
        })).getKendoGrid();
        documentReady()
    });
</script>
</body>
</html>