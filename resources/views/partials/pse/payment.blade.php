<div class="">
    <div class="row">
        <div class="pse col-md-12 margin-bottom" style="text-align:center">
        <h4>PAGO CON CUENTA EN COLOMBIA</h4>
        <button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal">
            <img src="https://micredito.colfuturo.org/micredito2014/images/pse.png" width="180" height="80" alt="Pagos seguros en linea">
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
        <h4 class="modal-title">{{$beneficiary->personal->PER_NOMBRES}} {{$beneficiary->personal->PER_APELLIDOS}}</h4>
        </div>
        <div class="modal-body">
            <div class="col-md-12">
            <div class="col-md-3">
                <strong>CC:</strong>
                <span>{{$beneficiary->identification}}</span>
            </div>
            <div class="col-md-4">
                <strong>CÓDIGO:</strong>
            <span>{{$beneficiary->promo->BEN_CODIGO_GIRO}}</span>
            </div>
            <div class="col-md-5">
                <strong>TRM: {{ \App\Pse\Pse::trm() }}</strong> al {{\Carbon\Carbon::now()->toDateString()}}
            </div>
            </div>

            <div class="col-md-12 corte"><p>Corte al: {{$beneficiary->dataBeneficiario->LADTCPW}} </p></div>

            <div class="col-md-12 margin-top">
                {!! Form::open(['url' => 'foo/bar']) !!}
                <div class="col-md-12">
                <div class="col-md-6">
                    {{  Form::radio('opt_pay', 'COL',['checked'],['id'=>'opt_pay_col']) }}
                    {{  Form::label('Valor calculado por COLFUTURO *','Valor calculado por COLFUTURO *')}}
                    @if( $beneficiary->getMora() > 0 )
                    <span class="nota">* Incluye la cuota actual y los valores en mora</span>
                    @endif
                </div>
                <div class="col-md-3">
                    {{  Form::label('COP', 'COP 6.687.098,00', ['class' => 'awesome'])}}
                </div>
                <div class="col-md-3">
                    {{  Form::label('USD', 'USD 2.129,52', ['class' => 'awesome'])}}
                </div>
                </div>
                <div class="col-md-12">

                <div class="col-md-6">
                    {{ Form::radio('opt_pay', 'OTR', false, ['id'=>'opt_pay_otr']) }}
                    {{  Form::label('Otro valor','Otro valor')}}

                </div>
                
                <div class="col-md-3">
                    {{ Form::label('COP', 'COP') }}
                    {{ Form::text('COP','',['size'=>'10']) }}
                </div>
                <div class="col-md-3">
                    {{ Form::label('USD', 'USD') }}
                    {{ Form::text('USD','',['size'=>'10']) }}
                </div>

                <div class="pay">
                    {{ Form::submit('PAGAR',['class' => 'btn-pse']) }}
                </div>
                
                </div>
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

