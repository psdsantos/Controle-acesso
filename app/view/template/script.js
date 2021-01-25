// Datatable setup
var table;
$(document).ready( function () {

    table = $('#tabela').DataTable( {

        "columnDefs": [
            {
                "className": "dt-center",
                "targets": "_all"
            }
        ],

        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'pdfHtml5',
                //download: 'open',
                messageTop: function () {
                    var today = new Date();
                    var dd = String(today.getDate()).padStart(2, '0');
                    var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
                    var yyyy = today.getFullYear();
                    today = dd + '/' + mm + '/' + yyyy;

                    if(!dmin.includes("NaN") && !dmax.includes("NaN"))
                        return 'Relatório de autorizações realizadas entre ' + dmin + ' e ' + dmax + ' (inclusive).';

                    else if(!dmin.includes("NaN") && dmax.includes("NaN"))
                        return 'Relatório de autorizações realizadas entre ' + dmin + ' e ' + today + ' (inclusive).';

                    else if(dmin.includes("NaN") && !dmax.includes("NaN"))
                        return 'Relatório de autorizações realizadas do início até ' + dmax + ' (inclusive).';
                },
                title: 'Autorizações',
                // especificar quais colunas serão exportadas
                exportOptions: {
                    columns: [ 0, 1, 2, 3, 4, 5 ],

                },

                // colocar imagem
                customize: function ( doc ) {
                    doc.content.splice( 1, 0, {
                        margin: [ 0, 15, 0, 15 ], // left, top, right, bot
                        orientation: 'portrait',
                        pageSize: 'A4',
                        alignment: 'center',
                        image: 'data:image/png;base64,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',
                        width: 60,
                    } );
                }
            },
        ],

        "language": {
            "lengthMenu": "Mostrar _MENU_ itens por página",
            "zeroRecords": "Nenhum registro encontrado",
            "info": "Mostrando página _PAGE_ de _PAGES_",
            "infoEmpty": "Não há registros",
            "infoFiltered": "(filtrado de _MAX_ registros no total)",
            "search": "Procurar:",
            "paginate": {
                "first":      "Primeiro",
                "last":       "Último",
                "next":       "Próximo",
                "previous":   "Anterior"
            }
        }

    } );

    // Event listener to the two range filtering inputs to redraw on input
    $('#min, #max').keyup( function() {
        table.draw();
    } );
} );

// Jquery datepicker and input masks setup
$( function() {
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
        onClose: function(){ table.draw(); }
    });
    $("#max").datepicker({
        dateFormat: "dd/mm/yy",
        showOtherMonths: true,
        selectOtherMonths: true,
        onClose: function(){ table.draw(); }
    });

    $("#datepicker").inputmask({ "mask": "99/99/9999" });
    $("#min").inputmask({ "mask": "99/99/9999" });
    $("#max").inputmask({ "mask": "99/99/9999" });
    $("#timepicker").inputmask({"mask": "99:99"});

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
} );