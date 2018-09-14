$(document).ready(function() {
    oTable = $('#articulos').DataTable({
        responsive: true,
        columnDefs: [
            { orderable: false, targets: -1 }
        ],
        "language": {
            "info": "Mostrando _START_ a _END_ de _TOTAL_ artículos filtrados",
            "paginate": {
                "first":      "Primera",
                "last":       "Ultima",
                "next":       "Siguiente",
                "previous":   "Anterior"
            },
            "lengthMenu": "Mostrar _MENU_ articulos",
            "search": "Buscar:",
            "infoFiltered": "(de un total de _MAX_ articulos)",
        }
    });
});
