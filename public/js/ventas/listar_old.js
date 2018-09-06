$(window).load(function() {
    // Setup - add a text input to each footer cell
    /*$('#articulos head th').each( function () {
        var title = $(this).text();

        // Si no es la última columna, poner el buscador
        if (!$(this).is(':first-child')) {
            $(this).html('<input type="text" class="form-control" placeholder="Buscar ' + title + '" >');
        }
    });*/

    /*$('#articulos thead tr#buscador th').each( function () {
        var title = $('#articulos thead th').eq( $(this).index() ).text();
        $(this).html( '<input type="text" onclick="stopPropagation(event);" placeholder="Buscar" />' );
    } );*/

    $.extend( $.fn.dataTable.defaults, {
        responsive: true
    } );

    // DataTables
    oTable = $('#articulos, #ventas').DataTable({
        //orderCellsTop: true,
        columnDefs: [
            { orderable: false, targets: -1 },
            {
                "targets": [ 0 ],
                "visible": false
            }
        ],
        paging:   false,
        //order: [[0, 'desc']],
        //pageLength: 10,
        language: {
            "sProcessing":     "Procesando...",
            "sLengthMenu":     "Mostrar _MENU_ registros",
            "sZeroRecords":    "No se encontraron resultados",
            "sEmptyTable":     "Ningún dato disponible en esta tabla",
            "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_",
            "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0",
            "sInfoFiltered":   "(filtrado de un total de _MAX_)",
            "sInfoPostFix":    "",
            "sSearch":         "Buscar:",
            "sUrl":            "",
            "sInfoThousands":  ",",
            "sLoadingRecords": "Cargando...",
            "oPaginate": {
                "sFirst":    "Primero",
                "sLast":     "Último",
                "sNext":     "Siguiente",
                "sPrevious": "Anterior"
            },
            "oAria": {
                "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
                "sSortDescending": ": Activar para ordenar la columna de manera descendente"
            }
        }
    });

    // Apply the search to every column
    /*oTable.columns().every( function () {
        var that = this;

        $( 'input', this.footer() ).on( 'keyup change', function () {
            if ( that.search() !== this.value ) {
                that
                    .search( this.value )
                    .draw();
            }
        } );
    });*/
    /*$("#articulos thead input").on( 'keyup change', function () {
        table
            .column( $(this).parent().index()+':visible' )
            .search( this.value )
            .draw();
    } );

    function stopPropagation(evt) {
        if (evt.stopPropagation !== undefined) {
            evt.stopPropagation();
        } else {
            evt.cancelBubble = true;
        }
    }*/

    $('.cantidad').on('keyup input', function() {
        var cantidad = $(this).val(),
            precio_unitario = $(this).parent().parent().find('td.col-precio').data('precio'),
            descuento = $(this).parent().parent().find('td.col-dto').find('input').val(),
            nuevo_subtotal = calcularSubtotal(cantidad, precio_unitario, descuento);

        $(this).addClass('selected');

        // Calculo el subtotal con el precio y la cantidad y el descuento
        $(this).parent().parent().find('td.col-subtotal').empty().html(
            '$' + nuevo_subtotal.toFixed(2)
        );

        $(this).parent().parent().find('td.col-subtotal').data('subtotal', nuevo_subtotal);

        guardarArticulosParaVenta(false);
    });

    function calcularSubtotal(cantidad, precio_unitario, descuento)
    {
        var subtotal = (precio_unitario * cantidad);

        if (descuento != 0) {
            subtotal = subtotal - subtotal * (descuento / 100);
        }

        return subtotal;
    }

    $('#articulos tbody tr').on( 'click', 'td', function () {
        // Si el padre está seleccionado, no lo destildo, pero no sino está seleccionado, lo tildo
        if ($(this).hasClass('col-cantidad') || $(this).hasClass('col-dto')) {
            if (!$(this).parent().hasClass('selected')) {
                $(this).parent().toggleClass('selected');
            }
        } else {
            $(this).parent().toggleClass('selected');
        }

        // Verifico si la fila quedó seleccionada y sino le quito el subtotal
        if (!$(this).parent().hasClass('selected')) {
            $(this).parent().find('td.col-subtotal').empty().html('-');
        } else {
            // La fila se seleccionó, calculo el subtotal
            var cantidad = $(this).parent().find('td.col-cantidad input').val(),
                precio_unitario = $(this).parent().find('td.col-precio').data('precio'),
                descuento = $(this).parent().find('td.col-dto input').val(),
                subtotal = calcularSubtotal(cantidad, precio_unitario, descuento);

            // Calculo el subtotal con el precio y la cantidad y el descuento
            $(this).parent().find('td.col-subtotal').empty().html(
                '$' + subtotal.toFixed(2)
            );

            $(this).parent().find('td.col-subtotal').data('subtotal', subtotal);
        }

        setTimeout(function(){
            oTable.search( '' ).columns().search( '' ).draw();

            guardarArticulosParaVenta(false);
        }, 1000);
    });

    $('#confirmar-articulos-venta').on('click', function(e) {
        if (confirm('Confirmar artículos para vender?')) {
            // Obtengo todos los artículos
            guardarArticulosParaVenta(true);
        }
    });

    $('#cancelar-venta').on('click', function() {
        if (confirm('Cancelar?')) {
            // Destildo los articulos seleccionados
            $('table#articulos tbody tr').each(function() {
                $(this).removeClass('selected');
            });

            guardarArticulosParaVenta();
        }
    });

    $('#modificar-articulo').on('click', function(e) {
        if (!confirm('Cambiar artículo?')) {
            e.preventDefault();
        }
    });

    /**
     * Se guardan los articulos para la venta
     * @param redirect si es true se redije, sino no
     */
    function guardarArticulosParaVenta(redirect) {
        // Obtengo los articulos
        var articulos = armarArticulosParaGuardar();

        $.ajax({
            type: 'POST',
            url: '../../ventas/guardar-articulos-venta',
            dataType: 'json',
            data:
            {
                'articulos': articulos,
                '_token': $('input[name="_token"]').val()
            },
            success: function(data) {
                if (data.success == true) {
                    if (redirect == true)
                        window.location.href = '../../ventas/' + data.url;
                } else {
                    alert('Error interno del servidor.');
                }
            }
        });
    }

    function armarArticulosParaGuardar() {
        var articulos = [];

        $('#articulos tr.selected').each(function() {
            var fila = {
                codigo: null,
                color: null,
                talle: null,
                genero: null,
                precio: null,
                cantidad: null,
                descuento: null,
                subtotal: null,
            };
            //codigo = color = talle = genero = precio = cantidad = null;

            // Recorro todos los td para armar la fila
            $(this).find('td').each(function() {
                var columna = $(this);

                if (columna.hasClass('col-codigo')) {
                    fila.codigo = columna.html();

                } else if (columna.hasClass('col-color')) {
                    fila.color = columna.html();

                } else if (columna.hasClass('col-talle')) {
                    fila.talle = columna.html();

                } else if (columna.hasClass('col-genero')) {
                    fila.genero = columna.html();

                } else if (columna.hasClass('col-precio')) {
                    fila.precio = columna.html();

                } else if (columna.hasClass('col-cantidad')) {
                    fila.cantidad = columna.find('input').val();

                } else if (columna.hasClass('col-subtotal')) {
                    fila.subtotal = columna.data('subtotal');

                } else if (columna.hasClass('col-dto')) {
                    fila.descuento = columna.find('input').val();
                }
            });

            articulos.push(fila);
        });

        /*$.each(oTable.rows('.selected').data(), function () {
         var articulo = $(this),
         codigo = articulo[0],
         color  = articulo[2],
         talle  = articulo[3],
         genero = articulo[4],
         precio = articulo[5],
         cantidad = articulo[6];

         var fila = {
         'codigo' : codigo,
         'color'  : color,
         'talle'  : talle,
         'precio' : precio,
         'genero' : genero,
         'cantidad' : cantidad
         };

         articulos.push(fila);
         });*/

        return articulos;
    }

    $('.porc-dto').on('keyup change input keypress', function() {
        var precio_unitario = $(this).parent().parent().parent().find('td.col-precio').data('precio'),
            descuento = $(this).parent().parent().parent().find('td.col-dto').find('input').val(),
            cantidad = $(this).parent().parent().parent().find('td.col-cantidad').find('input').val(),
            nuevo_subtotal = calcularSubtotal(cantidad, precio_unitario, descuento);

        $(this).parent().parent().parent().find('td.col-subtotal').empty().html(
            '$' + nuevo_subtotal.toFixed(2)
        );

        $(this).parent().parent().parent().find('td.col-subtotal').data('subtotal', nuevo_subtotal);
    })
        .on('focusout', '.porc-dto', function () {
            guardarArticulosParaVenta();
        });

    function calcularTotal()
    {
        var total = 0;

        $('#articulos').find('td.subtotal').each(function() {
            total = total + $(this).data('subtotal-dto');
        });

        return total;
    }

    $('div.dataTables_filter input').focus();
});