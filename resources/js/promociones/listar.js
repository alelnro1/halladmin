$(document).ready(function() {
    oTable = $('#promociones').DataTable({
        columnDefs: [
            { orderable: false, targets: -1 }
        ],
        "language": {
            "info": "Mostrando _START_ a _END_ de _TOTAL_ promociones filtrados",
            "paginate": {
                "first":      "Primera",
                "last":       "Ultima",
                "next":       "Siguiente",
                "previous":   "Anterior"
            },
            "lengthMenu": "Mostrar _MENU_ promociones",
            "search": "Buscar:",
            "infoFiltered": "(de un total de _MAX_ promociones)",
        }
    });
});