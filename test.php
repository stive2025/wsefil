<?php

$data=array(
    'data'=>json_encode([
        'commercial_name'=>'JUAN',
        'table'=>'1',
        'create_date'=>date('Y/m/d H:i:s',time()-18000),
        'items'=>[],
        'nro_order'=>'23',
        'client_name'=>'STEVEN CESEN',
        'order_number_day'=>"",
        'contributor'=>[
            "id"=>6,
            "created_at"=>"2025-02-20T05:08:09.000000Z",
            "updated_at"=>"2025-04-30T03:28:04.000000Z",
            "name"=>"ESPINOSA MALDONADO THALIA MICHELLE",
            "identification"=>"1150016960001",
            "direction"=>"MERCADILLO 168-20 Y MACARA FRENTE AL ESTADIO REINA DEL CISNE",
            "commercial_name"=>"LOXA FIDELIS",
            "regimen"=>"RIMPE - NEGOCIO POPULAR",
            "phone"=>"0939113754",
            "user_limit"=>-1,
            "doc_limit"=>-1,
            "estab_limit"=>-1,
            "logo_path"=>"1150016960001.png",
            "public_ip"=>"10.8.0.2",
            "zone1_ip"=>"192.168.18.101",
            "zone2_ip"=>"192.168.18.100",
            "zone3_ip"=>"null",
            "nro_prints"=>2
        ],
        'context'=>"cuenta"
    ]
));

echo json_encode($data);

// curl -X POST 'http://10.8.0.2/loxafidelis/services/cuenta.php' -H 'Content-Type: application/json' -D {"data":"{\"commercial_name\":\"JUAN\",\"table\":\"1\",\"create_date\":\"2025\\\/06\\\/26 21:46:46\",\"items\":[],\"nro_order\":\"23\",\"client_name\":\"STEVEN CESEN\",\"order_number_day\":\"\",\"contributor\":{\"id\":6,\"created_at\":\"2025-02-20T05:08:09.000000Z\",\"updated_at\":\"2025-04-30T03:28:04.000000Z\",\"name\":\"ESPINOSA MALDONADO THALIA MICHELLE\",\"identification\":\"1150016960001\",\"direction\":\"MERCADILLO 168-20 Y MACARA FRENTE AL ESTADIO REINA DEL CISNE\",\"commercial_name\":\"LOXA FIDELIS\",\"regimen\":\"RIMPE - NEGOCIO POPULAR\",\"phone\":\"0939113754\",\"user_limit\":-1,\"doc_limit\":-1,\"estab_limit\":-1,\"logo_path\":\"1150016960001.png\",\"public_ip\":\"10.8.0.2\",\"zone1_ip\":\"192.168.18.101\",\"zone2_ip\":\"192.168.18.100\",\"zone3_ip\":\"null\",\"nro_prints\":2},\"context\":\"cuenta\"}"}