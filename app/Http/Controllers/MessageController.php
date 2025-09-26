<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\Connection;
use App\Models\Contact;
use App\Models\Message;
use App\Models\User;
use App\Services\MessageService;
use App\Services\SocketService;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    protected SocketService $socket;
    
    public function __construct(SocketService $socket){
        $this->socket=$socket;
    }

    public function index()
    {
        return Message::paginate(10);
    }
    
    public function connectmessage(Request $request){
        $message_service=new MessageService();
        $message=$message_service->handleMessage($this->socket,$request);
        return $message;
    }

    // public function store(Request $request)
    // {
    //     $chat_id=0;
    //     $user_id=null;

    //     if(isset($request->chat_id)){ //El chat existe
    //     if(isset($request->chat_id)){ //El chat existe
            
    //         $chat_id=$request->chat_id;
    //         $chat_id=$request->chat_id;

    //         Chat::where('id',$request->chat_id)->update([
    //             'last_message'=>($request->media_type=="chat") ? $request->body : "Multimedia",
    //             'unread_message'=>($request->from_me==false) ? Chat::where('id',$request->chat_id)->first()->unread_message+1 : 0
    //         ]);
    //         Chat::where('id',$request->chat_id)->update([
    //             'last_message'=>($request->media_type=="chat") ? $request->body : "Multimedia",
    //             'unread_message'=>($request->from_me==false) ? Chat::where('id',$request->chat_id)->first()->unread_message+1 : 0
    //         ]);

    //         $user_id=Chat::where('id',$request->chat_id)->first()->user_id;
    //         $user_id=Chat::where('id',$request->chat_id)->first()->user_id;
            
    //     }else{
    //     }else{

    //         //  Buscamos si existe el contacto
    //         $contact=Contact::where('phone_number',$request->number)->first();
    //         $contact_id=0;
    //         //  Buscamos si existe el contacto
    //         $contact=Contact::where('phone_number',$request->number)->first();
    //         $contact_id=0;

    //         if($contact!=null){
    //         if($contact!=null){

    //             $contact_id=$contact->id;
    //             $prev_chat=Chat::where('contact_id',$contact_id)->first();
    //             $contact_id=$contact->id;
    //             $prev_chat=Chat::where('contact_id',$contact_id)->first();

    //             if($prev_chat!=null){
    //             if($prev_chat!=null){
                    
    //                 $chat_id=$prev_chat->id;
    //                 $state=$prev_chat->state;
    //                 $chat_id=$prev_chat->id;
    //                 $state=$prev_chat->state;

    //                 if($prev_chat->state=='CLOSED'){
    //                     $state='PENDING';
    //                 }
    //                 if($prev_chat->state=='CLOSED'){
    //                     $state='PENDING';
    //                 }

    //                 Chat::where('id',$chat_id)->update([
    //                     'last_message'=>($request->media_type=="chat") ? $request->body : "Multimedia",
    //                     'unread_message'=>Chat::where('id',$chat_id)->first()->unread_message+1,
    //                     'state'=>$state
    //                 ]);
    //                 Chat::where('id',$chat_id)->update([
    //                     'last_message'=>($request->media_type=="chat") ? $request->body : "Multimedia",
    //                     'unread_message'=>Chat::where('id',$chat_id)->first()->unread_message+1,
    //                     'state'=>$state
    //                 ]);

    //                 $user_id=$prev_chat->user_id;
    //                 $user_id=$prev_chat->user_id;

    //             }else{
    //             }else{

    //                 $create_chat=Chat::create([
    //                     'state'=>($request->from_me==true) ? 'OPEN' : 'PENDING',
    //                     'last_message'=>($request->media_type=="chat") ? $request->body : "Multimedia",
    //                     'unread_message'=>($request->from_me==true) ? 0 : 1,
    //                     'contact_id'=>$contact_id,
    //                     'user_id'=>($contact_id!=null) ? $contact->user_id : User::where('role',3)->first()->id
    //                 ]);
    //                 $create_chat=Chat::create([
    //                     'state'=>($request->from_me==true) ? 'OPEN' : 'PENDING',
    //                     'last_message'=>($request->media_type=="chat") ? $request->body : "Multimedia",
    //                     'unread_message'=>($request->from_me==true) ? 0 : 1,
    //                     'contact_id'=>$contact_id,
    //                     'user_id'=>($contact_id!=null) ? $contact->user_id : User::where('role',3)->first()->id
    //                 ]);

    //                 $chat_id=$create_chat->id;
    //                 $user_id=($contact_id!=null) ? $contact->user_id : User::where('role',3)->first()->id;
    //                 $chat_id=$create_chat->id;
    //                 $user_id=($contact_id!=null) ? $contact->user_id : User::where('role',3)->first()->id;

    //             }
    //             }

    //         }else{
    //         }else{
                
    //             $create_contact=Contact::create([
    //                 'name'=>$request->notify_name,
    //                 'phone_number'=>$request->number,
    //                 'profile_picture'=>"",
    //                 'user_id'=>Connection::where('number',$request->to)->first()->user_id
    //             ]);
    //             $create_contact=Contact::create([
    //                 'name'=>$request->notify_name,
    //                 'phone_number'=>$request->number,
    //                 'profile_picture'=>"",
    //                 'user_id'=>Connection::where('number',$request->to)->first()->user_id
    //             ]);
                
    //             $contact_id=$create_contact->id;
    //             $contact_id=$create_contact->id;

    //             $create_chat=Chat::create([
    //                 'state'=>'PENDING',
    //                 'last_message'=>($request->media_type=="chat") ? $request->body : "Multimedia",
    //                 'unread_message'=>1,
    //                 'contact_id'=>$contact_id,
    //                 'user_id'=>Connection::where('number',$request->to)->first()->user_id
    //             ]);
    //             $create_chat=Chat::create([
    //                 'state'=>'PENDING',
    //                 'last_message'=>($request->media_type=="chat") ? $request->body : "Multimedia",
    //                 'unread_message'=>1,
    //                 'contact_id'=>$contact_id,
    //                 'user_id'=>Connection::where('number',$request->to)->first()->user_id
    //             ]);
                
    //             $chat_id=$create_chat->id;
    //             $user_id=Connection::where('number',$request->to)->first()->user_id;
    //         }
    //     }
    //             $chat_id=$create_chat->id;
    //             $user_id=Connection::where('number',$request->to)->first()->user_id;
    //         }
    //     }

    //     if(request()->filled('data')){
    //     if(request()->filled('data')){

    //         $type='image';
    //         $format=$request->fileformat;
    //         $type='image';
    //         $format=$request->fileformat;
            
    //         if($request->filetype==='audio'){
    //             $format='wav';
    //         }
    //         if($request->filetype==='audio'){
    //             $format='wav';
    //         }

    //         $name=$this->testdir($request->filetype);
    //         $filename=$name.'/'.time().'.'.$format;
    //         $name=$this->testdir($request->filetype);
    //         $filename=$name.'/'.time().'.'.$format;
            
    //         $stream=base64_decode($request->data);
    //         $file=file_put_contents($filename, $stream);
    //         $stream=base64_decode($request->data);
    //         $file=file_put_contents($filename, $stream);
            
    //     }else{
    //         $filename=$request->media_url;
    //     }
    //     }else{
    //         $filename=$request->media_url;
    //     }

    //     if(!$request->from_me){
    //         $user_id=Connection::where('number',$request->to)->first()->user_id;
    //     }
    //     if(!$request->from_me){
    //         $user_id=Connection::where('number',$request->to)->first()->user_id;
    //     }

    //     //  Creamos el mensaje
    //     $data=[
    //         'id_message_wp'=>$request->id_message_wp,
    //         'body'=>($request->media_type=='chat') ? $request->body : "Multimedia",
    //         'ack'=>$request->ack,
    //         'from_me'=>$request->from_me,
    //         'to'=>$request->to,
    //         'media_type'=>$request->media_type,
    //         'media_path'=>($request->media_type!='chat') ? $filename : "",
    //         'timestamp_wp'=>$request->timestamp,
    //         'is_private'=>$request->is_private,
    //         'state'=>"G_TEST",
    //         'created_by'=>$user_id,
    //         'chat_id'=>$chat_id,
    //         'temp_signature'=>$request->temp_signature
    //     ];
    //     //  Creamos el mensaje
    //     $data=[
    //         'id_message_wp'=>$request->id_message_wp,
    //         'body'=>($request->media_type=='chat') ? $request->body : "Multimedia",
    //         'ack'=>$request->ack,
    //         'from_me'=>$request->from_me,
    //         'to'=>$request->to,
    //         'media_type'=>$request->media_type,
    //         'media_path'=>($request->media_type!='chat') ? $filename : "",
    //         'timestamp_wp'=>$request->timestamp,
    //         'is_private'=>$request->is_private,
    //         'state'=>"G_TEST",
    //         'created_by'=>$user_id,
    //         'chat_id'=>$chat_id,
    //         'temp_signature'=>$request->temp_signature
    //     ];

    //     $create_message=Message::create($data);
    //     $create_message=Message::create($data);
        
    //     return response()->json([
    //         "status"=>200,
    //         "message"=>"Mensaje creado correctamente.",
    //         "user_id"=>$user_id,
    //         "chat_id"=>$chat_id,
    //         "media"=>$data,
    //         'media_path'=>($request->media_type!='chat') ? $filename : ""
    //     ],200);
    // }
    
    //     return response()->json([
    //         "status"=>200,
    //         "message"=>"Mensaje creado correctamente.",
    //         "user_id"=>$user_id,
    //         "chat_id"=>$chat_id,
    //         "media"=>$data,
    //         'media_path'=>($request->media_type!='chat') ? $filename : ""
    //     ],200);
    // }
    
    public function show(Message $id)
    {
        return response()->json([
            "data"=>$id,
            "status"=>200
        ],200);
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
            "chat_id"=>Message::where('id_message_wp',$request->id_wp)->where('from_me',$request->from_me)->first()->chat_id,
            "user_id"=>Message::where('id_message_wp',$request->id_wp)->where('from_me',$request->from_me)->first()->created_by,
            "temp_signature"=>Message::where('id_message_wp',$request->id_wp)->where('from_me',$request->from_me)->first()->temp_signature
        ],200);
    }
}
