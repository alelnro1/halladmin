$(document).ready(function () {
    var askConfirmation = true;

    $('body').on('submit', '#abrir-caja-form', function (e) {
        if (askConfirmation) {
            e.preventDefault();

            monto = $('#monto').val();

            if (monto == "") {
                $.confirm({
                    title: 'Error!',
                    content: 'Ingrese un monto',
                    type: 'red',
                    typeAnimated: true,
                    buttons: {
                        cerrar: function () {
                        }
                    }
                });
            } else {
                $.confirm({
                    title: 'Confirmar',
                    content: 'El monto que ha ingresado es $' + monto + ' ¿Es correcto?',
                    typeAnimated: true,
                    buttons: {
                        cancelar: {
                            text: 'Cancelar',
                            btnClass: 'btn-red',
                            action: function () {
                            }
                        },
                        confirmar: {
                            text: 'Confirmar',
                            btnClass: 'btn-green',
                            action: function () {
                                askConfirmation = false; // done asking confirmation, now submit the form
                                $('#abrir-caja-form').submit();
                            }
                        }
                    }
                });
            }
        }
    });
});