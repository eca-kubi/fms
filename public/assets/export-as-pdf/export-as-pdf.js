function exportAsPDF (element, tablePrefix= 'nmr') {
   // let body = typeof  editor.body == 'function'? editor.body() : editor.body;
    kendo.drawing.drawDOM($(element), {
            allPages: true,
            margin: {left: "1cm", top: "1.5cm", right: "1cm", bottom: "1cm"},
            multipage: true,
            scale: 0.7,
            forcePageBreak: ".page-break",
            //keepTogether: 'table, li',
            template: $(`#page-template-body`).html()
        }
    )
        .then(function (group) {
            // Render the result as a PDF file
            return kendo.drawing.exportPDF(group, {
                allPages: true,
                paperSize: "A4",
                scale: 0.7,
                forcePageBreak: ".page-break",
                margin: {left: "1cm", top: "1cm", right: "1cm", bottom: "1cm"}
            });
        })
        .done(function (data) {
            // Save the PDF file
            kendo.saveAs({
                dataURI: data,
                fileName: "PdfExport.pdf",
            });
        }).then(r => {console.log('Export done')});
}

function getPDF(selector) {
    kendo.drawing.drawDOM($(selector),  {
        scale: 0.7,
        paperSize: 'letter',
        template: $("#page-template").html()
    }).then(function (group) {
        kendo.drawing.pdf.saveAs(group, "Document.pdf");
    });
}
