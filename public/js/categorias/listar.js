$(document).ready(function() {
    oTable = $('#categorias').DataTable({
        responsive: true,
        columnDefs: [
            { orderable: false, targets: -1 }
        ],
        "language": {
            "info": "Mostrando _START_ a _END_ de _TOTAL_ categorias filtrados",
            "paginate": {
                "first":      "Primera",
                "last":       "Ultima",
                "next":       "Siguiente",
                "previous":   "Anterior"
            },
            "lengthMenu": "Mostrar _MENU_ categorias",
            "search": "Buscar:",
            "infoFiltered": "(de un total de _MAX_ categorias)",
        }
    });
});
