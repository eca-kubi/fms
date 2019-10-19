/// <reference path='../../assets/ts/kendo.all.d.ts' />
/// <reference path='../../assets/typescript/moment.d.ts' />

//let moment_format = 'DD/MM/YYYY';

let URL_ROOT = '';
let lists = [];
let employeeDataSource;
let currentRowSelected = false;
let pageWithRowSelected = -1;
let expandedRows = [];
let summaryTemplate = `<div class="">
        <b>Action</b>:  #= "<span class='text-center action-tools'>" +
                        "<span class='col' title=''><a href='javascript:' class='action-edit badge badge-success btn k-button text-black in-detail-row border'><i class='k-icon k-i-edit'></i>Review</a></span>" +
                        "<span class='col d-none' title=''><a href='javascript:' class='text-danger action-delete in-detail-row'><i class='fas fa-trash-alt'></i></a></span>" +
                        "<span class='col d-none' title=''><a href='javascript:' class='action-more-info in-detail-row'><i class='fas fa-info-circle'></i></a></span>" +
                        "<span class='col' title=''><a href='\\\\#' class='text-black action-print print-it in-detail-row badge badge-primary btn k-button border' target='_blank'><i class='k-icon k-i-printer'></i>Print</a></span>" +
                        "</span>"# </br>
        <b>Date Raised</b>: #= kendo.toString(kendo.parseDate(date_raised), 'dddd dd MMM, yyyy') #</br>
        #=amount_requested? '<b>Amount Requested</b>: ' + kendo.format('{0:c}', amount_requested) + '</br>' : ''#
        #=percentage? '<b>Amount Requested</b>: ' + percentage + '% of Salary</br>' : '' #
        <b>Approved by HoD?</b> #= hod_approval? 'Yes' : 'No' #</br>
        #=hod_approval_date? '<b>HoD Approval Date: </b>' + kendo.toString(kendo.parseDate(hod_approval_date), 'dddd dd MMM, yyyy')+'</br>': '' #
        <b>Approved by HR? </b> #= hr_approval? 'Yes' : 'No' # </br>
        #=hr_approval_date? '<b>HR Approval Date: </b>' + kendo.toString(kendo.parseDate(hr_approval_date), 'dddd dd MMM, yyyy')+'</br>': '' #
        <b >Amount Payable </b>: #= amount_payable? kendo.toString('GH₵ ' + kendo.format('{0:n}', amount_payable)) : 'Pending' #</br>
        <b>Approved by Finance Manager? </b> #= fmgr_approval? 'Yes' : 'No' # </br>
        #=fmgr_approval_date? '<b>Finance Mgr. Approval Date: </b>' + kendo.toString(kendo.parseDate(fmgr_approval_date), 'dddd dd MMM, yyyy')+'</br>': '' #
        <b>Amount Approved </b>: #= amount_approved? kendo.toString('GH₵ ' + kendo.format('{0:n}', amount_approved)) : 'Pending' #</br>
        <b>Amount Received </b>: #= amount_received? kendo.toString('GH₵ ' + kendo.format('{0:n}', amount_received)) : 'Pending' #</br>
        #=date_received? '<b>Date Received: </b>' + kendo.toString(kendo.parseDate(date_received), 'dddd dd MMM, yyyy')+'</br>': '' #
        #=received_by? '<b>Received by: </b>' + received_by  +'</br>': '' #
    </div>`;
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

    //$('form[data-toggle=validator] input[type=text]:not([readonly]):first').focus();

    $(window).resize(function () {
        $('.content-wrapper').css('margin-top', $('.navbar-fixed').height() + 'px');
        //kendo.resize($("#salary_advance_manager").parent());
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
        let currentScrollPos = window.pageYOffset;
        /*if (prevScrollpos > currentScrollPos) {
            navbar.prop('style').top = "0";
        } else {
            navbar.prop('style').top = "-60px";
        }*/
        prevScrollpos = currentScrollPos;
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

function dateRangeFilter(args) {
    let filterCell = args.element.parents(".k-filtercell");
    let field = filterCell.attr('data-field');
    //let clearButton = $('<button type="button" class="k-button k-button-icon" title="Clear" aria-label="Clear" data-bind="visible:operatorVisible" style=""><span class="k-icon k-i-filter-clear"></span></button>')
    filterCell.empty();
    filterCell.html('<span style="display:flex; justify-content:center;"><span>From:</span><input  class="start-date"  placeholder="mm/dd/yyyy"/><span>To:</span><input  class="end-date"  placeholder="mm/dd/yyyy"/></span>');

    $(".start-date", filterCell).kendoDatePicker({
        change: function (e) {
            let startDate = e.sender.value(),
                endDate = $("input.end-date", filterCell).data("kendoDatePicker").value(),
                dataSource = $salaryAdvanceGrid.data("kendoGrid").dataSource;

            if (startDate & endDate) {
                let filter = {logic: "and", filters: []};
                filter.filters.push({field: field, operator: "gte", value: startDate});
                filter.filters.push({field: field, operator: "lte", value: endDate});
                dataSource.filter(filter);
            }
        }
    });

    $(".end-date", filterCell).kendoDatePicker({
        change: function (e) {
            let startDate = $("input.start-date", filterCell).data("kendoDatePicker").value(),
                endDate = e.sender.value(),
                dataSource = $salaryAdvanceGrid.data("kendoGrid").dataSource;

            if (startDate & endDate) {
                let filter = {logic: "and", filters: []};
                filter.filters.push({field: field, operator: "gte", value: startDate});
                filter.filters.push({field: field, operator: "lte", value: endDate});
                dataSource.filter(filter);
                /*let filterEvent = $.Event('filter');
                filterEvent.field = field;
                filterEvent.filter = filter;
                filterEvent.sender = $salaryAdvanceGrid;
                $salaryAdvanceGrid.data('kendoGrid').trigger('filter', filterEvent);*/
            }
        }
    });

}

function selectGridRow(searchedId, grid, dataSource, idField) {
    var filters = dataSource.filter() || {};
    var sort = dataSource.sort() || {};
    var models = dataSource.data();
    // We are using a Query object to get a sorted and filtered representation of the data, without paging applied, so we can search for the row on all pages
    var query = new kendo.data.Query(models);
    var rowNum = 0;
    var modelToSelect = null;

    models = query.filter(filters).sort(sort).data;
    if (models.length <= 0) return;
    // Now that we have an accurate representation of data, let's get the item position
    for (var i = 0; i < models.length; ++i) {
        var model = models[i];
        if (model[idField] == searchedId) {
            modelToSelect = model;
            rowNum = i;
            break;
        }
    }

    // If you have persistSelection = true and want to clear all existing selections first, uncomment the next line
    // grid._selectedIds = {};

    // Now go to the page holding the record and select the row
    let currentPageSize = dataSource.pageSize();
    let pageWithRow = pageWithRowSelected = parseInt((rowNum / currentPageSize)) + 1; // pages are one-based
    if (!currentRowSelected) {
        currentRowSelected = true;
        dataSource.page(pageWithRowSelected);
    }
    var row = grid.element.find("tr[data-uid='" + modelToSelect.uid + "']");
    if (row.length > 0) {
        grid.select(row);

        // Scroll to the item to ensure it is visible
        grid.content.scrollTop(grid.select().position().top);
        grid.expandRow(row);
    }
}

function onDataBound(e){

    var items = expandedRows['expanded'];
    var grid = this;
    if(items){
        items = JSON.parse(items);
        items.forEach(function(x){
            var item = grid.dataSource.view().find(function(y){
                return y.id_salary_advance == x;
            });

            if(item){
                var row = $('#'+grid.element.attr('id') + ' tr[data-uid="'+item.uid+'"]')
                grid.expandRow(row);
            }
        })
    }
}

function onDetailExpand(e){
    var item = this.dataItem(e.masterRow);

    var items = expandedRows['expanded'];

    if(items){
        items = JSON.parse(items);

    }else{
        items = [];
    }

    items.push(item.id_salary_advance);
    expandedRows['expanded'] = JSON.stringify(items);
}

function onDetailCollapse(e){
    var item = this.dataItem(e.masterRow);
    var items =JSON.parse(expandedRows['expanded']);

    items = items.filter(function(x){
        return x != item.id_salary_advance;
    });

    expandedRows['expanded'] = JSON.stringify(items);
}

/*
function parseHtml(s) {
    return (new DOMParser()).parseFromString(s, 'text/html').body.innerHTML;
}*/
