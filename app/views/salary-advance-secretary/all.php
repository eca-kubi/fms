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
                    <div class="salary-advance-manager" id="salary_advance_manager"></div>
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
<?php
$universal = new stdClass();
$universal->hr_comment_editable = getCurrentHR() == $current_user->user_id;
$universal->fgmr_comment_editable = $universal->amount_requested_editable = getCurrentFgmr() == $current_user->user_id;
?>
<!--suppress HtmlUnknownTarget -->
<script>
    let universal = JSON.parse(`<?php echo json_encode($universal); ?>`);
    $(document).ready(function () {
        $salaryAdvanceManagerGrid = $("#salary_advance_manager");
        $salaryAdvanceManagerGrid.on('change', "input[name=employee]", function (e) {
            //let select = $(this).data("kendoDropDownList");
        });
        let salaryAdvanceDataSource = new kendo.data.DataSource({
            pageSize: 5,
            transport: {
                read: {
                    url: URL_ROOT + "/salary-advance-secretary-ajax/",
                    type: "post",
                    dataType: "json"
                },
                update: {
                    url: URL_ROOT + "/salary-advance-secretary-ajax/update/",
                    type: 'post',
                    dataType: 'json'
                },
                destroy: {
                    url: URL_ROOT + "/salary-advance-secretary-ajax/destroy/",
                    type: 'post',
                    dataType: 'json'
                },
                create: {
                    url: URL_ROOT + "/salary-advance-secretary-ajax/create",
                    type: 'post',
                    dataType: 'json'
                },
                /* parameterMap: function (options, operation) {
                     if (operation !== "read" && options.models) {
                         return {models: kendo.stringify(options.models)};
                     }
                 }*/
            },
            requestEnd: function (e) {
                if (e.type === 'update' && !e.response[0].success) {
                    toastError(e.response[0].reason);
                }
            },
            schema: {
                model: {
                    id: "id_salary_advance",
                    fields: {
                        name: {
                            editable: false
                        },
                        date_raised: {
                            type: 'date',
                            editable: false
                        },
                        amount_requested: {
                            type: 'number',
                            editable: false,
                            validation: { //set validation rules
                                required: true,
                                min: '0'
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
                            type: 'boolean',
                            editable: false
                        },
                        hod_approval: {
                            type: 'boolean',
                            editable: false
                        },
                        fmgr_approval: {
                            type: 'boolean',
                            editable: false
                        },
                        user_id: {type: "number"},
                        department_id: {type: "number"}
                    }
                }
            }
        });

        $salaryAdvanceManagerGrid.kendoGrid({
            autoFitColumn: true,
            selectable: true,
            mobile: true,
            noRecords: true,
            navigatable: true,
            toolbar: ["create"],
            editable: 'popup',
            filterable: {
                operators: {
                    date: {
                        gte: "From Date",
                        lte: "To Date"
                    },
                    string: {
                        startswith: "Starts with",
                        eq: "Is equal to",
                        neq: "Is not equal to",
                        contains: "Contains"
                    }
                }
            },
            columnMenu: true,
            sortable: true,
            groupable: true,
            height: 520,
            resizable: true,
            scrollable: true,
            pageable: {
                alwaysVisible: false,
                pageSizes: [5, 10, 15, 20],
                buttonCount: 5
            },
            columns: [
                {
                    field: 'name',
                    title: 'Employee',
                    editor: dropDownEditor,
                    template: "#:name#",
                    width: "15%",
                    headerAttributes: {
                        "class": "title"
                    }
                },
                {
                    field: 'department',
                    title: 'Department',
                    headerAttributes: {
                        "class": "title"
                    },
                    filterable: {
                        ui: departmentFilter
                    }
                },
                {
                    field: 'date_raised',
                    title: 'Date Raised',
                    width: "14%",
                    template: "#= kendo.toString(kendo.parseDate(data.date_raised), 'dddd dd MMM, yyyy') #",
                    headerAttributes: {
                        "class": "title"
                    }
                },
                {
                    field: 'amount_requested',
                    title: 'Amount Requested',
                    width: "12%",
                    template: "#= kendo.toString('GH₵ ' + kendo.format('{0:n}', data.amount_requested)) #",
                    headerAttributes: {
                        "class": "title"
                    }
                },
                {
                    title: 'HoD',
                    headerAttributes: {
                        "class": "title"
                    },
                    columns: [
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
                            template: function (dataItem) {
                                let hod_comment = dataItem.hod_comment ? dataItem.hod_comment : '';
                                return "<span title='" + hod_comment + "'>" + hod_comment + "</span>"
                            }
                        },
                        {
                            field: 'hod_approval',
                            title: 'Approved?',
                            editor: customBoolEditor,
                            template: "#= hod_approval? 'Yes' : 'No' #",
                            headerAttributes: {
                                "class": "title"
                            }
                        }
                    ],
                },
                {
                    title: 'HR',
                    headerAttributes: {
                        "class": "title"
                    },
                    columns: [
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
                            template: function (dataItem) {
                                let hr_comment = dataItem.hr_comment ? dataItem.hr_comment : '';
                                return "<span title='" + hr_comment + "'>" + hr_comment + "</span>"
                            }
                        },
                        {
                            field: 'hr_approval',
                            title: 'Approved?',
                            editor: customBoolEditor,
                            template: "#= hr_approval? 'Yes' : 'No' #",
                            headerAttributes: {
                                "class": "title"
                            }
                        }
                    ],
                },
                {
                    title: 'Finance Mgr',
                    headerAttributes: {
                        "class": "title"
                    },
                    columns: [
                        {
                            field: 'fmgr_comment',
                            title: 'FMgr. Comment',
                            hidden: false,
                            editor: textAreaEditor,
                            headerAttributes: {
                                "class": "title"
                            },
                            attributes: {
                                class: 'comment'
                            },
                            template: function (dataItem) {
                                let fmgr_comment = dataItem.fmgr_comment ? dataItem.fmgr_comment : '';
                                return "<span title='" + fmgr_comment + "'>" + fmgr_comment + "</span>"
                            }
                        },
                        {
                            field: 'fmgr_approval',
                            title: 'Approved?',
                            editor: customBoolEditor,
                            template: "#= fmgr_approval? 'Yes' : 'No' #",
                            headerAttributes: {
                                "class": "title"
                            }
                        }
                    ],
                },
                {
                    template: "<span class='text-center action-tools row'>" +
                        "<span class='col' title='Edit'><a href='\\#' class='text-black action-edit'><i class='fa fa-pencil'></i></a></span>" +
                        "<span class='col' title='Delete'><a href='\\#' class='text-danger action-delete'><i class='fas fa-trash-alt'></i></a></span>" +
                        "<span class='col' title='More Info'><a href='\\#' class='text-primary action-more-info'><i class='fas fa-info-circle'></i></a></span>" +
                        "</span>",
                    width: "10%",
                    title: "Action"
                },
            ],
            detailTemplate: kendo.template(`
    <div class="">
     Name: #= name #
    </div>
   `),
            dataSource: salaryAdvanceDataSource,
            dataBinding: function () {
                let no = (this.dataSource.page() - 1) * this.dataSource.pageSize();
            },
            dataBound: function (e) {
                let len = $salaryAdvanceManagerGrid.find("tbody tr").length;
                /*for(let i=0;i<len ; i++)
                {
                    let model = grid.data("kendoGrid").dataSource.at(i);
                    if (model && !model.hod_comment_editable) {//field names
                        model.fields["hod_comment"].editable = false;
                    } else {
                        model.fields["hod_comment"].editable = true;
                    }
                }*/
            },
            beforeEdit: function (e) {
                if (e.model.isNew()) {
                    // Disable the editor of the "id" column when editing data items
                    //var numeric = e.container.find("input[name=id]").data("kendoNumericTextBox");
                    //numeric.enable(false);
                    window.grid_uid = e.model.uid;
                    e.model.fields['name'].editable = true;
                    e.model.fields['amount_requested'].editable = true;
                } else {
                    e.model.fields['name'].editable = false;
                    e.model.fields['amount_requested'].editable = !(e.model.hod_approval || e.model.fmgr_approval || e.model.hr_approval);
                }
            },
            edit: function (e) {
                e.container.find('.k-edit-label:not(:eq(3)):not(:eq(0))').hide();
                e.container.find('.k-edit-field:not(:eq(3)):not(:eq(0))').hide();
                e.container.find('.k-edit-label:eq(3) label').text('Amt. Requested');
                e.container.find('.k-edit-field:eq(3) input').attr('data-required-msg', 'Amount Requested is required!');
                e.container.find('.k-edit-label').addClass('pt-2');
                e.container.find('.k-edit-field').addClass('pt-2');
            },
            save: function (e) {
                console.log(('saved'))
                //e.model.employee.name
            }
        });

        $salaryAdvanceManagerGrid.data('kendoGrid').thead.kendoTooltip({
            filter: "th.title",
            position: 'top',
            content: function (e) {
                let target = e.target; // element for which the tooltip is shown
                return $(target).text();
            }
        });
        $salaryAdvanceManagerGrid.on("click", ".action-edit", function () {
            let grid = $salaryAdvanceManagerGrid.data("kendoGrid");
            //let currentRow = grid.currentRow();
            //let dataItem = grid.dataItem(currentRow);
            let row = $(this).closest("tr");
            let $this = $(this);
            let actionTools = $this.closest('.action-tools');
            grid.editRow(row);
            actionTools.html("<span class='col'><a href='#' class='text-success action-confirm-edit'><i class='fa fa-check'></i></a></span>" +
                "<span class='col'><a href='#' class='text-black action-cancel-edit'><i class='k-icon k-i-cancel'></i></a></span>");
        });
        $salaryAdvanceManagerGrid.on("click", ".action-cancel-edit", function () {
            //let row = $(this).closest("tr");
            let $this = $(this);
            let actionTools = $this.closest('.action-tools');
            actionTools.html("<span class='col' title='Edit'><a href='#' class='text-black action-edit'><i class='fa fa-pencil'></i></a></span>" +
                "<span class='col' title='Delete'><a href='#' class='text-danger action-delete'><i class='fas fa-trash-alt'></i></a></span>" +
                "<span class='col' title='More Info'><a href='#' class='text-primary action-more-info'><i class='fas fa-info-circle'></i></a></span>" +
                "</span>");
            $salaryAdvanceManagerGrid.data("kendoGrid").cancelChanges();
        });
        $salaryAdvanceManagerGrid.on("click", ".action-confirm-edit", function () {
            //let row = $(this).closest("tr");
            let $this = $(this);
            let actionTools = $this.closest('.action-tools');
            actionTools.html("<span class='col' title='Edit'><a href='#' class='text-black action-edit'><i class='fa fa-pencil'></i></a></span>" +
                "<span class='col' title='Delete'><a href='#' class='text-danger action-delete'><i class='fas fa-trash-alt'></i></a></span>" +
                "<span class='col' title='More Info'><a href='#' class='text-primary action-more-info'><i class='fas fa-info-circle'></i></a></span>" +
                "</span>");
            $salaryAdvanceManagerGrid.data("kendoGrid").saveChanges();
        });
        $salaryAdvanceManagerGrid.on("click", ".action-delete", function () {
            let row = $(this).closest("tr");
            $salaryAdvanceManagerGrid.data("kendoGrid").removeRow(row);
        });
        $salaryAdvanceManagerGrid.on("click", ".action-more-info", function () {
            let row = $(this).closest("tr");
            row.find('.k-hierarchy-cell>a').click();
        });
    });
</script>
