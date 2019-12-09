// 
let URL_ROOT = "";
let firstLoadDone = false;
let collapsed = {},
    groups = [],
    scrollLeft = 0,
    scrollTop = 0;
let expandedRows = [];
let activeApplicants = [];
let kDefaultCalendar;
let commentTooltip;
let selectedRowId;
let grid;
let $salaryAdvanceGrid;
let dataSource;
const GHS_SYMBOL = "₵";
let customGH = $.extend(true, {}, kendo.culture(), {
    name: "custom-GH",
    numberFormat: {
        currency: {
            symbol: GHS_SYMBOL,
            name: "GH Cedi",
            abbr: "GHS"
        }
    }
});

//add a reference to the custom culture script
kendo.cultures["custom-GH"] = customGH;
$(function () {
    jQuery.fx.off = true;
    URL_ROOT = $('#url_root').val();
    $('.print-it').printPage();
    $('.content-wrapper').css('margin-top', $('.navbar-fixed').height() + 'px');
    $(window).on("resize", function () {
        $('.content-wrapper').css('margin-top', $('.navbar-fixed').height() + 'px');
    });
// fix column width for tables in collapse
    $('.hide-child').removeClass('show').trigger('hidden.bs.collapse');
    kDefaultCalendar = $("<input id='kDefaultCalendar' class='k-default-calendar' type='date'>").kendoCalendar();
});

kendo.ui.Grid.fn["currentRow"] = function () {
//this will only work if grid is navigatable
    let cell = this.current();
    if (cell) {
        return cell.closest('tr');
    }
//following will only work if grid is selectable, it will get the 1st row only for multiple selection
    if (this.options.selectable !== false)
        return this.select();
    return null;
};

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
    container.append('<span class="k-invalid-msg" data-for="' + options.field + '"></span>')
}

function employeesEditor(container, options) {
    $('<input id="employees"  required name="' + options.field + '" data-bind="value:' + options.field + '" data-bind="text:' + options.field + '" data-required-msg="Please select an employee!"/>')
        .appendTo(container)
        .kendoAutoComplete({
            dataTextField: "name",
            dataSource: {
                transport: {
                    read: {
                        url: URL_ROOT + '/employees-ajax/employees',
                        dataType: "json"
                    }
                },
                group: {field: 'department_short_name'}
            },
            filter: "contains",
            suggest: true,
            ignoreCase: true,
        });
    container.append('<span class="k-invalid-msg" data-for="' + options.field + '"></span>')
}

function textAreaEditor(container, options) {
    $('<textarea class="k-textbox" name="' + options.field + '" style="width:100%;height:100%;"  rows="3" required/>').appendTo(container);
}

/*function customBoolEditor(container, options) {
    let guid = kendo.guid();
    $(`<input class="k-checkbox" id="${guid}" type="checkbox" name="${options.field}" data-bind="checked:${options.field}" data-type="boolean">`).appendTo(container);
    $(`<label class="k-checkbox-label" for="${guid}">&#8203;</label>`).appendTo(container);
}*/

function approvalEditor(container, options) {
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
                let editorContainer = e.sender.element.parents(".k-edit-form-container");

                dataItem.set(options.field, value);
                e.sender.element.data("kendoTooltip").hide();
                if (value === false) {
                    grid.editable.options.fields.forEach((v) => {
                        let input = editorContainer.find("input[name=" + v.field + "]").getKendoNumericTextBox();
                        if (Configurations.validations.minMaxAmountInputs.includes(v.field)) {
                            input.enable(false);
                            input.wrapper.find(".k-tooltip").hide()
                        }
                    });
                } else {
                    grid.editable.options.fields.forEach((v) => {
                        if (Configurations.validations.minMaxAmountInputs.includes(v.field))
                            editorContainer.find("input[name=" + v.field + "]").getKendoNumericTextBox().enable(true);
                    });
                }
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

toastError = function f(message, timeout = 3000) {
    $.toast({
// heading: '<u>Information</u>',
        text: `<b class="text-bold row"><i class="fa fa-warning text-warning p-1"></i> <span class="text-danger">${message}</span></b>`,
//icon: 'warning',
        loader: false,        // Change it to false to disable loader
        loaderBg: '#9EC600',  // To change the background
        position: 'mid-center',
        stack: 1,
        hideAfter: timeout,
        beforeShow: function (element) {
            element.addClass("bg-white card")
        },
        afterShown(element) {
            $(".dimbackground-curtain").remove();
            element.dimBackground({darkness: 0.6});
        },
        afterHidden(element) {
            element.undim();
        }
    });
};

toastSuccess = function f(message, timeout = 3000) {
    $.toast({
// heading: '<u>Information</u>',
        text: `<b class="text-bold text-danger"><i class="fa fa-check text-success p-1"></i> <span class="text-success">${message}</span></b>`,
//icon: 'warning',
        loader: false,        // Change it to false to disable loader
        loaderBg: '#9EC600',  // To change the background
        position: 'top-center',
        stack: 1,
        hideAfter: timeout,
        beforeShow: function (element) {
            element.addClass("bg-white card")
        },
        afterShown(element) {
            $(".dimbackground-curtain").remove();
            element.dimBackground({darkness: 0.6});
        },
        afterHidden(element) {
            element.undim();
        }
    });
};
/*
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
}*/

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

function dataSourceError(e) {
    dataSource.cancelChanges();
    dataSource.read();
    if (e.status === "parsererror") {
        toastError("Some required assets failed to load.");
        return;
    }
    toastError(e.errors[0]['message']);
}

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

/*function selectGridRow(searchedId, grid, dataSource, idField) {
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
    dataSource.page(pageWithRow);

    const row = grid.element.find("tr[data-uid='" + modelToSelect.uid + "']");
    if (row.length > 0) {
        grid.select(row);

// Scroll to the item to ensure it is visible
        //grid.content.scrollTop(grid.select().position().top);
    }
    return row;
}*/

function onRequestEnd(e) {
    if ((e.type === 'update' || e.type === 'create') && e.response.length > 0) {
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
}

function onChange() {
    grid.expandRow(grid.select())
}

function onDataBound() {
    let data = grid.dataSource.data();
    $.each(data, function (i, row) {
        $('tr[data-uid="' + row.uid + '"] ').attr('data-id-salary-advance', row['id_salary_advance'])
            .attr("data-request-number", row.request_number)
            .find(".print-it").attr("href", URL_ROOT + "/salary-advance/print/" + row.id_salary_advance);
    });
    $(".print-it").printPage();

    if (selectedRowId)
        grid.current(grid.element.find("tr[data-id-salary-advance=" + selectedRowId + "]").find("td[role=gridcell]:first"));
    let headingRow = grid.element.find('thead tr[role=row]');
    let filterRow = grid.element.find('thead tr.k-filter-row');
    headingRow.find('th.k-hierarchy-cell').hide();
    headingRow.find('th.k-hierarchy-cell').next('th').attr('colspan', 2);
    filterRow.find('th.k-hierarchy-cell').hide();
    filterRow.find('th.k-hierarchy-cell').next('th').attr('colspan', 2);
    filterRow.find('input:first').attr('placeholder', 'Search...');
    filterRow.find('input:eq(1)').attr('placeholder', 'Search...');
    filterRow.find('input:eq(2)').attr('placeholder', 'Search...');
    filterRow.find('input:eq(3)').attr('placeholder', 'Search...');

    if (!firstLoadDone) {
        firstLoadDone = true;
        if (universal.requestNumber)
            filterString(universal.requestNumber, 'request_number');
    }

    let groups = grid.dataSource.group();
    if (groups.length) {
        grid.tbody.children(".k-grouping-row").each(function () {
            let row = $(this),
                groupKey = rowGroupKey(row, grid);
            if (collapsed[groupKey]) {
                grid.collapseRow(row);
            }
        });
    }

    let items = expandedRows['expanded'];
    if (items) {
        items = JSON.parse(items);
        items.forEach(function (x) {
            const item = grid.dataSource.data().find(function (y) {
                return y.id_salary_advance === x;
            });

            if (item) {
                const row = $('#' + grid.element.attr('id') + ' tr[data-id-salary-advance="' + item.id_salary_advance + '"]');
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
    if ($.inArray(item.id_salary_advance, items) < 0)
        items.push(item.id_salary_advance);
    expandedRows['expanded'] = JSON.stringify(items);
    createKendoTooltip(e.detailRow, "span.comment")
}

function onDetailCollapse(e) {
    let item = this.dataItem(e.masterRow);
    let items = JSON.parse(expandedRows['expanded']);

    items = items.filter(function (x) {
        return x !== item.id_salary_advance;
    });

    expandedRows['expanded'] = JSON.stringify(items);
}

function onDetailInit(e) {
    let colSize = e.sender.content.find('colgroup col').length;
    e.detailRow.find('.k-hierarchy-cell').hide();
    e.detailCell.attr('colspan', colSize);
}

function onBeforeEdit(e) {
    window.grid_uid = e.model.uid; // uid of current editing row
    e.model.fields.amount_requested.editable = (e.model.hod_approval === null) && universal.isSecretary;
    e.model.fields.hod_comment.editable = e.model.fields.hod_approval.editable = e.model.hod_approval === null && (e.model.department_id === universal.currentDepartmentID) && universal.isManager;
    e.model.fields.amount_payable.editable = e.model.fields.hr_comment.editable = e.model.fields.hr_approval.editable = universal.isHr && (e.model.hr_approval === null) && e.model.hod_approval === true;
    e.model.fields.gm_approval.editable = e.model.fields.gm_comment.editable = universal.isGM && (e.model.gm_approval === null) && e.model.hr_approval === true;
    e.model.fields.fmgr_comment.editable = e.model.fields.fmgr_approval.editable = universal.isFmgr && (e.model.fmgr_approval === null) && e.model.gm_approval === true;
    e.model.fields.amount_approved.editable = (universal.isFmgr && (e.model.fmgr_approval === null) && e.model.gm_approval === true) || (e.model.fmgr_approval === true && universal.isFinanceOfficer);
    e.model.fields.received_by.editable = e.model.fields.amount_received.editable = e.model.fields.finance_officer_comment.editable = ((e.model.fmgr_approval === true) && universal.isFinanceOfficer && e.model.date_received !== null);
}

function onEdit(e) {
    let tooltip;
    let title = $(e.container).parent().find(".k-window-title");
    let update = $(e.container).parent().find(".k-grid-update");
    let cancel = $(e.container).parent().find(".k-grid-cancel");
    $(title).text('');
    $(update).html('<span class="k-icon k-i-check"></span>OK');
    $(cancel).html('<span class="k-icon k-i-cancel"></span>Cancel');

    let extRadioButtonGroup = e.container.find("[data-role=extradiobuttongroup]");
    let updateButton = e.container.find('.k-grid-update');
    extRadioButtonGroup.each(function () {
        let element = $(this);
        tooltip = element.data('kendoTooltip');
        updateButton.click(function (e) {
            if (element.data('kendoExtRadioButtonGroup').value() == null) {
                tooltip.show(element);
                e.preventDefault();
            }
        });
    });

    let bulkLabelField = e.container.find('.k-edit-label:eq(0), .k-edit-field:eq(0)');
    let requestNumberField = e.container.find(".k-edit-label:eq(1), .k-edit-field:eq(1)");
    let nameLabelField = e.container.find('.k-edit-label:eq(2), .k-edit-field:eq(2)');
    let departmentLabelField = e.container.find('.k-edit-label:eq(4), .k-edit-field:eq(4)');
    let amountRequestedLabelField = e.container.find('.k-edit-label:eq(5), .k-edit-field:eq(5)');
    let hodApprovalLabelField = e.container.find('.k-edit-label:eq(6), .k-edit-field:eq(6)');
    let hodCommentLabelField = e.container.find('.k-edit-label:eq(7), .k-edit-field:eq(7)');
    let hrApprovalLabelField = e.container.find('.k-edit-label:eq(9), .k-edit-field:eq(9)');
    let hrCommentLabelField = e.container.find('.k-edit-label:eq(10), .k-edit-field:eq(10)');
    let amountPayableLabelField = e.container.find('.k-edit-label:eq(11), .k-edit-field:eq(11)');
    let gmApprovalLabelField = e.container.find('.k-edit-label:eq(13), .k-edit-field:eq(13)');
    let gmCommentLabelField = e.container.find('.k-edit-label:eq(14), .k-edit-field:eq(14)');
    let fmgrApprovalLabelField = e.container.find('.k-edit-label:eq(16), .k-edit-field:eq(16)');
    let fmgrCommentLabelField = e.container.find('.k-edit-label:eq(17), .k-edit-field:eq(17)');
    let amountApprovedLabelField = e.container.find('.k-edit-label:eq(18), .k-edit-field:eq(18)');
    let amountReceivedLabelField = e.container.find('.k-edit-label:eq(20), .k-edit-field:eq(20)');
    let receivedByLabelField = e.container.find('.k-edit-label:eq(21), .k-edit-field:eq(21)');
    let dateReceivedLabelField = e.container.find('.k-edit-label:eq(22), .k-edit-field:eq(22)');
    let financeOfficerCommentLabelField = e.container.find('.k-edit-label:eq(23), .k-edit-field:eq(23)');

    e.container.find('.k-edit-label, .k-edit-field').addClass("pt-2").toggle(false);
    requestNumberField.toggle();
    nameLabelField.toggle();
    departmentLabelField.toggle();
    amountRequestedLabelField.toggle();
    hodApprovalLabelField.toggle();
    hrApprovalLabelField.toggle();
    gmApprovalLabelField.toggle();
    fmgrApprovalLabelField.toggle();
    amountPayableLabelField.toggle(e.model.fields.hr_approval.editable || e.model.hr_approval === true);
    amountApprovedLabelField.toggle(e.model.fields.fmgr_approval.editable || e.model.fmgr_approval === true);
    hodCommentLabelField.toggle(e.model.fields.hod_approval.editable || e.model.hod_comment !== null);
    hrCommentLabelField.toggle(e.model.fields.hr_approval.editable || e.model.hr_comment !== null);
    fmgrCommentLabelField.toggle(e.model.fmgr_comment !== null || e.model.fields.fmgr_approval.editable);
    gmCommentLabelField.toggle(e.model.fields.gm_approval.editable || e.model.gm_comment !== null);
    receivedByLabelField.toggle(e.model.fields.received_by.editable || e.model.received_by !== null);
    amountReceivedLabelField.toggle(e.model.fields.amount_received.editable || e.model.received_by !== null);
    dateReceivedLabelField.toggle(e.model.date_received !== null);
    financeOfficerCommentLabelField.toggle(e.model.finance_officer_comment !== null);

    e.container.getKendoWindow().bind('deactivate', function () {
        let data = grid.dataSource.data();
        $.each(data, function (i, row) {
            $('tr[data-uid="' + row.uid + '"] ').attr('data-id-salary-advance', row['id_salary_advance'])
                .attr('data-request-number', row.request_number)
                .find(".print-it").attr("href", URL_ROOT + "/salary-advance/print/" + row["id_salary_advance"]);
        });
        $(".print-it").printPage();
        setTimeout(function () {
            grid.content.lockscroll(false)
        }, 10)
    });

    e.container.on("keypress", ".k-input", function (e) {
        if (e.which === 13)
            $(this).trigger("blur").next("input").trigger("focus");
    });

    grid.editable.validatable._errorTemplate = (function anonymous(data
    ) {
        let $kendoOutput;
        $kendoOutput = '<div class="k-widget k-tooltip k-tooltip-validation row mt-2"><span class="k-icon k-i-info d-inline col"> </span><span class="col">' + (data.message) + '</span><span class="k-callout k-callout-n"></span></div>';
        return $kendoOutput;
    });
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
    grid: {
        options: {
            autoFitColumn: true,
            selectable: true,
            mobile: true,
            noRecords: true,
            navigatable: true,
            persistSelection: true,
            excel: {
                fileName: "Salary Advance Export.xlsx",
                filterable: true
            },
            excelExport: function (e) {
                let sheet = e.workbook.sheets[0];
                sheet.columns[0].autoWidth = false;
                for (let rowIndex = 1; rowIndex < sheet.rows.length; rowIndex++) {
                    let row = sheet.rows[rowIndex];
                    if (row.type === "data") {
                        let dataItem = {
                            hod_approval: row.cells[6].value,
                            hr_approval: row.cells[9].value,
                            gm_approval: row.cells[13].value,
                            fmgr_approval: row.cells[16].value
                        };
                        row.cells[6].value = dataItem.hod_approval == null ? 'Pending' : (dataItem.hod_approval ? 'Approved' : 'Rejected');
                        row.cells[9].value = dataItem.hr_approval == null ? 'Pending' : (dataItem.hr_approval ? 'Approved' : 'Rejected');
                        row.cells[13].value = dataItem.gm_approval == null ? 'Pending' : (dataItem.gm_approval ? 'Approved' : 'Rejected');
                        row.cells[16].value = dataItem.fmgr_approval == null ? 'Pending' : (dataItem.fmgr_approval ? 'Approved' : 'Rejected');
                        // alternating row colors
                        if (rowIndex % 2 === 0) {
                            let row = sheet.rows[rowIndex];
                            for (let cellIndex = 0; cellIndex < row.cells.length; cellIndex++) {
                                //row.cells[cellIndex].fontName = "Poppins";
                            }
                        }
                    }
                }
            },
            editable: 'popup',
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
            groupable: false,
            height: 520,
            resizable: true,
            scrollable: true,
            columnResizeHandleWidth: 30,
            pageable: {
                alwaysVisible: false,
                pageSizes: [20, 40, 60, 80, 100],
                buttonCount: 5
            },
            detailTemplate: kendo.template($("#detailTemplate").html()),
            toolbar: [{name: "excel", template: $("#exportToExcel").html()}],
            edit: onEdit,
            dataBound: onDataBound,
            detailInit: onDetailInit,
            detailExpand: onDetailExpand,
            detailCollapse: onDetailCollapse,
            change: onChange,
            columns: [
                {
                    command: [
                        {
                            name: "custom_edit",
                            text: "Edit",
                            iconClass: "k-icon k-i-edit",
                            className: "badge badge-success btn k-button text-black border k-grid-custom-edit m-2",
                            click: function () {
                                let currentRow = grid.currentRow();
                                let id_salary_advance;
                                selectedRowId = id_salary_advance = currentRow.attr("data-id-salary-advance");
                                scrollTop = grid.content.scrollTop();
                                scrollLeft = grid.content.scrollLeft();
                                grid.select(currentRow);
                                grid.content.lockscroll(true);
                                grid.dataSource.read().then(function () {
                                    grid.editRow(grid.table.find("tr[data-id-salary-advance=" + id_salary_advance + "]"));
                                });
                            }
                        },
                        {name: "print", template: $("#printButton").html()}
                    ],
                    headerAttributes: {class: "title"},
                    title: "Action",
                    width: 190
                },
                {
                    field: "bulk_request_number",
                    title: "Bulk Number",
                    width: 230,
                    headerAttributes: {
                        "class": "title"
                    },
                    filterable: {cell: {showOperators: false, suggestionOperator: "contains", operator: "contains"}},
                },
                {
                    field: "request_number",
                    title: "Request Number",
                    width: 230,
                    headerAttributes: {
                        "class": "title"
                    },
                    filterable: {cell: {showOperators: false, suggestionOperator: "contains", operator: "contains"}},
                },
                {
                    field: "name",
                    filterable: {cell: {showOperators: false, suggestionOperator: "contains", operator: "contains"}},
                    headerAttributes: {class: "title"},
                    title: "Employee",
                    width: 290
                },
                {
                    field: 'department',
                    title: 'Department',
                    headerAttributes: {
                        class: "title"
                    },
                    width: 260,
                    filterable: {cell: {showOperators: false, suggestionOperator: "contains", operator: "contains"}}
                },
                {
                    field: 'amount_requested',
                    title: 'Amount Requested',
                    width: 180,
                    headerAttributes: {
                        "class": "title"
                    },
                    format: "{0:c}",
                    filterable: false,
                    editor: editNumberWithoutSpinners
                },
                {
                    field: 'date_raised',
                    title: 'Date Raised',
                    headerAttributes: {
                        class: "title"
                    },
                    width: 450,
                    groupHeaderTemplate: "Date Raised: #= kendo.toString(kendo.parseDate(value), 'dddd dd MMM, yyyy') #",
                    filterable: {cell: {template: dateRangeFilter}},
                    format: "{0:dddd dd MMM, yyyy}",
                    nullable: true
                },
                {
                    field: 'hod_approval',
                    title: 'HoD Approval',
                    editor: approvalEditor,
                    template: function (dataItem) {
                        return "<span>" + (dataItem.hod_approval === null ? '<i class="fa fa-warning text-yellow"></i>  Pending' : dataItem.hod_approval ? '<i class="fa fa-check text-success"></i> Approved' : '<i class="fa fa-warning text-danger"></i> Rejected') + "</span>"
                    },
                    headerAttributes: {
                        "class": "title"
                    },
                    width: 200,
                    groupHeaderTemplate: "HoD Approval: #= value===null? 'Pending' : (value? 'Approved' : 'Rejected') # | Total: #= count #",
                    aggregates: ["count"],
                    filterable: false
                },
                {
                    field: 'hod_comment',
                    title: 'HoD Comment',
                    hidden: false,
                    nullable: true,
                    editor: textAreaEditor,
                    headerAttributes: {
                        "class": "title"
                    },
                    attributes: {
                        class: 'comment'
                    },
                    width: 200,
                    filterable: false
                },
                {
                    field: 'hod_approval_date',
                    title: 'HoD Approval Date ',
                    headerAttributes: {
                        "class": "title"
                    },
                    width: 200,
                    groupHeaderTemplate: "Date Raised: #= value ? kendo.toString(kendo.parseDate(value), 'dddd dd MMM, yyyy') : '' #",
                    hidden: false,
                    format: "{0:dddd dd MMM, yyyy}",
                    filterable: false,
                    nullable: true
                },
                {
                    field: 'hr_approval',
                    title: 'HR Approval',
                    editor: approvalEditor,
                    template: function (dataItem) {
                        return "<span>" + (dataItem.hr_approval === null ? '<i class="fa fa-warning text-yellow"></i>  Pending' : dataItem.hr_approval ? '<i class="fa fa-check text-success"></i> Approved' : '<i class="fa fa-warning text-danger"></i> Rejected') + "</span>"
                    },
                    headerAttributes: {
                        "class": "title"
                    },
                    groupHeaderTemplate: "HR Approval: #= value===null? 'Pending' : (value? 'Approved' : 'Rejected') # |  Total: #= count #",
                    aggregates: ["count"],
                    width: 200,
                    filterable: false
                },
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
                    width: 200,
                    filterable: false,
                    nullable: true
                },
                {
                    field: 'amount_payable',
                    title: 'Amount Payable',
                    format: "{0:c}",
                    headerAttributes: {
                        "class": "title"
                    },
                    groupHeaderTemplate: "Amount Payable: #= value?  kendo.format('{0:c}', value) : 'Pending' #",
                    aggregates: ["max", "min"],
                    width: 200,
                    filterable: false,
                    nullable: true,
                    editor: editNumberWithoutSpinners
                },
                {
                    field: 'hr_approval_date',
                    title: 'HR Approval Date ',
                    headerAttributes: {
                        "class": "title"
                    },
                    width: 200,
                    groupHeaderTemplate: "HR Approval Date: #= value ? kendo.toString(kendo.parseDate(value), 'dddd dd MMM, yyyy') : '' #",
                    hidden: false,
                    filterable: false,
                    format: "{0:dddd dd MMM, yyyy}",
                    nullable: true
                },
                {
                    field: 'gm_approval',
                    title: 'GM Approval',
                    editor: approvalEditor,
                    template: function (dataItem) {
                        return "<span>" + (dataItem.gm_approval === null ? '<i class="fa fa-warning text-yellow"></i>  Pending' : dataItem.gm_approval ? '<i class="fa fa-check text-success"></i> Approved' : '<i class="fa fa-warning text-danger"></i> Rejected') + "</span>"
                    },
                    headerAttributes: {
                        "class": "title"
                    },
                    width: 200,
                    groupHeaderTemplate: "GM Approval: #= value=== null? 'Pending' : ( value? 'Approved': 'Rejected' )# | Total: #= count #",
                    aggregates: ["count"],
                    filterable: false
                },
                {
                    field: 'gm_comment',
                    title: 'GM Comment',
                    editor: textAreaEditor,
                    headerAttributes: {
                        "class": "title"
                    },
                    attributes: {
                        class: 'comment'
                    },
                    width: 200,
                    filterable: false,
                    nullable: true
                },
                {
                    field: 'gm_approval_date',
                    title: 'GM Approval Date ',
                    headerAttributes: {
                        "class": "title"
                    },
                    width: 200,
                    groupHeaderTemplate: "GM's Approval Date: #= value ? kendo.toString(kendo.parseDate(value), 'dddd dd MMM, yyyy') : '' #",
                    filterable: false,
                    format: "{0:dddd dd MMM, yyyy}",
                    nullable: true
                },
                {
                    field: 'fmgr_approval',
                    title: 'Fin Mgr Approval',
                    editor: approvalEditor,
                    template: function (dataItem) {
                        return "<span>" + (dataItem.fmgr_approval === null ? '<i class="fa fa-warning text-yellow"></i>  Pending' : dataItem.fmgr_approval ? '<i class="fa fa-check text-success"></i> Approved' : '<i class="fa fa-warning text-danger"></i> Rejected') + "</span>"
                    },
                    headerAttributes: {
                        "class": "title"
                    },
                    width: 200,
                    groupHeaderTemplate: "Fin Mgr Approval: #= value? 'Approved' : (value===null? 'Pending' : 'Rejected') # |  Total: #=count #",
                    aggregates: ["count"],
                    filterable: false
                },
                {
                    field: 'fmgr_comment',
                    title: 'Fin Mgr Comment',
                    hidden: false,
                    editor: textAreaEditor,
                    headerAttributes: {
                        "class": "title"
                    },
                    attributes: {
                        class: 'comment'
                    },
                    width: 200,
                    nullable: true,
                    filterable: false
                },
                {
                    field: 'amount_approved',
                    title: 'Amount Approved',
                    nullable: true,
                    headerAttributes: {
                        "class": "title"
                    },
                    format: "{0:c}",
                    width: 200,
                    filterable: false,
                    editor: editNumberWithoutSpinners
                },
                {
                    field: 'fmgr_approval_date',
                    title: 'Fin Mgr Approval Date ',
                    headerAttributes: {
                        "class": "title"
                    },
                    width: 200,
                    groupHeaderTemplate: "Fin Mgr Approval Date: #= value ? kendo.toString(kendo.parseDate(value), 'dddd dd MMM, yyyy') : '' #",
                    hidden: false,
                    filterable: false,
                    format: "{0:dddd dd MMM, yyyy}",
                    nullable: true
                },
                {
                    field: 'amount_received',
                    title: 'Amount Received',
                    nullable: true,
                    attributes: {
                        class: 'amount_received'
                    },
                    headerAttributes: {
                        "class": "title"
                    },
                    width: 200,
                    filterable: false,
                    format: "{0:c}",
                    editor: editNumberWithoutSpinners
                },
                {
                    field: 'received_by',
                    title: 'Received By',
                    hidden: false,
                    headerAttributes: {
                        "class": "title"
                    },
                    width: 200,
                    groupHeaderTemplate: "Received By: #:  value #",
                    filterable: false,
                    nullable: true,
                    editor: employeesEditor
                },
                {
                    field: 'date_received',
                    title: 'Date Received',
                    hidden: false,
                    nullable: true,
                    headerAttributes: {
                        "class": "title"
                    },
                    width: 450,
                    groupHeaderTemplate: "Date Received: #= value? kendo.toString(kendo.parseDate(value), 'dddd dd MMM, yyyy') : '' #",
                    filterable: {
                        cell: {
                            template: dateRangeFilter
                        }
                    },
                    format: "{0:dddd dd MMM, yyyy}"
                },
                {
                    field: 'finance_officer_comment',
                    title: 'Fin Officer Comment',
                    hidden: false,
                    editor: textAreaEditor,
                    headerAttributes: {
                        "class": "title"
                    },
                    attributes: {
                        class: 'comment'
                    },
                    width: 200,
                    nullable: true,
                    filterable: false
                }
            ]
        },

    },
    validations: {
        minMaxAmount: {
            required: function (input) {
                if (input.hasClass("no-validation")) return true;
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
                    let model = grid.dataSource.getByUid(grid_uid);
                    input.attr("data-min-msg", "Amount must be more than 10% of salary.");
                    return (MIN_PERCENTAGE / 100) * model.basic_salary <= kendo.parseFloat(input.val());
                }
                return true;
            },
            max: function (input) {
                if ($.inArray(input.attr("name"), Configurations.validations.minMaxAmountInputs) > -1) {
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
        field = grid.dataSource.group()[groupIdx].field,
        groupValue = item[field];
    return "" + groupIdx + groupValue;
}

function documentReady() {
    commentTooltip = createKendoTooltip(grid.table, "td.comment");

    grid.table.on("click", ".action-delete", function () {
        let row = $(this).closest("tr");
        grid.removeRow(row);
    });
    grid.table.on('click', '.k-grid-add-disabled', function () {
        toastError($(this).attr("data-title"));
    });
    // persist collapsed state of grouped records
    grid.table.on("click", ".k-grouping-row .k-i-collapse, .k-grouping-row .k-i-expand", function () {
        let row = $(this).closest("tr"),
            groupKey = rowGroupKey(row, grid);

        collapsed[groupKey] = !$(this).hasClass("k-i-collapse");
    });

    let excelUpload = $("#excelUpload").kendoUpload({
        multiple: false,
        async: {
            saveUrl: URL_ROOT + "/salary-advance/upload-salaries",
            //removeUrl: "remove",
            autoUpload: false
        },
        success(e) {
            if (e.operation === "upload" && e.response.success) {
                $.ajax(URL_ROOT + "/salary-advance/update-salaries").then(r => null);
            }
        },
        validation: {
            allowedExtensions: [".xlsx", ".xls", ".csv"]
        },
        showFileList: true
    }).data("kendoUpload");

    let uploadSalariesWindow = $("#uploadSalariesWindow").kendoWindow({
        width: "600px",
        title: "",
        visible: false,
        actions: [
            "Minimize",
            "Maximize",
            "Close"
        ],
        minimize(e) {
            let title = this.wrapper.find(".k-window-title");
            title.html("<span class='fa fa-file-upload'></span> <span class='ml-1'>Upload Salaries</span>")
                .next()
                .one("click", "a[aria-label=window-restore]", function () {
                    title.html("");
                });
        },
        close: function () {
            this.wrapper.find("input#excelUpload").getKendoUpload().clearAllFiles();
        }
    }).data("kendoWindow");

    $("#uploadSalariesButton").on("click", function () {
        uploadSalariesWindow.center().open();
    });

    let exchangeRateInput = $("#exchangeRateInput").kendoNumericTextBox({
        min: 0.1,
        spinners: false,
        placeholder: " GHS",
        decimals: 3,
        culture: "custom-GH",
        format: "c3"
    }).data("kendoNumericTextBox");

    let exchangeRateWindow = $("#exchangeRateWindow").kendoWindow({
        width: "600px",
        title: "",
        visible: false,
        actions: [
            "Minimize",
            "Maximize",
            "Close"
        ],
        minimize(e) {
            let title = this.wrapper.find(".k-window-title");
            title.html("<span class='fa fa-exchange'></span> <span class='ml-1'>Exchange Rate</span>")
                .next()
                .one("click", "a[aria-label=window-restore]", function () {
                    title.html("");
                });
        },
    }).data("kendoWindow");

    $("#exchangeRateForm").on("submit", function (e) {
        e.preventDefault();
        let form = $(this);
        let validator = form.data("kendoValidator");
        if (validator.validate()) {
            $.post(URL_ROOT + "/salary-advance/exchange-rate", form.serialize(), function (data) {
                if (data.success) {
                    toastSuccess("Exchange rate updated successfully!", 5000);
                }
            }, "json").then(r => null);
        }
    })
        .kendoValidator();

    $("#exchangeRateButton").on("click", function () {
        exchangeRateWindow.center().open();
    });
}

function disableGridAddButton() {
    let errorMessage = moment() > moment(30, "DD") ? "Applications cannot be accepted after 10 days into the month! <br> <span>Please try again next month.</span>" : "You have an active salary advance request for this month!";
    if (!DEBUG_MODE)
        kGridAddButton.attr('disabled', 'disabled')
            .attr("data-title", errorMessage)
            .attr('title', moment() > moment(30, "DD") ? "Applications cannot be accepted after 10 days into the month! Please try again next month." : "You have an active salary advance request for this month!")
            .removeClass("k-grid-add")
            .addClass("k-state-disabled k-grid-add-disabled")
            .removeAttr("href");
}

/*function enableGridAddButton() {
    kGridAddButton.removeAttr('disabled').removeAttr("title")
        .addClass('k-grid-add')
        .removeClass('k-state-disabled k-grid-add-disabled')
        .attr('href', '#');
}*/

/*function findFromDataSource(field, value) {
    return grid.dataSource.data().find(function (y) {
        return y[field] === value;
    });
}*/

/*function appendToHeader() {
    $(".k-grouping-header").empty();
    $(".k-grouping-header>span").append($("#groupByColumnsTemplate").html());
    groupByColumnsSelect = $("#groupByColumnsSelect").kendoMultiSelect({}).data("kendoMultiSelect");
    // grid.unbind("dataBound", appendToHeader);
}*/

/*$.fn.extend({
    toggleScroll: function (toggleState) {
        let noScroll = function (e) {
            e.preventDefault();
            e.stopPropagation();
            return false;
        };
        if (toggleState) {
            this.on('scroll touchmove mousewheel', function (e) {
                e.preventDefault();
                e.stopPropagation();
                return false;
            })
        } else {
            this.off('scroll touchmove mousewheel');
        }
    }
});*/

function createKendoTooltip(target, filter, position = "top") {
    return target.kendoTooltip({
        filter: filter,
        position: position,
        content: function (e) {
            // hide popup as default action
            e.sender.popup.element.css("visibility", "hidden");
            let text = $(e.target).text();
            if (text) e.sender.popup.element.css("visibility", "visible");
            return text;
        }
    }).data("kendoTooltip");
}
