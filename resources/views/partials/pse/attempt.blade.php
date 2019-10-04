<!-- Trigger the modal with a button -->
                                    
    <!-- Modal -->
    <div class="modal fade" id="payment-confirmation" role="dialog">
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
                    <div class="col-md-12"><p><strong>Corte al:</strong> {{$beneficiary->dataBeneficiario->LADTCPW}} </p></div>
                </div>

                <div class="col-md-12 margin-top">
                     <h3>Informacion de la transacción:</h3>
                    
                    <div class="col-md-6 gray" >Código de servicio: </div> <div class="col-md-5 yellow" >{{ $status->ServiceCode }} </div>
                    <br>
                    <div class="col-md-6 gray" >Estado de transacción: </div> <div class="col-md-5 yellow" >{{ $status->State }}  </div>
                    <br>
                    <div class="col-md-6 gray" >Monto: </div> <div class="col-md-5 yellow" >${{ number_format($status->Amount) }} </div>
                    <br>
                    <div class="col-md-6 gray" >Banco: </div> <div class="col-md-5 yellow" >{{ $status->BankName }} </div>
                    <br>
                    <div class="col-md-6 gray" >Cédula:</div> <div class="col-md-5 yellow" >{{ $status->Reference3 }} </div>
                    <br>
                    <div class="col-md-6 gray" >Fecha de solicitud:</div> <div class="col-md-5 yellow" >{{ \Carbon\Carbon::parse($status->SolicitedDate)->format('Y-m-d h:m:d')}} </div>
                    <br>
                    <div class="col-md-6 gray" >Ticked ID:</div> <div class="col-md-5 yellow" >{{ $status->PaymentID }} </div>
                    <br>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            </div>
            </div>
        
        </div>
    </div>