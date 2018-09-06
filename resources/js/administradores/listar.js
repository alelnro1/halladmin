$(document).ready(function() {
    oTable = $('#administradores').DataTable({
        responsive: true,
        columnDefs: [
            { orderable: false, targets: -1 }
        ],
        "language": {
            "info": "Mostrando _START_ a _END_ de _TOTAL_ administradores filtrados",
            "paginate": {
                "first":      "Primera",
                "last":       "Ultima",
                "next":       "Siguiente",
                "previous":   "Anterior"
            },
            "lengthMenu": "Mostrar _MENU_ administradores",
            "search": "Buscar:",
            "infoFiltered": "(de un total de _MAX_ administradores)",
        }
    });
});
