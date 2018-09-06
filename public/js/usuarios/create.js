$(function () {
    $("#domicilio").geocomplete();

    $('#tooltip-modulos-habilitados').tooltip({
        show: null
    });

    $('#menus-habilitados').selectpicker({
        countSelectedText: function (numSelected, numTotal) {
            return (numSelected == 1) ? "{0} seleccionados" : "{0} seleccionados";
        },
        multipleSeparator: ', ',
        dropupAuto: false
    });

    /**** VALIDACIONES *****/
    $.validator.setDefaults({
        highlight: function(element) {
            $(element).closest('.form-group').addClass('has-error');
        },
        unhighlight: function(element) {
            $(element).closest('.form-group').removeClass('has-error');
        }
    });

    $('form#form-nuevo-usuario').validate();
});