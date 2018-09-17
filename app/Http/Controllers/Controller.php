<?php

namespace App\Http\Controllers;

use App\Local;
use App\Menu;
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
     * @return null
     */
    public function getLocalId()
    {
        if (session('LOCAL_ACTUAL')) {
            return session('LOCAL_ACTUAL')->id;
        }

        return null;
    }
}
