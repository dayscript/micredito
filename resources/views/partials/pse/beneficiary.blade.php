@section('content')

  @section('content')
  <div class="container">
      <div class="row">
          <div class="col-md-8 col-md-offset-2">
              <div class="panel panel-default">
                  <div class="panel-heading"><h2>beneficiary</h2></div>

                  <div class="panel-body">
                      @if (session('status'))
                          <div class="alert alert-success">
                              {{ session('status') }}
                          </div>
                      @endif
                  <div class="">
                    <div class="row">
                      <div class="col-md-12">
                        <div class="">
                          <div class="row">
                            <div class="col-md-8">
                              <a href="">
                                <img src="" alt="">
                                <span class="icon voyager-file-text"></span>
                                <span class="oi oi-file"></span>
                                Imprimir
                              </a>
                              <div class="row">
                                <div class="info col-md-12 margin-bottom">
                                  <h3 class="font-weight">{{ $beneficiary->personal->PER_NOMBRES }} {{ $beneficiary->personal->PER_APELLIDOS }}</h3>
                                  <strong class="font-line-1">
                                  {{ $beneficiary->beca->TES_TIPO_ESTUDIO}},
                                  {{ $beneficiary->beca->PUNIV_PROGRAMA_UNIVERSITARIO}},
                                  {{ $beneficiary->beca->UNI_UNIVERSIDAD}}
                                  </strong>
                                  <div class="">
                                    <strong>CC: </strong> <span>{{$beneficiary->identification}}</span>
                                  </div>
                                  <div class="">
                                    <strong>Código: </strong> <span>{{$beneficiary->promo->BEN_CODIGO_GIRO}}</span>
                                  </div>
                                  <div class="">
                                    <strong>Corte al: </strong> <span>
                                    {{ $beneficiary->dataBeneficiario->LADTCPW }}
                                    </span>
                                  </div>
                                  <div class="">
                                    <strong>Estatus: </strong> <span>{{ $beneficiary->personal->EST_ESTADO}}</span>
                                    <span>{{ $beneficiary->personal->EST_ESTADO_DESC }}</span>
                                  </div>
                                </div>
                              </div>
                              <div class="row">
                                <div class="col-md-12 margin-bottom">
                                  <div class="col-md-12 row margin-bottom-10">
                                      <div class=" col-md-7 block background-gray">
                                        <strong>SALDO DE DEUDA</strong>
                                      </div>
                                      <div class="col-md-5 block background-black">
                                         <strong>USD {{ number_format($beneficiary->dataBeneficiario->LMAMPOW, 2) }}</strong>
                                      </div>
                                  </div>
                                  <div class="col-md-12 row margin-bottom-10">
                                    <div class="col-md-7 block background-gray">
                                      <strong>SALDO MENOS CONDONACION</strong>
                                    </div>
                                    <div class="col-md-5 block background-black">
                                      <strong>USD {{ number_format($beneficiary->dataBeneficiario->USU02, 2)}}</strong>
                                    </div>
                                  </div>

                                  @if( $beneficiary->dataBeneficiario->LMNOPDW > 0 )
                                  <div class="col-md-12 row margin-bottom-10">
                                    <div class="col-md-7 block background-gray">
                                      <strong>MORA</strong>
                                    </div>
                                    <div class="col-md-5 block background-red">
                                      <strong>USD {{number_format($beneficiary->dataBeneficiario->LMAMTPW, 2)}} - {{ $beneficiary->dataBeneficiario->LMNOPDW}} días</strong>
                                    </div>
                                    {{-- <div class="col-md-1 color-red">
                                      <span> {{ $beneficiary->dataBeneficiario->LMNOPDW}} días</span>
                                    </div> --}}

                                  </div>
                                    <div class="col-md-12 row margin-bottom-10">
                                      <div class="col-md-7 block background-gray">
                                        <strong>A PAGAR</strong>
                                      </div>
                                      <div class="col-md-5 block ">
                                        <strong>USD {{number_format($beneficiary->getCuotaTotal(), 2)}}</strong>
                                      </div>
                                    </div>
                                  @endif
                                </div>
                              </div>
                              @if(
                                  $beneficiary->personal->EST_ESTADO != 'POE' ||
                                  $beneficiary->personal->EST_ESTADO != 'JPC' ||
                                  $beneficiary->personal->EST_ESTADO != 'JPS' ||
                                  $beneficiary->personal->EST_ESTADO != 'JUR' ||
                                  $beneficiary->personal->EST_ESTADO != 'CAS'
                              )
                              <div class="row">
                                <div class="col-md-12 margin-bottom">
                                  <p><strong><span>IMPORTANTE:</span> La fecha de corte de su extracto es diferente a la fecha calendario.
                                    Si quiere realizar el pago y no le aparece la cuota verifique en el "plan de pagos",
                                    ingrese a PSE y marque la opción "otro valor". </strong></p>
                                </div>
                              </div>
                              @endif
                            </div>
                            <div class="col-md-4">

                              <pse-beneficiary-payment></pse-beneficiary-payment>

                              <div class="row">
                                <div class="pse-information col-md-12 margin-bottom">
                                  <div class="block_0info" id="block_0info">
                                    <div class="pmsg" style="display:none">
                                      <div class="pmsg_zone" id="pmsg_zone"
                                           style="display: block; overflow: hidden; height: 292px; margin: 0px; padding: 0px; width: 340px; opacity: 1;">
                                      <div class="pmsg_holder">
                                        <h1><a href="#"><img src="images/pmsg_close.jpg"></a>TENGA EN CUENTA</h1>
                                        <div>
                                        <ul>
                                          <li>La mayoría de los bancos requieren que las cuentas estén previamente inscritas para pago por PSE.</li>
                                          <li>Los bancos tienen límites en montos y números de transacciones por día y/o medio de pago.</li>
                                          <li>Si su operación es rechazada por una falla técnica o le aparece un mensaje <b>" transacción se encuentra PENDIENTE ..."</b>, espere mínimo 30 minutos y reintente.</li>
                                          <li>Para verificar las condiciones de pago de su banco a través de PSE, le sugerimos consultar el link
                                            <a href="https://www.psepagos.com.co/mas_informacion/acercade.htm" target="pse">Información bancaria para uso de PSE</a></li>
                                        </ul>
                                        </div>
                                      </div>
                                      </div>
                                    </div>
                                    <center>
                                      <img src="https://micredito.colfuturo.org/micredito2014/images/info.jpg">
                                      <br>
                                      <b>Consulte Información importante sobre los pagos PSE</b>
                                    </center>
                                  </div>
                                </div>
                              </div>
                              <div class="row">
                                <div class="pse-exterior col-md-12 margin-bottom" style="text-align:center">
                                  <div class="">CUENTA EN EL EXTERIOR</div>
                                  <div class="">
                                    <a href="#" onclick="showExternalAccount(''); return false;">Ver datos >></a>
                                  </div>
                                </div>
                              </div>

                              <div class="row" style="text-align:center">
                                <div class="col-md-12">
                                  <a href="#">Generar Excel</a>
                                </div>
                              </div>
        										</div>
                          </div>
                        </div>

                        <div class="container">
                          <div class="row">
                            <div class="col-md-12 row margin-bottom">
                              <a href="#"><strong>Pago en el exterior</strong></a>
                            </div>
                          </div>
                        </div>

                        {{-- <pse-beneficiary-credit-desciption></pse-beneficiary-credit-desciption> --}}

                        <div class="container">
                          <div class="row">
                            <div class="col-md-12 row">
                              <ul class="beneficiary-menu ">
                                <li>RESUMEN</li>
                                <li>EXTRACTO</li>
                                <li>MOVIMIENTO</li>
                                <li>PLAN DE PAGOS</li>
                              </ul>
                            </div>
                          </div>
                        </div>

                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                          <li class="nav-item">
                            <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">RESUMEN</a>
                          </li>
                          <li class="nav-item">
                            <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">EXTRACTO</a>
                          </li>
                          <li class="nav-item">
                            <a class="nav-link" id="messages-tab" data-toggle="tab" href="#messages" role="tab" aria-controls="messages" aria-selected="false">MOVIMIENTO</a>
                          </li>
                          <li class="nav-item">
                            <a class="nav-link" id="settings-tab" data-toggle="tab" href="#settings" role="tab" aria-controls="settings" aria-selected="false">PLAN DE PAGOS</a>
                          </li>
                        </ul>

                        <div class="tab-content">
                          <div class="tab-pane active" id="home" role="tabpanel" aria-labelledby="home-tab">asdsad</div>
                          <div class="tab-pane" id="profile" role="tabpanel" aria-labelledby="profile-tab">...</div>
                          <div class="tab-pane" id="messages" role="tabpanel" aria-labelledby="messages-tab">...</div>
                          <div class="tab-pane" id="settings" role="tabpanel" aria-labelledby="settings-tab">...</div>
                        </div>

                        <script>
                          $(function () {
                            $('#myTab li:last-child a').tab('show')
                          })
                        </script>

                      </div>
                    </div>
                    </div>
                  </div>
              </div>
          </div>
      </div>
  </div>
  @endsection
@stop
