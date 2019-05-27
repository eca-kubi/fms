/// <reference path='../../assets/ts/kendo.all.d.ts' />
/// <reference path='../../assets/typescript/moment.d.ts' />

//let moment_format = 'DD/MM/YYYY';

let URL_ROOT = '';
let lists = [];
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
    //$('.print-it').printPage();
    $('.content-wrapper').css('margin-top', $('.navbar-fixed').height() + 'px');
    URL_ROOT = $('#url_root').val();

    $('.modal').on('shown.bs.modal', (e) => {
        let $this = $(e.currentTarget);
        $this.find('input[type=text]:first').focus();
    });

    //$('form[data-toggle=validator] input[type=text]:not([readonly]):first').focus();

    $(window).resize(function () {
        $('.content-wrapper').css('margin-top', $('.navbar-fixed').height() + 'px');
        //kendo.resize($("#salary_advance_manager").parent());
    });

    // fix column width for tables in collapse
    $('.hide-child').removeClass('show').trigger('hidden.bs.collapse');

    kendo.ui.Grid.fn.currentRow = function () {
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

window.addEventListener("load", function () {
    setTimeout(() => {
        $('.content').removeClass('d-none invisible');
        $('footer').removeClass('d-none');
        setTimeout(function () {
            $.unblockUI();
            $('.blockable').unblock({message: null});
            $('body').scrollTo('.box', 1000, {offset: -150})
                .scrollTo('#box', 1000, {offset: -150});
        }, 1000);
    }, 500);

    let prevScrollpos = window.pageYOffset;

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

function dropDownEditor(container, options) {
    $('<input  required name="' + options.field + '" data-bind="value:name" data-required-msg="Employee is required!"/>')
        .appendTo(container)
        .on('change', function (e) {
            console.log('');
            let option = options;
            let contain = container;
            //options.model.department = options.model.employee.department;
            // options.model.name = options.model.employee.name;
        })
        .kendoDropDownList({
            dataTextField: "employee.name",
            dataValueField: "employee.user_id",
            dataSource: {
                transport: {
                    read: {
                        url: URL_ROOT + '/employees-ajax/',
                        dataType: "json"
                    },
                }
            },
            filter: "contains",
            suggest: true,
            select: function (e) {
                //console.log('select')
            },
            change: function (e) {
                let grid = $salaryAdvanceManagerGrid.data('kendoGrid');
                let model = grid.dataSource.getByUid(grid_uid);
                let selectedIndex = e.sender.selectedIndex;
                if (selectedIndex) {
                    let data = e.sender.dataSource.at(selectedIndex - 1);
                    model.user_id = data.user_id;
                    model.department = data.department;
                    model.department_id = data.department_id;
                }
                //grid.refresh();
                //console.log('change');
            },
            close: function (e) {
                //console.log('close')
            },
            optionLabel: "Select an Employee",
            //index: 1
        });
}

function textAreaEditor(container, options) {
    $('<textarea class="k-textbox" name="' + options.field + '" style="width:100%;height:100%;"  required/>').appendTo(container);
}

function customBoolEditor(container, options) {
    let guid = kendo.guid();
    $(`<input class="k-checkbox" id="${guid}" type="checkbox" name="${options.field}" data-bind="checked: ${options.field}" data-type="boolean">`).appendTo(container);
    $(`<label class="k-checkbox-label" for="${guid}">&#8203;</label>`).appendTo(container);
}

toastError = function f(message) {
    $.toast({
        // heading: '<u>Information</u>',
        text: `<b class="text-bold"><i class="fa fa-warning text-warning"></i> <span>${message}</span></b>`,
        //icon: 'warning',
        loader: false,        // Change it to false to disable loader
        loaderBg: '#9EC600',  // To change the background
        position: 'top-center',
        stack: 1,
        hideAfter: false
    });
};

toastSuccess = function f(message, timeout=false) {
    $.toast({
        // heading: '<u>Information</u>',
        text: `<b class="text-bold"><i class="fa fa-check text-success"></i> <span>${message}</span></b>`,
        //icon: 'warning',
        loader: false,        // Change it to false to disable loader
        loaderBg: '#9EC600',  // To change the background
        position: 'top-center',
        stack: 1,
        hideAfter: timeout,
    });
};

function departmentFilter(element) {
    element.kendoDropDownList({
        dataSource: new kendo.data.DataSource({
            transport: {
                read: {
                    url: URL_ROOT + '/departments-ajax/',
                    dataType: "json"
                }
            }
        }),
        filter: "contains",
        optionLabel: "Select Department"
    });
}

function parseHtml(s) {
    return (new DOMParser()).parseFromString(s, 'text/html').body.innerHTML;
}