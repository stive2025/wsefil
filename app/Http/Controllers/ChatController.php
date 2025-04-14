<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\Contact;
use App\Models\Message;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
            ->when(request()->filled('user_id'),function($query){
                $query->where('user_id',request('user_id'));
            })
            ->when(request()->filled('state'),function($query){
                $query->where('state',request('state'));
            })
            ->where('user_id',Auth::user()->id)
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

    public function search(Chat $id)
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

    public function download(Request $request,Chat $id){
        $items=[];

        

        $data=[
            "client_name"=>Contact::where('id',$id->contact_id)->first()->name,
            "client_phone"=>Contact::where('id',$id->contact_id)->first()->phone_number,
            "items"=>json_encode($items)
        ];

        $invoice=Pdf::loadView('ride',$data);
        $name=$data['client_name'];

        //$filename=Contact::where('id',$id->contact_id)->first()->name.'_'.Contact::where('id',$id->contact_id)->first()->phone_number.'.pdf';
        $filename="Chat_$name.pdf";
        return $invoice->download($filename);
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

    public function transfer(Request $request, Chat $id)
    {
        //  Revisamos si hay mensaje privado
        $last_message="";

        if(request()->filled('is_private')){
            if(request('is_private')==true){
                $data=[
                    'id_message_wp'=>"",
                    'body'=>$request->body,
                    'ack'=>1,
                    'from_me'=>false,
                    'to'=>"",
                    'media_type'=>"chat",
                    'media_path'=>"",
                    'timestamp_wp'=>time()-18000,
                    'is_private'=>true,
                    'state'=>"G_TEST",
                    'created_by'=>Auth::user()->id,
                    'chat_id'=>$id->id
                ];
    
                $create_message=Message::create($data);
                $last_message=$request->body;
            }else{
                $last_message=$id->last_message;
            }
        }

        Chat::where('id',$id->id)
            ->update([
                "last_message"=>$last_message,
                'unread_message'=>intval($id->unread_message)+1,
                "user_id"=>$request->to
            ]);

        return response()->json([
            "status"=>200,
            "message"=>"Chat transferido."
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
