<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        if(Auth::user()->tokenCan('chats.filter.agent')){
            $contactos=Contact::when(request()->filled('name'),function($query){
                $query->where('name','REGEXP',request('name'));
            })
                ->paginate(7);
        }else{
            $contactos=Contact::when(request()->filled('name'),function($query){
                $query->where('name','REGEXP',request('name'));
            })
                ->where('user_id',Auth::user()->id)
                ->paginate(7);
        }

        foreach($contactos as $contacto){
            $contacto->chat=$contacto->find($contacto->id)->chats()->first();
        }

        return $contactos;
    }
    
    public function indexChats(Request $request)
    {
        $contacts=Contact::when(request()->filled('name'),function($query){
                $query->where('name','REGEXP',request('name'));
            })
            ->when(request()->filled('phone'),function($query){
                $query->where('phone_number','REGEXP',request('phone'));
            })
            ->when(request()->filled('id'),function($query){
                $query->where('id',request('id'));
            })
            ->where('user_id',Auth::user()->id)
            ->paginate(7);

        foreach($contacts as $contact){

            try {
                $contact->chat=$contact->find($contact->id)->chats()->first();
                $contact->chat->ack=$contact->chat->find($contact->chat->id)->messages()->orderby('id','DESC')->first()->ack;   
            } catch (\Throwable $th) {
                $contact->chat=[];
                // $contact->chat->ack="";
            }

        }

        return $contacts;

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {   

        if(preg_match('/^\d{11,}$/',$request->phone_number)){

            if(Contact::where('phone_number',$request->phone_number)->first()==null){

                $data=[
                    'name'=>$request->name,
                    'phone_number'=>$request->phone_number,
                    'profile_picture'=>$request->profile_picture,
                    'user_id'=>Auth::user()->id,
                    'sync_id'=>$request->sync_id,
                    'count_edits'=>0
                ];

                $create_contact=Contact::create($data);

                return response()->json([
                    "status"=>200,
                    "message"=>"Contacto creado correctamente.",
                    "data"=>$create_contact
                ],200);

            }else{

                return response()->json([
                    "status"=>400,
                    "message"=>"Contacto ya existe."
                ],200);
                
            }

        }else{
            return response()->json([
                "status"=>400,
                "message"=>"Contacto no cumple formato para un número de whatsapp."
            ],200);
        }

    }

    public function storeImport(Request $request)
    {   

        if(preg_match('/^\d{11,}$/',$request->phone_number)){

            if(Contact::where('phone_number',$request->phone_number)->first()==null){

                $data=[
                    'name'=>$request->name,
                    'phone_number'=>$request->phone_number,
                    'profile_picture'=>$request->profile_picture,
                    'user_id'=>$request->user_id
                ];

                $create_contact=Contact::create($data);

                return [
                    "status"=>200
                ];

            }else{
                return [
                    "status"=>"FOUND"
                ];
            }
        }
    }

    /**
     * Display the specified resource.
    */
    public function show(Contact $id)
    {
        return $id;
    }
    
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Contact $id)
    {
        if($id->count_edits>0){
            
            return response()->json([
                "status"=>400,
                "message"=>"Contacto ya ha sido actualizado."
            ],200);

        }else{

            $id->update($request->all());
            
            return response()->json([
                "status"=>200,
                "message"=>"Contacto actualizado correctamente.",
                "data"=>$id
            ],200);

        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Contact $id)
    {
        $id->delete();
        return response()->json([
            "status"=>200,
            "message"=>"Contacto eliminado."
        ],200);
    }
}
