$(function(){
    var askConfirmation = true;

    $('#cerrar-caja-form').on('submit', function (e) {
        if (askConfirmation) {
            e.preventDefault();

            monto = $('#monto').val();

            if (monto == "") {
                $.confirm({
                    title: 'Error!',
                    content: 'Ingrese un monto',
                    type: 'red',
                    buttons: {
                        Ok: {}
                    }
                });
            } else {
                $.confirm({
                    title: 'Confirmar',
                    content: '¿Confirma el cierre de caja?',
                    buttons: {
                        cancelar: {
                            text: 'Cancelar',
                            btnClass: 'btn-red'
                        },
                        confirmar: {
                            text: 'Confirmar',
                            btnClass: 'btn-green',
                            action: function () {
                                askConfirmation = false; // done asking confirmation, now submit the form

                                $('#cerrar-caja-form').submit();
                            }
                        }
                    }
                });
            }
        }
    })
});