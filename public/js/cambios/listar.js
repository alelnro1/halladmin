$(document).ready(function() {
    $('table#articulos').on('click', '.seleccionar-articulo', function(e) {
        e.preventDefault();

        if (confirm('Está seguro que el artículo que seleccionó es el que se desea cambiar?')) {
            var articulo = $(this);

            $.ajax({
                url: 'seleccionar-articulo',
                type: 'POST',
                data:
                {
                    'articulo': articulo.data('id'),
                    '_token': $('input[name="_token"]').val()
                },
                success: function(data) {
                    location.href = 'articulos';
                }
            })
        }
    });


    // Setup - add a text input to each footer cell
    $('#articulos tfoot th').each( function () {
        var title = $(this).text();

        // Si no es la última columna, poner el buscador
        if (!$(this).is(':last-child')) {
            $(this).html('<input type="text" class="form-control" placeholder="Buscar..." >');
        }
    });

    oTable = $('#articulos').DataTable({
        responsive: true,
        columnDefs: [
            { orderable: false, targets: -1 }
        ],
        order: [[0, 'desc']],
        "language": {
            "info": "Mostrando _START_ a _END_ de _TOTAL_ cambios filtrados",
            "paginate": {
                "first":      "Primera",
                "last":       "Ultima",
                "next":       "Siguiente",
                "previous":   "Anterior"
            },
            "lengthMenu": "Mostrar _MENU_ cambios",
            "search": "Buscar:",
            "infoFiltered": "(de un total de _MAX_ cambios)",
        }
    });

    // Apply the search to every column
    oTable.columns().every( function () {
        var that = this;

        $( 'input', this.footer() ).on( 'keyup change', function () {
            if ( that.search() !== this.value ) {
                that
                    .search( this.value )
                    .draw();
            }
        } );
    });
});
