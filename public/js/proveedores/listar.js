$(window).load(function() {
    oTable = $('#proveedores').DataTable({
        responsive: true,
        columnDefs: [
            { orderable: false, targets: -1 }
        ],
        lengthChange: false,
        searching: false,
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
