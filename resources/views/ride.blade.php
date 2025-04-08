<!DOCTYPE html>
<html>
<head>
    <title>PRUEBA FACTURA</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" >
</head>
<body>
    <!-- ESTILOS DEL RIDE -->
    <style>
        *{
            font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
        }

        h2{
            font-size: 16px;
            margin: 0;
        }

        h3{
            font-size: 14px;
            margin: 0;
        }

        p,span,strong{
            margin: 0;
            font-size: 12px;
        }

        .Ride__head{
            width: 100%;
        }
        .Ride__head>tr{
            width: 100%;
        }
        .Ride__head>tr>td{
            display: block;
            text-align: start;
        }
        
        .Ride__logo{
            width: 150px;
            height: 150px;
        }

        .Ride__logo>img{
            width: 100%;
            height: 100%;
        }

        .Ride__datesPerson{
            width: 90%;
            border: 1px solid grey;
            border-radius: 20px;
            padding: 10px 10px;
            box-sizing: border-box;
            margin-top: 20px;
        }

        .Ride__datesPerson>h2{
            margin-bottom: 20px;
        }

        .Ride__datesPerson>div{
            width: 100%;
        }

        .Ride__datesPerson>div>label{
            width: 100%;
            margin-bottom: 5px;
            display: block;
        }

        .Ride__datesDoc{
            width: 95%;
            height: 320px;
            border: 1px solid grey;
            border-radius: 20px;
            padding: 10px 10px;
            box-sizing: border-box;
        }

        .Ride__datesDoc>div>img{
            width: 100%;
        }

        .Ride__marginTop{
            margin-top: 40px;
        }

        .Ride__marginBottom{
            margin-bottom: 5px;
        }

        .Ride__client{
            width: 100%;
            margin-top: 10px;
            margin-bottom: 10px;
            font-size: 12px;
        }

        .Ride__items{
            width: 100%;
            font-size: 12px;
            text-align: center;
            border: 1px solid grey;
        }

        .Ride__footer{
            width: 100%;
        }

        .Ride__footer>tr{
            width: 100%;
        }

        .Ride__footer>tr>td{
            width: 50%;
        }

        .Ride__infoAdicional{
            width: 100%;
            font-size: 12px;
            margin-bottom: 10px;
        }

        .Ride__infoPays{
            width: 100%;
            font-size: 12px;
        }

        .Ride__infoAdicional>table,.Ride__infoPays>table{
            border: 1px solid grey;
        }

        .Ride__totals{
            width: 100%;
            font-size: 14px;
        }

        .Ride__totals>tr>td:first-child{
            text-align: left;
        }

        .Ride__totals>tr>td:last-child{
            text-align: right;
        }
    </style>

    <!-- 1) Encabezado del RIDE -->
    <table class="Ride__head">
        <tr>
            <td style="width:50%;">
                
            
                <div class="Ride__datesPerson">
                    <h2>{{ $commercial_name }}</h2>
                    <div>
                        <label>
                            <strong>R. SOCIAL:</strong>
                            <span>{{ $name }}</span>
                        </label>
                        <label>
                            <strong>DIRECCIÓN:</strong>
                            <span>{{ $direction }}</span>
                        </label>
                        <label>
                            <strong>TELÉFONO:</strong>
                            <span>{{ $phone }}</span>
                        </label>
                        <label>
                            <strong>EMAIL:</strong>
                            <span>{{ $email }}</span>
                        </label>
                    </div>
                    {{ $oc==true ? '<strong>OBLIGADO A LLEVAR CONTABILIDAD</strong>' : '' }}
                </div>
            </td>
            <td style="width:50%;">
                <div class="Ride__datesDoc">
                    <h3>R.U.C.: {{ $identification }}</h3>
                    <h3>AMBIENTE: PRODUCCIÓN</h3>
                    <h3>TIPO DE EMISIÓN: NORMAL</h3>
                    <h2 class="Ride__marginTop Ride__marginBottom">FACTURA ELECTRÓNICA: {{ $sequential }}</h2>
                    <h2 class="Ride__marginBottom">NÚMERO DE AUTORIZACIÓN: </h2>
                    <p class="Ride__marginBottom">{{ $access_key }}</p>
                    <p class="Ride__marginBottom"><strong>Fecha y hora de autorización: </strong>{{ $date }}</p>
                    <p><strong>Régimen: </strong>{{ $regimen }}</p>
                    
                    <p>{{ $access_key }}</p>
                </div>
            </td>
        </tr>
    </table>

    <!-- 2) Datos del cliente -->

    <table class="Ride__client" style="border: 1px solid grey;">
        <tr>
            <th style="border-bottom: 1px solid grey;">DATOS DEL CLIENTE</th>
        </tr>
        <tr>
            <td><strong>Razón social: </strong>{{ $client_name }}</td>
        </tr>
        <tr>
            <td><strong>R.U.C./C.I.:</strong> {{ $client_ci}}</td>
        </tr>
        <tr>
            <td><strong>Email:</strong> {{ $client_email}}</td>
        </tr>
        <tr>
            <td><strong>Dirección:</strong> {{ $client_direction }}</td>
        </tr>
    </table>

    <!-- 3) Cuerpo del RIDE -->

    <table class="Ride__items">
        <tr>
            <th>CANT.</th>
            <th>CÓDIGO</th>
            <th>DESCRIPCIÓN</th>
            <th>INF. ADI.</th>
            <th>P. UNI.</th>
            <th>DSCTO</th>
            <th>IMPORTE</th>
        </tr>
        @foreach(json_decode($items) as $item)
            
        @endforeach
    </table>

    <!-- 4) Footer del RIDE -->
    <table class="Ride__footer">
        <tr>
            <td>
                <div>
                    <div class="Ride__infoAdicional">
                        <table>
                            <tr>
                                <th colspan="2" style="width:100%;border-bottom: 1px solid grey;">INFORMACIÓN ADICIONAL</th>
                            </tr>
                            @foreach (json_decode($adicional) as $way)
                                <tr>
                                    <td>{{ strtoupper($way->field) }}</td>
                                    <td>{{ $way->value }}</td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                    <div class="Ride__infoPays">
                        <table>
                            <tr>
                                <th colspan="4" style="width:100%;border-bottom: 1px solid grey;">PAGOS</th>
                            </tr>
                            <tr>
                                <th>Forma de pago</th>
                                <th>Valor</th>
                                <th>Plazo</th>
                                <th>Tiempo</th>
                            </tr>
                            @foreach (json_decode($pay_ways) as $way)
                                <tr>
                                    <td>{{ $way->way }}</td>
                                    <td>{{ $way->value }}</td>
                                    <td>{{ $way->amount }}</td>
                                    <td>{{ $way->way_time }}</td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
            </td>
            <td>
                <table class="Ride__totals">
                    <tr>
                        <td>SUBTOTAL SIN IMPUESTO ($):</td>
                        <td>{{ $subtotal }}</td>
                    </tr>
                    <tr>
                        <td>DESCUENTO ($):</td>
                        <td>{{ $dscto }}</td>
                    </tr>
                    <tr>
                        <td>ICE ($):</td>
                        <td>{{ $ice }}</td>
                    </tr>
                    <tr>
                        <td>IVA 15% ($):</td>
                        <td>{{ $iva15 }}</td>
                    </tr>
                    <tr>
                        <td>IVA 5% ($):</td>
                        <td>{{ $iva5 }}</td>
                    </tr>
                    <tr>
                        <td>PROPINA ($):</td>
                        <td>{{ $propina }}</td>
                    </tr>
                    <tr>
                        <td>VALOR TOTAL ($):</td>
                        <td>{{ $total }}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

</body>
</html>