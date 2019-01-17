<?php

namespace App\Colfuturo;

use Illuminate\Database\Eloquent\Model;
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
 
    public function execute($sql){
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
    public function getPersonal(){

        $sql = "SELECT per.PER_NUMERO_DOCUMENTO,
                BEN.BEN_CODIGO,
                cnf_est.CEST_NOMBRE_LARGO as EST_ESTADO_DESC,
                cnf_est.CEST_NOMBRE_CORTO as EST_ESTADO,
                ben_est.BESTA_FECHA_INICIO as EST_FECHA_INICIO,
                ben_est.BESTA_FECHA_FIN as EST_FECHA_FIN,
                DateDiff (month, ben_est.BESTA_FECHA_INICIO, ben_est.BESTA_FECHA_FIN) + 1 AS Meses,
                ben.BEN_CODIGO_GIRO,
                ben_his_con_max.BEN_PORC_TIPO_COND,
                ben_his_con_max.BEN_PORC_CONDONACION,
                per.PER_NOMBRES,
                per.PER_APELLIDOS
                FROM PLI.PERSONA per
                JOIN PLI.PLIS_BENEFICIARIO ben ON per.PER_CODIGO = ben.PER_CODIGO
                JOIN PLI.PLIS_BENEFICIARIO_ESTATUS ben_est ON ben.BEN_CODIGO = ben_est.BEN_CODIGO
                JOIN PLI.PLI_CNF_ESTATUS cnf_est ON ben_est.CEST_CODIGO = cnf_est.CEST_CODIGO
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
        
        $this->personal = self::parseToUtf((object)$this->execute($sql));

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

        $this->beca = self::parseToUtf((object)$this->execute($sql));
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
        
        $this->promo =  self::parseToUtf((object)$this->execute($sql));
    
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

        $dateCourt = $this->dataBeneficiario->LADTCPW;
        $dataBase = mysqli_connect( 'ns11.colfuturo.org',  'micredito',  'Cre2014Col','colfuturo' );

		$sql = "SELECT SUM(VALOR_PAGADO_USD) AS TOTAL_ABONADO
                FROM PSE_transactions
                WHERE PER_NUMERO_DOCUMENTO = " . $this->dataBeneficiario->CNNOSSW .
                " AND FECHA_REGISTRO >= '".$dateCourt."'";

		$result = mysqli_query( $dataBase, $sql );
        $at_valor = mysqli_fetch_assoc($result);
        
		return $at_valor['TOTAL_ABONADO'];

    }
    

    /*
    *
    *
    */
    function getCuotaTotal( )	{

		$mora = self::getMora( );
		$cuota_final = self::getCuota( );
		$cuota_final += $mora;
		$cuota_final -= self::getAbonos( );

		if( $cuota_final <= 0){
			$cuota_final = 0;
		}
		
		return $cuota_final;

	}


    
}
