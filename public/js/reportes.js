$(document).ready(function() {
    oTable = $('#ventas').DataTable({
        responsive: true,
        columnDefs: [
            { orderable: false, targets: [-1, -2] },
            {
                "targets": [ 0 ],
                "visible": false
            }
        ],
        order: [[0, 'desc']],
        pageLength: 5,
        ordering:false,
        lengthChange: false,
        searching: false,
        "language": {
            "info": "Mostrando _START_ a _END_ de _TOTAL_ ventas",
            "paginate": {
                "first":      "Primera",
                "last":       "Ultima",
                "next":       "Siguiente",
                "previous":   "Anterior"
            },
            "lengthMenu": "Mostrar _MENU_",
            "search": "Buscar:",
            "infoFiltered": "(de un total de _MAX_ )",
        }
    });
});
