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
        currencySymbol: "<?php echo CURRENCY_GHS; ?>",
        currentDepartment: "<?php echo $current_user->department ?>",
        currentDepartmentID: <?php echo $current_user->department_id; ?>,
        isFinanceOfficer: Boolean("<?php echo isFinanceOfficer($current_user->user_id); ?>"),
        isHr: Boolean("<?php echo isCurrentHR($current_user->user_id) ?>"),
        isFmgr: Boolean("<?php echo isCurrentFmgr($current_user->user_id) ?>"),
        isGM: Boolean("<?php echo isCurrentGM($current_user->user_id) ?>"),
        isManager: Boolean("<?php echo isCurrentManager($current_user->user_id) ?>"),
        isSecretary: Boolean("<?php echo isSecretary($current_user->user_id) ?>"),
        requestNumber: "<?php echo $request_number ?>"
    };
    $(document).ready(function () {
        $salaryAdvanceGrid = $('#salary_advance');
        dataSource = new kendo.data.DataSource({
            transport: {
                read: {
                    url: URL_ROOT + "/salary-advance-bulk-requests-ajax/index/" + universal.requestNumber,
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
            error: function (e) {
                toastError(e.errors ? e.errors[0]['message'] : "The page failed to load some required assets.");
                this.cancelChanges();
            },
            requestEnd: function (e) {
                if ((e.type === 'update' || e.type === 'create') && e.response.length > 0) {
                    toastSuccess('Success', 5000);
                }
            },
            change: function () {
                if (this.hasChanges()) {
                    $('.k-grid-cancel-changes, .k-grid-save-changes').removeClass('d-none');
                }
            },
            schema: {
                model: {
                    id: "id_salary_advance_bulk_requests",
                    fields: {
                        request_number: {nullable: true, type: "string", editable: false},
                        date_raised: {editable: false, type: "date"},
                        department: {editable: false, type: "string"},
                        department_id: {editable: false, type: "number"},
                        raised_by_id: {type: "number"},
                        raised_by: {type: "string"}
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
            toolbar: kendo.template($('#toolbarTemplate_Bulk_Requests').html()),
            excel: {
                fileName: "Salary Advance Export - Bulk Requests.xlsx",
                filterable: true
            },
            dataSource: salaryAdvanceDataSource,
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
                    attributes: {class: "action"},
                    command: [{
                        name: "details",
                        text: "&nbsp;DETAILS",
                        iconClass: "fas fa-info-circle",
                        className: "badge badge-warning btn k-button text-black border text-primary w3-hover-text-white mx-2",
                        click: function (e) {
                            let grid = $salaryAdvanceGrid.getKendoGrid();
                            let requestNumber = grid.dataItem($(e.currentTarget).closest("tr")).request_number;
                            window.location.href = URL_ROOT + "/salary-advance/bulk-requests/" + requestNumber;
                        }
                    },
                        {
                            name: "print",
                            text: "&nbsp;PRINT",
                            iconClass: "k-icon k-i-printer",
                            className: "action-bulk-print badge badge-primary btn k-button border text-bold text-white",
                            click: function () {
                            },
                            visible: function (dataItem) {
                                //todo Print in bulk
                                return true;
                            }
                        }],
                    headerAttributes: {class: "title"},
                    title: "Action",
                    width: 190
                },
                {
                    field: "raised_by",
                    filterable: {cell: {showOperators: false}},
                    headerAttributes: {class: "title"},
                    title: "Submitted By",
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
                        cell: {
                            showOperators: false
                        }
                    },
                    hidden: universal.isSecretary
                },
                {
                    title: "Request Number",
                    width: 250,
                    field: "request_number",
                    filterable: {cell: {showOperators: false}},
                    headerAttributes: {class: "title"}
                },
                {
                    field: 'date_raised',
                    title: 'Date Raised',
                    headerAttributes: {
                        "class": "title"
                    },
                    width: 450,
                    groupHeaderTemplate: "Date Raised: #= kendo.toString(kendo.parseDate(value), 'dddd dd MMM') #",
                    filterable: {
                        cell: {
                            template: dateRangeFilter
                        }
                    },
                    format: "{0:dddd dd MMM, yyyy}"
                }
            ]
        });
    });
</script>
</body>
</html>