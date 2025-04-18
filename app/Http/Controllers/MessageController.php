<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\Connection;
use App\Models\Contact;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use WebSocket\Client;
use Exception;
use Illuminate\Support\Facades\Auth;

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

    public function push($data){
        try {
    
            $client = new Client("ws://127.0.0.1:8081");
            $client->send(json_encode($data));
            $client->close();

        } catch (Exception $e) {
            return $e;
        }
    }

    public function connectmessage(Request $request){
        $chat_id=$request->chat_id;

        $chat_update=Chat::where('id',$request->chat_id)->update([
            'last_message'=>$request->body,
            'unread_message'=>($request->from_me==false) ? Chat::where('id',$request->chat_id)->first()->unread_message+1 : 0,
        ]);

        $data=[
            'body'=>$request->body,
            'number'=>$request->number,
            'chat_id'=>$chat_id,
            'media'=>[
                // [
                //     "filename"=>'http://193.46.198.228:8085/back/public/bg_wp.png',
                //     "caption"=>"Prueba media desde CRM"
                // ]
            ]
        ];

        $connection=Connection::where('id',1)->first();

        if($connection->status=='DISCONNECTED'){
            
            return response()->json([
                "status"=>400,
                "message"=>"Whatsapp desconectado."
            ],400);

        }else{ 

            $ws=$this->push($data);
            
        }
    }

    public function checkdir($name){
        if(!is_dir($name)){
            echo $name."<br>";
            mkdir(public_path($name),0777,true);
            return true;
        }else{
            return true;
        }
    }

    public function testdir(Request $request){
        /*
            Vamos a subir un archivo al servidor, el árbol debe ser el siguiente:
                ROOT: Public
                        files
                            year
                                month
                                    day
        */

        //  Comprobamos que exista la carpete con coincidencia: Y/m/d
        $root='files/'.$request->type.'/';
        $name=date('Y/m/d',time()-18000);
        return $this->checkdir($root.$name);
    }

    public function store(Request $request)
    {
        $chat_id=0;
        $user_id=null;

        if(isset($request->chat_id)){ //El chat existe
            
            $chat_id=$request->chat_id;

            Chat::where('id',$request->chat_id)->update([
                'last_message'=>$request->body,
                'unread_message'=>($request->from_me==false) ? Chat::where('id',$request->chat_id)->first()->unread_message+1 : 0
            ]);

            $user_id=Chat::where('id',$request->chat_id)->first()->user_id;
            
        }else{

            //  Buscamos si existe el contacto
            $contact=Contact::where('phone_number',$request->number)->first();
            $contact_id=0;

            if($contact!=null){

                $contact_id=$contact->id;
                $prev_chat=Chat::where('contact_id',$contact_id)->first();

                if($prev_chat!=null){
                    
                    $chat_id=$prev_chat->id;
                    $state=$prev_chat->state;

                    if($prev_chat->state=='CLOSED'){
                        $state='PENDING';
                    }

                    Chat::where('id',$chat_id)->update([
                        'last_message'=>$request->body,
                        'unread_message'=>Chat::where('id',$chat_id)->first()->unread_message+1,
                        'state'=>$state
                    ]);

                    $user_id=$prev_chat->user_id;

                }else{

                    $create_chat=Chat::create([
                        'state'=>($request->from_me==true) ? 'OPEN' : 'PENDING',
                        'last_message'=>$request->body,
                        'unread_message'=>($request->from_me==true) ? 0 : 1,
                        'contact_id'=>$contact_id,
                        'user_id'=>($contact_id!=null) ? $contact->user_id : User::where('role',3)->first()->id
                    ]);

                    $chat_id=$create_chat->id;
                    $user_id=($contact_id!=null) ? $contact->user_id : User::where('role',3)->first()->id;

                }

            }else{

                $create_contact=Contact::create([
                    'name'=>$request->notify_name,
                    'phone_number'=>$request->number,
                    'profile_picture'=>"",
                    'user_id'=>User::where('role',3)->first()->id
                ]);
                
                $contact_id=$create_contact->id;

                $create_chat=Chat::create([
                    'state'=>'PENDING',
                    'last_message'=>$request->body,
                    'unread_message'=>1,
                    'contact_id'=>$contact_id,
                    'user_id'=>User::where('role',3)->first()->id
                ]);
    
                $chat_id=$create_chat->id;
                $user_id=User::where('role',3)->first()->id;

            }
        }

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
            'created_by'=>$user_id,
            'chat_id'=>$chat_id
        ];

        $create_message=Message::create($data);

        return response()->json([
            "status"=>200,
            "message"=>"Mensaje creado correctamente.",
            "user_id"=>$user_id,
            "chat_id"=>$chat_id
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
