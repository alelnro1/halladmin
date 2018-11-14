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
                        alert('Escriba un motivo para cancelar la venta');
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
    });


function BuscarContribuyente() {
    const nroDocumento = $('#nro_documento').val();
    const buscarButton = $('button#buscar-contribuyente');
    const buscarContribuyenteURL = buscarButton.data('buscar-contribuyente-url');

    $.ajax({
        url: buscarContribuyenteURL,
        data: {
            'nro_documento' : nroDocumento,
            '_token': $('input[name="_token"]').val()
        },
        type: 'POST',
        dataType: 'json',
        success: function (data) {
            console.log(data)
        }
    })
}