<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use Barryvdh\DomPDF\Facade\Pdf;
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
        if(request('download')==false){
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

        }else{



        }
    }

    public function generateRIDE(){
        $items=[
            array(
                'codigo'=>'ITEM_1',
                'name'=>'PRODUCTO 1',
                'info'=>'',
                'quantity'=>1,
                'subtotal'=>10.00,
                'iva'=>'IVA 15',
                'dscto'=>0.00,
                'importe'=>10.00,
            ),
            array(
                'codigo'=>'ITEM_2',
                'name'=>'PRODUCTO 2',
                'quantity'=>1,
                'subtotal'=>5.00,
                'iva'=>'IVA 15',
                'info'=>'',
                'dscto'=>0.00,
                'importe'=>5.00,
            ),
            array(
                'codigo'=>'ITEM_1',
                'name'=>'PRODUCTO 1',
                'info'=>'',
                'quantity'=>1,
                'subtotal'=>10.00,
                'iva'=>'IVA 15',
                'dscto'=>0.00,
                'importe'=>10.00,
            ),
            array(
                'codigo'=>'ITEM_2',
                'name'=>'PRODUCTO 2',
                'quantity'=>1,
                'subtotal'=>5.00,
                'iva'=>'IVA 15',
                'info'=>'',
                'dscto'=>0.00,
                'importe'=>5.00,
            ),
            array(
                'codigo'=>'ITEM_1',
                'name'=>'PRODUCTO 1',
                'info'=>'',
                'quantity'=>1,
                'subtotal'=>10.00,
                'iva'=>'IVA 15',
                'dscto'=>0.00,
                'importe'=>10.00,
            ),
            array(
                'codigo'=>'ITEM_2',
                'name'=>'PRODUCTO 2',
                'quantity'=>1,
                'subtotal'=>5.00,
                'iva'=>'IVA 15',
                'info'=>'',
                'dscto'=>0.00,
                'importe'=>5.00,
            ),
            array(
                'codigo'=>'ITEM_1',
                'name'=>'PRODUCTO 1',
                'info'=>'',
                'quantity'=>1,
                'subtotal'=>10.00,
                'iva'=>'IVA 15',
                'dscto'=>0.00,
                'importe'=>10.00,
            ),
            array(
                'codigo'=>'ITEM_2',
                'name'=>'PRODUCTO 2',
                'quantity'=>1,
                'subtotal'=>5.00,
                'iva'=>'IVA 15',
                'info'=>'',
                'dscto'=>0.00,
                'importe'=>5.00,
            ),
            array(
                'codigo'=>'ITEM_1',
                'name'=>'PRODUCTO 1',
                'info'=>'',
                'quantity'=>1,
                'subtotal'=>10.00,
                'iva'=>'IVA 15',
                'dscto'=>0.00,
                'importe'=>10.00,
            ),
            array(
                'codigo'=>'ITEM_2',
                'name'=>'PRODUCTO 2',
                'quantity'=>1,
                'subtotal'=>5.00,
                'iva'=>'IVA 15',
                'info'=>'',
                'dscto'=>0.00,
                'importe'=>5.00,
            ),
            array(
                'codigo'=>'ITEM_1',
                'name'=>'PRODUCTO 1',
                'info'=>'',
                'quantity'=>1,
                'subtotal'=>10.00,
                'iva'=>'IVA 15',
                'dscto'=>0.00,
                'importe'=>10.00,
            ),
            array(
                'codigo'=>'ITEM_2',
                'name'=>'PRODUCTO 2',
                'quantity'=>1,
                'subtotal'=>5.00,
                'iva'=>'IVA 15',
                'info'=>'',
                'dscto'=>0.00,
                'importe'=>5.00,
            ),
            array(
                'codigo'=>'ITEM_1',
                'name'=>'PRODUCTO 1 CON CABLE USB Y MONTAJE',
                'info'=>'',
                'quantity'=>1,
                'subtotal'=>10.00,
                'iva'=>'IVA 15',
                'dscto'=>0.00,
                'importe'=>10.00,
            ),
            array(
                'codigo'=>'ITEM_2',
                'name'=>'PLACA DE DESARROLLO ESP32 CAM WIFI Y BLUETOOTH CAMARA OV2640',
                'quantity'=>1,
                'subtotal'=>5.00,
                'iva'=>'IVA 15',
                'info'=>'',
                'dscto'=>0.00,
                'importe'=>5.00,
            ),
            array(
                'codigo'=>'ITEM_1',
                'name'=>'PRODUCTO 1',
                'info'=>'',
                'quantity'=>1,
                'subtotal'=>10.00,
                'iva'=>'IVA 15',
                'dscto'=>0.00,
                'importe'=>10.00,
            ),
            array(
                'codigo'=>'ITEM_2',
                'name'=>'PRODUCTO 2',
                'quantity'=>1,
                'subtotal'=>5.00,
                'iva'=>'IVA 15',
                'info'=>'',
                'dscto'=>0.00,
                'importe'=>5.00,
            ),
            array(
                'codigo'=>'ITEM_1',
                'name'=>'PRODUCTO 1',
                'info'=>'',
                'quantity'=>1,
                'subtotal'=>10.00,
                'iva'=>'IVA 15',
                'dscto'=>0.00,
                'importe'=>10.00,
            ),
            array(
                'codigo'=>'ITEM_2',
                'nombre'=>'PRODUCTO 2',
                'cantidad'=>1,
                'subtotal'=>5.00,
                'iva'=>'IVA 15',
                'info'=>'',
                'dscto'=>0.00,
                'importe'=>5.00,
            )
        ];

        $pay_way=[
            [
                'way'=>'SIN UTILIZACION DEL SISTEMA FINANCIERO',
                'value'=>10.00,
                'amount'=>30,
                'way_time'=>'DIAS'
            ],
            [
                'way'=>'CON UTILIZACION DEL SISTEMA FINANCIERO',
                'value'=>7.25,
                'amount'=>30,
                'way_time'=>'DIAS'
            ]
        ];

        $adicional=[
            [
                'field'=>'nota',
                'value'=>'P/R CONSUMO EN ESTABLECIMIENTO'
            ],
            [
                'field'=>'pago',
                'value'=>'7.25 CON CODIGO AHORITA 74469698'
            ]
        ];

        $data=[
            'items'=>json_encode($items),
            'commercial_name'=>'INTELIVE',
            'name'=>'CESEN PACCHA STEVEN RAFAEL',
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
            'pay_ways'=>json_encode($pay_way),
            'adicional'=>json_encode($adicional)
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
