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
    let $salaryAdvanceManagerGrid;
    $(document).ready(function () {
        $salaryAdvanceManagerGrid = $('#salary_advance_manager');
        $salaryAdvanceManagerGrid.on('change', "input[name=employee]", function (e) {
            //let select = $(this).data("kendoDropDownList");
        });
        let salaryAdvanceDataSource = new kendo.data.DataSource({
            pageSize: 5,
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
                /* parameterMap: function (options, operation) {
                     if (operation !== "read" && options.models) {
                         return {models: kendo.stringify(options.models)};
                     }
                 }*/
            },
            requestEnd: function (e) {
                if (e.type === 'update' && !e.response[0].success) {
                    toastError('An error occurred!');
                } else if (e.type === 'update' && e.response[0].success) {
                    toastSuccess('Success', 5000);
                }
                if (e.type === 'create' && e.response[0].success) {
                    toastSuccess('Success', 5000);
                }
            },
            schema: {
                model: {
                    id: "id_salary_advance",
                    fields: {
                        name: {
                            editable: false,
                            from: "employee.name"
                        },
                        employee: {
                            defaultValue: {}
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
                            type: 'boolean'
                        },
                        hod_approval: {
                            type: 'boolean'
                        },
                        fmgr_approval: {
                            type: 'boolean'
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

        $salaryAdvanceManagerGrid.kendoGrid({
            autoFitColumn: true,
            selectable: true,
            mobile: true,
            noRecords: true,
            navigatable: true,
            //toolbar: ["create"],
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
                            title: 'Approved by HoD?',
                            editor: customBoolEditor,
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
                            title: 'Approved by HR?',
                            editor: customBoolEditor,
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
                            template: function (dataItem) {
                                return "<span title='" + (dataItem.amount_payable? kendo.toString('GH₵ ' + kendo.format('{0:n}', dataItem.amount_payable)) : 'Pending') + "'>" + (dataItem.amount_payable? kendo.toString('GH₵ ' + kendo.format('{0:n}', dataItem.amount_payable)) : 'Pending') + "</span>"
                            },
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
                            title: 'Finance Mgr. Comment',
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
                            title: 'Approved by FMgr?',
                            editor: customBoolEditor,
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
                            template: function (dataItem) {
                                return "<span title='" + (dataItem.amount_approved? kendo.toString('GH₵ ' + kendo.format('{0:n}', dataItem.amount_approved)) : 'Pending') + "'>" + (dataItem.amount_approved? kendo.toString('GH₵ ' + kendo.format('{0:n}', dataItem.amount_approved)) : 'Pending' ) + "</span>"
                            },
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
        <b>Employee</b>: #= name # </br>
        <b>Date Raised</b>: #= kendo.toString(kendo.parseDate(date_raised), 'dddd dd MMM, yyyy') #</br>
        <b>Amount Requested </b>: #= kendo.toString('GH₵ ' + kendo.format('{0:n}', amount_requested)) #</br>
        <b>Approved by HoD?</b> #= hod_approval? 'Yes' : 'No' #</br>
        #=hod_approval_date? '<b>HoD Approval Date: </b>' + kendo.toString(kendo.parseDate(hod_approval_date), 'dddd dd MMM, yyyy')+'</br>': '' #
        <b>Approved by HR? </b> #= hr_approval? 'Yes' : 'No' # </br>
        #=hr_approval_date? '<b>HR Approval Date: </b>' + kendo.toString(kendo.parseDate(hr_approval_date), 'dddd dd MMM, yyyy')+'</br>': '' #
        <b>Amount Payable </b>: #= amount_payable? kendo.toString('GH₵ ' + kendo.format('{0:n}', amount_payable)) : 'Pending' #</br>
        <b>Approved by Finance Manager? </b> #= fmgr_approval? 'Yes' : 'No' # </br>
        #=fmgr_approval_date? '<b>Finance Mgr. Approval Date: </b>' + kendo.toString(kendo.parseDate(fmgr_approval_date), 'dddd dd MMM, yyyy')+'</br>': '' #
        <b>Amount Approved </b>: #= amount_approved? kendo.toString('GH₵ ' + kendo.format('{0:n}', amount_approved)) : 'Pending' #</br>
        <b>Amount Received </b>: #= amount_received? kendo.toString('GH₵ ' + kendo.format('{0:n}', amount_received)) : 'Pending' #</br>
        #=date_received? '<b>Date Received: </b>' + kendo.toString(kendo.parseDate(date_received), 'dddd dd MMM, yyyy')+'</br>': '' #
        #=received_by? '<b>Received by: </b>' + ''  +'</br>': '' #
    </div>
   `),
            dataSource: salaryAdvanceDataSource,
            dataBinding: function () {
                //let no = (this.dataSource.page() - 1) * this.dataSource.pageSize();
            },
            dataBound: function (e) {
                //let len = $salaryAdvanceManagerGrid.find("tbody tr").length;
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
                if (!e.model.isNew()) {
                    e.model.fields['name'].editable = false;
                    e.model.fields['hod_comment'].editable = e.model['hod_comment_editable'];
                    e.model.fields['hr_comment'].editable = e.model['hr_comment_editable'];
                    e.model.fields['fmgr_comment'].editable = e.model['fmgr_comment_editable'];
                    e.model.fields['fmgr_approval'].editable = !e.model.fmgr_approval; // not editable once approved
                    e.model.fields['hod_approval'].editable = !e.model.hod_approval; // not editable once approved
                    e.model.fields['hr_approval'].editable = !e.model.hr_approval; // not editable once approved
                    e.model.fields['amount_requested'].editable = universal['amount_requested_editable'] && e.model.hr_approval;
                }
            },
            edit: function (e) {
                let textAreaHodComment = e.container.find('.k-edit-field:eq(4) textarea');
                let textAreaHrComment = e.container.find('.k-edit-field:eq(7) textarea');
                let textAreaFmgrComment = e.container.find('.k-edit-field:eq(8) textarea');
                let amountPayable = e.container.find('.k-edit-field:eq(9) input');
                let amountApproved = e.container.find('.k-edit-field:eq(13) input');
                e.container.find('.k-edit-label').addClass('pt-2');
                e.container.find('.k-edit-field').addClass('pt-2');
                e.container.find('.k-edit-field .k-checkbox').parent().removeClass('pt-2');
                if (e.model.fields['hod_comment'].editable) {
                    e.container.find('.k-edit-label:not(:eq(3),:eq(4),:eq(5))').hide();
                    e.container.find('.k-edit-field:not(:eq(3),:eq(4),:eq(5))').hide();
                    textAreaHodComment.attr('required', true).attr('data-required-msg', 'HoD Comment is required!');
                    amountApproved.attr('required', false);
                    amountPayable.attr('required', false);
                } else if (e.model.fields['hr_comment'].editable) {
                    e.container.find('.k-edit-label:not(:eq(0),:eq(3),:eq(7),:eq(8),:eq(9))').hide();
                    e.container.find('.k-edit-field:not(:eq(0),:eq(3),:eq(7),:eq(8),:eq(9))').hide();
                    amountApproved.attr('required', false);
                    textAreaHrComment.attr('required', true).attr('data-required-msg', 'HR Comment is required!');
                    amountPayable.attr('required', true).attr('data-required-msg', 'Amount Payable is required!');
                } else if (e.model.fields['fmgr_comment'].editable) {
                    e.container.find('.k-edit-label:not(:eq(11),:eq(8),:eq(9))').hide();
                    e.container.find('.k-edit-field:not(:eq(11),:eq(8),:eq(9))').hide();
                    amountPayable.attr('required', false);
                    amountApproved.attr('required', true).attr('data-required-msg', 'Amount Approved is required!');
                    textAreaFmgrComment.attr('required', true).attr('data-required-msg', 'Fmgr. Comment is required!');
                }
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
        $salaryAdvanceManagerGrid.on("click", ".action-edit", function (e) {
            let grid = $salaryAdvanceManagerGrid.data("kendoGrid");
            let currentRow = grid.currentRow();
            let dataItem = grid.dataItem(currentRow);
            let errorMsg = '';
            if (dataItem['hod_approval_editable'] || (dataItem['hr_approval_editable'] && dataItem.hod_approval) || (dataItem['fmgr_approval_editable'] && dataItem.hr_approval)) {
                let row = $(this).closest("tr");
                let $this = $(this);
                let actionTools = $this.closest('.action-tools');
                grid.editRow(row);
                actionTools.html("<span class='col'><a href='#' class='text-success action-confirm-edit'><i class='fa fa-check'></i></a></span>" +
                    "<span class='col'><a href='#' class='text-black action-cancel-edit'><i class='k-icon k-i-cancel'></i></a></span>");

            } else {
                if (!dataItem.hod_approval) {
                    errorMsg = 'HoD must approve it first!'
                } else if (!dataItem.hr_approval) {
                    errorMsg = 'HR must approve it first!'
                }
                e.preventDefault();
                $.toast({
                    // heading: '<u>Information</u>',
                    text: '<b class="text-bold"><i class="fa fa-warning text-warning"></i> <span>' + errorMsg + ' </span></b>',
                    //icon: 'warning',
                    loader: false,        // Change it to false to disable loader
                    loaderBg: '#9EC600',  // To change the background
                    position: 'top-center',
                    stack: 1,
                    hideAfter: false
                });
            }
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
