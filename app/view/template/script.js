const queryString = window.location.search;
const urlParams = new URLSearchParams(queryString);


function pegarNome() {
    // puxar URL
    // se GET[pagina] = autorizacao,
    // return 'Autorizações'
    const pagina = urlParams.get('pagina');
    switch (pagina) {
        case 'registro':
            return 'Registros';
        case 'autorizacao':
            return 'Autorizações';
        case 'requisitante':
            return 'Requisitantes';
        case 'usuario':
            return 'Usuários';
        case 'categoria':
            return 'Categorias';
        case 'turma':
            return 'Turmas';
        case 'coordenacao':
            return 'Coordenações'
    }
}

function definirLargura() {
    // puxar URL
    // se GET[pagina] = requisitante,
    // return ['50%', '35%', '15%'] // de acordo com cada um
    const pagina = urlParams.get('pagina');
    switch (pagina) {
        case 'registro':
            return ['25%', '30%', '15%', '15%', '15%'];
        case 'autorizacao':
            return ['5%', '33%', '33%', '6%', '15%', '10%'];
        case 'requisitante':
            return ['50%', '35%', '15%'];
        case 'usuario':
            return ['25%', '50%', '25%'];
        case 'categoria':
            return ['30%', '70%'];
        default: // Turmas e Coordenacoes
            return ['15%', '15%', '70%'];
    }
}

function definirColunas() {
    //[ 0, 1, 2 ]
    const pagina = urlParams.get('pagina');
    switch (pagina) {
        case 'registro':
            return [1, 2, 3, 4, 5];
        case 'categoria':
            return [0, 1];
        case 'autorizacao':
            return [0, 1, 2, 3, 4, 5];
        default:
            return [0, 1, 2];
    }
}

// Custom filtering function which will search between two dates (dmin and dmax)
// só funciona pra autorizações e registros
if(urlParams.get('pagina') == 'autorizacao' || urlParams.get('pagina') == 'registro'){
    var dmin;
    var dmax;

    $.fn.dataTable.ext.search.push(
        function(settings, dados, dataIndex) {
            min = $('#min').val();
            min = min.split("/");
            min = new Date(parseInt(min[2], 10),
                parseInt(min[1], 10) - 1, // month is zero-based
                parseInt(min[0], 10));

            var dd = String(min.getDate()).padStart(2, '0');
            var mm = String(min.getMonth() + 1).padStart(2, '0'); //January is 0!
            var yyyy = min.getFullYear();
            dmin = dd + '/' + mm + '/' + yyyy;

            max = $('#max').val();
            max = max.split("/");
            max = new Date(parseInt(max[2], 10),
                parseInt(max[1], 10) - 1, // month is zero-based
                parseInt(max[0], 10));

            var dd = String(max.getDate()).padStart(2, '0');
            var mm = String(max.getMonth() + 1).padStart(2, '0'); //January is 0!
            var yyyy = max.getFullYear();
            dmax = dd + '/' + mm + '/' + yyyy;

            if(urlParams.get('pagina') == 'autorizacao') var date = dados[4] || 0; // use data for the date column
            if(urlParams.get('pagina') == 'registro') var date = dados[3] || 0; // use data for the date column
            date = date.split("/");
            date = new Date(parseInt(date[2], 10),
                parseInt(date[1], 10) - 1, // month is zero-based
                parseInt(date[0], 10));

            if ((isNaN(min) && isNaN(max)) ||
                (isNaN(min) && date <= max) ||
                (min <= date && isNaN(max)) ||
                (min <= date && date <= max)) {
                return true;
            }
            return false;
        }
    );
}

var nomeRelatorio = "Relatório de " + pegarNome();
var largura = definirLargura();
var table;
var ordenacao = [[ 0, "desc"  ]];
if(urlParams.get('pagina') == 'registro') ordenacao = [[ 3, "desc" ]];
if(urlParams.get('pagina') == 'autorizacao') ordenacao = [[ 4, "desc" ]];
$(document).ready(function() {
    // Event listener to the two range filtering inputs to redraw on input
    $('#min, #max').keyup(function() {
        table.draw();
    });

    // Datatable initialisation
    table = $('#tabela').DataTable({
        language:
        {
            "sEmptyTable": "Nenhum registro encontrado",
            "sInfo": "Mostrando de _START_ até _END_ de _TOTAL_ registros",
            "sInfoEmpty": "Mostrando 0 até 0 de 0 registros",
            "sInfoFiltered": "(Filtrados de _MAX_ registros)",
            "sInfoPostFix": "",
            "sInfoThousands": ".",
            "sLengthMenu": "Mostrar _MENU_ resultados por página",
            "sLoadingRecords": "Carregando...",
            "sProcessing": "Processando...",
            "sZeroRecords": "Nenhum registro encontrado",
            "sSearch": "Pesquisar",
            "oPaginate": {
                "sNext": "Próximo",
                "sPrevious": "Anterior",
                "sFirst": "Primeiro",
                "sLast": "Último"
            },
            "oAria": {
                "sSortAscending": ": Ordenar colunas de forma ascendente",
                "sSortDescending": ": Ordenar colunas de forma descendente"
            }
        },
        columnDefs: [{
            //className: "dt-center",
            type: 'date-eu', // formato dd/mm/YYYY
            targets: [3, 4],
        }],
        order: ordenacao,
        dom: 'Blfrtip',
        paging: true,
        autoWidth: true,

        buttons: [{
            text: 'Baixar PDF',
            extend: 'pdfHtml5',
            filename: 'Relatório_'+pegarNome(),
            orientation: 'portrait',
            pageSize: 'A4',
            messageTop: function() {
                if(urlParams.get('pagina') == 'autorizacao' || urlParams.get('pagina') == 'registro'){
                    if (!dmin.includes("NaN") && !dmax.includes("NaN"))
                        return 'Relatório de autorizações realizadas entre ' + dmin + ' e ' + dmax + ' (inclusive).';

                    else if (!dmin.includes("NaN") && dmax.includes("NaN"))
                        return 'Relatório de autorizações realizadas desde ' + dmin + ' (inclusive).';

                    else if (dmin.includes("NaN") && !dmax.includes("NaN"))
                        return 'Relatório de autorizações realizadas do início até ' + dmax + ' (inclusive).';

                    else return " ";
                }
            },
            exportOptions: {
                columns: definirColunas(),
                search: 'applied',
                order: 'applied'
            },
            customize: function(doc) {
                //Remove the title created by datatTables
                doc.content.splice(0, 1);
                //Create a date string that we use in the footer. Format is dd-mm-yyyy
                var now = new Date();
                var jsDate = now.getDate() + '/' + String(now.getMonth() + 1).padStart(2, '0') + '/' + now.getFullYear();
                // Logo converted to base64
                var logo = "data:image/jpeg;base64,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";
                // Set page margins [left,top,right,bottom] or [horizontal,vertical]
                // or one number for equal spread
                // It's important to create enough space at the top for a header !!!
                doc.pageMargins = [20, 200, 20, 50];
                // Set the font size fot the entire document
                doc.defaultStyle.fontSize = 12;
                // Set the fontsize for the table header
                //doc.styles.tableHeader.fontSize = 12;
                doc.styles.tableHeader.alignment = 'center';

                if(urlParams.get('pagina') == 'autorizacao' || urlParams.get('pagina') == 'registro') var tableIndex = 1;
                else var tableIndex = 0;

                doc.content[tableIndex].alignment = 'center';
                doc.content[tableIndex].table.widths = largura;


                // Create a header object with 3 columns
                // Left side: Logo
                // Middle: brandname
                // Right side: A document title
                doc['header'] = (function() {
                    return {
                        columns: [{
                                image: logo,
                                width: 200,
                                margin: [100, 40, 130, 40]
                            },
                            {
                                text: nomeRelatorio,
                                fontSize: 18,
                                margin: [0, 130, -70, 0]
                            },
                            {
                                text: "Coordenadoria de Laboratórios",
                                fontSize: 14,
                                margin: [-50, 80, 40, 40]
                            }
                        ]
                    }
                });
                // Create a footer object with 2 columns
                // Left side: report creation date
                // Right side: current page and total pages
                doc['footer'] = (function(page, pages) {
                    return {
                        columns: [{
                                alignment: 'left',
                                text: ['Gerado em: ', {
                                    text: jsDate.toString()
                                }]
                            },
                            {
                                alignment: 'right',
                                text: ['Página ', {
                                    text: page.toString()
                                }, ' de ', {
                                    text: pages.toString()
                                }]
                            }
                        ],
                        margin: 20
                    }
                });
                // Change dataTable layout (Table styling)
                // To use predefined layouts uncomment the line below and comment the custom lines below
                // doc.content[0].layout = 'lightHorizontalLines'; // noBorders , headerLineOnly
                var objLayout = {};
                objLayout['hLineWidth'] = function(i) {
                    return .5;
                };
                objLayout['vLineWidth'] = function(i) {
                    return .5;
                };
                objLayout['hLineColor'] = function(i) {
                    return '#aaa';
                };
                objLayout['vLineColor'] = function(i) {
                    return '#aaa';
                };
                objLayout['paddingLeft'] = function(i) {
                    return 4;
                };
                objLayout['paddingRight'] = function(i) {
                    return 4;
                };
                doc.content[tableIndex].layout = objLayout;

                console.log(doc);
            }
        }],
    });
});

// Jquery datepicker and input masks setup
$(function() {
    $("#datepicker").datepicker({
        minDate: 0, // não pode no passado
        maxDate: "+1w -1", // mostra 1 semana -1 dia
        dateFormat: "dd/mm/yy",
        showOtherMonths: true,
        selectOtherMonths: true
    });
    $("#min").datepicker({
        dateFormat: "dd/mm/yy",
        showOtherMonths: true,
        selectOtherMonths: true,
        onClose: function() {
            table.draw();
        }
    });
    $("#max").datepicker({
        dateFormat: "dd/mm/yy",
        showOtherMonths: true,
        selectOtherMonths: true,
        onClose: function() {
            table.draw();
        }
    });

    $("#datepicker").inputmask({
        "mask": "99/99/9999"
    });
    $("#min").inputmask({
        "mask": "99/99/9999"
    });
    $("#max").inputmask({
        "mask": "99/99/9999"
    });
    $("#timepicker").inputmask({
        "mask": "99:99"
    });

    // check time validity
    /*
    $("#timepicker").change(function validateTimepicker(){
    var u_input = $("#timepicker").val().replace(/[^0-9\.]/g, '');
    var today = new Date().getHours();

    var output = document.querySelector("output");
    var timepicker = document.querySelector('#timepicker');

    if($("#datepicker").datepicker('getDate').getDate() <= new Date()){
    if (u_input >= 700 && u_input <= 2300 && u_input > parseInt(new Date().getHours()+"00")) {
    output.innerHTML = ('');
} else {
output.innerHTML = ('Horário inválido.');
}
}
} );*/

    // remove readonly attribute before submitting (user cannot type the date)
    $("#submit-btn").click(function() {
        $("#datepicker").removeAttr('readonly');
        $("#autorizacaoForm").submit();
    });
});

// Show all autorizações by a requisitante
// Procura variável 'search' na requisiçao GET (URL)
// e a coloca na pesquisa da tabela DataTable
function getQueryVariable(variable) {
    var query = window.location.search.substring(1);
    var vars = query.split("&");
    for (var i=0;i<vars.length;i++) {
        var pair = vars[i].split("=");
        if (pair[0] == variable) {
          return decodeURIComponent(pair[1]);
        }
    }
    return ""; //not found
}
$(document).ready( function () {
    table.search( getQueryVariable("search") ).draw();
} );
