$(document).ready(function() {
    // Inicializo una variable resultado que indica si el articulo existe en la base de datos
    //var articulo_existe = false;

    /*********************************************/
    // override jquery validate plugin defaults
    $.validator.setDefaults({
        highlight: function(element) {
            $(element).closest('.form-group').addClass('has-error');
        },
        unhighlight: function(element) {
            $(element).closest('.form-group').removeClass('has-error');
        },
        errorElement: 'span',
        errorClass: 'text-danger',
        errorPlacement: function(error, element) {
            if(element.parent('.input-group').length) {
                error.insertAfter(element.parent());
            } else {
                error.insertAfter(element);
            }
        }
    });

    /*jQuery.validator.addMethod("verificarMismosPreciosConCodigoIgual", function(value, element) {
        var codigo = element.parent().parent().parent().find('.fila-codigo input').val();

        $('#precio_MVc9NrTP').parent().parent().parent().parent().find('tr .fila-precio input').each(function() {
            console.log($(this).val())
        });

        if (element.parent().parent().parent().parent().find('.fila-codigo input').val() == )
        console.log(value, element);
        return this.optional(element) || /^http:\/\/mycorporatedomain.com/.test(value);
    }, "Please specify the correct domain for your documents");*/

    $('#ingresar-mercaderia').on('click', function (e) {
        if (!confirm('Confirmar ingreso de mercadería?'))
            e.preventDefault();
    });

    // Cargo por primera vez una fila si no hay ningun articulo temporal
    if ($('#nueva-mercaderia tbody tr').length <= 0) {
        nuevaFila();
    }

    // Creo la validación del formulario
    $('form#mercaderia-form').validate({
        submitHandler: function(form) {
            // Habilito todos los inputs y selects justo antes de enviarlos
            $('tbody input, tbody select').each(function() {
                $(this).attr('disabled', false);
            });

            guardarFilas();
            form.submit();
        },
        invalidHandler: function(event, validator) {
            alert('Verifique los errores.');
            return false;
        }
    });

    $('#nueva-mercaderia input').each(function() {
        var id = $(this).data('id');

        // Agrego que sea obligatorio
        $(this).rules("add", {
            required: true,
            messages: {required: 'Este campo es obligatorio'}
        });
    });

    // Agrego las reglas de validaciones de todos los inputs ya existentes
    agregarValidacionesAInputs();


    // Defino una funcion que guarda las filas anteriores en caso de perdida
    function guardarFilas() {
        // Obtengo las filas
        var filas_anteriores = armarFilasParaGuardar();

        $.ajax({
            type: 'POST',
            url: 'guardar-filas-anteriores',
            data:
                {
                    'articulos': filas_anteriores,
                    'local_id': $('#ingresar-mercaderia').data('local-id'),
                    '_token': $('input[name="_token"]').val()
                }
        });
    }

    // Obtengo todas las filas anteriores a la nueva que se agregará
    function armarFilasParaGuardar() {
        var filas = [];

        $('tr.datos-articulo').each(function(){
            // Fila temporal que almacenará el contenido de los inputs de la fila
            var temp_fila = {};

            // Recorro cada fila
            $(this).find('td').each(function(){
                // Recorro cada input
                $(this).find('input').each(function() {
                    // Si es un buscador, lo excluyo
                    if (!$(this).parent().hasClass('bs-searchbox')) {
                        var id = $(this).data('id'),
                            contenido_input = $(this).val();

                        temp_fila[id] = contenido_input;
                    }
                });

                // Busco los selects
                $(this).find('select').each(function() {
                    var id = $(this).data('id');

                    temp_fila[id] = $(this).val();
                });
            });

            // Si tiene la clase "existe" => le marco que existe para que despues cuando lo cargue, aparezcan los campos deshabilitados
            if ($(this).hasClass('existe')) {
                temp_fila['existe'] = "si"
            } else {
                temp_fila['existe'] = "no";
            }

            filas.push(temp_fila)
        });

        return filas;
    }

    $('#agregar-articulo').on('click', function(e) {
        e.preventDefault();
        guardarFilas();

        // Cargo una fila nueva
        nuevaFila();

        agregarValidacionesAInputs();
    });


    /**
     * Se selecciona un artículo despues de hacer una busqueda con el dialog
     */
    $('body').on('click', '.elegir-articulo', function(e){
        e.preventDefault();

        var data_articulo = $(this).data('articulo');

        // Creo una fila nueva si la ultima no esta vacia. Suele servir para cuando se carga por primera vez y va directo a buscar
        if (!ultimaFilaVacia()) {
            nuevaFila();
        }

        // Lleno los campos de la fila recien agregada (la última)
        $('#nueva-mercaderia tbody tr').last().each(function() {
            // Le agrego la clase que existe para que siempre que se recargue no se pueda editar el codigo, descripcion, etc
            $(this).addClass('existe');

            $(this).find('td').each(function () {
                // Busco los inputs
                $(this).find('input').each(function () {
                    // Obtengo el id del input (#codigo, #descripcion, etc)
                    var id = $(this).data('id');

                    // Del codigo que está guardado en el DOM, obtengo el id correspondiente
                    // {"id":18,"codigo":"b","precio":"2","descripcion":"b","talle_id":"1","color":"","talle":{"id":1,"nombre":"45","tipo_talle_id":"1","created_at":"2016-07-29 19:13:47","updated_at":"2016-07-29 19:13:47"}}
                    $(this).val(data_articulo[id]);
                });

                if ($(this).hasClass('fila-codigo')) {
                    $(this).find('input').val(data_articulo.datos_articulo.codigo).prop('disabled', true);
                }

                if ($(this).hasClass('descripcion')) {
                    $(this).find('input').val(data_articulo.datos_articulo.descripcion).prop('disabled', true);
                }

                if ($(this).hasClass('fila-precio')) {
                    $(this).find('input').val(data_articulo.datos_articulo.precio).prop('disabled', true);
                }

                if ($(this).hasClass('fila-genero')) {
                    var genero_select = $(this).find('select.genero');

                    // Busco el select de generos, y cargo los talles
                    genero_select.selectpicker('val', data_articulo.datos_articulo.genero_id).prop('disabled', true).selectpicker('refresh');

                    //cargarTallesDeGenero(genero_select);
                }

                if ($(this).hasClass('fila-categoria')) {
                    $(this).find('select.categoria_id').selectpicker('val', data_articulo.datos_articulo.categoria_id).prop('disabled', true).selectpicker('refresh');
                }

                // Busco el select y le selecciono el valor que tenga guardado
                $(this).find('select.talle_id').each(function() {
                    $(this).selectpicker('val', data_articulo['talle_id']).selectpicker('refresh');
                });
            });
        });

        guardarFilas();

        $('#dialog-articulos').dialog('close');
    });


    // Hago que cuando haga click dentro del body en un .eliminar-articulo, se borre esa fila si hay mas de 1
    $('body').on('click', '.eliminar-articulo', function(){
        if (confirm('Eliminar?')) {
            // Borro la fila actual
            $(this).parents('tr:first').remove();

            // Cuento cuantas filas me quedaron
            var cantidad_de_filas = $('#nueva-mercaderia tbody tr').length;

            // Si no hay ninguna fila => cargo una nueva
            if (cantidad_de_filas < 1) {
                nuevaFila();
            }

            guardarFilas();
        }
    });

    oTable = $('#mercaderia-existente').DataTable({
        responsive: true,
        columnDefs: [
            { orderable: false, targets: -1 }
        ],
        lengthChange: false,
        pageLength: 5,
        language: {
            "sProcessing":     "Procesando...",
            "sLengthMenu":     "Mostrar _MENU_ registros",
            "sZeroRecords":    "No se encontraron resultados",
            "sEmptyTable":     "Ningún dato disponible en esta tabla",
            "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_",
            "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0",
            "sInfoFiltered":   "(filtrado de un total de _MAX_)",
            "sInfoPostFix":    "",
            "sSearch":         "Buscar:",
            "sUrl":            "",
            "sInfoThousands":  ",",
            "sLoadingRecords": "Cargando...",
            "oPaginate": {
                "sFirst":    "Primero",
                "sLast":     "Último",
                "sNext":     "Siguiente",
                "sPrevious": "Anterior"
            },
            "oAria": {
                "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
                "sSortDescending": ": Activar para ordenar la columna de manera descendente"
            }
        }
    });

    quitarToolTipASelects();

    $('#buscar-articulo').on('click', function(){
        $('#dialog-articulos').dialog({
            title: 'Artículos',
            hide: { effect: "explode", duration: 1000 },
            show: { effect: "blind", duration: 800 },
            modal: true,
            width: 600
        });
    });

    $('body')
        .on('change', '.selectpicker', function(e) {
            guardarFilas();

            // Si esta modificando la categoria id, cargamos los talles
            /*if ($(this).hasClass('categoria_id')) {
                var categoria = $(this);

                cargarTallesDeCategoria(categoria);
            }*/

            quitarToolTipASelects();
        })

        .on('mouseleave', '.talle_id, .categoria_id, .proveedor_id, .genero', function(e) {
            quitarToolTipASelects();
        })

        .on('change', '.talle_id', function(e) {
            guardarFilas();
        })

        .on('input change focus', 'input.descripcion', function(){
            var field = $(this);
            /*var input = $(this),
                descripcion = input.val(),
                codigo = input.parent().parent().find('td.fila-codigo input').val(); // El codigo de la fila que se modifica

            recargarDescripciones(input, codigo, descripcion);*/

            recargarDatos(field);
        })

        .on('input', 'input.precio', function() {
            var field = $(this);
            /*var input = $(this);
                precio = input.val(),
                codigo = input.parent().parent().parent().find('td.fila-codigo input').val(); // El codigo de la fila que se modifica

            recargarPrecio(input, codigo, precio);*/

            recargarDatos(field)
        })

        .on('change', 'select.categoria_id', function() {
            var field = $(this);
            /*var select = $(this);
                categoria_id = select.val(),
                codigo = select.parent().parent().parent().find('td.fila-codigo input').val(); // El codigo de la fila que se modifica

            // Busco todas las categorias con el codigo actual
            select.parent().parent().parent().parent().find('td.fila-categoria').each(function() {
                // Encuentro codigos iguales => escribo la descripcion
                if ($(this).parent().find('td.fila-codigo input').val() == codigo ) {
                    console.log($(this).find('select'));
                    cargarTallesDeCategoria($(this).find('select'));
                    // Actualizo
                    $(this).find('select').selectpicker('val', categoria_id).selectpicker('refresh');
                }
            });*/

            recargarDatos(field);
        })

        .on('change', 'select.genero', function() {
                var field = $(this);

            //cargarTallesDeGenero(field);
            recargarDatos(field);
        })

        .on('change', '#nueva-mercaderia input', function() {
            // Cada vez que se cambia de input, excepto el codigo que se guarda despues, se guarda la mercadería temporalmente

            if (!$(this).hasClass('codigo'))
                guardarFilas();
        })

        // Mientras va tipeando el codigo se pone en mayúscula
        .on('input', '.codigo', function() {
            $(this).val( $(this).val().toUpperCase() );
        })

        .on('focusout', '.codigo', function() {
            var codigo = $(this);

            cargarDatosDeArticuloExistente(codigo);

            // El articulo no existe en la base de datos, recargo las descripciones y categorias
            if ($(this).parent().hasClass('existe') == false) {
                // Busco para recargar los datos
                var descripcion = $(this).parent().parent().find('td.descripcion input'),
                    precio = $(this).parent().parent().find('td.fila-precio input'),
                    categoria = $(this).parent().parent().find('td.fila-categoria select'),
                    genero = $(this).parent().parent().find('td.fila-genero select');

                // Si la descripcion no existe en la base de datos => recargo
                recargarDatos(descripcion);
                recargarDatos(precio);
                recargarDatos(categoria);
                recargarDatos(genero)
            }

            // Reinicio el articulo_existe para el siguiente articulo
            //setArticuloExistente(false);
        });

    function recargarDatos(field) {
        var id = field.data('id'),
            valor = field.val(),
            codigo = field.closest('tr').find('td.fila-codigo input').val();

        if (id == "descripcion") {
            recargarDescripciones(field, codigo, valor);
        } else if (id == "precio") {
            recargarPrecio(field, codigo, valor);
        } else if (id == "categoria_id") {
            recargarCategoria(field, codigo, valor);
        } else if (id == "genero") {
            recargarGenero(field, codigo, valor);
        }

        guardarFilas();
    }




    // Cuando ya habian datos, hay que cargar los talles del genero que existe
    $(window).on('load', function() {
        // Se selecciona y deshabilita la categoria y el genero
        $('select.categoria_id').each(function () {
            var categoria = $(this);

            // Si la fila tiene clase "existe" es porque el articulo que estaba cargado ya existe => deshabilito la categoria
            if (categoria.closest('tr').hasClass('existe')){
                $(this).prop('disabled', true).selectpicker('refresh');
            }

            quitarToolTipASelects();
        });

        $('select.genero').each(function () {
            var genero = $(this);

            //cargarTallesDeGenero(genero);

            // Si la fila tiene clase "existe" es porque el articulo que estaba cargado ya existe => deshabilito la categoria
            if (genero.closest('tr').hasClass('existe')){
                $(this).prop('disabled', true).selectpicker('refresh');
            }

            quitarToolTipASelects();
        });

    });

    /**********************************************************************
     **********************************************************************
     ************************** FUNCIONES ********************************
     **********************************************************************
     ********************************************************************/

    /**
     * Se le saca el tooltip que aparece en los selects para que no moleste al seleccionar
     */
    function quitarToolTipASelects() {
        $('.categoria_id, .talle_id, .proveedor_id, .genero').tooltip({
            disabled: true
        });
    }

    /**
     * Se recibe una categoria como select y se cargan los talles en el select de talles de la fila
     * @param categoria
     */
    /*function cargarTallesDeCategoria(categoria) {
        $.ajax({
            url: 'cargar-talles-de-categoria',
            type: 'POST',
            data: {
                'categoria' : categoria.val(),
                '_token': $('input[name="_token"]').val()
            },
            dataType: 'json',
            success: function(data) {
                // Borro las opciones que estaban antes y cargo las nuevas
                categoria.parent().parent().parent().find('td.talles').each(function () {
                    $(this).find('select').each(function () {
                        var select = $(this),
                            talle_seleccionado = select.data('talle-seleccionado');

                        // Borro los talles anteriores
                        select.find('option').remove();

                        // Le agrego la opcion vacía
                        select.append(
                            "<option value=''>Elija...</option>"
                        );

                        // Cargo las nuevas subcategorias
                        $.each(data.talles, function(talle_id) {
                            var talle = data.talles[talle_id],
                                id = talle.id,
                                nombre = talle.nombre;

                            select.append(
                                "<option value='" + id + "'>" + nombre + "</option>"
                            );
                        });

                        // Elimino y recreo el selectpicker
                        select.selectpicker('destroy').selectpicker({
                            'title' : 'Elija...'
                        });

                        // Si ya habia un talle seleccionado antes de recargar => lo selecciono
                        if (talle_seleccionado != undefined) {
                            select.selectpicker('val', talle_seleccionado).selectpicker('refresh');
                        }

                        // Elimino el tooltip de nuevo
                        quitarToolTipASelects();
                    });
                });
            }
        })
    }*/

    /**
     * Se recibe una genero como select y se cargan los talles en el select de talles de la fila
     * @param genero
     */
    function cargarTallesDeGenero(genero) {
        $.ajax({
            url: 'cargar-talles-de-genero',
            type: 'POST',
            data: {
                'genero' : genero.val(),
                '_token': $('input[name="_token"]').val()
            },
            dataType: 'json',
            success: function(data) {
                // Borro las opciones que estaban antes y cargo las nuevas
                genero.closest('tr').find('td.talles').each(function () {
                    $(this).find('select').each(function () {
                        var select = $(this),
                            talle_seleccionado = select.data('talle-seleccionado');

                        // Borro los talles anteriores
                        select.find('option').remove();

                        // Le agrego la opcion vacía
                        select.append(
                            "<option value=''>Elija...</option>"
                        );

                        // Cargo las nuevas subcategorias
                        $.each(data.talles, function(talle_id) {
                            var talle = data.talles[talle_id],
                                id = talle.id,
                                nombre = talle.nombre;

                            select.append(
                                "<option value='" + id + "'>" + nombre + "</option>"
                            );
                        });

                        // Elimino y recreo el selectpicker
                        select.selectpicker('destroy').selectpicker({
                            'title' : 'Elija...'
                        });

                        // Si ya habia un talle seleccionado antes de recargar => lo selecciono
                        if (talle_seleccionado != undefined) {
                            select.selectpicker('val', talle_seleccionado).selectpicker('refresh');
                        }

                        // Elimino el tooltip de nuevo
                        quitarToolTipASelects();
                    });
                });
            }
        })
    }



    /**
     * Se agregan validaciones a inputs dinamicamente
     */
    function agregarValidacionesAInputs() {
        /*$('#nueva-mercaderia')
            // Busco los inputs
            .find('input').each(function() {
                var id = $(this).data('id');

                if (id == "precio") {
                    // Si es precio agrego que sea numérico
                    $(this).rules("add", {
                        number: true,
                        required: true,
                        verificarMismosCamposDeArticulosConCodigoIgual: true,
                        messages: {
                            number: 'Ingrese un número válido',
                            required: 'Este campo es obligatorio',
                            verificarMismosCamposDeArticulosConCodigoIgual: 'Los precios deben ser los mismos para códigos iguales'
                        }
                    });
                } else if (id == "cantidad") {
                    // Si es cantidad agrego que sea digitos
                    $(this).rules("add", {
                        digits: true,
                        required: true,
                        messages: {
                            digits: 'Ingrese sólo dígitos',
                            required: 'Este campo es obligatorio'
                        }
                    });
                } else {
                    // Agrego que sea obligatorio
                    $(this).rules("add", {
                        required: true,
                        messages: {
                            required: 'Este campo es obligatorio'
                        }
                    });
                }
            })

            // Busco el select
            .find('select').each(function() {
                console.log($(this));
                // Agrego que sea obligatorio
                $(this).rules("add", {
                    required: true,
                    messages: { required: 'Este campo es obligatorio' }
                });
            });

        console.log('asd');*/
    }

    /**
     * Se carga una nueva fila al final de la tabla, se cargan los selects
     */
    function nuevaFila() {
        var fila = $('#nueva-fila-articulo').html();

        $('#nueva-mercaderia tbody').last().append(fila);

        // Inicializo el select picker y le saco el tooltip a todos los selects
        $('#nueva-mercaderia tbody tr').last().find('.selectpicker').selectpicker('refresh');

        // Le saco el tooltip a todos los selects
        quitarToolTipASelects();

        agregarValidacionesAInputs();
    }

    /**
     * Devuelve true si la ultima fila de articulos es vacia
     * @returns {boolean}
     */
    function ultimaFilaVacia() {
        var fila_vacia = true;

        $('#nueva-mercaderia tbody tr').last().each(function(){
            $(this).find('td').each(function(){
                // Busco los inputs
                $(this).find('input').each(function() {
                    var id = $(this).data('id'),
                        contenido_input = $(this).val();

                    if (contenido_input != ""){
                        fila_vacia = false;
                    }
                });

                // Busco el select
                $(this).find('select').each(function() {
                    if ($(this).val() != "")
                        fila_vacia = false;
                });
            });
        });

        return fila_vacia;
    }

    /**
     * Recarga las descripciones con el mismo codigo
     * @param input
     * @param codigo
     * @param descripcion
     */
    var descripcion_existente = null;

    function recargarDescripciones(input, codigo, descripcion) {
        descripcion_existente = descripcion;

        // Si la descripcion esta vacia, busco el primer articulo de igual codigo con descripcion llena
        // Tambien, solo se busca si hay al menos un caracter
        if (descripcion == "") {
            $('#nueva-mercaderia').find('tr td.fila-codigo').each(function() {
                // Encuentro codigos iguales
                if ($(this).find('input').val() == codigo ){
                    var valor = $(this).parent().find('td.descripcion input').val();

                    if ( valor != "" && valor.length > 1) {
                        descripcion_existente = valor;
                        return false;
                    }
                }
            });
        }

        // Busco todas las descripciones con el codigo actual
        $('#nueva-mercaderia').find('td.fila-codigo').each(function() {
            // Encuentro codigos iguales y no está en la base de datos => escribo la descripcion
            if ($(this).find('input').val() == codigo && !$(this).closest('tr').hasClass('existe')){
                $(this).closest('tr').find('td.descripcion input').each(function(){
                    $(this).val(descripcion_existente);
                });
            }
        });
    }

    /**
     * Recarga los precios con el mismo codigo
     * @param input
     * @param codigo
     * @param precio
     */
    function recargarPrecio(input, codigo, precio) {
        // Si la descripcion esta vacia, busco el primer articulo de igual codigo con descripcion llena
        if (precio == "") {
            $('#nueva-mercaderia').find('td.fila-codigo').each(function() {
                // Encuentro codigos iguales
                if ($(this).find('input').val() == codigo ){
                    // Si la descripcion está llena, la guardo
                    var valor = $(this).parent().find('td.fila-precio input').val();

                    if ( valor != "" && valor.length > 1) {
                        precio = valor;
                    }
                }
            });
        }

        // Busco todas las descripciones con el codigo actual
        $('#nueva-mercaderia').find('td.fila-codigo').each(function() {
            // Encuentro codigos iguales => escribo la descripcion
            if ($(this).find('input').val() == codigo ){
                $(this).parent().find('td.fila-precio input').val(precio);
            }
        });
    }

    /**
     * Recarga los generos con el mismo codigo
     * @param select
     * @param codigo
     * @param categoria_id
     */
    function recargarGenero(select, codigo, genero) {
        // Busco todas las categorias con el codigo actual
        /*$('#nueva-mercaderia').find('tr td.fila-genero').each(function() {
            // Encuentro codigos iguales => escribo la descripcion
            if ($(this).parent().find('td.fila-codigo input').val() == codigo ) {
                // Actualizo
                $(this).find('select').selectpicker('val', genero).selectpicker('refresh');
            }
        });*/


        // Si la descripcion esta vacia, busco el primer articulo de igual codigo con descripcion llena
        if (genero == "") {
            $('#nueva-mercaderia').find('td.fila-codigo').each(function() {
                // Encuentro codigos iguales
                if ($(this).find('input').val() == codigo ){
                    // Si la descripcion está llena, la guardo
                    var valor = $(this).parent().find('td.fila-genero select').val();

                    if ( valor != "") {
                        genero = valor;
                        return false;
                    }
                }
            });

        }

        // Busco todas las descripciones con el codigo actual
        $('#nueva-mercaderia').find('td.fila-codigo').each(function() {
            // Encuentro codigos iguales => escribo la descripcion
            if ($(this).find('input').val() == codigo ){
                $(this).parent().find('select.genero').selectpicker('val', genero).selectpicker('refresh');

                // Si la categoria actual es distinta a la nueva que se quiere poner => recargo los talles
                //cargarTallesDeCategoria($(this).parent().find('select'));

                // Si el genero actual es distinto al nuevo que se quiere poner => recargo los talles
                //cargarTallesDeGenero($(this).parent().find('select'));
            }
        });
    }

    /**
     * Recarga las categorias con el mismo codigo
     * @param input
     * @param codigo
     * @param precio
     */
    function recargarCategoria(select, codigo, categoria_id) {
        // Busco todas las categorias con el codigo actual
        /*$('#nueva-mercaderia').find('tr td.fila-categoria').each(function() {
            // Encuentro codigos iguales => escribo la descripcion
            if ($(this).parent().find('td.fila-codigo input').val() == codigo ) {
                /*console.log($(this).find('select'));
                cargarTallesDeCategoria($(this).find('select'));*/
                // Actualizo
                /*$(this).find('select').selectpicker('val', categoria_id).selectpicker('refresh');
            }
        });*/


        // Si la descripcion esta vacia, busco el primer articulo de igual codigo con descripcion llena
        if (categoria_id == "") {
            $('#nueva-mercaderia').find('td.fila-codigo').each(function() {
                // Encuentro codigos iguales
                if ($(this).find('input').val() == codigo ){
                    // Si la descripcion está llena, la guardo
                    var valor = $(this).parent().find('td.fila-categoria select').val();

                    if ( valor != "") {
                        categoria_id = valor;
                        return false;
                    }
                }
            });
        }

        // Busco todas las descripciones con el codigo actual
        $('#nueva-mercaderia').find('td.fila-codigo').each(function() {
            // Encuentro codigos iguales => escribo la descripcion
            if ($(this).find('input').val() == codigo ){
                $(this).parent().find('select.categoria_id').selectpicker('val', categoria_id).selectpicker('refresh');

                // Si la categoria actual es distinta a la nueva que se quiere poner => recargo los talles
                //cargarTallesDeCategoria($(this).parent().find('select'));

                // Si el genero actual es distinto al nuevo que se quiere poner => recargo los talles
                //cargarTallesDeGenero($(this).parent().find('select'));
            }
        });
    }

    /**
     * Se cargan los datos de un articulo ya existente
     * @param codigo
     */
    
    /*function setArticuloExistente(val) {
        articulo_existe = val;
        console.log('se seteó a: ' + articulo_existe);
    }*/

    function cargarDatosDeArticuloExistente(codigo) {
        $.ajax({
            url: 'buscar-articulo-con-codigo',
            type: 'POST',
            data: {
                'codigo': codigo.val(),
                '_token': $('input[name="_token"]').val()
            },
            dataType: 'json',
            success: function(data) {
                if (data.articulo != "") {
                    //setArticuloExistente(true);

                    // Marco al articulo como existente
                    codigo.prop('disabled', true).closest('tr').addClass('existe');

                    // Escribo en la misma fila la descripcion y la categoria
                    codigo.closest('tr').find('td').each(function() {
                        // Escribo la descripcion ya existente e inhabilito el campo
                        if ($(this).hasClass('descripcion')) {
                            $(this).find('input').each(function() {
                                $(this).val(data.articulo.descripcion).attr('disabled', 'disabled');
                            });
                        }

                        // Selecciono la categoria ya existente, cargo los talles e inhabilito el campo
                        if ($(this).hasClass('fila-categoria')) {
                            // Busco el select de la categoria
                            $(this).find('select').each(function() {
                                // Selecciono la categoria correspondiente a ese articulo
                                $(this).selectpicker('val', data.articulo.categoria.id);

                                // Cargo los talles de la categoria
                                //cargarTallesDeCategoria($(this));

                                $(this).prop('disabled', true).selectpicker('refresh');
                            });
                        }

                        // Selecciono el genero ya existente, cargo los talles e inhabilito el campo
                        if ($(this).hasClass('fila-genero')) {
                            $(this).find('select').each(function() {
                                // Selecciono la categoria correspondiente a ese articulo
                                $(this).selectpicker('val', data.articulo.genero.id);

                                // Cargo los talles de la categoria
                                //cargarTallesDeGenero($(this));

                                $(this).prop('disabled', true).selectpicker('refresh');
                            });
                        }

                        // Escribo el precio ya existente e inhabilito el campo
                        if ($(this).hasClass('fila-precio')){
                            $(this).find('input.precio')
                                .val(data.articulo.precio)
                                .attr('disabled', 'disabled');
                        }
                    });
                } else {
                    //setArticuloExistente(false);

                    // Marco al articulo como inexistente
                    codigo.closest('tr').removeClass('existe');

                    // Está vacio, habilitar descripcion y categoria
                    codigo.closest('tr').find('td').each(function() {
                        // Escribo la descripcion ya existente e inhabilito el campo
                        if ($(this).hasClass('descripcion')) {
                            recargarDatos($(this).find('input'));
                            //$(this).find('input').attr('disabled', false).val('');
                        }

                        // Escribo la descripcion ya existente e inhabilito el campo
                        if ($(this).hasClass('fila-categoria')) {
                            recargarDatos($(this).find('select'));
                            // Busco el select de la categoria
                            /*$(this).find('select').each(function() {
                                $(this).prop('disabled', false).selectpicker('val', '').selectpicker('refresh');
                            });*/
                        }

                        if ($(this).hasClass('fila-genero')) {
                            recargarDatos($(this).find('select'));
                        }

                        if ($(this).hasClass('fila-precio')) {
                            recargarDatos($(this).find('input'));
                            //$(this).find('input').attr('disabled', false).val('');
                        }
                    });
                }

                // Se cargo la descripcion y la categoria, o no, dependiendo si existian => guardo las filas
                guardarFilas();
            }
        });
    }
});