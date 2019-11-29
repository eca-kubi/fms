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
        currencySymbol: "<?php echo CURRENCY_GHS; ?>",
        currentDepartment: "<?php echo $current_user->department ?>",
        currentDepartmentID: <?php echo $current_user->department_id; ?>,
        isFinanceOfficer: Boolean("<?php echo isFinanceOfficer($current_user->user_id); ?>"),
        isHr: Boolean("<?php echo isCurrentHR($current_user->user_id) ?>"),
        isFmgr: Boolean("<?php echo isCurrentFmgr($current_user->user_id) ?>"),
        isGM: Boolean("<?php echo isCurrentGM($current_user->user_id) ?>"),
        isManager: Boolean("<?php echo isCurrentManager($current_user->user_id) ?>"),
        requestNumber: "<?php echo $request_number ?>",
        isSecretary: Boolean("<?php echo isSecretary($current_user->user_id); ?>")
    };
    let MIN_PERCENTAGE = <?php echo MIN_PERCENTAGE ?>;
    let MAX_PERCENTAGE = <?php echo MAX_PERCENTAGE ?>;
    $(document).ready(function () {
        $salaryAdvanceGrid = $('#salary_advance');
        dataSource = new kendo.data.DataSource({
            transport: {
                read: {
                    url: URL_ROOT + "/salary-advance-bulk-requests-ajax/index/",
                    dataType: "json",
                    data: {request_number: universal.requestNumber}
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
                    if (type !== "read")
                        return JSON.stringify(data);
                },
                errors: function (response) {
                    return response.errors;
                }
            },
            error: dataSourceError,
            requestEnd: onRequestEnd,
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
                            validation: Configurations.validations.minMaxAmount
                        },
                        basic_salary: {editable: false, type: "number"},
                        request_number: {nullable: true, type: "string", editable: false},
                        date_raised: {editable: false, type: "date"},
                        date_received: {editable: false, nullable: true, type: "date"},
                        department: {editable: false, type: "string"},
                        department_id: {editable: false, type: "number"},
                        department_ref: {editable: false},
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
                        hod_comment: {
                            editable: false,
                            type: "string",
                            validation: Configurations.validations.minMaxAmount
                        },
                        hod_id: {type: "number"},
                        hr_approval: {editable: false, nullable: true, type: "boolean"},
                        hr_approval_date: {editable: false, nullable: true, type: "date"},
                        hr_comment: {editable: false, type: "string"},
                        hr_id: {type: "number"},
                        is_bulk_request: {defaultValue: true, type: "boolean"},
                        name: {editable: false},
                        percentage: {
                            editable: false,
                            nullable: true,
                            type: "number"
                        },
                        raised_by_id: {type: "number"},
                        raised_by_secretary: {type: "boolean"},
                        received_by: {editable: false, nullable: true, type: "string"},
                        user_id: {type: "number"},
                        finance_officer_id: {type: "number", nullable: true},
                        finance_officer_comment: {type: "string", editable: false}
                    }
                }
            }
        });
        grid = $salaryAdvanceGrid.kendoGrid($.extend({}, Configurations.grid.options, {dataSource: dataSource}, {
            groupable: true,
            dataSource: dataSource,
            beforeEdit: function (e) {
                window.grid_uid = e.model.uid; // uid of current editing row
                e.model.fields.amount_requested.editable = (e.model.hod_approval === null) && universal.isSecretary;
                e.model.fields.hod_comment.editable = e.model.fields.hod_approval.editable = e.model.hod_approval === null && (e.model.department_id === universal.currentDepartmentID) && universal.isManager;
                e.model.fields.amount_payable.editable = e.model.fields.hr_comment.editable = e.model.fields.hr_approval.editable = universal.isHr && (e.model.hr_approval === null) && e.model.hod_approval === true;
                e.model.fields.gm_approval.editable = e.model.fields.gm_comment.editable = universal.isGM && (e.model.gm_approval === null) && e.model.hr_approval === true;
                e.model.fields.amount_approved.editable = e.model.fields.fmgr_comment.editable = e.model.fields.fmgr_approval.editable = universal.isFmgr && (e.model.fmgr_approval === null) && e.model.gm_approval === true;
                e.model.fields.received_by.editable = e.model.fields.amount_received.editable = e.model.fields.finance_officer_comment.editable = ((e.model.fmgr_approval === true) && universal.isFinanceOfficer && e.model.date_received !== null);
            },
            edit: onEdit,
        })).getKendoGrid();
        documentReady();
    });
</script>
</body>
</html>