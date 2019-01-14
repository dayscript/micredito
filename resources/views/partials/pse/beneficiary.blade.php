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
                                  <h3 class="font-weight">Juan Alejandro Abadía Franco</h3>
                                  <strong class="font-line-1">Maestría, Human Rights Law, University Of Kent</strong>
                                  <div class="">
                                    <strong>CC: </strong> <span>1032371488</span>
                                  </div>
                                  <div class="">
                                    <strong>Código: </strong> <span>1032371488</span>
                                  </div>
                                  <div class="">
                                    <strong>Corte al: </strong> <span>1032371488</span>
                                  </div>
                                  <div class="">
                                    <strong>Estatus: </strong> <span>1032371488</span>
                                  </div>
                                </div>
                              </div>
                              <div class="row">
                                <div class="col-md-12 margin-bottom">
                                  <div class="">
                                      <div class=" col-md-8 block background-gray">
                                        <strong>SALDO DE DEUDA</strong>
                                      </div>
                                      <div class="col-md-4 block background-black">
                                         <strong>USD 25.001,92</strong>
                                      </div>
                                  </div>
                                  <div class="">
                                    <div class="col-md-8 block background-gray">
                                      <strong>SALDO MENOS CONDONACION</strong>
                                    </div>
                                    <div class="col-md-4 block background-black">
                                      <strong>USD 25.001,92</strong>
                                    </div>

                                  </div>
                                </div>
                              </div>
                              <div class="row">
                                <div class="col-md-12 margin-bottom">
                                  <p><strong><span>IMPORTANTE:</span> La fecha de corte de su extracto es diferente a la fecha calendario.
                                    Si quiere realizar el pago y no le aparece la cuota verifique en el "plan de pagos",
                                    ingrese a PSE y marque la opción "otro valor". </strong></p>
                                </div>
                              </div>
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

                        <pse-beneficiary-credit-desciption></pse-beneficiary-credit-desciption>

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
