$(window).load(function() {
    oTable = $('#articulos').DataTable({
        responsive: true,
        columnDefs: [
            { orderable: false, targets: -1 },
            { "visible": false, "targets": 0 }
        ],
        "drawCallback": function ( settings ) {
            var api = this.api();
            var rows = api.rows( {page:'current'} ).nodes();
            var last=null;

            api.column(0, {page:'current'} ).data().each( function ( group, i ) {
                if ( last !== group ) {
                    $(rows).eq( i ).before(
                        '<tr class="group"><td colspan="5">'+group+'</td></tr>'
                    );

                    last = group;
                }
            } );
        },
        "language": {
            "info": "Mostrando _START_ a _END_ de _TOTAL_ artículos filtrados",
            "paginate": {
                "first":      "Primera",
                "last":       "Ultima",
                "next":       "Siguiente",
                "previous":   "Anterior"
            },
            "lengthMenu": "Mostrar _MENU_ articulos",
            "search": "Buscar:",
            "infoFiltered": "(de un total de _MAX_ articulos)",
        }
    });

    // Order by the grouping
    $('#articulos tbody').on( 'click', 'tr.group', function () {
        var currentOrder = table.order()[0];
        if ( currentOrder[0] === 0 && currentOrder[1] === 'asc' ) {
            table.order( [ 0, 'desc' ] ).draw();
        }
        else {
            table.order( [ 0, 'asc' ] ).draw();
        }
    });
});
