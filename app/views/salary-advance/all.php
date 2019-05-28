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
<?php
$universal = new stdClass();
$universal->hr_comment_editable = getCurrentHR() == $current_user->user_id;
$universal->fgmr_comment_editable = $universal->amount_requested_editable = getCurrentFgmr() == $current_user->user_id;
?>
<!--suppress HtmlUnknownTarget -->
<script>
    let universal = JSON.parse(`<?php echo json_encode($universal); ?>`);
    let $salaryAdvanceGrid;
    $(document).ready(function () {
        $salaryAdvanceGrid = $("#salary_advance");
        $salaryAdvanceGrid.on('change', "input[name=employee]", function (e) {
            //let select = $(this).data("kendoDropDownList");
        });
        // '/salary-advance-ajax/'
        let salaryAdvanceDataSource = new kendo.data.DataSource({
            pageSize: 5,
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
                /* parameterMap: function (options, operation) {
                     if (operation !== "read" && options.models) {
                         return {models: kendo.stringify(options.models)};
                     }
                 }*/
            },
            requestEnd: function (e) {
                if (e.type === 'update' && !e.response[0].success) {
                    e.response[0].reason ? toastError(e.response[0].reason) : toastError('An error occurred!');
                } else if (e.type === 'update' && e.response[0].success) {
                    toastSuccess('Success', 5000);
                }
                if (e.type === 'create' && e.response[0].success) {
                    toastSuccess('Success', 5000);
                }
                if (e.type === 'destroy' && e.response[0].success) {
                    toastSuccess('Success', 5000);
                } else if (e.type === 'destroy' && !e.response[0].success) {
                    e.response[0].reason ? toastError(e.response[0].reason) : toastError('An error occurred!');
                    salaryAdvanceDataSource.cancelChanges();
                }
            },
            schema: {
                model: {
                    id: "id_salary_advance",
                    fields: {
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
                        amount_payable: {
                            type: 'number',
                            validation: { //set validation rules
                                required: true,
                                min: '0'
                            }
                        },
                        amount_approved: {
                            type: 'number',
                            validation: { //set validation rules
                                required: true,
                                min: '0'
                            }
                        },
                        received_by: {
                            type: 'string'
                        },
                        fmgr_approval_date: {
                            type: 'date'
                        },
                        hr_approval_date: {
                            type: 'date'
                        },
                        date_received: {
                            type: 'date'
                        },
                        department_ref: {},
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
                    field: 'date_raised',
                    title: 'Date Raised',
                    width: "14%",
                    template: "#= kendo.toString(kendo.parseDate(date_raised), 'dddd dd MMM, yyyy') #",
                    headerAttributes: {
                        "class": "title"
                    },
                    groupHeaderTemplate: "Date Raised: #= kendo.toString(kendo.parseDate(value), 'dddd dd MMM, yyyy h:mm:ss tt') #"
                },
                {
                    field: 'amount_requested',
                    title: 'Amount Requested',
                    width: "12%",
                    template: "#= kendo.toString('GH₵ ' + kendo.format('{0:n}', amount_requested)) #",
                    headerAttributes: {
                        "class": "title"
                    },
                    groupHeaderTemplate: "Amount Requested: #= kendo.toString('GH₵ ' + kendo.format('{0:n}', value)) #",
                    aggregates: ["max", "min"]
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
                            //editor: textAreaEditor,
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
                            title: 'HoD Approved?',
                            //editor: customBoolEditor,
                            template: "#= hod_approval? 'Yes' : 'No' #",
                            headerAttributes: {
                                "class": "title"
                            },
                            groupHeaderTemplate: "HoD Approved: #= value? 'Yes' : 'No' # | Total: #= count #",
                            aggregates: ["count"]

                        },
                        {
                            field: 'hod_approval_date',
                            title: 'HoD. Approval Date ',
                            headerAttributes: {
                                "class": "title"
                            },
                            hidden: true
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
                            //editor: textAreaEditor,
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
                            title: 'HR Approved?',
                            //editor: customBoolEditor,
                            template: "#= hr_approval? 'Yes' : 'No' #",
                            headerAttributes: {
                                "class": "title"
                            },
                            groupHeaderTemplate: "HR Approved: #= value? 'Yes' : 'No' # |  Total: #= count #",
                            aggregates: ["count"]
                        },
                        {
                            field: 'amount_payable',
                            title: 'Amount Payable',
                            template: "#= amount_payable? amount_payable : 'Pending' #",
                            headerAttributes: {
                                "class": "title"
                            },
                            groupHeaderTemplate: "Amount Payable: #= value? kendo.toString('GH₵ ' + kendo.format('{0:n}', value)) : 'Pending' #",
                            aggregates: ["max", "min"]
                        },
                        {
                            field: 'hr_approval_date',
                            title: 'HR. Approval Date ',
                            headerAttributes: {
                                "class": "title"
                            },
                            hidden: true
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
                            title: 'Finance Manager Comment',
                            hidden: false,
                            //editor: textAreaEditor,
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
                            title: 'Finance Manager Approved?',
                            //editor: customBoolEditor,
                            template: "#= fmgr_approval? 'Yes' : 'No' #",
                            headerAttributes: {
                                "class": "title"
                            },
                            groupHeaderTemplate: "Finance Manager Approved: #= value? 'Yes' : 'No' # |  Total: #=count #",
                            aggregates: ["count"]

                        },
                        {
                            field: 'amount_approved',
                            title: 'Amount Approved',
                            template: "#= amount_approved? amount_approved: 'Pending' #",
                            headerAttributes: {
                                "class": "title"
                            },
                            groupHeaderTemplate: "Amount Approved: #= value? kendo.toString('GH₵ ' + kendo.format('{0:n}', value)): 'Pending' #",
                            aggregates: ["max", "min"]
                        },
                        {
                            field: 'fmgr_approval_date',
                            title: 'Finance Mgr. Approval Date ',
                            headerAttributes: {
                                "class": "title"
                            },
                            hidden: true
                        }
                    ],
                },
                {
                    template: "<span class='text-center action-tools row'>" +
                        "<span class='col' title='Edit'><a href='\\#' class='text-black action-edit'><i class='fa fa-pencil'></i></a></span>" + "<span class='col' title='Delete'><a href='\\#' class='text-danger action-delete'><i class='fas fa-trash-alt'></i></a></span><span class='col' title='More Info'><a href='\\#' class='text-primary action-more-info'><i class='fas fa-info-circle'></i></a></span>" +
                        "</span>",
                    width: "10%",
                    title: "Action"
                },
            ],
            detailTemplate: kendo.template(`
    <div class="">
        <b>Date Raised</b>: #= kendo.toString(kendo.parseDate(date_raised), 'dddd dd MMM, yyyy') #</br>
        <b>Amount Requested </b>: #= kendo.toString('GH₵ ' + kendo.format('{0:n}', amount_requested)) #</br>
        <b>Approved by HoD?</b> #= hod_approval? 'Yes' : 'No' #</br>
        #=hod_approval_date? '<b>HoD Approval Date: </b>' + kendo.toString(kendo.parseDate(hod_approval_date), 'dddd dd MMM, yyyy')+'</br>': '' #
        <b>Approved by HR? </b> #= hr_approval? 'Yes' : 'No' # </br>
        #=hr_approval_date? '<b>HR Approval Date: </b>' + kendo.toString(kendo.parseDate(hr_approval_date), 'dddd dd MMM, yyyy')+'</br>': '' #
        <b>Amount Approved </b>: #= amount_approved? kendo.toString('GH₵ ' + kendo.format('{0:n}', amount_approved)) : 'Pending' #</br>
        <b>Approved by Finance Manager? </b> #= fmgr_approval? 'Yes' : 'No' # </br>
        #=fmgr_approval_date? '<b>Finance Mgr. Approval Date: </b>' + kendo.toString(kendo.parseDate(fmgr_approval_date), 'dddd dd MMM, yyyy')+'</br>': '' #
        <b>Amount Payable </b>: #= amount_payable? kendo.toString('GH₵ ' + kendo.format('{0:n}', amount_payable)) : 'Pending' #</br>
        <b>Amount Received </b>: #= amount_received? kendo.toString('GH₵ ' + kendo.format('{0:n}', amount_received)) : 'Pending' #</br>
    </div>
   `),
            dataSource: salaryAdvanceDataSource,
            dataBinding: function () {
                //let no = (this.dataSource.page() - 1) * this.dataSource.pageSize();
            },
            dataBound: function (e) {
                //let len = $salaryAdvanceGrid.find("tbody tr").length;
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
                    e.model.fields['amount_requested'].editable = true;
                } else {
                    e.model.fields['amount_requested'].editable = !(e.model.hod_approval || e.model.fmgr_approval || e.model.hr_approval);
                }
            },
            edit: function (e) {
                e.container.find('.k-edit-label:not(:eq(1))').hide();
                e.container.find('.k-edit-field:not(:eq(1))').hide();
                e.container.find('.k-edit-label:eq(1) label').text('Amt. Requested');
                e.container.find('.k-edit-field:eq(1) input').attr('data-required-msg', 'Amount Requested is required!');
                e.container.find('.k-edit-label').addClass('pt-2');
                e.container.find('.k-edit-field').addClass('pt-2');
            }
        });

        $salaryAdvanceGrid.data('kendoGrid').thead.kendoTooltip({
            filter: "th.title",
            position: 'top',
            content: function (e) {
                let target = e.target; // element for which the tooltip is shown
                return $(target).text();
            }
        });
        $salaryAdvanceGrid.on("click", ".action-edit", function () {
            let grid = $salaryAdvanceGrid.data("kendoGrid");
            //let currentRow = grid.currentRow();
            //let dataItem = grid.dataItem(currentRow);
            let row = $(this).closest("tr");
            let $this = $(this);
            let actionTools = $this.closest('.action-tools');
            grid.editRow(row);
            actionTools.html("<span class='col'><a href='#' class='text-success action-confirm-edit'><i class='fa fa-check'></i></a></span>" +
                "<span class='col'><a href='#' class='text-black action-cancel-edit'><i class='k-icon k-i-cancel'></i></a></span>");
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
        }
    });
</script>