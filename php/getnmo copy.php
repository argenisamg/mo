<?php
	// include_once("connection.php");		
	include_once("ConnectionDB.php");		
	include_once("consult_all_upn.php");		
	
    // $pndata = "";
    $response = array();
    $arrayMos = array();
    $arrayFinal = array();

    /**Get server time */
    date_default_timezone_set('America/Denver');
    $dateServer = date('Y-m-d H:i:s');    
    // $hour_actually = date('H:i:s', strtotime($dateServer));    
    $hour_actually = date('H:i:s', strtotime($dateServer . ' +1 hour'));
    // $hour_actually = date('d-m-Y');

	// if(isset($_POST["pndata"]) && isset($_POST["linedata"])) {
	// 	$pndata = filter_var($_POST["pndata"], FILTER_SANITIZE_STRING);        
    //     $linedata = filter_var($_POST["linedata"], FILTER_SANITIZE_STRING);        
	//  } else {
    //     $response = array("boolean" => false, "msg" => "Empty data received !");                    
    //     echo json_encode($response);  
    //  }
	  
	
	//  if (!empty($pndata) && !empty($linedata)) {        
		#Query para obtener la data con base a la MO:              
        // $pndata = 'M1198370-001$GV01';
        // $pndata = 'M1253266-001$BS00';
        
        /**Aqui voy a ejecutar el Query 'disparador', el cual va a obtener el/los UPN's 
         * Una vez obtenidos, con un ciclo ejecutar cada una de las funciones que devuelven la respuesta
         * al algoritmo de JavaScript.
         * en la variable $pndata, voy a guardar cada uno de los UPS obtenidos en el ciclo y llamar 
         * a la funcion que hace la tarea con ese UPN
        */
        /*
        SELECT MO,UPN,max(CREATEDATE)AS "DATE" FROM SFCMO WHERE CREATEDATE >= SYSDATE -7 
        and UPN IN(SELECT UPN FROM SFCMODEL X, ERPITEMMASTER Y WHERE X.UPN = Y.CPN AND UPPER(X.CUSTOMER) = 'MONICA' AND Y.CATEGORY = 'SA' )
        group by MO, UPN ORDER BY "DATE" DESC;
         */


         #$arrayallUpn

         

        $pndata = 'M1198369-001$LP03';
        $linedata = 'B3';
        $queryOracle = "SELECT MO, UPN, CRT_DATE, LINE
                        FROM MOPLANNING
                        WHERE UPN = :partnumber AND LINE = :linedata AND ROWNUM <= 5
                        ORDER BY CRT_DATE ASC"; 
                         
                               
        #Create an instance of ConnectionDB:
        $connection = new ConnectionDB();
        $conexionOracle = $connection->conectarOracle();
        if ($conexionOracle) {
            $sqlExecute = oci_parse($conexionOracle, $queryOracle);
            if ($sqlExecute) {
                oci_bind_by_name($sqlExecute, ':partnumber', $pndata);     
                oci_bind_by_name($sqlExecute, ':linedata', $linedata);
                oci_execute($sqlExecute);                
                
                //$dateOnly = "";
                while ($row = oci_fetch_array($sqlExecute)) {                                                           
                    $arrayMos[] = array( 'mo' => $row['MO'], 'date' => $row['CRT_DATE']);                    
                   // $dateOnly = $row['CRT_DATE'];
                    } #end while                                                        
                    
                    #Get the data from each MO generated above:                    
                    foreach ($arrayMos as $item) {                        
                        if ($item['mo'] != "") {                                                                                                              
                            $arrayDataMos = getMoInformation($item['mo'], $conexionOracle);
                            $boolean = $arrayDataMos['status'];                           
                            if ($boolean != 1) {
                                $response = array("boolean" => false, "msg" => $arrayDataMos['msg']);                                           
                                echo json_encode($response);
                                exit();
                            }           
                            $arrayFinal[] = $arrayDataMos['data'];
                        }
                    } # end foreach                                      
                        $response = array("boolean" => true, "msg" => "success", 'data' => $arrayFinal, 'hora' => $hour_actually);                                           
                        echo json_encode($response);                        
                        $connection->cerrarConexionOracle();                                              
                        exit();                                               
            } else {
                $response = array("boolean" => false, "msg" => "An error has occured trying to create first statement !");                    
                echo json_encode($response);                          
                exit();                
            } #end if sqlExecute
                       
            } else {
                $response = array("boolean" => false, "msg" => "An error has occurred while trying to connect to Data Base !");                    
                echo json_encode($response);                          
                exit();
            }  #end if $conexionOracle  
                             	
	//  } else {
    //     $response = array("boolean" => false, "msg" => "Data received is empty !");        
    //     echo json_encode($response);
    //     exit();
    //  } #end if !empty

    function getMoInformation($paramReceived, $conexionOracle) {        
        $arrayDataByMo = array();
        $return = array();
        $queryOracle = "SELECT E.UPN, E.MO, F.POSITION, F.CPN, E.CREATEDATE, F.CATEGORY,  B.DESCRIPTION
                            FROM SFCMO E, SFCMOITEM F, SFCCATEGORY B
                            WHERE
                            E.MO = F.MO
                            AND F.CATEGORY = B.CATEGORY
                            AND  E.MO = :modata                            
                            ORDER BY F.POSITION ASC";
        
        if ($conexionOracle) {
            $sqlExecute = oci_parse($conexionOracle, $queryOracle);        
            if ($sqlExecute) {
                oci_bind_by_name($sqlExecute, ':modata', $paramReceived);            
                oci_execute($sqlExecute);             
                // set_time_limit(500);                        
            while ($row = oci_fetch_array($sqlExecute)) {                                                           
                $arrayDataByMo[] = array(  'upn' => $row[0], 
                                            'mo' => $row[1], 
                                            'position' => $row[2], 
                                            'cpn' => $row[3], 
                                            'createdate' => $row[4], 
                                            'category' => $row[5], 
                                            'description' => $row[6]
                                        );                
                } #end while

                if (!empty($arrayDataByMo)) {                                            
                    //$return = $arrayDataByMo;                  
                    $return = array("status" => true, "msg" => "succes", 'data' => $arrayDataByMo);       
                } else {
                    $return = array("status" => false, "msg" => "MO data is empty.");                
                } # end if !empty($arrayDataByMo             

            } else {
                $return = array("status" => false, "msg" => "An error has occured trying to create second statement.");            
            } # end if $sqlExecute
                          
            } else {
                $return = array("status" => false, "msg" => "An error has occurred connecting to Data Base.");                                
            }  #end if $conexionOracle 
        oci_free_statement($sqlExecute);
        return $return;
    
    } # end function getInformation
 ?>