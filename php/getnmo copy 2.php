<?php	
	include_once("ConnectionDB.php");		
	include_once("consult_all_upn.php");   

	// $pndata = 'M1198370-001$GV01';
    // $pndata = 'M1253266-001$BS00'; 
    
    //M1253269-001$JS01 - 05-JUN-23
    //M1266105-001$DV03 - 05-JUN-23

    // $arrayallUpn = array(   ['upn' => 'M1198370-001$GV01'], 
    //                         ['upn' => 'M1198370-001$GV01']
    //                     );
    #Starter:
    $lenghtArrUPN = count($arrayallUpn);    
    $arrayPerrote = array();
    for ($i = 0; $i < $lenghtArrUPN; $i++) { 
        $tempUpn = $arrayallUpn[$i]['upn'];        
        $arrayPerrote[] = foundMoByUpn($tempUpn);
    }

     /**Get server time */
     date_default_timezone_set('America/Denver');
     $dateServer = date('Y-m-d H:i:s');    
     // $hour_actually = date('H:i:s', strtotime($dateServer));    
     $hour_actually = date('H:i:s', strtotime($dateServer . ' +1 hour'));
     // $hour_actually = date('d-m-Y');

    $response = array("boolean" => true, "msg" => "success", 'data' => $arrayPerrote, 'hora' => $hour_actually);                                           
    echo json_encode($response);
    exit();


function foundMoByUpn($upnParam) {             
    $response = array();
    $arrayMos = array();
    $arrayFinal = array(); // This array saves all MOs by a given UPN

    #Line's production Array
    // $arrayLines = ['B1', 'B2', 'B3', 'B4', 'B5'];

    $pndata = $upnParam;
    $linedata = 'B3';
    #Get all MOs related with an UPN given and an specific LINE:
    $queryOracle = "SELECT MO, UPN, CRT_DATE, LINE
                    FROM MOPLANNING
                    WHERE UPN = :partnumber AND LINE = :linedata AND ROWNUM <= 5
                    ORDER BY CRT_DATE ASC"; 

    #Get all MOs about UPN given and an specific LINE:
    // $queryOracle = "
    //                 SELECT MO, UPN, CRT_DATE, LINE
    //                 FROM MOPLANNING
    //                 WHERE UPN = :partnumber
    //                 AND  ROWNUM <= 5
    //                 ORDER BY CRT_DATE ASC"; 
                    // WHERE UPN IN (SELECT UPN FROM SFCMODEL X, ERPITEMMASTER Y WHERE X.UPN = Y.CPN AND UPPER(X.CUSTOMER) = 'MONICA' AND Y.CATEGORY = 'S-' )
                                                        
    #Create an instance of ConnectionDB:
    $connection = new ConnectionDB();
    $conexionOracle = $connection->conectarOracle();
    if ($conexionOracle) {
        $sqlExecute = oci_parse($conexionOracle, $queryOracle);
        if ($sqlExecute) {
            oci_bind_by_name($sqlExecute, ':partnumber', $pndata);     
            oci_bind_by_name($sqlExecute, ':linedata', $linedata);
            oci_execute($sqlExecute);                
                        
            while ($row = oci_fetch_array($sqlExecute)) {                                                           
                $arrayMos[] = array( 'mo' => $row['MO'], 'date' => $row['CRT_DATE']);                                    
                } #end while                                                        
                
                #Get the data from each MO generated above:                    
                foreach ($arrayMos as $item) {  
                    $arrayDataMos = array();                      
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
                
                    //print_r(json_encode($arrayFinal));
                    //$response = array("boolean" => true, "msg" => "success", 'data' => $arrayFinal, 'hora' => $hour_actually);                                           
                    // echo json_encode($response);                        
                    //$connection->cerrarConexionOracle();                                              
                    //exit();                                               
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

        return $arrayFinal;
} #end foundMoByUpn
        
                             	
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