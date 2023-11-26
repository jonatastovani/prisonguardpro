<?php

namespace App\Http\Controllers;

use App\Models\RefCoresCabelo;
use Illuminate\Http\Request;

class RefCoresCabeloController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $getAll = RefCoresCabelo::all();
        return dd($getAll);
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
    public function show(RefCoresCabelo $refCoresCabelo)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(RefCoresCabelo $refCoresCabelo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, RefCoresCabelo $refCoresCabelo)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RefCoresCabelo $refCoresCabelo)
    {
        //
    }
}
