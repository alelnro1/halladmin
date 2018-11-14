<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ContabilidadController extends Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        return view('contabilidad.index');
    }
}
