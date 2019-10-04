<div class="">
    <div class="row">
        <div class="pse col-md-12 margin-bottom" style="text-align:center">
        <h4>PAGO CON CUENTA EN COLOMBIA</h4>
        <button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal">
            <img class="img-pse" width="180" height="80" alt="Pagos seguros en linea">
        </button>
        </div>
    </div>
    <!-- Trigger the modal with a button -->
                                    
    <!-- Modal -->
    <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
    
        <!-- Modal content-->
        <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Confirmación de pago</h4>
        </div>
        <div class="modal-body">
            <div class="col-md-12 text-center">
                <h3>{{$beneficiary->personal->PER_NOMBRES}} {{$beneficiary->personal->PER_APELLIDOS}}</h3>
            </div>
            <div class="col-md-6">
                <div class="col-md-12">
                    <strong>CC:</strong>
                    <span>{{$beneficiary->identification}}</span>
                </div> 
                <div class="col-md-12">
                    <strong>CÓDIGO:</strong>
                <span>{{$beneficiary->promo->BEN_CODIGO_GIRO}}</span>
                </div>
            </div>
            <div class="col-md-6">
                <div class="col-md-12">
                        <strong>TRM: {{ \App\Pse\Pse::trm() }}</strong> al {{\Carbon\Carbon::now()->toDateString()}}
                </div>
                <div class="col-md-12"><p>Corte al: {{$beneficiary->dataBeneficiario->LADTCPW}} </p></div>
            </div>

            <div class="col-md-12 margin-top">
                {!! Form::open( ['route' => ['pse.beneficiary.pay', $beneficiary->identification], 'id' => 'payment'] ) !!}
                <div class="col-md-12 wrapper">
                    <div class="col-md-6">
                        {{  Form::radio(
                                'opt_pay', 
                                'COL',
                                ( $beneficiary->getCuotaTotalCop() == 0 ) ? '':'checked' ,
                                ['id'=>'opt_pay_col',( $beneficiary->getCuotaTotalCop() == 0 ) ? 'disabled':'']) 
                        }}
                        {{  Form::label('Valor calculado por COLFUTURO *','Valor calculado por COLFUTURO *')}}
                        @if( $beneficiary->getMora() > 0 )
                        <span class="nota">* Incluye la cuota actual y los valores en mora</span>
                        @endif
                    </div>
                    <div class="col-md-3 text-right">
                        {{  Form::label('COP', 'COP $'.number_format($beneficiary->getCuotaTotalCop(), 2) , ['class' => 'awesome'])}}
                    </div>
                    <div class="col-md-3 text-right">
                        {{  Form::label('USD', 'USD $'.number_format($beneficiary->getCuotaTotal(), 2), ['class' => 'awesome'])}}
                    </div>
                </div>

                <div class="col-md-12 wrapper">

                    <div class="col-md-6">
                        {{  Form::radio('opt_pay', 'OTR', false , ['id'=>'opt_pay_otr']) }}
                        {{  Form::label('Otro valor','Otro valor')}}

                    </div>
                    
                    <div class="col-md-3 text-right">
                        {{ Form::label('COP', 'COP') }}
                        {{ Form::text('COP','',['size'=>'10','id'=>'input_cop','name'=>'input_cop']) }}
                    </div>
                    <div class="col-md-3 text-right">
                        {{ Form::label('USD', 'USD') }}
                        {{ Form::text('USD','',['size'=>'10','id'=>'input_usd','name'=>'input_usd']) }}
                    </div>

                    <div class="second-options col-md-12 row">
                        <div class="col-md-12">
                            {{ Form::radio('opt_pay_type','CAP',['checked'],['id'=>'opt_pay_type1','style' => ''] ) }}
                            {{ Form::label('capital', 'Cuota Mensual, excedente a capital.') }}
                            <a class="interrogation" 
                                title="Capital: el mayor valor pagado ser? abonado a capital y deber? 
                                cancelar el mes siguiente la cuota que aparezca en el plan de pagos.  ">
                            </a>
                        </div>
                        <div class="col-md-12">
                            {{ Form::radio('opt_pay_type','ANT',false,['id'=>'opt_pay_type0','style' => ''] ) }}
                            {{ Form::label('anticipada', 'Cuota Mensual, excedente a cuota anticipada.') }}
                            <a class="interrogation" 
                                title="Cuota anticipada:
                                el mayor valor pagado se abonar? a la pr?xima cuota 
                                que tiene en el plan de pagos. Deber? verificar hasta que mes le alcanza a cubrir.
                                ">
                            </a>
                        </div>
                    </div>
                    {{ Form::hidden('trm', \App\Pse\Pse::trm(),['id'=>'trm'] ) }}
                </div>
                @if( !$beneficiary->canPay()->total )
                    <div class="pay">

                        {{ Form::label('Redirect','Procesando transaccion, por favor espere.',['id'=>'redirect','class'=>'hidden']) }}
                        <br>
                        {{ Form::submit('PAGAR',['class' => 'btn-pse']) }}
                        
                    </div>
                @else
                    <div class="pay message-error">
                        En este momento su factura #{{ $beneficiary->canPay()->ID_PSE_ATTEMPT }} presenta un proceso de pago cuya transacción se encuentra 
                        PENDIENTE de recibir confirmación por parte de su entidad financiera, por favor espere unos minutos y vuelva a consultar más tarde 
                        para verificar si su pago fue confirmado de forma exitosa. <br>
                        Si desea mayor información sobre el estado actual de su operación puede comunicarse a nuestras líneas de atención al cliente al 
                        teléfono 57-1-3405394 o enviar sus inquietudes al correo recaudos@colfuturo.org y pregunte por el estado de la transacción: #{{ $beneficiary->canPay()->CODIGO_TRANSACCION}}
                    </div>
                @endif
               
                {!! Form::close() !!}
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        </div>
        </div>
        
    </div>
    </div>
    
</div>

