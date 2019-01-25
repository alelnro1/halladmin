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