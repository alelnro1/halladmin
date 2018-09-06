$(window).on('load', function() {
    $('.seleccionar-cliente').on('click', function(e) {
        e.preventDefault();

        seleccionarCliente($(this).data('id'));
    });

    $('#saltar-eleccion-cliente').on('click', function() {
        seleccionarCliente('');
    });

    // Setup - add a text input to each footer cell
    $('#clientes_existentes tfoot th').each( function () {
        var title = $(this).text();

        // Si no es la última columna, poner el buscador
        if (!$(this).is(':last-child')) {
            $(this).html('<input type="text" class="form-control" placeholder="Buscar" >');
        }
    });

    oTable = $('#clientes_existentes').DataTable({
        responsive: true,
        columnDefs: [
            { orderable: false, targets: -1 }
        ],
        lengthChange: false,
        searching: true,
        language: {
            "info": "Mostrando _START_ a _END_ de _TOTAL_ filtrados",
            "paginate": {
                "first":      "Primera",
                "last":       "Ultima",
                "next":       "Siguiente",
                "previous":   "Anterior"
            },
            "lengthMenu": "Mostrar _MENU_ locales",
            "search": "Buscar:",
            "infoFiltered": "(de un total de _MAX_ locales)",
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

    function seleccionarCliente(cliente)
    {
        $.ajax({
            url: 'seleccionar-cliente',
            data: {
                'cliente': cliente,
                '_token': $('input[name="_token"]').val()
            },
            type: 'POST',
            dataType: 'json',
            success: function (data) {
                if (data.success == true) {
                    window.location.href = 'previsualizar';
                } else {
                    alert('Cliente no existente');
                }
            }
        });
    }

});