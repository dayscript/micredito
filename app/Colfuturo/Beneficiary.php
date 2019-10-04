<?php

namespace App\Colfuturo;

use Illuminate\Database\Eloquent\Model;
use App\Pse\Pse;
use Carbon\Carbon;

class Beneficiary
{
    
    public function __construct(){

        

    }


    /*
    *
    *
    */

    public function setIdentification($identification){
        $this->identification = $identification;
    }

    /*
    *
    *
    */
 
    public function odbcExecute($sql){
        $conn = odbc_connect("ColfuturoPLI", "pli", "qvwXkY8D") or die ( odbc_errormsg() );
        $result = odbc_exec ($conn, $sql);
        $return 	= odbc_fetch_array ($result);
        odbc_free_result ($result);
        return $return;
    }


    /*
    *
    *
    */

    public function mysqlExecute($sql, $id = false){
        
        $dataBase = mysqli_connect( 'ns11.colfuturo.org',  'micredito',  'Cre2014Col','colfuturo' );        
        $result = mysqli_query( $dataBase, $sql );
        return mysqli_fetch_assoc($result);
    }



    /*
    *
    *
    */
    public function getPersonal(){

        $sql = "SELECT per.PER_NUMERO_DOCUMENTO,
                BEN.BEN_CODIGO,
                ipe.PIPER_EMAIL as PER_CORREO_ELECTRONICO,
                cnf_est.CEST_NOMBRE_LARGO as EST_ESTADO_DESC,
                cnf_est.CEST_NOMBRE_CORTO as EST_ESTADO,
                ben_est.BESTA_FECHA_INICIO as EST_FECHA_INICIO,
                ben_est.BESTA_FECHA_FIN as EST_FECHA_FIN,
                DateDiff (month, ben_est.BESTA_FECHA_INICIO, ben_est.BESTA_FECHA_FIN) + 1 AS Meses,
                ben.BEN_CODIGO_GIRO,
                ben_his_con_max.BEN_PORC_TIPO_COND,
                ben_his_con_max.BEN_PORC_CONDONACION,
                per.PER_NOMBRES,
                per.PER_APELLIDOS,
                PER.PER_CODIGO,
                dom.DOM_TELEFONO as RES_TELEFONO
                FROM PLI.PERSONA per
                JOIN PLI.PLIS_BENEFICIARIO ben ON per.PER_CODIGO = ben.PER_CODIGO
                JOIN PLI.PLI_PERSONA_INFO_PERSONAL	ipe	on PER.PER_CODIGO = ipe.PER_CODIGO
                JOIN PLI.PLIS_BENEFICIARIO_ESTATUS ben_est ON ben.BEN_CODIGO = ben_est.BEN_CODIGO
                JOIN PLI.PLI_CNF_ESTATUS cnf_est ON ben_est.CEST_CODIGO = cnf_est.CEST_CODIGO
                JOIN PLI.PLIS_BENEFICIARIO_DOMICILIO bd	on bd.BEN_CODIGO = ben.BEN_CODIGO
                JOIN PLI.DOMICILIO dom on dom.DOM_CODIGO	= bd.DOM_CODIGO
                LEFT JOIN (
                        SELECT tmp1_ben_his_con.BENCOND_CODIGO,
                                tmp1_ben_his_con.BEN_CODIGO,
                                tmp1_ben_his_con.BENCOND_FECHA_DESDE,
                                tmp1_ben_his_con.BENCOND_PORCENTAJE_PROP_CONDONACION AS BEN_PORC_TIPO_COND,
                                tmp1_ben_his_con.BENCOND_PORCENTAJE_CONDONACION AS BEN_PORC_CONDONACION
                                FROM (
                                    SELECT tmp2_ben_his_con.BEN_CODIGO, MAX(tmp2_ben_his_con.BENCOND_FECHA_DESDE) AS BENCOND_FECHA_DESDE
                                    FROM PLI.PLIS_BENEFICIARIO_HISTORIAL_CONDONACION tmp2_ben_his_con
                                    WHERE getdate() >= tmp2_ben_his_con.BENCOND_FECHA_DESDE
                                    GROUP BY tmp2_ben_his_con.BEN_CODIGO
                                ) AS tmp1_ben_his_con_max
                        JOIN PLI.PLIS_BENEFICIARIO_HISTORIAL_CONDONACION tmp1_ben_his_con ON tmp1_ben_his_con_max.BEN_CODIGO = tmp1_ben_his_con.BEN_CODIGO
                        AND tmp1_ben_his_con_max.BENCOND_FECHA_DESDE = tmp1_ben_his_con.BENCOND_FECHA_DESDE
                )
                AS ben_his_con_max ON ben.BEN_CODIGO = ben_his_con_max.BEN_CODIGO
                WHERE PER.PER_NUMERO_DOCUMENTO = '".$this->identification."'  AND ben_est.BESTA_ESTADO_ACTIVO = 1  AND ben.BEN_CODIGO_GIRO = '".$this->promo->BEN_CODIGO_GIRO."'
                ORDER BY EST_FECHA_FIN DESC";
        
        $this->personal = self::parseToUtf((object)$this->odbcExecute($sql));

    }

       
    /*
    *
    *
    */
    public function getBeca(){

        $sql = "SELECT PLI.TIPO_ESTUDIO.TESTU_NOMBRE AS TES_TIPO_ESTUDIO,
                PLI.PLIS_BENEFICIARIO_PROYECTO_ACADEMICO.BPACA_NOMBRE AS PUNIV_PROGRAMA_UNIVERSITARIO, PLI.AREA_ESTUDIO.AES_AREA_ESTUDIO,
                PLI.UNIVERSIDAD.UNI_UNIVERSIDAD
				FROM    PLI.PLIS_BENEFICIARIO_PROYECTO_ACADEMICO
				INNER JOIN PLI.PLIS_BENEFICIARIO ON PLI.PLIS_BENEFICIARIO_PROYECTO_ACADEMICO.BEN_CODIGO = PLI.PLIS_BENEFICIARIO.BEN_CODIGO
				INNER JOIN PLI.PERSONA ON PLI.PLIS_BENEFICIARIO.PER_CODIGO = PLI.PERSONA.PER_CODIGO
				INNER JOIN PLI.TIPO_ESTUDIO ON PLI.PLIS_BENEFICIARIO_PROYECTO_ACADEMICO.TESTU_CODIGO = PLI.TIPO_ESTUDIO.TESTU_CODIGO
				INNER JOIN PLI.AREA_ESTUDIO ON PLI.PLIS_BENEFICIARIO_PROYECTO_ACADEMICO.AES_CODIGO = PLI.AREA_ESTUDIO.AES_CODIGO
				INNER JOIN PLI.UNIVERSIDAD ON PLI.PLIS_BENEFICIARIO_PROYECTO_ACADEMICO.UNI_CODIGO = PLI.UNIVERSIDAD.UNI_CODIGO
			    WHERE (((PLI.PERSONA.PER_NUMERO_DOCUMENTO)='" . $this->identification . "') AND PLI.PLIS_BENEFICIARIO.BEN_CODIGO_GIRO = '" . $this->promo->BEN_CODIGO_GIRO . "')";

        $this->beca = self::parseToUtf((object)$this->odbcExecute($sql));
    }


    /*
    *
    *
    */
    public function getPromo(){

        $sql = "SELECT MAX(PLI.PERSONA.PER_NUMERO_DOCUMENTO) AS PER_NUMERO_DOCUMENTO,
                MAX(PLI.PLIS_BENEFICIARIO.BEN_CODIGO_GIRO) AS BEN_CODIGO_GIRO,
                                MAX(PLI.PLI_PROMOCION.PROM_ANIO) AS BEN_PROMOCION
                FROM PLI.PLIS_BENEFICIARIO
                INNER JOIN PLI.PERSONA ON PLI.PLIS_BENEFICIARIO.PER_CODIGO = PLI.PERSONA.PER_CODIGO
                INNER JOIN PLI.PLI_PROMOCION ON PLI.PLIS_BENEFICIARIO.PROM_CODIGO = PLI.PLI_PROMOCION.PROM_CODIGO
                INNER JOIN PLI.PLI_PROGRAMA ON PLI.PLI_PROGRAMA.PROG_CODIGO=PLI.PLI_PROMOCION.PROG_CODIGO
                WHERE PLI.PLI_PROGRAMA.PAT_CODIGO=2
                GROUP BY PLI.PERSONA.PER_NUMERO_DOCUMENTO
                HAVING (((PLI.PERSONA.PER_NUMERO_DOCUMENTO)='" . $this->identification . "'))";
        
        $this->promo =  self::parseToUtf((object)$this->odbcExecute($sql));
    
    }


    /*
    *
    *
    */
    function dataBeneficiario(){
    	$libreria = "colfuturo";
        $sql = "SELECT * FROM " . $libreria . ".LINTWEB1 WHERE TRIM(CNNOSSW) = '" . $this->identification . "'";
        $linkPCB = odbc_connect ( 'AS400MYSQL', 'micredito', 'Cre2014Col' );
        $result  = odbc_exec ($linkPCB, $sql);
        $return  = odbc_fetch_array ($result);
        $return['LADTCPW'] = Carbon::parse($return['LADTCPW'])->format('Y-m-d');

        $this->dataBeneficiario = self::parseToUtf((object)$return);
        odbc_free_result ($result);
    }

    /*
    *
    *
    */
    public function getAll(){
        $this->getPromo();
        $this->getBeca();
        $this->getPersonal();
        $this->dataBeneficiario();
    }

    /*
    *
    *
    */
    public function parseToUtf($items){

        if(is_object($items)){
            foreach($items as $key => $item){
                if(is_string($item) || is_int($item)){
                    $items->$key = utf8_encode($item);
                }else{
                    $items = self::parseToUtf($item);
                }
            }
        }
        return $items;
    }


    /*
    *
    *
    */
    function getCuota(){
        $link = odbc_connect ( 'AS400MYSQL', 'micredito', 'Cre2014Col' );
        $libreria = 'colfuturo';
        
		/*
		 * LINWEB4
		*/
		$sql 		= "SELECT *
		FROM " . $libreria . ".LINTWEB4
		WHERE LMNOACQ = '" . $this->dataBeneficiario->LMNOACW . "' and LIDPLPQ = 'PPM'    and LCA3VC > 0  ORDER BY W6DTPD limit 0,1 " ;
		$result 	= odbc_exec ($link, $sql);

		$cuota="0";
		$saldoInicial = self::getSaldo( );
		$saldo = $saldoInicial;

		for ($i = 1; $linWeb4 = odbc_fetch_array ($result); $i++) {
			$saldo -= (float)$linWeb4["LCA3VC"]; // Le resto el Capital
			if( $saldo < 0.001 )
				$saldo = 0;
			if ($i==1)	{
				$cuota = $linWeb4["LCA3VC"] + $linWeb4["LCA3SG"] + $linWeb4["LCA3SGC"] + $linWeb4["LBAMLCQ"] + $linWeb4["LCA3VI"]+$linWeb4["LCA3VIC"];
				// valida que la primera cuota este en el mismo mes de la fecha de corte en caso contrario coloca la cuota en 0
				if ( substr( $linWeb4["W6DTPD"],4,2 ) != substr( $this->dataBeneficiario->LADTCPW,4,2)) {
					$cuota = "0";
				}
			}
		}

		$sqli 		= "SELECT *
		FROM " . $libreria . ".LINTWEB4
		WHERE LMNOACQ = '" . $this->dataBeneficiario->LMNOACW . "' and LIDPLPQ = 'PPS' AND LCA3VC > 0 ORDER BY W6DTPD limit 0,1 ";
		$resulta 	= odbc_exec ($link, $sqli);
		$cuota_sin_condonacion="0";
		$saldos = $saldoInicial;

		for ($i = 1; $linWeb4 = odbc_fetch_array ($resulta); $i++) {
			$saldos -= (float)$linWeb4["LCA3VC"]; // Le resto el Capital
			if( $saldos < 0.001 )
				$saldos = 0;
			if($i==1){
				$cuota_sin_condonacion = $linWeb4["LCA3VC"] + $linWeb4["LCA3SG"] + $linWeb4["LCA3SGC"] + $linWeb4["LBAMLCQ"] + $linWeb4["LCA3VI"]+$linWeb4["LCA3VIC"];
			// valida que la primera cuota este en el mismo mes de la fecha de corte en caso contrario coloca la cuota en 0
			if (substr($linWeb4["W6DTPD"],4,2) != substr( $this->dataBeneficiario->LADTCPW,4,2)) {
				$cuota_sin_condonacion="0";
			}}

		}

		/* VALIDO ACADEMICO */
		$academico = array("PED" => "1", "PEE" => "1","PGE" => "1","PGEP" => "1","PGF" => "1","PGO" => "1","PGOPT" => "1","PGP" => "1","POE" => "1","PPT" => "1","REN" => "1","SUS" => "1");
		$ACA = strtoupper( $this->personal->EST_ESTADO );
		$valido_aca = isset( $academico[$ACA] );


		if( $cuota > 0 && !$valido_aca )
			$cuota_final = $cuota;
		else	{
			if( $cuota_sin_condonacion > 0 && !$valido_aca )
				$cuota_final = $cuota_sin_condonacion;
			else
				$cuota_final = 0;
		}

		return $cuota_final;

    }

    
    /*
    *
    *
    */
    function getSaldo(){
        $link = odbc_connect ( 'AS400MYSQL', 'micredito', 'Cre2014Col' );
        $libreria = 'colfuturo';

		if( !isset( $linWeb2 ) )	{
			/*
			 * LINWEB2
			*/
			$sql 		= "SELECT LMAMCBK
			FROM " . $libreria . ".LINTWEB2
			WHERE LMNOACK = '" . $this->dataBeneficiario->LMNOACW . "'";
			$result 	= odbc_exec ($link, $sql);
			$linWeb2 	= odbc_fetch_array ($result);
		}

		return $linWeb2["LMAMCBK"];

    }


    /*
    *
    *
    */
    function getMora( $conAbonos = false )	{
        
        $link = odbc_connect ( 'AS400MYSQL', 'micredito', 'Cre2014Col' );
        $libreria = 'colfuturo';
		
		$linWeb4T	= array (			"W6DTPD" => 0,
				"LBAMLCQ" => 0,
				"LCA3VI" => 0,
				"LCA3VC" => 0,
				"LCA3SG" => 0,
				"LCA3VC" => 0,
				"LCA3SGC" => 0,
				"LCA3VIC"=> 0,
		);
		$sql 		= "SELECT *
		FROM " . $libreria . ".LINTWEB4
		WHERE LMNOACQ = '" . $this->dataBeneficiario->LMNOACW . "'
		AND W6DTPD <= '" .  str_replace('-','',$this->dataBeneficiario->LADTCPW) . "' AND LIDPLPQ = 'FVE'  ORDER BY W6DTPD";
		$result 	= odbc_exec ($link, $sql);

		for ($i = 0; $linWeb4 = odbc_fetch_array ($result); $i++) {

			$linWeb4T["LCA3SGC"]    += $linWeb4["LCA3SGC"];
			$linWeb4T["LCA3VIC"]    += $linWeb4["LCA3VIC"];
			$linWeb4T["W6DTPD"] 	+= $linWeb4["W6DTPD"];
			$linWeb4T["LBAMLCQ"] 	+= $linWeb4["LBAMLCQ"];
			$linWeb4T["LCA3VI"] 	+= $linWeb4["LCA3VI"];
			//	$linWeb4T["LCA3VC"] 	+= $linWeb4["LCA3VC"];
			$linWeb4T["LCA3SG"] 	+= $linWeb4["LCA3SG"];
			$linWeb4T["LCA3VC"] 	+= $linWeb4["LCA3VC"];
		}
		$conAbonos = $linWeb4T["LCA3VC"] + $linWeb4T["LCA3SG"] + $linWeb4T["LCA3VI"] + $linWeb4T["LBAMLCQ"]+$linWeb4T["LCA3SGC"]+  $linWeb4T["LCA3VIC"];
		
		if( $conAbonos )
			$conAbonos -= min( $conAbonos, self::getAbonos( ) );
		return $conAbonos;
	}


    /*
    *
    *
    */
    function getAbonos( )	{

        $sql = "SELECT SUM(VALOR_PAGADO_USD) AS TOTAL_ABONADO
                FROM PSE_transactions
                WHERE PER_NUMERO_DOCUMENTO = " . $this->dataBeneficiario->CNNOSSW .
                " AND FECHA_REGISTRO >= '".$this->dataBeneficiario->LADTCPW ."'";

		$at_valor = self::mysqlExecute($sql);

		return $at_valor['TOTAL_ABONADO'];

    }
    

    /*
    *
    *
    */
    public function getCuotaTotal( )	{

		$mora = self::getMora( );
		$cuota_final = self::getCuota( );
		$cuota_final += $mora;
		$cuota_final -= self::getAbonos( );

		if( $cuota_final <= 0){
			$cuota_final = 0;
		}
		
		return $cuota_final;

    }

    /*
    *
    *
    */

    public function getCuotaTotalCop(){
        return self::getCuotaTotal() * Pse::trm();
    }
    


    /*
    *
    *
    */
    public function createAttemptPay(){

        $becario['EST_CODIGO'] = 0;
        $becario['RES_CELULAR'] = str_replace(' ', '', $this->personal->RES_TELEFONO);
        $becario['RES_TELEFONO'] = str_replace(' ', '', $this->personal->RES_TELEFONO);
        $trm = str_replace(',','',Pse::trm());
        $sql = "INSERT INTO `PSE_attempts` ( `PER_NUMERO_DOCUMENTO`,`PER_NOMBRES`,`PER_APELLIDOS`, `PER_CORREO_ELECTRONICO`,
                `PER_CORREO_ELECTRONICO2`, `RES_TELEFONO`, `RES_CELULAR`,`BEN_CODIGO_GIRO`, `EST_CODIGO`, `EST_ESTADO`,
                `VALOR_COLFUTURO`,`VALOR_PAGADO_COP`,`VALOR_PAGADO_USD`,`VALOR_IVA`,`VALOR_TIPO`,`VALOR_TRM`, `FECHA_ESTADO`,
                `ESTADO`, `FECHA_REGISTRO`, `IP` ) VALUES (
                '" . $this->promo->PER_NUMERO_DOCUMENTO  . "',
                '" . $this->personal->PER_NOMBRES  . "',
                '" . $this->personal->PER_APELLIDOS  . "',
                '" . $this->personal->PER_CORREO_ELECTRONICO  . "',
                '" . $this->personal->PER_CORREO_ELECTRONICO  . "',
                '" . $becario['RES_TELEFONO']  . "',
                '" . $becario['RES_CELULAR']  . "',
                '" . $this->personal->BEN_CODIGO_GIRO  . "',
                '" . $becario['EST_CODIGO']  . "',
                '" . $this->personal->EST_ESTADO  . "',
                '" . $this->cuota . "',
                '" . $this->paymentCOP . "',
                '" . $this->paymentUSD . "',
                '" . "0" . "',
                '" . $this->type . "',
                '" . $trm . "',
                NOW( ),
                'CREATE',
                NOW( ),
                '" . $_SERVER['REMOTE_ADDR'] . "'
                    )";
        
        $dataBase = mysqli_connect( 'ns11.colfuturo.org',  'micredito',  'Cre2014Col','colfuturo' );        
        $result = mysqli_query( $dataBase, $sql );
        $this->id_attempt = mysqli_insert_id($dataBase);
        return $this->id_attempt;
    
    }


    public function initAttemptPay($PaymentIdentifier){
            $sql = "INSERT INTO PSE_states ( ID_PSE_ATTEMPT, ESTADO, ESTADO_PAGO, ID_FORMA_PAGO,
                            VALOR_PAGADO, TICKETID, ID_CLAVE, ID_CLIENTE, FRANQUICIA,
                            COD_APROBACION, CODIGO_SERVICIO, CODIGO_BANCO,
                            NOMBRE_BANCO, CODIGO_TRANSACCION, CICLO_TRANSACCION,
                            CAMPO1, CAMPO2, CAMPO3, FECHA,
                            ERROR, ERROR_MSG, FECHA_REGISTRO ) VALUES (
                    '" . $this->id_attempt . "',
                    'INIT',
                    '-3',
                    '0',
                    '" . $this->paymentCOP . "',
                    '0',
                    '0',
                    '0',
                    '0',
                    '0',
                    '0',
                    '0',
                    '0',
                    '0',
                    '0',
                    '" . $_SERVER['REMOTE_ADDR'] . "',
                    '" . 'CC' . "',
                    '" . $this->identification . "',
                    " . "NOW()" . ",
                    0,
                    '0',
                    NOW() ) ";
            $dataBase = mysqli_connect( 'ns11.colfuturo.org',  'micredito',  'Cre2014Col','colfuturo' );        
            $result = mysqli_query( $dataBase, $sql );

            $sql = "UPDATE `PSE_attempts` SET `ESTADO` = 'INIT', `ID_PSE` = '" . $PaymentIdentifier . "' WHERE ID_PSE_ATTEMPT = " . $this->id_attempt;

            $result = mysqli_query( $dataBase, $sql );
    }

    /*
    *
    *
    */

    public function updateAttemptState($status, $attempt){

        // if($status->State == "OK") {
        //     $stateName = 'APPROVED';
        // } else if($status->State == "PENDING"){
        //     $stateName = 'PENDING';
        // } else if($status->State == "NOT_AUTHORIZED"){
        //     $stateName = 'REJECTED';
        // } else if($status->State == "FAILED"){
        //     $stateName = 'FAILED';
        // } else {
        //     $stateName = "UNKNOWN";
        // }ESTADO_PAGO ='" . $status->State . "',


        $sql =  "UPDATE PSE_states
                SET ESTADO ='" . $status->State . "',
                ID_FORMA_PAGO ='29',
                VALOR_PAGADO = '" . $status->Amount . "',
                TICKETID ='',
                ID_CLAVE ='123',
                ID_CLIENTE ='" . $status->Reference3 . "',
                FRANQUICIA ='',
                COD_APROBACION = '0',
                CODIGO_SERVICIO ='" . $status->ServiceCode . "',     
                CODIGO_BANCO ='" . $status->BankCode . "',  
                NOMBRE_BANCO ='" . $status->BankName . "',  
                CODIGO_TRANSACCION ='" . $status->TrazabilityCode . "',    
                CICLO_TRANSACCION ='" . $status->CycleNumber . "', 
                CAMPO1 ='" . $status->Reference1 . "',    
                CAMPO2 ='" . $status->Reference2 . "',    
                CAMPO3 ='" . $status->Reference3 . "',    
                FECHA ='" . $status->SolicitedDate . "', 
                ERROR ='0',
                ERROR_MSG ='" . $status->ErrorMessage . "',
                FECHA_REGISTRO = NOW()
                WHERE ID_PSE_ATTEMPT =" . $attempt;
    
            $dataBase = mysqli_connect( 'ns11.colfuturo.org',  'micredito',  'Cre2014Col','colfuturo' );        
            $result = mysqli_query( $dataBase, $sql );
            
            $sql = "UPDATE PSE_attempts
                    SET ESTADO = '" . $status->State . "', FECHA_ESTADO = NOW( )
                    WHERE ID_PSE_ATTEMPT = " . $attempt;

            $result = mysqli_query( $dataBase, $sql );
    }

    /*
    *
    *
    */
    public function getAttemps(){
            $attempts = [];
            $sql = "SELECT ID_PSE_ATTEMPT, ESTADO 
                    FROM PSE_attempts
                    WHERE  PER_NUMERO_DOCUMENTO = " . $this->identification . " 
                    ORDER BY FECHA_ESTADO ASC";
            $dataBase = mysqli_connect( 'ns11.colfuturo.org',  'micredito',  'Cre2014Col','colfuturo' );        
            $result = mysqli_query( $dataBase, $sql );
            while ($obj = mysqli_fetch_object($result)) {
                $attempts [] = $obj;
            }
            return $attempts;
    }



    public function canPay(){
        $sql = "SELECT COUNT(*) AS total, ID_PSE_ATTEMPT, ESTADO, ( SELECT CODIGO_TRANSACCION
                FROM PSE_states
                WHERE PSE_states.ID_PSE_ATTEMPT = PSE_attempts.ID_PSE_ATTEMPT LIMIT 1 ) 
                AS CODIGO_TRANSACCION
                FROM PSE_attempts
                WHERE PER_NUMERO_DOCUMENTO = '" . $this->identification . "'
                AND ( ESTADO = 'BANK_WAIT' OR ESTADO = 'INIT' ) ";
    
        $dataBase = mysqli_connect( 'ns11.colfuturo.org',  'micredito',  'Cre2014Col','colfuturo' );        
        $result = mysqli_query( $dataBase, $sql );
        $result = mysqli_fetch_object($result);
        return $result;
    }


    /*
    *
    *
    */

    public function setCuota($value){

        $this->cuota = $value;
    }
    
    /*
    *
    *
    */

    public function setPaymentCOP($value){

        $this->paymentCOP = $value;
    }
    
    /*
    *
    *
    */

    public function setPaymentUSD($value){

        $this->paymentUSD = $value;
    }
    
    /*
    *
    *
    */

    public function setType($value){
        
        $this->type = $value;
    }
   
    public function tasaPorPromocion ( $promo = null, $estado = null )	{
        /*
        Promoción | POE, PEE, PPT, PGO, PGF, PDR, PDF, PGE | SUR, SUS | PAO, PRC, PDP, PGP | PAAF, PAAC
        1992	Lib + 2	0%	0%	Lib + 2
        1993	Lib + 2	0%	0%	Lib + 2
        1994	Lib + 2	0%	0%	Lib + 2
        1995	Lib + 2	0%	4%	Lib + 2
        1996	Lib + 2	0%	4%	Lib + 2
        1997	Lib + 2	0%	4%	Lib + 2
        1998	Lib + 2	0%	4%	Lib + 8
        1999	Lib + 2	0%	4%	Lib + 8
        2000	Lib + 2	0%	4%	Lib + 8
        2001	Lib + 2	0%	4%	Lib + 8
        2002	Lib + 2	0%	4%	Lib + 8
        2003	Lib + 2	0%	4%	Lib + 8
        2004	Lib + 2	0%	Lib + 3	Lib + 8
        2005	Lib + 2	0%	Lib + 3	Lib + 8
        2006	Lib + 2	0%	Lib + 3	Lib + 8
        2007	Lib + 2	0%	Lib + 3	Lib + 8
        2008	Lib + 2	0%	Lib + 3	Lib + 8
        2009	Lib + 2	0%	Lib + 3	Lib + 8
        2010	Lib + 2	0%	Lib + 3	Lib + 8
        2011	5%		0%	6%		13%
        */
        
        $promo  = ( is_null($promo) )  ? $this->promo->BEN_PROMOCION:$promo;
        $estado = ( is_null($estado) ) ? $this->personal->EST_ESTADO:$promo;

        $data = array( 'type' => '%', 'value' => 0 );
        $promo = (int)$promo;
        $estado = strtoupper( trim( $estado ) );

        if( $promo >= 1992 )	{
            if( in_array( $estado, array( 'POE', 'PEE', 'PPT', 'PGO', 'PGF', 'PDR', 'PDF', 'PGE', 'PDP', 'PGP' ) ) )	{
                if( $promo <= 2010 )	{
                    $data = array( 'type' => 'Lib', 'value' => 2 );
                }
                else if( $promo <= 2012 )	{
                    $data = array( 'type' => '%', 'value' => 5 );
                }

                else if( $promo <= 2013 )	{
                    $data = array( 'type' => '%', 'value' => 6 );
                }

                else if( $promo <= 2014 )	{
                    $data = array( 'type' => '%', 'value' => 7 );
                }

                else if( $promo <= 2015 )	{
                    $data = array( 'type' => '%', 'value' => 7 );
                }
                else if( $promo <= 2017 )	{
                    $data = array( 'type' => '%', 'value' => 7 );
                }


            }
            else if( in_array( $estado, array( 'SUR', 'SUS' ) ) )	{
                $data = array( 'type' => '%', 'value' => 0 );
            }
            else if( in_array( $estado, array( 'PAO', 'PRC','PAEP' ) ) )	{
                if( $promo <= 1994 )	{
                    $data = array( 'type' => '%', 'value' => 0 );
                }
                else if( $promo <= 2003 )	{
                    $data = array( 'type' => '%', 'value' => 4 );
                }
                else if( $promo <= 2010 )	{
                    $data = array( 'type' => 'Lib', 'value' => 3 );
                }
                else if( $promo <= 2012 )	{
                    $data = array( 'type' => '%', 'value' => 6 );
                }

                else if( $promo <= 2013 )	{
                    $data = array( 'type' => '%', 'value' => 8 );
                }

                else if( $promo <= 2014 )	{
                    $data = array( 'type' => '%', 'value' => 9 );
                }
                else if( $promo <= 2015 )	{
                    $data = array( 'type' => '%', 'value' => 9 );
                }
                else if( $promo <= 2017 )	{
                    $data = array( 'type' => '%', 'value' => 9 );
                }


            }
    //		else if( in_array( $estado, array( 'PAAS', 'PAAC' ) ) )	{
            else if( in_array( $estado, array( 'PAEC', 'PAES'  ) ) )	{
                if( $promo <= 1997 )	{
                    $data = array( 'type' => 'Lib', 'value' => 2 );
                }
                else if( $promo <= 2010 )	{
                    $data = array( 'type' => 'Lib', 'value' => 8 );
                }
                else if( $promo <= 2012 )	{
                    $data = array( 'type' => '%', 'value' => 13 );
                }
                else if( $promo <= 2013 )	{
                    $data = array( 'type' => '%', 'value' => 13 );
                }
                else if( $promo <= 2014)	{
                    $data = array( 'type' => '%', 'value' => 15 );
                }
                else if( $promo <= 2015)	{
                    $data = array( 'type' => '%', 'value' => 15 );
                }
                else if( $promo <= 2017)	{
                    $data = array( 'type' => '%', 'value' => 15 );
                }
            }
        }

        switch( $data['type'] )	{
            case 'Lib':
                $data['txt'] = $data['type'] . ' ' . ( (int)$data['value'] >= 0 ? '+' : '-' ) . $data['value'];
                break;
            default:
                $data['txt'] = $data['value'] . $data['type'];
        }

        return $data;

    } 
    
    public function calcIntereses(){

        $interes = $this->tasaPorPromocion()['type'] == 'Lib' ? 'Libor (promedio últimos 3 meses)':'Tasa Fija';
        $lib_v = ($this->dataBeneficiario->LMRTNMW * 100 ) - ($this->tasaPorPromocion()['type'] == 'Lib' ? $this->tasaPorPromocion()['value']:0 );
        
        //  $EstadoL=array("PDP"=>"1","PGP"=>"1");
        $Becario_libor= isset($EstadoL[strtoupper( $this->personal->EST_ESTADO )])? "mas2" : "";
					
        if ($Becario_libor == "mas2"){
            $libor_t="libor + 2 ";
            $libor_a= ( $lib_v + 2 );
        }else { 
            $libor_a= $this->dataBeneficiario->LMRTNMW * 100 ;
            // $libor_t=( $values['type'] == 'Lib' ? $values['txt'] . ' ' : '' );
        }


        return [
                'interes'=>$interes,
                'lib_v'=> $lib_v,
            ];

    }


    
}
