@section('content')

  @section('content')
  <div class="container">
      <div class="row">
          <div class="col-md-8 col-md-offset-2">
              <div class="panel panel-default">
                  <div class="panel-heading">Dashboard</div>

                  <div class="panel-body">
                      @if (session('status'))
                          <div class="alert alert-success">
                              {{ session('status') }}
                          </div>
                      @endif

                  <div class="col-md-12">

                    <div class="col-md-8">
                      <a href="">
                        <img src="" alt="">
                        Imprimir
                      </a>
                      <div class="personal info">
                        <h3>Juan Alejandro Abadía Franco</h3>
                        <span>Maestría, Human Rights Law, University Of Kent</span>
                        <div class="">
                          <span>CC</span> <span>1032371488</span>
                        </div>
                        <div class="">
                          <span>Código</span> <span>1032371488</span>
                        </div>
                        <div class="">
                          <span>Corte al</span> <span>1032371488</span>
                        </div>
                        <div class="">
                          <span>Estatus</span> <span>1032371488</span>
                        </div>
                      </div>
                      <div class="">
                        <div class="">
                            SALDO DE DEUDA  USD 25.001,92
                        </div>
                        <div class="">
                            SALDO MENOS CONDONACION  USD 25.001,92
                        </div>
                      </div>
                      <div class="">
                        <p>IMPORTANTE: La fecha de corte de su extracto es diferente a la fecha calendario.
                          Si quiere realizar el pago y no le aparece la cuota verifique en el "plan de pagos",
                          ingrese a PSE y marque la opción "otro valor". </p>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="pse" style="text-align:center">
                        <h4>PAGO CON CUENTA EN COLOMBIA</h4>
                        <img src="https://micredito.colfuturo.org/micredito2014/images/pse.png" width="180" height="80" alt="Pagos seguros en linea">
                      </div>

                      <div class="pse-information">
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
                      <div class="pse-exterior" style="text-align:center">
                        <div class="">CUENTA EN EL EXTERIOR</div>
                        <div class="">
                          <a href="#" onclick="showExternalAccount(''); return false;">Ver datos >></a>
                        </div>
                      </div>

                      <div class="" style="text-align:center">
                          <a href="#">Generar Excel</a>
                      </div>
										</div>
                    <div class="col-md-12">
                      <ul class="beneficiary-menu">
                        <li>RESUMEN</li>
                        <li>EXTRACTO</li>
                        <li>MOVIMIENTO</li>
                        <li>PLAN DE PAGOS</li>
                      </ul>
                    </div>

                  </div>

                  </div>
              </div>
          </div>
      </div>
  </div>
  @endsection
@stop
