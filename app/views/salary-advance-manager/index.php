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
        dataSource = new kendo.data.DataSource({
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
                if (groups.length !== this.group().length) {
                    let dataSourceGroups = this.group(),
                        length = groups.length;
                    if (length > dataSourceGroups.length) {
                        if (dataSourceGroups.length === 0) {
                            collapsed = {};
                        } else {
                            for (let key in collapsed) {
                                if (key.indexOf(length - 1) === 0) {
                                    collapsed[key] = false;
                                }
                            }
                        }
                    }
                    groups = this.group().slice(0);
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
                            editable: false
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
                        gm_approval: {
                            editable: false,
                            nullable: true,
                            type: "boolean"
                        },
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
                            validation: Configurations.validations.minMaxAmount,
                            editable: false
                        },
                        amount_approved: {
                            nullable: true,
                            type: 'number',
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
                        finance_officer_comment: {type: "string", editable: false}
                    }
                }
            }
        });
        $salaryAdvanceGrid = $('#salary_advance');
        grid = $salaryAdvanceGrid.kendoGrid($.extend({}, Configurations.grid.options, {dataSource: dataSource}, {
            groupable: true,
            toolbar: [{name: "excel", template: $("#exportToExcel").html()}],
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
            beforeEdit: function (e) {
                window.grid_uid = e.model.uid; // uid of current editing row
                e.model.fields.amount_requested.editable = false;
                e.model.fields.hod_comment.editable = e.model.fields.hod_approval.editable = e.model.hod_approval === null && (e.model.department_id === universal.currentDepartmentID);
                e.model.fields.amount_payable.editable = e.model.fields.hr_comment.editable = e.model.fields.hr_approval.editable = universal.isHr && (e.model.hr_approval === null) && e.model.hod_approval === true;
                e.model.fields.gm_approval.editable = e.model.fields.gm_comment.editable = universal.isGM && (e.model.gm_approval === null) && e.model.hr_approval === true;
                e.model.fields.amount_approved.editable = e.model.fields.fmgr_comment.editable = e.model.fields.fmgr_approval.editable = universal.isFmgr && (e.model.fmgr_approval === null) && e.model.gm_approval === true;
                e.model.fields.received_by.editable = e.model.fields.amount_received.editable = e.model.fmgr_approval === true;
            },
            edit: function (e) {
                let nameLabelField = e.container.find('.k-edit-label:eq(1), .k-edit-field:eq(1)');
                let departmentLabelField = e.container.find('.k-edit-label:eq(2), .k-edit-field:eq(2)');
                let amountRequestedLabelField = e.container.find('.k-edit-label:eq(4), .k-edit-field:eq(4)');
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

                e.container.find('.k-edit-label, .k-edit-field').addClass("pt-2").toggle(false);
                nameLabelField.toggle();
                departmentLabelField.toggle();
                amountRequestedLabelField.toggle();
                hodApprovalLabelField.toggle();
                hrApprovalLabelField.toggle();
                gmApprovalLabelField.toggle();
                fmgrApprovalLabelField.toggle();
                amountPayableLabelField.toggle(e.model.fields.hr_approval.editable);
                amountApprovedLabelField.toggle(Boolean(e.model.fmgr_approval));
                hodCommentLabelField.toggle(e.model.fields.hod_approval.editable || e.model.hod_comment !== null);
                hrCommentLabelField.toggle(e.model.fields.hr_approval.editable || e.model.hr_comment !== null);
                gmCommentLabelField.toggle(e.model.fields.gm_approval.editable || e.model.gm_comment !== null);
                fmgrCommentLabelField.toggle(e.model.fields.fmgr_approval.editable || e.model.fmgr_comment !== null);
                receivedByLabelField.toggle(e.model.fields.received_by.editable || e.model.received_by !== null);
                amountReceivedLabelField.toggle(e.model.fields.amount_received.editable || e.model.received_by !== null);
                dateReceivedLabelField.toggle(e.model.date_received !== null);

                // Validations
                hodCommentLabelField.find('.k-textbox').attr('data-required-msg', 'This field is required!');
                hrCommentLabelField.find('.k-textbox').attr('data-required-msg', 'This field is required!');
                amountPayableLabelField.find('.k-input').attr('data-required-msg', 'This field is required!');
                gmCommentLabelField.find('.k-textbox').attr('data-required-msg', 'This field is required!');
                fmgrCommentLabelField.find('.k-textbox').attr('data-required-msg', 'This field is required!');
                amountApprovedLabelField.find('.k-input').attr('data-required-msg', 'This field is required!');
                amountReceivedLabelField.find('.k-input').attr('data-required-msg', 'This field is required!');
                receivedByLabelField.find('.k-input').attr('data-required-msg', 'This field is required!');
            }
        })).getKendoGrid();
        documentReady()
    });
</script>
</body>
</html>