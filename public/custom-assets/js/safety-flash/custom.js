/// <reference path='../../assets/ts/kendo.all.d.ts' />
/// <reference path='../../assets/typescript/moment.d.ts' />

//let moment_format = 'DD/MM/YYYY';

let URL_ROOT = '';
$(document).ready(function () {
    jQuery.fx.off = true;
    URL_ROOT = $('#url_root').val();
    $('.print-it').printPage();
    $('.content-wrapper').css('margin-top', $('.navbar-fixed').height() + 'px');
    $('.modal').on('shown.bs.modal', (e) => {
        let $this = $(e.currentTarget);
        $this.find('input[type=text]:first').focus();
    });

    //$('form[data-toggle=validator] input[type=text]:not([readonly]):first').focus();

    $(window).resize(function () {
        $('.content-wrapper').css('margin-top', $('.navbar-fixed').height() + 'px');
    });

    // fix column width for tables in collapse
    $('.hide-child').removeClass('show').trigger('hidden.bs.collapse');

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

window.addEventListener("load", function () {
    setTimeout(() => {
        $('.content').removeClass('d-none invisible');
        $('footer').removeClass('d-none');
        setTimeout(function () {
            $.unblockUI();
            $('.blockable').unblock({message: null});
            /*$('body').scrollTo('.box', 1000, {offset: -150})
                .scrollTo('#box', 1000, {offset: -150});*/
        }, 1000);
    }, 500);

    let prevScrollpos = window.pageYOffset;

    window.onscroll = function () {
        let navbar = $('.navbar-fixed');
        /*if (prevScrollpos > currentScrollPos) {
            navbar.prop('style').top = "0";
        } else {
            navbar.prop('style').top = "-60px";
        }*/
        prevScrollpos = window.pageYOffset;
    };
    console.log("All resources finished loading!");
});

function dropDownEditor(container, options) {
    $('<input id="employeeDropDownList"  required name="' + options.field + '" data-bind="value:name" data-required-msg="Employee is required!"/>')
        .appendTo(container)
        .on('change', function () {
            console.log('');
            //let option = options;
            //let contain = container;
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
                    }
                },
                group: {field: 'department_short_name'}
            },
            dataBound: function () {
                let kDropDownList = $("#employeeDropDownList").data("kendoDropDownList");
                let data = kDropDownList.dataSource.data();
                //Iterate over the dataItems and search for a value.
                for (let x = 0; x < data.length; x++) {
                    if (data[x].has_active_application) {
                        //removes item
                        kDropDownList.dataSource.remove(data[x]);
                        //selects first item
                        //kDropDownList.select(0);
                    }
                }
            },
            filter: "contains",
            suggest: true,
            select: function (e) {
                //console.log('select')
            },
            change: function (e) {
                let grid = $salaryAdvanceGrid["data"]('kendoGrid');
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
    $('<textarea class="k-textbox" name="' + options.field + '" style="width:100%;height:100%;"  rows="6" required/>').appendTo(container);
}

function customBoolEditor(container, options) {
    let guid = kendo.guid();
    $(`<input class="k-checkbox" id="${guid}" type="checkbox" name="${options.field}" data-bind="checked:${options.field}" data-type="boolean">`).appendTo(container);
    $(`<label class="k-checkbox-label" for="${guid}">&#8203;</label>`).appendTo(container);
}

function approvalEditor(container, options) {
    let guid = kendo.guid();
    let grid = $salaryAdvanceGrid["data"]('kendoGrid');
    let model = grid.dataSource.getByUid(grid_uid);
    let data = [{
        id: true, name: "Approve"
    }, {
        id: false, name: "Reject",
    }];
// Initialize the kendoExtRadioButtonGroup.
    let radioButtonGroup = $(`<div id='approvalRadioButtonGroup_${options.field}'></div>`)
        .appendTo(container)
        .kendoExtRadioButtonGroup({
            dataSource: data,
            dataValueField: "id",
            dataTextField: "name",
            groupName: options.field,
            orientation: "horizontal",
            change: function (e) {
                //console.log("Event: change", kendo.format("id: {0}, value: {1}", e.dataItem.id, e.dataItem.name));
                let row = grid.tbody.find("tr[data-uid='" + grid_uid + "']");
                let dataItem = grid.dataItem(row);
                let value = e.dataItem.id === true;
                let checkedInp = this.element.find('.k-radio:checked');
                dataItem.set(options.field, value);
                /*if (!checkedInp.prop("checked")) {
                    checkedInp.prop('checked',  true);
                }*/
                // grid.refresh();
                // kendoFastReDrawRow(grid, row, dataItem);
            },
            dataBound: function () {
                //console.log("Event: dataBound");
            }
        }).data("kendoExtRadioButtonGroup");

    radioButtonGroup.value(Boolean(model[options.field]));
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

toastSuccess = function f(message, timeout = false) {
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

function kendoFastReDrawRow(grid, row, dItem) {
    let dataItem = dItem ? dItem : grid.dataItem(row);

    let rowChildren = $(row).children('td[role="gridcell"]');

    for (let i = 0; i < grid.columns.length; i++) {

        let column = grid.columns[i];
        let template = column.template;
        let cell = rowChildren.eq(i);

        if (template !== undefined) {
            let kendoTemplate = kendo.template(template);

            // Render using template
            cell.html(kendoTemplate(dataItem));
        } else {
            let fieldValue = dataItem[column.field];

            let format = column.format;
            let values = column.values;

            if (values !== undefined && values != null) {
                // use the text value mappings (for enums)
                for (let j = 0; j < values.length; j++) {
                    let value = values[j];
                    if (value.value === fieldValue) {
                        cell.html(value.text);
                        break;
                    }
                }
            } else if (format !== undefined) {
                // use the format
                cell.html(kendo.format(format, fieldValue));
            } else {
                // Just dump the plain old value
                cell.html(fieldValue);
            }
        }
    }
}