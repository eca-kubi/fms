/// <reference path='../../assets/ts/kendo.all.d.ts' />
/// <reference path='../../assets/typescript/moment.d.ts' />
let URL_ROOT = '';
let lists = [];
let employeeDataSource;
let currentRowSelected = false;
let firstLoadDone = false;
let pageWithRowSelected = -1;
let expandedRows = [];
let firstDayOfMonth = moment().startOf('month').format('YYYY-MM-DD');
let lastDayOfMonth = moment().endOf('month').format('YYYY-MM-DD');
let kDefaultCalendar;
let activeApplicants = [];
$(document).ready(function () {
    jQuery.fx.off = true;
    URL_ROOT = $('#url_root').val();
    employeeDataSource = new kendo.data.DataSource({
        transport: {
            read: {
                url: URL_ROOT + '/employees-ajax/',
                dataType: "json"
            }
        },
    });
    $('.print-it').printPage();
    $('.content-wrapper').css('margin-top', $('.navbar-fixed').height() + 'px');
    $('.modal').on('shown.bs.modal', (e) => {
        let $this = $(e.currentTarget);
        $this.find('input[type=text]:first').focus();
    });
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
    };

    kendo.ui.DropDownList.fn["disableItem"] = function () {

    };
    kDefaultCalendar = $("<input id='kDefaultCalendar' class='k-default-calendar' type='date'>").kendoCalendar();
});

window.addEventListener("load", function () {
    setTimeout(() => {
        $('.content').removeClass('d-none invisible');
        $('footer').removeClass('d-none');
        setTimeout(function () {
            $.unblockUI();
            $('.blockable').unblock({message: null});
        }, 1000);
    }, 500);
    console.log("All resources finished loading!");
});

function dropDownEditor(container, options) {
    $('<input id="employeeDropDownList"  required name="' + options.field + '" data-bind="value:name" data-bind="text:name" data-required-msg="Please select an employee!"/>')
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
                },
                group: {field: 'department_short_name'}
            },
            dataBound: function (e) {
                let model = grid.dataSource.getByUid(grid_uid);
                e.sender.select(function (dataItem) {
                    return dataItem.name === model.name;
                });
                this.options.disableItems(e.sender);
            },
            select: function (e) {
                if (e.item.hasClass("k-state-disabled")) {
                    e.preventDefault();
                }
            },
            open: function (e) {
                this.options.disableItems(e.sender);
            },
            change: function (e) {
                let model = grid.dataSource.getByUid(grid_uid);
                if (e.sender.selectedIndex) {
                    let data = e.sender.dataSource.at(e.sender.selectedIndex - 1);
                    model.user_id = data.user_id;
                    model.department = data.department;
                    model.department_id = data.department_id;
                    model.basic_salary = data.basic_salary;
                }
                grid.refresh();
                grid.editCell(grid.table.find("td:eq(2)"))
            },
            filter: "contains",
            suggest: true,
            optionLabel: "Select an Employee",
            disableItems: function (dropDown) {
                let model = grid.dataSource.getByUid(grid_uid);
                dropDown.ul.find("li").each(function () {
                    let li = $(this);
                    if (bulkApplicants.includes(li.text()) && model.name !== li.text() || activeApplicants.includes(li.text())) {
                        li.addClass("k-state-disabled cursor-disabled");
                        li.attr("title", "This employee has already been selected or has an active application!");
                    } else {
                        li.removeClass("k-state-disabled cursor-disabled");
                        li.removeAttr("title");
                    }
                });
            }
        });
    container.append('<span class="k-invalid-msg" data-for="name"></span>')
}

function textAreaEditor(container, options) {
    $('<textarea class="k-textbox" name="' + options.field + '" style="width:100%;height:100%;"  rows="3" required/>').appendTo(container);
}

function customBoolEditor(container, options) {
    let guid = kendo.guid();
    $(`<input class="k-checkbox" id="${guid}" type="checkbox" name="${options.field}" data-bind="checked:${options.field}" data-type="boolean">`).appendTo(container);
    $(`<label class="k-checkbox-label" for="${guid}">&#8203;</label>`).appendTo(container);
}

function approvalEditor(container, options) {
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
                let dataItem = grid.dataItem(grid.element.find(`tr[data-uid=${window.grid_uid}]`));
                let value = e.dataItem.id === true;
                dataItem.set(options.field, value);
                e.sender.element.data("kendoTooltip").hide();
            },
        }).data("kendoExtRadioButtonGroup");
    $(`#approvalRadioButtonGroup_${options.field}`).kendoTooltip({
        content: "<span><i class='k-icon k-i-info'></i> This field is required! </span>",
        showOn: "",
        animation: {
            open: {
                effects: "none"
            },
            close: {
                effects: "none"
            }
        },
        autoHide: false,
        hide: function () {
            const tooltip = this;
            if (radioButtonGroup.value() == null) {
                setTimeout(function () {
                    tooltip.show();
                }, 2);
            }
        },
        show: function () {
            this.popup.wrapper.find('.k-tooltip-button').hide();
        }
    });
    if (model[options.field] == null) {
        radioButtonGroup.value(null);
    } else {
        radioButtonGroup.value(Boolean(model[options.field]));
    }
}

toastError = function f(message) {
    $.toast({
// heading: '<u>Information</u>',
        text: `<b class="text-bold row"><i class="fa fa-warning text-warning m-2"></i> <span class="text-danger">${message}</span></b>`,
//icon: 'warning',
        loader: false,        // Change it to false to disable loader
        loaderBg: '#9EC600',  // To change the background
        position: 'top-center',
        stack: 1,
        hideAfter: false,
        beforeShow: function (element) {
            element.addClass("bg-white card")
        }
    });
};

toastSuccess = function f(message, timeout = false) {
    $.toast({
// heading: '<u>Information</u>',
        text: `<b class="text-bold text-danger"><i class="fa fa-check text-success"></i> <span class="text-success">${message}</span></b>`,
//icon: 'warning',
        loader: false,        // Change it to false to disable loader
        loaderBg: '#9EC600',  // To change the background
        position: 'top-center',
        stack: 1,
        hideAfter: timeout,
        beforeShow: function (element) {
            element.addClass("bg-white card")
        }
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

/*function kendoFastReDrawRow(grid, row, dItem) {
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
}*/

function filterDate(startDate, endDate, field) {
    let grid = $salaryAdvanceGrid.data("kendoGrid");
    let filter = {logic: "and", filters: grid.dataSource.filter() ? grid.dataSource.filter().filters : []};
    for (let i = 0; i < filter.filters.length; i++) {
        let filterObj = filter.filters[i];
        if (filterObj.field === field) {
            filter.filters.splice(i, 1);
            i--;
        }
    }
    filter.filters.push({field: field, operator: "gte", value: startDate});
    filter.filters.push({field: field, operator: "lte", value: endDate});
    grid.dataSource.filter(filter);
}

function filterString(value, field) {
    let grid = $salaryAdvanceGrid.getKendoGrid();
    let filter = {logic: "and", filters: grid.dataSource.filter() ? grid.dataSource.filter().filters : []};
    filter.filters.push({field: field, operator: "contains", value: value});
    grid.dataSource.filter(filter);
}

/*let triggerDateFilterEvent = function (filter, field) {
let grid = $salaryAdvanceGrid.data("kendoGrid");
let filterEvent = $.Event('filter');
filterEvent.field = field;
filterEvent.filter = filter;
filterEvent.sender = grid;
grid.trigger('filter', filterEvent);
};*/

function dateRangeFilter(args) {
    let filterCell = args.element.parents(".k-filtercell");
    let field = filterCell.attr('data-field');
    let dataSource = $salaryAdvanceGrid.data("kendoGrid").dataSource;
    let grid = $salaryAdvanceGrid.data("kendoGrid");
    let defaultCalendar = kDefaultCalendar.data("kendoCalendar");

    let resetDatePickers = function (dateInputs) {
        dateInputs.each(function () {
            let datePicker = $(this).data("kendoDatePicker");
            datePicker.min(defaultCalendar.min());
            datePicker.max(defaultCalendar.max());
            datePicker.value(null);
            $(this).data("kendoDateInput").value(null);
        });
    };

    let clearDateFilter = function () {
        let filters = dataSource.filter() ? dataSource.filter().filters : [];
        for (let i = 0; i < filters.length; i++) {
            let filterObj = filters[i];
            if (filterObj.field === field) {
                filters.splice(i, 1);
                i--;
            }
        }
    };
    filterCell.empty();
    filterCell.html('<span class="pr-5" style="display:flex; justify-content:center;"><span>From:</span><input  class="start-date" /><span>To:</span><input  class="end-date"/> <button type="button" class="k-button k-button-icon d-none" title="Clear" aria-label="Clear"  style=""><span class="k-icon k-i-filter-clear"></span></button></span>');
    let kClearButton = filterCell.find(".k-button[title=Clear]").attr("id", `${field}_ClearButton`);
    kClearButton.on("click", function () {
        let dateInputs = $(this).siblings('.k-datepicker').find('.k-input');
        clearDateFilter();
        let filter = {logic: "and", filters: grid.dataSource.filter() ? grid.dataSource.filter().filters : []};
        if (filter.filters.length === 0) {
            grid.dataSource.filter([]);
        } else {
            grid.dataSource.filter(filter);
        }
        resetDatePickers(dateInputs);
        $(this).addClass("d-none");
    });

    $(".start-date", filterCell).kendoDatePicker({
        change: function (e) {
            let startDate = e.sender.value(),
                endDate = $("input.end-date", filterCell).data("kendoDatePicker").value();
            if (startDate == null) {
                $("input.end-date", filterCell).data("kendoDatePicker").min(kDefaultCalendar.data("kendoCalendar").min());
            } else {
                $("input.end-date", filterCell).data('kendoDatePicker').min($("input.start-date", filterCell).data('kendoDatePicker').value());
            }
            if (startDate && endDate) {
                filterDate(startDate, endDate, field);
                $("#" + field + "_ClearButton").removeClass("d-none");
            }
        },
        dateInput: true
    });

    $(".end-date", filterCell).kendoDatePicker({
        change: function (e) {
            let startDate = $("input.start-date", filterCell).data("kendoDatePicker").value(),
                endDate = e.sender.value();
            if (endDate == null) {
                $("input.start-date", filterCell).data("kendoDatePicker").max(kDefaultCalendar.data("kendoCalendar").max());
            } else {
                $("input.start-date", filterCell).data('kendoDatePicker').max($("input.end-date", filterCell).data('kendoDatePicker').value());
            }
            if (startDate && endDate) {
                filterDate(startDate, endDate, field);
                $("#" + field + "_ClearButton").removeClass("d-none");
            }
        },
        dateInput: true
    });
}

function selectGridRow(searchedId, grid, dataSource, idField) {
    let filters = dataSource.filter() || {};
    let sort = dataSource.sort() || {};
    let models = dataSource.data();
// We are using a Query object to get a sorted and filtered representation of the data, without paging applied, so we can search for the row on all pages
    let query = new kendo.data.Query(models);
    let rowNum = 0;
    let modelToSelect = null;

    models = query.filter(filters).sort(sort).data;
    if (models.length <= 0) return;
// Now that we have an accurate representation of data, let's get the item position
    for (let i = 0; i < models.length; ++i) {
        const model = models[i];
        if (model[idField] === searchedId) {
            modelToSelect = model;
            rowNum = i;
            break;
        }
    }
    if (!modelToSelect) return; // The row was not found in the current table model
// If you have persistSelection = true and want to clear all existing selections first, uncomment the next line
// grid._selectedIds = {};

// Now go to the page holding the record and select the row
    let currentPageSize = dataSource.pageSize();
    let pageWithRow = pageWithRowSelected = parseInt((rowNum / currentPageSize)) + 1; // pages are one-based
    if (!currentRowSelected) {
        currentRowSelected = true;
        dataSource.page(pageWithRow);
    }
    const row = grid.element.find("tr[data-uid='" + modelToSelect.uid + "']");
    if (row.length > 0) {
        grid.select(row);

// Scroll to the item to ensure it is visible
        //grid.content.scrollTop(grid.select().position().top);
    }
    return row;
}

function onDataBound() {

    let items = expandedRows['expanded'];
    const grid = this;
    if (items) {
        items = JSON.parse(items);
        items.forEach(function (x) {
            const item = grid.dataSource.data().find(function (y) {
                return y.request_number === x;
            });

            if (item) {
                const row = $('#' + grid.element.attr('id') + ' tr[data-request-number="' + item.request_number + '"]');
                grid.expandRow(row);
            }
        })
    }
}

function onDetailExpand(e) {
    const item = this.dataItem(e.masterRow);

    let items = expandedRows['expanded'];

    if (items) {
        items = JSON.parse(items);

    } else {
        items = [];
    }
if ($.inArray(item.request_number, items) < 0)
    items.push(item.request_number);
    expandedRows['expanded'] = JSON.stringify(items);
}

function onDetailCollapse(e) {
    let item = this.dataItem(e.masterRow);
    let items = JSON.parse(expandedRows['expanded']);

    items = items.filter(function (x) {
        return x !== item.request_number;
    });

    expandedRows['expanded'] = JSON.stringify(items);
}

function editNumberWithoutSpinners(container, options) {
    $('<input name="' + options.field + '" data-text-field="' + options.field + '" ' +
        'data-value-field="' + options.field + '" ' +
        'data-bind="value:' + options.field + '" ' +
        'data-format="' + options.format + '"/>')
        .appendTo(container)
        .kendoNumericTextBox({
            spinners: false,
            min: 0
        });
}

function refreshGrid() {
    let grid = $salaryAdvanceGrid.data("kendoGrid");
    let expanded = $.map(grid.tbody.children(":has(> .k-hierarchy-cell .k-i-collapse)"), function (row) {
        return $(row).data("uid");
    });

    grid.one("dataBound", function () {
        grid.expandRow(grid.tbody.children().filter(function (idx, row) {
            return $.inArray($(row).data("uid"), expanded) >= 0;
        }));
    });
    grid.refresh();
}

function purgeBulkApplicants() {
// determine names not in the model and remove them from the list of bulk applicants
    let data = grid.dataSource.data();
    for (let i = 0; i < bulkApplicants.length; i++) {
        let found = false;
        for (let j = 0; j < data.length; j++) {
            let model = data[j];
            if (bulkApplicants[i] === model.name) {
                found = true;
                break;
            }
        }
        if (!found) {
            bulkApplicants.splice(i, 1);
            i--;
        }
    }
}

let Configurations = {
    validations: {
        minMaxAmount: {
            required: function (input) {
                if ($.inArray(input.attr("name"), Configurations.validations.minMaxAmountInputs) > -1) {
                    input.attr("data-required-msg", "Enter an amount.");
                    return input.val() !== "";
                } else if (input.is("[name]")) {
                    input.attr("data-required-msg", "This field is required!");
                    return input.val() !== "";
                }
                return true;
            },
            min: function (input) {
                if ($.inArray(input.attr("name"), Configurations.validations.minMaxAmountInputs) > -1) {
                    let grid = $salaryAdvanceGrid.getKendoGrid();
                    let model = grid.dataSource.getByUid(grid_uid);
                    input.attr("data-min-msg", "Amount must be more than 10% of salary.");
                    return (MIN_PERCENTAGE / 100) * model.basic_salary <= kendo.parseFloat(input.val());
                }
                return true;
            },
            max: function (input) {
                if ($.inArray(input.attr("name"), Configurations.validations.minMaxAmountInputs) > -1) {
                    let grid = $salaryAdvanceGrid.getKendoGrid();
                    let model = grid.dataSource.getByUid(grid_uid);
                    input.attr("data-max-msg", "Amount must not exceed 30% of salary.");
                    return (MAX_PERCENTAGE / 100) * model.basic_salary >= kendo.parseFloat(input.val());
                }
                return true;
            }
        },
        minMaxAmountInputs: ["amount_requested", "amount_approved", "amount_payable"]
    }
};

function rowGroupKey(row, grid) {
    let next = row.nextUntil("[data-uid]").next(),
        item = grid.dataItem(next.length ? next : row.next()),
        groupIdx = row.children(".k-group-cell").length,
        groups = grid.dataSource.group(),
        field = grid.dataSource.group()[groupIdx].field,
        groupValue = item[field];
    return "" + groupIdx + groupValue;
}

function findFromDataSource(field, value) {
    return grid.dataSource.data().find(function (y) {
        return y[field] === value;
    });
}