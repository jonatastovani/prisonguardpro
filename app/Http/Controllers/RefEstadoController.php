<?php

namespace App\Http\Controllers;

use App\Models\RefEstado;
use Illuminate\Http\Request;

class RefEstadoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $estados = RefEstado::all();
        return dd($estados);
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
    public function show(RefEstado $refEstado)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(RefEstado $refEstado)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, RefEstado $refEstado)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RefEstado $refEstado)
    {
        //
    }
}