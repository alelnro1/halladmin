<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ClientesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Valido el input
        $validator = Validator::make(
            $request->all(), [
            'nombre'      => 'required|max:100',
            'apellido'    => 'required|max:100',
            'email'       => 'required|email|max:100',
            'telefono'    => 'max:30',
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $request->request->add(['negocio_id' => Auth::user()->negocio_id]);

        // Creo el cliente
        $cliente = Cliente::create($request->all());

        $this->crearSesionVentaClienteLocal($cliente->id);

        return redirect('ventas/previsualizar');
    }

    /**
     * Se seleccionó a un cliente existente => se crea la variable de sesion
     *
     * @param Request $request
     */
    public function seleccionarClienteParaVender(Request $request)
    {
        // Inicializo variables de resultados
        $success = true;

        // Recibo el id del cliente
        $cliente_id = $request->cliente;

        // Si el id del cliente es vacio, se salteó el paso de seleccion de cliente => borro la sesion
        if ($cliente_id == '') {
            $this->crearSesionVentaClienteLocal($cliente_id);
        } else {
            // Verifico que el cliente exista
            $cliente = Cliente::where('id', $request->cliente)->first();

            // El cliente existe
            if ($cliente) {
                $this->crearSesionVentaClienteLocal($cliente_id);
            } else {
                $success = false;
            }
        }

        echo json_encode(['success' => $success]);
    }

    /**
     * Creo una variable de sesion para guardar temporalmente el cliente al que se le venderá
     *
     * @param $cliente_id
     */
    private function crearSesionVentaClienteLocal($cliente_id)
    {
        // Creo una sesion que durará hasta el final de la venta, se concrete o se cancele
        session(['VENTA_LOCAL_' . $this->getLocalId() . '_USER_ID' . Auth::user()->id . '_CLIENTE_ID' => $cliente_id]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int                      $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
