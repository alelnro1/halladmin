$(function(){
    ocultarOMostrarCargoPadre();

    $('#tiene_padre').on('click', function(e){
        ocultarOMostrarCargoPadre();
    });

    function ocultarOMostrarCargoPadre(){
        if($('#tiene_padre').is(":checked")) {
            $('#padre_id').slideDown();
        } else {
            $('#padre_id').slideUp();
        }
    }
});