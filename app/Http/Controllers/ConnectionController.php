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
        $connections=Connection::get();

        return response()->json([
            "status"=>200,
            "data"=>$connections
        ],200);
        
    }
    
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $connection=Connection::first();

        if($connection==null){
            $create_connection=Connection::create([
                'qr_code'=>$request->code_qr,
                'status'=>"PENDING"
            ]);
        }else{
            $update_connection=Connection::where('id',$connection->id)->update([
                'qr_code'=>$request->code_qr,
                'status'=>$request->status
            ]);
        }

        return response()->json([
            "status"=>200,
            "message"=>"Conexión creada correctamente."
        ],200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Connection $id)
    {   
        return response()->json([
            "status"=>200,
            "data"=>$id
        ],200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $connection=Connection::first();

        $update_connection=Connection::where('id',$connection->id)->update($request->all());

        return response()->json([
            "status"=>200,
            "message"=>"Estado de conexión actualizado."
        ],200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
