<?php

namespace App\Http\Controllers;

use App\Common\RestResponse;
use App\Models\RefPresoConvivioTipo;
use Illuminate\Http\Request;

class RefPresoConvivioTipoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $resource = RefPresoConvivioTipo::with('cor')->get();
        $response = RestResponse::createSuccessResponse($resource, 200);
        return response()->json($response->toArray(), $response->getStatusCode());
    }
}
