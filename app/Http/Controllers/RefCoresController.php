<?php

namespace App\Http\Controllers;

use App\Common\RestResponse;
use App\Models\RefCores;
use Illuminate\Http\Request;

class RefCoresController extends Controller
{
    public function index()
    {
        $resource = RefCores::all();
        $response = RestResponse::createSuccessResponse($resource, 200);
        return response()->json($response->toArray(), $response->getStatusCode());
    }

}
