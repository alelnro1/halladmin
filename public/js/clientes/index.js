$(window).load(function() {
    oTable = $('#clientes').DataTable({
        responsive: true,
        columnDefs: [
            { orderable: false, targets: -1 }
        ],
        lengthChange: false,
        searching: false,
        language: {
            "info": "Mostrando _START_ a _END_ de _TOTAL_ filtrados",
            "paginate": {
                "first":      "Primera",
                "last":       "Ultima",
                "next":       "Siguiente",
                "previous":   "Anterior"
            },
            "lengthMenu": "Mostrar _MENU_ clientes",
            "search": "Buscar:",
            "infoFiltered": "(de un total de _MAX_ clientes)",
        }
    });
});