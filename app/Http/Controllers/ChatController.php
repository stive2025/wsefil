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

        if(request('download')==false){
            
            return $data;

        }else{

            $items=[];

            $data=[
                "client_name"=>Contact::where('id',$id->contact_id)->first()->name,
                "client_phone"=>Contact::where('id',$id->contact_id)->first()->phone_number,
                "items"=>json_encode($items)
            ];
            
            $invoice=Pdf::loadView('ride',$data);
            
            return base64_encode($invoice->download('prueba.pdf'));

        }
    }

    public function generateRIDE(){
        $items=[
            array(
                'from_me'=>true,
                'text'=>'Hola como estás?',
                'timestamp'=>'11:00'
            ),
            array(
                'from_me'=>true,
                'text'=>'Vas a ir a la U?',
                'timestamp'=>'11:01'
            ),
            array(
                'from_me'=>false,
                'text'=>'Todo bien y tú',
                'timestamp'=>'11:05'
            ),
            array(
                'from_me'=>true,
                'text'=>'No creo que vaya',
                'timestamp'=>'11:05'
            )
        ];

        $data=[
            'client_name'=>"STEVEN RAFAEL CESEN PACCHA",
            'client_phone'=>'593978950498',
            'identification'=>'1150575338001',
            'email'=>'steven.r.cesen@hotmail.com',
            'access_key'=>'1602202501115057533800110010010000001551224567811',
            'sequential'=>'001-001-000000155',
            'direction'=>'AV. MANUEL AGUSTIN AGUIRRE Y MAXIMILIANO RODRIGUEZ',
            'date'=>'2025-01-27T10:32:01',
            'phone'=>'0978950498',
            'regimen'=>'GENERAL',
            'oc'=>false,
            'client_name'=>'CALDERON ORDOÑEZ MARIA ANTONIETA',
            'client_ci'=>'1100660032',
            'client_email'=>'wilson@gmail.com',
            'client_direction'=>'AV. DE LAS AMERICAS',
            'subtotal'=>15.00,
            'iva15'=>2.25,
            'iva5'=>0,
            'ice'=>0,
            'dscto'=>0,
            'total'=>17.25,
            'propina'=>0,
            'items'=>json_encode($items)
        ];

        $invoice=Pdf::loadView('ride',$data);
        
        return $invoice->download('prueba.pdf');
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
        }

        Chat::where('id',$id->id)
            ->update([
                "last_message"=>$request->body,
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
