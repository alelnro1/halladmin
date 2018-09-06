$(function () {
    $("#domicilio").geocomplete();

    $('#tooltip-modulos-habilitados').tooltip({
        show: null
    });

    $('#categorias-habilitadas').selectpicker({
        countSelectedText: function (numSelected, numTotal) {
            return (numSelected == 1) ? "{0} seleccionados" : "{0} seleccionados";
        },
        multipleSeparator: ', ',
        dropupAuto: false,
        title: 'Seleccione las categorías de ropa',
        showSubtext: true
    });
});