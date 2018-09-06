$(document).ready(function() {
    oTable = $('#tipos-de-talles').DataTable({
        responsive: true,
        columnDefs: [
            { orderable: false, targets: -1 }
        ],
        "language": {
            "info": "Mostrando _START_ a _END_ de _TOTAL_ tipos-de-talles filtrados",
            "paginate": {
                "first":      "Primera",
                "last":       "Ultima",
                "next":       "Siguiente",
                "previous":   "Anterior"
            },
            "lengthMenu": "Mostrar _MENU_ tipos-de-talles",
            "search": "Buscar:",
            "infoFiltered": "(de un total de _MAX_ tipos-de-talles)",
        }
    });
});
