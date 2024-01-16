<?php

namespace App\Http\Controllers;

use App\Common\RestResponse;
use App\Models\Preso;
use Illuminate\Http\Request;

class PresoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $resource = Preso::with('pessoa.documentos')->get();
        $response = RestResponse::createSuccessResponse($resource, 200);
        return response()->json($response->toArray(), $response->getStatusCode());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Preso $preso)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Preso $preso)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Preso $preso)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Preso $preso)
    {
        //
    }
}
