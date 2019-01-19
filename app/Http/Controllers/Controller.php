<?php

namespace App\Http\Controllers;

use App\Menu;
use App\Models\Local;
use App\Models\Negocio;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesResources;
use Illuminate\Support\Facades\Auth;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Controller constructor.
     */
    public function __construct()
    {

    }

    /**
     * Getter del ID del local
     *
     * @return int
     */
    public function getLocalId(): int
    {
        if (session('LOCAL_ACTUAL')) {
            return session('LOCAL_ACTUAL')->id;
        }

        return null;
    }

    /**
     * Getter del objeto Local
     *
     * @return \App\Models\Local
     */
    public function getLocal(): Local
    {
        if (session('LOCAL_ACTUAL')) {
            return session('LOCAL_ACTUAL');
        }

        return null;
    }

    /**
     * Getter del negocio id
     *
     * @return Negocio |null
     */
    public function getNegocio(): Negocio
    {
        if (session('NEGOCIO_ACTUAL')) {
            return session('NEGOCIO_ACTUAL');
        }

        return null;
    }
}
