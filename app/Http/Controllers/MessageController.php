<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\Contact;
use App\Models\Message;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Message::paginate(10);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //  Creamos el mensaje
        $chat_id=0;

        if(isset($request->chat_id)){ //El chat existe
            
            $chat_id=$request->chat_id;

            Chat::where('id',$request->chat_id)->update([
                'last_message'=>$request->body,
                'unread_message'=>Chat::where('id',$request->chat_id)->first()->unread_message+1,
            ]);
            
        }else{

            //  Buscamos si existe el contacto
            $contact=Contact::where('phone_number',$request->number)->first();
            $contact_id=0;

            if($contact!=null){

                $contact_id=$contact->id;
                //  Busco un chat ABIERTO con este contact_id
                $prev_chat=Chat::where('contact_id',$contact_id)->where('state','OPEN')->first();

                if($prev_chat!=null){
                    
                    $chat_id=$prev_chat->id;

                    Chat::where('id',$chat_id)->update([
                        'last_message'=>$request->body,
                        'unread_message'=>Chat::where('id',$chat_id)->first()->unread_message+1,
                    ]);

                }else{

                    $create_chat=Chat::create([
                        'state'=>'OPEN',
                        'last_message'=>$request->body,
                        'unread_message'=>1,
                        'contact_id'=>$contact_id,
                        'user_id'=>$request->user_id
                    ]);
        
                    $chat_id=$create_chat->id;
                    
                }

            }else{
                $create_contact=Contact::create([
                    'name'=>"+$request->number",
                    'phone_number'=>$request->number,
                    'profile_picture'=>"",
                    'user_id'=>$request->user_id
                ]);

                $contact_id=$create_contact->id;

                $create_chat=Chat::create([
                    'state'=>'OPEN',
                    'last_message'=>$request->body,
                    'unread_message'=>1,
                    'contact_id'=>$contact_id,
                    'user_id'=>$request->user_id
                ]);
    
                $chat_id=$create_chat->id;
            }
        }

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
            'chat_id'=>$chat_id
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
            "message"=>"ACK actualizado.",
            "data"=>Message::where('id_message_wp',$request->id_wp)->where('from_me',$request->from_me)->first()->chat_id
        ],200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        
    }
}
