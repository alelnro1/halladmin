$(document).ready(function() {
    oTable = $('#proveedores').DataTable({
        responsive: true,
        rowReorder: {
            selector: 'td:nth-child(2)'
        },
        columnDefs: [
            { orderable: false, targets: -1 }
        ],
        "language": {
            "info": "Mostrando _START_ a _END_ de _TOTAL_ proveedores filtrados",
            "paginate": {
                "first":      "Primera",
                "last":       "Ultima",
                "next":       "Siguiente",
                "previous":   "Anterior"
            },
            "lengthMenu": "Mostrar _MENU_ proveedores",
            "search": "Buscar:",
            "infoFiltered": "(de un total de _MAX_ proveedores)",
        }
    });
});
