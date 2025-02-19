<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Message::get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //  Creamos el mensaje
        $data=[
            'id_message_wp'=>$request->id_message_wp,
            'body'=>$request->body,
            'ack'=>$request->ack,
            'from_me'=>$request->from_me,
            'to'=>$request->to,
            'media_type'=>$request->media_type,
            'media_path'=>$request->media_url,
            'timestamp_wp'=>$request->timestamp,
            'is_private'=>$request->is_private,
            'state'=>"G_TEST",
            'created_by'=>$request->user_id,
            'chat_id'=>$request->chat_id
        ];

        $create_message=Message::create($data);

        return response()->json([
            "status"=>200,
            "message"=>"Mensaje creado correctamente."
        ],200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        
    }

    public function updateACK(Request $request)
    {
        $update_ack=Message::where('id_message_wp',$request->id_wp)
            ->where('from_me',$request->from_me)
            ->update([
                "ack"=>$request->ack
            ]);
        
        return response()->json([
            "status"=>200,
            "message"=>"ACK actualizado."
        ],200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        
    }
}
