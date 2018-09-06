$(function () {
    // https://eternicode.github.io/bootstrap-datepicker/
    $('#datetimepicker1').datepicker({
        todayHighlight: true,
        endDate: '+0d',
        autoclose: true,
        format: 'yyyy/mm/dd'
    });

    $("#domicilio").geocomplete();
});