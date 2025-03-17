<?php

namespace App\Http\Controllers;

use App\Models\Connection;
use Illuminate\Http\Request;

class ConnectionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }
    
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data=[
            
        ];

        $create_connection=Connection::create($data);

        return response()->json([
            "status"=>200,
            "message"=>"Conexión creada correctamente.",
            "data"=>$create_connection
        ],200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
