$('#forma-de-pago').bootstrapToggle({
    'size': 'normal'
});

$('body')
    .on('click', '#cancelar-venta', function (e) {

        e.preventDefault();

        $("#dialog-cancelar-venta").dialog({
            modal: true,
            buttons: {
                Cancelar: function () {
                    var motivo = $('#motivo_cancelacion').val();

                    if ($.trim(motivo).length < 5) {
                        $.confirm({
                            title: '¿Por qué cancela la venta?',
                            content: 'Escriba un motivo para cancelar la venta',
                            type: 'red',
                            typeAnimated: true,
                            buttons: {
                                cerrar: function () {
                                }
                            }
                        });
                    } else {
                        $.ajax({
                            url: 'cancelar',
                            type: 'POST',
                            data: {
                                'motivo': motivo,
                                '_token': $('input[name="_token"]').val()
                            },
                            dataType: 'json',
                            success: function (data) {
                                $.confirm({
                                    title: 'Cancelada!',
                                    content: 'La venta se canceló',
                                    type: 'green',
                                    typeAnimated: true,
                                    buttons: {
                                        cerrar: function () {
                                        }
                                    }
                                });

                                window.location.href = 'nueva-venta';
                            }
                        })
                    }

                }
            }
        });
    })
    .on('click', '#concretar-venta', function (e) {

        $.confirm({
            title: 'Confirmar',
            content: '¿Confirma la venta?',
            buttons: {
                cancelar: {
                    text: 'Cancelar',
                    btnClass: 'btn-red'
                },
                confirmar: {
                    text: 'Confirmar',
                    btnClass: 'btn-green',
                    action: function () {
                        // Primero envío el medio de pago y después proceso la venta
                        $.ajax({
                            url: 'medio-y-factura',
                            type: 'POST',
                            data: {
                                'medio': $('#medio-de-pago').prop('checked'),
                                '_token': $('input[name="_token"]').val()
                            },
                            dataType: 'json',
                            success: function (data) {
                                window.location.href = 'concretar-venta';
                            }
                        });
                    }
                }
            }
        });
    })
    .on('click', '#modificar-articulo', function (e) {
        if (!confirm('Cambiar artículo?')) {
            e.preventDefault();
        }
    })
    .on('click', '#buscar-contribuyente', function (e) {
        BuscarContribuyente();
    })
    .on('click', '#facturar', function (e) {
        const elem = $(this);
        const facturarUrl = elem.data('facturar-url');
        const tipo_comprobante = $('#tipo_comprobante option:selected').val();
        const cuit = $('#cuit').val();

        $.ajax({
            url: facturarUrl,
            type: 'POST',
            data: {
                'cuit' : cuit,
                'tipo_comprobante': tipo_comprobante,
                '_token': $('input[name="_token"]').val()
            },
            dataType: 'json',
            success: function () {

            }
        })
    });

function BuscarContribuyente() {
    const cuit = $('#cuit').val();
    const tipo_comprobante = $('#tipo_comprobante option:selected').html();
    const buscarButton = $('button#buscar-contribuyente');
    const buscarContribuyenteURL = buscarButton.data('buscar-contribuyente-url');
    const cargandoDatos = $('#cargando-datos-contribuyente');
    const divDatosContribuyente = $('#datos-contribuyente');

    $.ajax({
        url: buscarContribuyenteURL,
        data: {
            'cuit': cuit,
            'tipo_comprobante': tipo_comprobante,
            '_token': $('input[name="_token"]').val()
        },
        type: 'POST',
        dataType: 'json',
        beforeSend: function() {
            cargandoDatos.show();
            divDatosContribuyente.hide();
        },
        success: function (data) {
            cargandoDatos.hide();

            let error = false;

            if ($.isEmptyObject(data)) {
                error = true;
                error_mensaje = 'El CUIT no existe';
            } else if (data.error == true) {
                error = true;
                error_mensaje = data.mensaje;
            }

            if (error == true) {
                $.confirm({
                    title: 'Error!',
                    content: error_mensaje,
                    type: 'red',
                    buttons: {
                        Ok: {}
                    }
                });
            } else {
                $('#tipo-contribuyente').empty().html(data.TipoContribuyente);
                $('#domicilio-fiscal').empty().html(data.DomicilioFiscal);
                $('#nombre-razon-social').empty().html(data.RazonSocial);

                divDatosContribuyente.show();
            }
        }
    })
}