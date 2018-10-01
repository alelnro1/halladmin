$(window).load(function() {
    // Setup - add a text input to each footer cell
    $('#articulos tfoot th').each( function () {
        var title = $(this).text();

        // Si no es la última columna, poner el buscador
        if (!$(this).is(':last-child')) {
            $(this).html('<input type="text" class="form-control" placeholder="' + title + '" >');
        }
    });

    // DataTables
    oTable = $('#articulos').DataTable({
        responsive: true,
        columnDefs: [
            { orderable: false, targets: -1 },
            {
                "targets": [ 0 ],
                "visible": false
            }
        ],
        columns: [
            { data: 'id'},
            { data: 'codigo' },
            { data: 'descripcion' },
            { data: 'color' },
            { data: 'talle' },
            { data: 'genero' },
            { data: 'precio' },
            { data: 'cantidad' },
            { data: 'subtotal' },
            { data: 'descuento' }
        ],
        rowId: 'id',
        select: {
            style: 'multi'
        },
        order: [[1, 'desc']],
        pageLength: 10,
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
            },
            "select": {
                "rows": " (%d artículo/s elegidos)"
            }

        }
    })
    // Si hizo click en un input, y la fila estaba seleccionada => no lo deselecciono
        .on( 'user-select', function ( e, dt, type, cell, originalEvent ) {
            var target = $(originalEvent.target),
                columna = target.closest('td'),
                fila = columna.closest('tr');

            // Ademas, si hizo click en el input => hago focus ahi
            if (target.is('input')) {
                var id = $(target).attr('id');

                $(originalEvent.target).focus();
            }

            if (fila.hasClass('selected')) {
                if (columna.hasClass('col-dto') || columna.hasClass('col-cantidad')) {
                    e.preventDefault();
                }
            }
        })
        .on('fnInitComplete', function () {
            alert('aaa');
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

    oTable.rows('.selected').select();
    guardarArticulosParaVenta(false);//console.log(oTable.rows({ selected: true }).);

    $('input.cantidad').on('keyup change input keypress keydown', function() {
        var cantidad = $(this).val(),
            precio_unitario = $(this).parent().parent().find('td.col-precio').data('precio'),
            descuento = $(this).parent().parent().find('td.col-dto').find('input').val(),
            nuevo_subtotal = calcularSubtotal(cantidad, precio_unitario, descuento);

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

    $('#articulos tbody').on( 'click', 'tr', function (e) {
        var cell = $(e.target).get(0);
        var td_clickeado = $(this).find(cell).closest('td');

        // Verifico si la fila quedó seleccionada y sino le quito el subtotal
        if (!td_clickeado.parent().hasClass('selected')) {
            td_clickeado.parent().find('td.col-subtotal').empty().html('-');
        } else {
            // La fila se seleccionó, calculo el subtotal
            var cantidad = td_clickeado.parent().find('td.col-cantidad input').val(),
                precio_unitario = td_clickeado.parent().find('td.col-precio').data('precio'),
                descuento = td_clickeado.parent().find('td.col-dto input').val(),
                subtotal = calcularSubtotal(cantidad, precio_unitario, descuento);

            // Calculo el subtotal con el precio y la cantidad y el descuento
            td_clickeado.parent().find('td.col-subtotal').empty().html(
                '$' + subtotal.toFixed(2)
            );

            td_clickeado.parent().find('td.col-subtotal').data('subtotal', subtotal);
        }

        // Guardo temporalmente la pagina actual para cuando dibujo de nuevo para actualizar,
        // no se cambie de pagina
        var pagina_actual = oTable.page();

        // Invalido la fila para que se actualicen los valores del DOM
        oTable.row($(this)).invalidate().draw();
        oTable.page(pagina_actual).draw(false);

        $(cell).focus();

        guardarArticulosParaVenta(false);
    });

    $('#confirmar-articulos-venta').on('click', function(e) {
        $.confirm({
            title: 'Confirmar',
            content: '¿Confirmar artículos para vender?',
            buttons: {
                cancelar: {
                    text: 'Cancelar',
                    btnClass: 'btn-red'
                },
                confirmar: {
                    text: 'Confirmar',
                    btnClass: 'btn-green',
                    action: function(){
                        // Obtengo todos los artículos para vender
                        var articulos = armarArticulosParaGuardar();

                        // Si no se seleccionó ningún artículo para vender no guardo
                        if (articulos.length > 0) {
                            guardarArticulosParaVenta(true);
                        } else {
                            $.confirm({
                                title: 'Error!',
                                content: 'Seleccione al menos 1 artículo',
                                type: 'red',
                                typeAnimated: true,
                                buttons: {
                                    cerrar: function () {
                                    }
                                }
                            });
                        }
                    }
                }
            }
        });
    });

    $('#cancelar-venta').on('click', function() {
        var cancelar_venta_url = $(this).attr('href');

        $.confirm({
            title: 'Confirmar',
            content: '¿Está seguro que desea cancelar la venta?',
            buttons: {
                cerrar: {
                    text: 'Cerrar',
                    btnClass: 'btn-red'
                },
                confirmar: {
                    text: 'Confirmar',
                    btnClass: 'btn-green',
                    action: function() {
                        // Destildo los articulos seleccionados
                        oTable.rows().deselect();

                        $('table#articulos tbody tr').each(function () {
                            $(this).removeClass('selected')
                                .find('td.col-subtotal').empty().html('-');
                        });

                        setTimeout(function () {
                            guardarArticulosParaVenta();
                        }, 10000);
                    }
                }
            }
        });
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
            data: {
                'articulos': articulos,
                '_token': $('input[name="_token"]').val()
            },
            success: function (data) {
                if (data.success == true) {
                    if (redirect == true)
                        window.location.href = '../../ventas/' + data.url;
                } else {
                    alert('Error interno del servidor.');
                }
            }
        });
    }

    /**
     * Armo un array con todos los articulos seleccionados
     * @returns {Array}
     */
    function armarArticulosParaGuardar() {
        var articulos = [];
        var filas_seleccionadas = oTable.rows( { selected: true } );

        filas_seleccionadas.every(function(a) {
            var columnas = $(this.node().cells);

            var fila = {
                codigo: null,
                color: null,
                talle: null,
                genero: null,
                precio: null,
                cantidad: null,
                descuento: null,
                subtotal: null
            };

            $.each(columnas, function (index, val) {
                var columna = $(val);

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

        return articulos;
    }

    $('#articulos tbody').on('keyup change input keypress keydown', '.porc-dto', function() {
        var precio_unitario = $(this).parent().parent().parent().find('td.col-precio').data('precio'),
            descuento = $(this).parent().parent().parent().find('td.col-dto').find('input').val(),
            cantidad = $(this).parent().parent().parent().find('td.col-cantidad').find('input').val(),
            nuevo_subtotal = calcularSubtotal(cantidad, precio_unitario, descuento);

        $(this).parent().parent().parent().find('td.col-subtotal').empty().html(
            '$' + nuevo_subtotal.toFixed(2)
        );

        $(this).parent().parent().parent().find('td.col-subtotal').data('subtotal', nuevo_subtotal);

        guardarArticulosParaVenta();
    });
});