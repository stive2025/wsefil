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
use Mockery\Undefined;

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

        if(request()->filled('chat_id')){

            $chat_id=$request->chat_id;
            $chat=Chat::where('id',$request->chat_id)->first();

            $chat_update=Chat::where('id',$request->chat_id)->update([
                'last_message'=>(request()->filled('media')) ? $request->body : "Multimedia",
                'unread_message'=>($request->from_me==false) ? Chat::where('id',$request->chat_id)->first()->unread_message+1 : 0,
            ]);

        }else{

            $contact=Contact::where('phone_number',$request->number)->first();

            $chat=Chat::create([
                'state'=>'OPEN',
                'last_message'=>(request()->filled('media')) ? $request->body : "Multimedia",
                'unread_message'=>1,
                'contact_id'=>$contact->id,
                'user_id'=>$contact->user_id
            ]);
        }

        $media_data=[];

        if(request()->filled('media')){
            //$media=json_decode($request->media);
            $media=$request->media;

            //$media=json_encode($media);
            $media=json_decode($media);

            if(count($media)>0){

                foreach($media as $file){
                    $path=$this->testdir($file->type);
                    $format="";
                    $name=date('H_i_s',time()-18000);

                    if($file->type=='audio'){
                        $format='.m4a';
                    }else if($file->type=='image'){
                        $format='.jpg';
                    }else if($file->type=='video'){
                        $format='.mp4';
                    }else{
                        $format='.pdf';
                    }

                    file_put_contents($path.'/'.$name.$format,base64_decode($file->media));
                    
                    array_push($media_data,[
                        "filename"=>$path.'/'.$name.$format,
                        "caption"=>($file->caption!="") ? $file->caption : ""
                    ]);
                }
            }
        }

        $data=[
            'body'=>$request->body,
            'number'=>$request->number,
            'chat_id'=>$chat->id,
            'from_me'=>true,
            'media'=>$media_data,
            'user_id'=>$chat->user_id,
            'temp_signature'=>$request->tempSignature
        ];

        $connection=Connection::where('user_id',$chat->user_id)->first();

        if($connection->status=='DISCONNECTED'){
            
            return response()->json([
                "status"=>400,
                "message"=>"Whatsapp desconectado."
            ],400);

        }else{

            $ws=$this->push($data);
            return $data;
            
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

    public function testdir($type){
        /*
            Vamos a subir un archivo al servidor, el árbol debe ser el siguiente:
                ROOT: Public
                        files
                            year
                                month
                                    day
        */

        //  Comprobamos que exista la carpete con coincidencia: Y/m/d
        $root='files/'.$type.'/';
        $name=date('Y/m/d',time()-18000);
        $dir=$this->checkdir($root.$name);
        return $root.$name;
    }

    public function store(Request $request)
    {
        $chat_id=0;
        $user_id=null;

        if(isset($request->chat_id)){ //El chat existe
            
            $chat_id=$request->chat_id;

            Chat::where('id',$request->chat_id)->update([
                'last_message'=>($request->media_type=="chat") ? $request->body : "Multimedia",
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
                        'last_message'=>($request->media_type=="chat") ? $request->body : "Multimedia",
                        'unread_message'=>Chat::where('id',$chat_id)->first()->unread_message+1,
                        'state'=>$state
                    ]);

                    $user_id=$prev_chat->user_id;

                }else{

                    $create_chat=Chat::create([
                        'state'=>($request->from_me==true) ? 'OPEN' : 'PENDING',
                        'last_message'=>($request->media_type=="chat") ? $request->body : "Multimedia",
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
                    'user_id'=>User::where('role',2)->first()->id
                ]);
                
                $contact_id=$create_contact->id;

                $create_chat=Chat::create([
                    'state'=>'PENDING',
                    'last_message'=>($request->media_type=="chat") ? $request->body : "Multimedia",
                    'unread_message'=>1,
                    'contact_id'=>$contact_id,
                    'user_id'=>User::where('role',2)->first()->id
                ]);
    
                $chat_id=$create_chat->id;
                $user_id=User::where('role',2)->first()->id;
            }
        }

        // if(request()->filled('data')){
            file_put_contents('./files/prueba2.png', base64_decode($request->data));
        // }

        //  Creamos el mensaje
        $data=[
            'id_message_wp'=>$request->id_message_wp,
            'body'=>($request->media_type=='chat') ? $request->body : "Multimedia",
            'ack'=>$request->ack,
            'from_me'=>$request->from_me,
            'to'=>$request->to,
            'media_type'=>$request->media_type,
            'media_path'=>($request->media_type!='chat') ? $request->filename : "",
            'timestamp_wp'=>$request->timestamp,
            'is_private'=>$request->is_private,
            'state'=>"G_TEST",
            'created_by'=>$user_id,
            'chat_id'=>$chat_id,
            'temp_signature'=>$request->temp_signature
        ];

        $create_message=Message::create($data);

        return response()->json([
            "status"=>200,
            "message"=>"Mensaje creado correctamente.",
            "user_id"=>$user_id,
            "chat_id"=>$chat_id,
            "media"=>$request->body
        ],200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        
    }

    public function testfiles(Request $request){

        //file_put_contents('files/prueba.png', base64_decode($request->data));

        return [
            "status"=>true
        ];
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
            "chat_id"=>Message::where('id_message_wp',$request->id_wp)->where('from_me',$request->from_me)->first()->chat_id,
            "user_id"=>Message::where('id_message_wp',$request->id_wp)->where('from_me',$request->from_me)->first()->created_by,
            "temp_signature"=>Message::where('id_message_wp',$request->id_wp)->where('from_me',$request->from_me)->first()->temp_signature
        ],200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        
    }
}
