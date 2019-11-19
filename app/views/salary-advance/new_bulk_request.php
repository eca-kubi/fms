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
/** @var string $request_number */
?>
<script>
    let MIN_PERCENTAGE = <?php echo MIN_PERCENTAGE ?>;
    let MAX_PERCENTAGE = <?php echo MAX_PERCENTAGE ?>;
    let grid = null;
    let universal = {
        basicSalary: "<?php echo $current_user->basic_salary; ?>",
        currencySymbol: "<?php echo CURRENCY_GHS; ?>",
        currentDepartment: "<?php echo $current_user->department ?>",
        currentDepartmentID: <?php echo $current_user->department_id; ?>,
        hasActiveApplication: "<?php echo hasActiveApplication($current_user->user_id) ?>",
        isFinanceOfficer: Boolean("<?php echo isFinanceOfficer($current_user->user_id); ?>"),
        isHr: Boolean("<?php echo isCurrentHR($current_user->user_id) ?>"),
        isFmgr: Boolean("<?php echo isCurrentFmgr($current_user->user_id) ?>"),
        isGM: Boolean("<?php echo isCurrentGM($current_user->user_id) ?>"),
        isManager: Boolean("<?php echo isCurrentManager($current_user->user_id) ?>"),
        requestNumber: "<?php echo $request_number ?>",
        isSecretary: Boolean("<?php echo isSecretary($current_user->user_id); ?>")
    };
    let $salaryAdvanceGrid;
    let salaryAdvanceDataSource;
    let bulkApplicants = [];
    const ERROR_AN_APPLICATION_ALREADY_EXISTS = 'E_1001';
    $(document).ready(function () {
        URL_ROOT = $('#url_root').val();
        kendo.culture().numberFormat.currency.symbol = 'GH₵';
        $salaryAdvanceGrid = $('#salary_advance');
        salaryAdvanceDataSource = new kendo.data.DataSource({
            pageSize: 20,
            batch: true,
            transport: {
                read: {
                    url: URL_ROOT + "/salary-advance-bulk-requests-ajax/init/",
                    dataType: "json",
                },
                update: {
                    url: URL_ROOT + "/salary-advance-bulk-requests-ajax/update/",
                    type: 'post',
                    dataType: 'json',
                    contentType: "application/json"
                },
                destroy: {
                    url: URL_ROOT + "/salary-advance-bulk-requests-ajax/destroy/",
                    type: "post",
                    dataType: "json",
                    contentType: "application/json"
                },
                create: {
                    url: URL_ROOT + "/salary-advance-bulk-requests-ajax/create",
                    type: "post",
                    dataType: "json",
                    contentType: "application/json",
                    data: {request_number: universal.requestNumber}
                },
                parameterMap: function (data, type) {
                    if (type === "create") {
                        return JSON.stringify(data);
                    }
                },
                errors: function (response) {
                    return response.errors;
                }
            },
            error: function (e) {
                salaryAdvanceDataSource.cancelChanges();
                salaryAdvanceDataSource.read();
                purgeBulkApplicants();
                toastError(e.errors ? e.errors[0]['message'] : "Some required assets on this page failed to load");
            },
            requestEnd: function (e) {
                if (e.type === 'create' && e.response.length > 0) {
                    toastSuccess('Success', 5000);
                    setTimeout(function () {
                        window.location.href = URL_ROOT + "/salary-advance/bulk-requests/" + universal.requestNumber;
                    }, 3);
                }
            },
            change: function (e) {
                let dataSource = e.sender;
                let dataSourceHasChanges = dataSource.hasChanges();
                if (dataSourceHasChanges) {
                    $('.k-grid-cancel-changes, .k-grid-save-changes').removeClass('d-none');
                }
                if (e["action"] === "remove" && dataSource.data().length === 0) {
                    // hide the cancel and save buttons
                    $(".k-grid-cancel-changes,.k-grid-save-changes").addClass("d-none")
                }

                if (e["action"] === "itemchange" && e.field === "name") {
                    purgeBulkApplicants();
                }
            },
            schema: {
                model: {
                    id: "id_salary_advance",
                    fields: {
                        amount_requested: {
                            editable: true,
                            nullable: true,
                            type: "number",
                            validation: Configurations.validations.amountRequested
                        },
                        basic_salary: {editable: false, type: "number"},
                        request_number: {defaultValue: universal.requestNumber, type: "string", editable: false},
                        date_raised: {editable: false, type: "date"},
                        department: {editable: false, type: "string"},
                        department_id: {editable: false, type: "number", nullable: true},
                        employee: {defaultValue: {}},
                        name: {from: "employee.name"},
                        raised_by_secretary: {type: "boolean", defaultValue: universal.isSecretary},
                        user_id: {type: "number", nullable: true}
                    }
                }
            }
        });

        grid = $salaryAdvanceGrid.kendoGrid({
            autoFitColumn: true,
            selectable: true,
            mobile: true,
            noRecords: true,
            navigatable: true,
            toolbar: kendo.template($('#toolbarTemplate_New_Bulk_Request').html()),
            excel: {
                fileName: "Salary Advance Export.xlsx",
                filterable: true
            },
            excelExport: function (e) {
                let sheet = e.workbook.sheets[0];
                sheet.columns[0].autoWidth = false;
                for (let rowIndex = 1; rowIndex < sheet.rows.length; rowIndex++) {
                    let row = sheet.rows[rowIndex];
                    // alternating row colors
                    if (rowIndex % 2 === 0) {
                        let row = sheet.rows[rowIndex];
                        for (let cellIndex = 0; cellIndex < row.cells.length; cellIndex++) {
                            //row.cells[cellIndex].fontName = "Poppins";
                        }
                    }
                }
            },
            editable: "incell",
            save: function (e) {
                if (e.values.name && e.values.name !== "") {
                    let dropDown = e.container.find("input").data("kendoDropDownList");
                    e.model.user_id = dropDown.dataSource.at(dropDown.selectedIndex - 1).user_id;
                    if (!bulkApplicants.includes(e.values.name)) {
                        bulkApplicants.push(e.values.name);
                    }
                }
            },
            saveChanges: function (e) {
                let models = grid.dataSource.data();
                for (let i = 0; i < models.length; i++) {
                    let model = models[i];
                    let row = selectGridRow(model.uid, grid, grid.dataSource, "uid");
                    if (model.user_id === null) {
                        e.preventDefault();
                        let cell = row.find("td:eq(1)");
                        grid.editCell(cell);
                        cell.next().click();
                        return false;
                    }
                    if (model.amount_requested === null) {
                        e.preventDefault();
                        let cell = row.find("td:eq(2)");
                        grid.editCell(cell);
                        cell.next().click();
                        return false;
                    }
                }
                if (!confirm("Are you sure you want to submit these requests?")) {
                    e.preventDefault();
                } else {
                    $(".k-grid-cancel-changes, .k-grid-save-changes").addClass("d-none");
                }
            },
            filterable: false,
            columnMenu: false,
            sortable: true,
            groupable: false,
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
                    command: [{name: "destroy", template: $("#destroyButton").html()}],
                    headerAttributes: {class: "title"},
                    title: "Action",
                    width: 100
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
                    field: 'amount_requested',
                    title: 'Amount Requested',
                    width: 250,
                    headerAttributes: {
                        "class": "title"
                    },
                    groupHeaderTemplate: "Amount Requested: #=  value ? kendo.format('{0:c}', value) : ''#",
                    aggregates: ["max", "min", "count"],
                    format: "{0:c}",
                    filterable: false
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
                },
                {
                    field: 'date_raised',
                    title: 'Date Raised',
                    headerAttributes: {
                        "class": "title"
                    },
                    width: 180,
                    groupHeaderTemplate: "Date Raised: #= kendo.toString(kendo.parseDate(value), 'dddd dd MMM, yyyy') #",
                    filterable: false,
                    format: "{0:dddd dd MMM, yyyy}",
                },
            ],
            dataSource: salaryAdvanceDataSource,
            dataBound: function (e) {
                let filterRow = grid.table.find('thead tr.k-filter-row');
                filterRow.find('input:first').attr('placeholder', 'Search...');
                filterRow.find('input:eq(1)').attr('placeholder', 'Search...');
                $.get(URL_ROOT + "/salary-advance-ajax/ActiveApplicants", {}, null, "json").done(function (data) {
                    activeApplicants = data;
                })

            },
            beforeEdit: function (e) {
                window.grid_uid = e.model.uid; // uid of current editing row
            },
            remove: function (e) {
                // remove names from bulk applicants
                for (let i = 0; i < bulkApplicants.length; i++) {
                    if (bulkApplicants[i] === e.model.name) {
                        bulkApplicants.splice(i, 1);
                        i--;
                    }
                }
            }
        }).getKendoGrid();

        $('.k-grid-cancel-changes').click(function () {
            $('.k-grid-cancel-changes, .k-grid-save-changes').addClass('d-none');
            bulkApplicants.length = 0;
        });

        $salaryAdvanceTooltip = grid.table.kendoTooltip({
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

        grid.table.on("click", ".action-delete", function () {
            let row = $(this).closest("tr");
            $salaryAdvanceGrid.data("kendoGrid").removeRow(row);
        });

        grid.bind("dataBound", onDataBound);
    });

</script>
</body>
</html>