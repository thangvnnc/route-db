let URLBase = "http://hongphat.tinhlaisuat.info";
// let URLBase = "http://local.com";

// let DBcustomers = localStorage.getItem('db_customers');
// let DBusers = localStorage.getItem('db_users');
//
// if(DBcustomers === null && DBusers === null) {
//     updateDatabase();
// }
//
// function updateDatabase() {
//
// }

function escapeHtml(unsafe) {
    return unsafe
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#039;");
}

$(".btn-backup").on('click', function (e) {
    waitRun();
    $.ajax({
        url: URLBase+ "/customer/get_all",
        method: "POST",
        contentType: 'application/json; charset=UTF-8',
        processData: false,
        success: function (data) {
            data = JSON.parse(data);
            let code = data.code;
            if(code !== 0)
            {
                updateErr();
                return;
            }

            let htmlTable = '' +
                '<div id="tableExportExcell">' +
                '<table>' +
                '<tr>' +
                '<th>id</th>' +
                '<th>code</th>' +
                '<th>name</th>' +
                '<th>note</th>' +
                '<th>phone</th>' +
                '<th>address</th>' +
                '<th>aco</th>' +
                '<th>adodai</th>' +
                '<th>aeo</th>' +
                '<th>among</th>' +
                '<th>anguc</th>' +
                '<th>atay</th>' +
                '<th>avai</th>' +
                '<th>qday</th>' +
                '<th>qdodai</th>' +
                '<th>qdui</th>' +
                '<th>qlung</th>' +
                '<th>qmong</th>' +
                '<th>created_at</th>' +
                '<th>updated_at</th>' +
                '</tr>';

            let items = data.content;
            for (let idx = 0; idx < items.length; idx++)
            {
                let item = items[idx];
                htmlTable += '<tr>';
                htmlTable += '<td>' + (item.id          == null ? "" : item.id)         + '</td>';
                htmlTable += '<td>' + (item.code        == null ? "" : item.code)       + '</td>';
                htmlTable += '<td>' + (item.name        == null ? "" : item.name)       + '</td>';
                htmlTable += '<td>' + (item.note        == null ? "" : item.note)       + '</td>';
                htmlTable += '<td>' + (item.phone       == null ? "" : item.phone)      + '</td>';
                htmlTable += '<td>' + (item.address     == null ? "" : item.address)    + '</td>';
                htmlTable += '<td>' + (item.aco         == null ? "" : item.aco)        + '</td>';
                htmlTable += '<td>' + (item.adodai      == null ? "" : item.adodai)     + '</td>';
                htmlTable += '<td>' + (item.aeo         == null ? "" : item.aeo)        + '</td>';
                htmlTable += '<td>' + (item.among       == null ? "" : item.among)      + '</td>';
                htmlTable += '<td>' + (item.anguc       == null ? "" : item.anguc)      + '</td>';
                htmlTable += '<td>' + (item.atay        == null ? "" : item.atay)       + '</td>';
                htmlTable += '<td>' + (item.avai        == null ? "" : item.avai)       + '</td>';
                htmlTable += '<td>' + (item.qday        == null ? "" : item.qday)       + '</td>';
                htmlTable += '<td>' + (item.qdodai      == null ? "" : item.qdodai)     + '</td>';
                htmlTable += '<td>' + (item.qdui        == null ? "" : item.qdui)       + '</td>';
                htmlTable += '<td>' + (item.qlung       == null ? "" : item.qlung)      + '</td>';
                htmlTable += '<td>' + (item.qmong       == null ? "" : item.qmong)      + '</td>';
                htmlTable += '<td>' + (item.created_at  == null ? "" : item.created_at) + '</td>';
                htmlTable += '<td>' + (item.updated_at  == null ? "" : item.updated_at) + '</td>';
                htmlTable += '</tr>';
            }
            htmlTable += '' +
                '</table>' +
                '</div>';
            $('body').append(htmlTable);
            window.open('data:application/vnd.ms-excel,' + encodeURIComponent($('body').find('#tableExportExcell').html()));
            $('body').find('#tableExportExcell').remove();
            // let dataCsv = "id,code,name,note,phone,address,aco,adodai,aeo,among,anguc,atay,avai,qday,qdodai,qdui,qlung,qmong,created_at,updated_at" + "\n"; + ",";
            // let items = data.content;
            // for (let idx = 0; idx < items.length; idx++)
            // {
            //     let item = items[idx];
            //     dataCsv += (item.id          == null ? "" : item.id)         + ",";
            //     dataCsv += (item.code        == null ? "" : item.code)       + ",";
            //     dataCsv += (item.name        == null ? "" : item.name)       + ",";
            //     dataCsv += (item.note        == null ? "" : item.note)       + ",";
            //     dataCsv += (item.phone       == null ? "" : item.phone)      + ",";
            //     dataCsv += (item.address     == null ? "" : item.address)    + ",";
            //     dataCsv += (item.aco         == null ? "" : item.aco)        + ",";
            //     dataCsv += (item.adodai      == null ? "" : item.adodai)     + ",";
            //     dataCsv += (item.aeo         == null ? "" : item.aeo)        + ",";
            //     dataCsv += (item.among       == null ? "" : item.among)      + ",";
            //     dataCsv += (item.anguc       == null ? "" : item.anguc)      + ",";
            //     dataCsv += (item.atay        == null ? "" : item.atay)       + ",";
            //     dataCsv += (item.avai        == null ? "" : item.avai)       + ",";
            //     dataCsv += (item.qday        == null ? "" : item.qday)       + ",";
            //     dataCsv += (item.qdodai      == null ? "" : item.qdodai)     + ",";
            //     dataCsv += (item.qdui        == null ? "" : item.qdui)       + ",";
            //     dataCsv += (item.qlung       == null ? "" : item.qlung)      + ",";
            //     dataCsv += (item.qmong       == null ? "" : item.qmong)      + ",";
            //     dataCsv += (item.created_at  == null ? "" : item.created_at) + ",";
            //     dataCsv += (item.updated_at  == null ? "" : item.updated_at) + "\n";
            // }
            // download("backup.csv", dataCsv);
        },
        error: function (request, status, error) {
            errorServer();
        },
        complete : function () {
            // $('.btn-save').prop( "disabled", false);
        }
    });
});

// function download(filename, text) {
//     var element = document.createElement('a');
//     element.setAttribute('href', 'data:data:application/csv;charset=utf-8,' + encodeURI(text));
//     element.setAttribute('download', filename);
//
//     element.style.display = 'none';
//     document.body.appendChild(element);
//
//     element.click();
//
//     document.body.removeChild(element);
// }
