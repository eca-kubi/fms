/// <reference path='../../assets/ts/kendo.all.d.ts' />
/// <reference path='../../assets/typescript/moment.d.ts' />

//let moment_format = 'DD/MM/YYYY';

let URL_ROOT = '';
let form_submit_count = 0;
let ID_SALARY_ADVANCE = 0;
let lists = [];
let $salaryAdvanceManagerGrid;
let employeeDataSource;
$(document).ready(function () {
    employeeDataSource = new kendo.data.DataSource({
        transport: {
            read: {
                url: URL_ROOT + '/employees-ajax/',
                dataType: "json"
            }
        },
    });
    $salaryAdvanceManagerGrid = $('#salary_advance_manager');
    $salaryAdvanceManagerGrid.on('change', "input[name=employee]", function (e) {
        let select = $(this).data("kendoDropDownList");
    });
    $('.print-it').printPage();
    let NAV_BAR_HEIGHT = $('.navbar-fixed').height();
    $('.content-wrapper').css('margin-top', NAV_BAR_HEIGHT + 'px');
    URL_ROOT = $('#url_root').val();
    //moment.modifyHolidays.add('Ghana');
    checkHODAssignment();
    $('td.not-allowed').on('click', function () {
        let department = $(this).data('department');
        $.toast('You are not  allowed to respond to ' + department + ' Impact Assessment!');
    });

    $('.modal').on('shown.bs.modal', (e) => {
        let $this = $(e.currentTarget);
        $this.find('input[type=text]:first').focus();
    });

    $('.search-button').click(function () {
        let $this = $(this);
        $this.parents('.box').boxWidget('expand');
        setTimeout(() => {
            $this.siblings('ul').find('input').focus();
        }, 200)
    });

    $('form[data-toggle=validator] input[type=text]:not([readonly]):first').focus();

    $(window).resize(function () {
        $('.content-wrapper').css('margin-top', $('.navbar-fixed').height() + 'px');
    });

    $('#add_cms_form').validator().on('submit', () => {
        let hod = $('#hod_id');
        if (hod.length === 0) return;
        if (hod.val() === '') {
            hod.siblings('.help-block').text('A manager must be assigned!');
            return false;
        }
    });

    $('form').submit(function () {
        let form_is_valid = this.checkValidity();
        if (form_is_valid) {
            form_submit_count++;
            if (form_submit_count > 1) {
                return false;
            }
        }
    });
    //=============================================================

    $('.bs-select')
        .selectpicker({
            liveSearch: true,
            virtualization: true,
            showTick: false,
            showContent: false
        })
        .on('loaded.bs.select show.bs.select', function () {
            $('.replace-multiple-select').remove();
            $('.multiple-hidden.bs-select').removeClass('d-none');
            $('.bs-select .dropdown-menu').addClass('p-0 rounded-0');
            $('.bs-select button').addClass('w3-hover-none rounded-0 w3-transparent w3-border');
            $('.bootstrap-select').addClass('exclude-hover');
            $('.bs-select').attr('tabindex', 1);
        });

    // '/cms-forms/add'
    $('#change_type').on('changed.bs.select', function () {
        if ($(this).has('option[value=Other]:selected').length) {
            $('#other_type').removeClass('d-none');
            $('[name=other_type]').attr('required', true);
            $('#add_cms_form').validator('update');
            $('#button_container').removeClass('col-sm-6').addClass('w-100');
        } else {
            $('#other_type').addClass('d-none');
            $('[name=other_type]').removeAttr('required');
            $('#add_cms_form').validator('update');
            $('#button_container').addClass('col-sm-6').removeClass('w-100');
        }
    });

    $('.section').on('shown.bs.collapse', function () {
        $(this).parent().find('.fa').eq(0).removeClass('fa-plus-circle').addClass('fa-minus-circle');
        resizeTables();
        return false;
    })
        .on('hidden.bs.collapse', function () {
            $(this).parent().find('.fa').eq(0).removeClass('fa-minus-circle').addClass('fa-plus-circle');
            return false;
        });

    // '/cms-forms/hod-assesment'
    $('[name=hod_approval]').on('change', function () {
        if ($(this).val() === "approved") {
            $('#hod_ref_num').removeClass('d-none');
            $('.gm.form-group').removeClass('d-none');
            $('[name=hod_ref_num], [name=gm_id]').attr('required', true);
            $('#hod_assessment_form').validator('update');
        } else {
            $('#hod_ref_num').addClass('d-none');
            $('.gm.form-group').addClass('d-none');
            $("[name=hod_ref_num], [name=gm_id]").attr('required', false);
            $('#hod_assessment_form').validator('update');
        }
    });

    // '/cms-forms/risk-assesment'
    //$('.impact_tbl').DataTable({searching: false, paging: false, info: false});

    // auto adjust dt columns
    $(window).resize(function () {
        setTimeout(function () {
            // body...
            resizeTables();
        }, 1000);
        kendo.resize($("#salary_advance_manager").parent());
    });

    // fix column width for tables in collapse
    $('.hide-child').removeClass('show').trigger('hidden.bs.collapse');


    // '/salary-advance-manager-ajax/action-list'
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
        schema: {
            model: {
                id: "id_salary_advance",
                fields: {
                    employee: {
                        editable: false,
                        defaultValue: {name: ''}
                    },
                    date_raised: {
                        type: 'date',
                        editable: false
                    },
                    amount_requested: {
                        type: 'number',
                        validation: { //set validation rules
                            required: true
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
                    }
                }
            }
        }
    });

    $salaryAdvanceManagerGrid.kendoGrid({
        autoFitColumn: true,
        mobile: true,
        noRecords: true,
        navigatable: true,
        toolbar: ["create"],
        editable: 'inline',
        filterable: true,
        columnMenu: true,
        sortable: true,
        groupable: true,
        height: 520,
        resizable: true,
        pageable: {
            alwaysVisible: false,
            pageSizes: [5, 10, 15, 20],
            buttonCount: 5
        },
        columns: [
            {
                field: 'employee',
                title: 'Employee',
                template: "#=employee.name#",
                editor: dropDownEditor,
                width: "15%"
            },
            {
                field: 'department',
                title: 'Department'
            },
            {
                field: 'date_raised',
                title: 'Date Raised',
                width: "15%",
                template: "#= kendo.toString(kendo.parseDate(data.date_raised), 'dddd dd MMM, yyyy') #"
            },
            {
                field: 'amount_requested',
                title: 'Amount Requested',
                template: "#= kendo.toString('GH₵ ' + kendo.format('{0:n}', data.amount_requested)) #",
            },
            {
                title: 'HoD',
                columns: [
                    {
                        field: 'hod_comment',
                        title: 'HoD Comment',
                        hidden: true,
                        editor: textAreaEditor
                    },
                    {
                        field: 'hod_approval',
                        title: 'Status',
                        editor: customBoolEditor,
                        template: "#= hod_approval? 'Approved' : 'Not Approved' #"
                    }
                ],
            },
            {
                title: 'HR',
                columns: [
                    {
                        field: 'hr_comment',
                        title: 'HR Comment',
                        editor: textAreaEditor,
                        hidden: true
                    },
                    {
                        field: 'hr_approval',
                        title: 'Status',
                        editor: customBoolEditor,
                        template: "#= hr_approval? 'Approved' : 'Not Approved' #"
                    }
                ],
            },
            {
                title: 'Finance Mgr',
                columns: [
                    {
                        field: 'fmgr_comment',
                        title: 'FMgr. Comment',
                        hidden: true,
                        editor: textAreaEditor
                    },
                    {
                        field: 'fmgr_approval',
                        title: 'Status',
                        editor: customBoolEditor,
                        template: "#= fmgr_approval? 'Approved' : 'Not Approved' #"
                    }
                ],
            },

            {command: ["edit", "destroy"], title: "&nbsp;", width: "220px"}
        ]
        /*detailTemplate: kendo.template(`
 <div>
     Name: #: employee #
   </div>
   `)*/,
        dataSource: salaryAdvanceDataSource,
        dataBinding: function () {
            //let no = (this.dataSource.page() - 1) * this.dataSource.pageSize();
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
                e.model.fields['employee'].editable = true;
                e.model.fields['hod_comment'].editable = false;
                e.model.fields['hr_comment'].editable = universal.hr_comment_editable;
                e.model.fields['fmgr_comment'].editable = universal.fmgr_comment_editable;
                e.model.fields['hod_approval'].editable = universal.hod_approval_editable;
                e.model.fields['hr_approval'].editable = universal.hr_approval_editable;
                e.model.fields['fmgr_approval'].editable = universal.fmgr_approval_editable;

            } else {
                e.model.fields['employee'].editable = false;
                e.model.fields['hod_comment'].editable = e.model.hod_comment_editable;
                e.model.fields['hr_comment'].editable = e.model.hr_comment_editable;
                e.model.fields['fmgr_comment'].editable = e.model.fmgr_comment_editable;
                e.model.fields['fmgr_approval'].editable = e.model.fmgr_approval_editable;
                e.model.fields['hod_approval'].editable = e.model.hod_approval_editable;
                e.model.fields['hr_approval'].editable = e.model.hr_approval_editable;

            }
        }
    });
    $salaryAdvanceManagerGrid.data('kendoGrid').thead.kendoTooltip({
        filter: "th",
        position: 'top',
        content: function (e) {
            let target = e.target; // element for which the tooltip is shown
            return $(target).text();
        }
    });

    $('.dataTables_length').addClass('d-inline-block mx-3');
    //proxyEmail();
    $('.bs-searchbox >input').attr('data-validate', false);
});

window.addEventListener("load", function () {
    setTimeout(() => {
        $('.content').removeClass('d-none invisible');
        $('footer').removeClass('d-none');
        setTimeout(function () {
            $.unblockUI();
            $(`.blockable`).unblock({message: null});
            $('body').scrollTo('.box', 1000, {offset: -150})
                .scrollTo('#box', 1000, {offset: -150});
        }, 1000);
    }, 500);

    let prevScrollpos = window.pageYOffset;
    $(document).on('click', '.add-input', addFormGroup);
    $(document).on('click', '.remove-input', removeFormGroup);
    $(document).on('show.bs.modal', '#stopProcess', (e) => {
        let href = $(e.relatedTarget).attr('data-href');
        $('#stopProcess .btn-primary').attr('data-href', href);
    });
    $(document).on('click', '#stopProcess .btn-primary', stopChangeProcess);

    window.onscroll = function () {
        let navbar = $('.navbar-fixed');
        let currentScrollPos = window.pageYOffset;
        if (prevScrollpos > currentScrollPos) {
            navbar.prop('style').top = "0";
        } else {
            navbar.prop('style').top = "-60px";
        }
        prevScrollpos = currentScrollPos;
    };
    console.log("All resources finished loading!");
});

function resizeTables() {
    if (typeof $.fn.dataTable === 'undefined') {
        return
    }
    $.fn.dataTable.tables({visible: true, api: true})
        .columns.adjust();
}

/*
function customBoolEditor(container, options) {
    let guid = kendo.guid();
    $('<input class="k-checkbox" id="' + guid + '" type="checkbox" name="completed" data-type="boolean" data-bind="checked:checkedCompleted">').appendTo(container);
    $('<label class="k-checkbox-label" for="' + guid + '">&#8203;</label>').appendTo(container);
    /!*if (options.model.checkedCompleted) {
        options.model.completed = 1;
    } else {
        options.model.completed = 0;
    }
}*/

function initList(id) {
    let options = {
        valueNames: ['title', 'ref_num', 'originator', 'date_raised', 'change_type'],
        searchClass: 'search',
        item: `<div class="col-sm-5 mx-sm-5">
        <dl>
            <dt></dt><dd class="ref_num"></dd>
            <dt></dt><dd class="title"></dd>
            <dt></dt><dd class="originator"></dd>
            <dt></dt><dd class="date_raised"></dd>
            <dt></dt><dd class="change_type"></dd>
            <dt></dt><dd></dd>
        </dl>
        </div>`
    };

    lists['list_' + id] = new List('list_container_' + id, options);
}

function searchList(element) {
    let $this = $(element);
    let list = lists[$this.attr('data-list-id')];
    let keyword = $this.val();
    list.search(keyword);
}

let addFormGroup = function () {
    let $inputGroup = $(this).closest('.input-group');
    let $multipleFormGroup = $inputGroup.closest('.multiple-form-group');
    let $lastInputGroupLast = $multipleFormGroup.find('.input-group:last');

    if ($multipleFormGroup.data('max') <= countFormGroup($multipleFormGroup)) {
        $lastInputGroupLast.find('.add-input').addClass('cursor-disabled');
        $.toast({
            text: 'You can only add up to three files!',
            position: 'top-center',
            stack: false,
            hideAfter: false,
            icon: 'warning',
            showHideTransition: 'plain'
        });
        return;
    }
    let $inputGroupClone = $inputGroup.clone();
    $(this)
        .toggleClass('add-input remove-input')
        .html('<i class="fas fa-minus-square text-danger"></i>');
    $inputGroup.addClass('mb-1');
    $inputGroupClone.find('input').val('');
    $inputGroupClone.insertAfter($inputGroup);
    $('form[data-toggle=validator]').validator('update')

};

let removeFormGroup = function () {
    let $formGroup = $(this).closest('.input-group');
    let $multipleFormGroup = $formGroup.closest('.multiple-form-group');

    let $lastFormGroupLast = $multipleFormGroup.find('.input-group:last');
    if ($multipleFormGroup.data('max') >= countFormGroup($multipleFormGroup)) {
        $lastFormGroupLast.find('.add-input').removeClass('cursor-disabled');
    }
    $formGroup.remove();
    $('form[data-toggle=validator]').validator('update')
};

let countFormGroup = function ($form) {
    return $form.find('.input-group').length;
};

let stopChangeProcess = function () {
    //let redirect = window.location.href;
    window.location.href = $(this).attr('data-href');
};

let checkHODAssignment = function () {
    let hod = $('#hod_id');
    if (hod.length === 0) return;
    if (hod.val() === '') {
        $.toast({
            // heading: 'warning',
            text: 'A Manager must be assigned!',
            position: 'top-center',
            stack: false,
            hideAfter: false,
            icon: 'error',
            showHideTransition: 'plain'
        });
        return false;
    }
};

function dropDownEditor(container, options) {
    $('<input required name="' + options.field + '"/>')
        .appendTo(container)
        .kendoDropDownList({
            dataTextField: "employee.name",
            dataValueField: "employee.user_id",
            dataSource: {
                transport: {
                    read: {
                        url: URL_ROOT + '/employees-ajax/',
                        dataType: "json"
                    }
                }
            },
            filter: "contains",
            suggest: true
            //index: 1
        });
}

function textAreaEditor(container, options) {
    $('<textarea class="k-textbox" name="' + options.field + '" style="width:100%;height:100%;" />').appendTo(container);
}

function customBoolEditor(container, options) {
    let guid = kendo.guid();
    $(`<input class="k-checkbox" id="${guid}" type="checkbox" name="${options.field}" data-bind="checked: ${options.field}" data-type="boolean">`).appendTo(container);
    $(`<label class="k-checkbox-label" for="${guid}">&#8203;</label>`).appendTo(container);
}