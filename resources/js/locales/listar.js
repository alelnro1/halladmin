$(document).ready(function() {
    oTable = $('#locales').DataTable({
        columnDefs: [
            { orderable: false, targets: -1 }
        ],
        "language": {
            "info": "Mostrando _START_ a _END_ de _TOTAL_ locales filtrados",
            "paginate": {
                "first":      "Primera",
                "last":       "Ultima",
                "next":       "Siguiente",
                "previous":   "Anterior"
            },
            "lengthMenu": "Mostrar _MENU_ locales",
            "search": "Buscar:",
            "infoFiltered": "(de un total de _MAX_ locales)",
        }
    });
});