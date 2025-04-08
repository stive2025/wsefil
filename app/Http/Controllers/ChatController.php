<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Chat::when(request()->filled('state'),function($query){
                $query->where('state',request('state'));
            })
            ->orderBy('updated_at','DESC')
            ->paginate(7);
    }
    
    /**
     * Store a newly created resource in storage.
    */
    public function store(Request $request)
    {
        $create_chat=Chat::create($request->all());

        return response()->json([
            "status"=>200,
            "message"=>"Chat creado correctamente.",
            "data"=>$create_chat
        ],200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Chat $id)
    {
        $chat=$id;
        $chat->messages=$chat->find($id->id)->messages;
        
        return $chat;
    }

    public function download(Chat $id)
    {
        $data=$id->find($id->id)->messages()
            ->when(request()->filled('start_date'),function($query){
                $query->where('created_at','>=',request('start_date'));
            })
            ->when(request()->filled('end_date'),function($query){
                $query->where('created_at','<=',request('end_date'));
            })
            ->when(request()->filled('body'),function($query){
                $query->where('body','REGEXP',request('body'));
            })
            ->get();
        
        return $data;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Chat $id)
    {
        $id->update($request->all());

        return response()->json([
            "status"=>200,
            "message"=>"Chat actualizado correctamente.",
            "data"=>$id
        ],200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Chat $id)
    {
        $id->delete();

        return response()->json([
            "status"=>200,
            "message"=>"Chat eliminado."
        ],200);
    }
}
