<?php	
	include_once("ConnectionDB.php");	                
    
    #Starter:
    $response = array();       
    $arrayPerrote = array();

    if (isset($_POST["upnsend"])) {      
        $upnsend = filter_var($_POST['upnsend'], FILTER_SANITIZE_STRING);          
        $arrayPerrote[] = foundMOsByUpn($upnsend);                       

        $response = array("boolean" => true, "msg" => "success", 'data' => $arrayPerrote);        
        echo json_encode($response);
        exit();

    } else {
        $response = array("boolean" => false, "msg" => "Empty data receibed.");        
        echo json_encode($response);
        exit();
    }  # end if isset         
     
function foundMOsByUpn($upnParam) {             
    $response = array();
    $arrayMos = array();
    $arrayFinal = array(); // This array saves all MOs by a given UPN
    
    #Get 5 last MOs related with an UPN given:
    // $queryOracle = "
    //                 SELECT MO, UPN, MAX(CRT_DATE) AS \"DATE_TIME\", LINE
    //                 FROM MOPLANNING
    //                 WHERE UPN = :partnumber
    //                 AND LINE <> 'R1'
    //                 GROUP BY MO, UPN, LINE
    //                 ORDER BY \"DATE_TIME\" DESC
    //                 "; 
    $queryOracle = "
                    SELECT MO, UPN, \"DATE_TIME\", LINE
                    FROM (
                            SELECT MO, UPN, MAX(CRT_DATE) AS \"DATE_TIME\", LINE
                            FROM MOPLANNING
                            WHERE UPN = :partnumber
                                AND LINE <> 'R1'
                            GROUP BY MO, UPN, LINE
                            ORDER BY \"DATE_TIME\" ASC
                    ) WHERE ROWNUM <= 5
                    "; 
                                
    #Create an instance of ConnectionDB:
    $connection = new ConnectionDB();
    $conexionOracle = $connection->conectarOracle();
    if ($conexionOracle) {
        $sqlExecute = oci_parse($conexionOracle, $queryOracle);
        if ($sqlExecute) {
            oci_bind_by_name($sqlExecute, ':partnumber', $upnParam);            
            oci_execute($sqlExecute);                
                        
            while ($row = oci_fetch_array($sqlExecute)) {                                                           
                $arrayMos[] = array('mo' => $row['MO'], 'date' => $row['DATE_TIME']);                                    
                } #end while                                  
                
                #Get the data from each MO generated above
                $contMos = 0;                    
                foreach ($arrayMos as $item) {  
                    $arrayDataMos = array();                      
                    if ($item['mo'] != "") {                                                                                                              
                        $arrayDataMos = getMoInformation($item['mo'], $conexionOracle);                        
                        $boolean = $arrayDataMos['status'];                           
                        if ($boolean != 1 || !$boolean) {
                            $response = array("boolean" => false, "msg" => $arrayDataMos['msg']);                                           
                            echo json_encode($response);
                            exit();
                        } else {                            
                            $arrayFinal[$contMos] = $arrayDataMos['data'];
                            $contMos++;
                        }           
                    }
                } # end foreach             
                
                    // print_r(json_encode($arrayFinal));
                   // $response = array("boolean" => true, "msg" => "success", 'data' => $arrayFinal);                                           
                    //echo json_encode($response);                        
                    $connection->cerrarConexionOracle();                                              
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
} #end foundMOsByUpn                                     		

function getMoInformation($paramReceived, $conexionOracle) {        
    $arrayDataByMo = array();
    $return = array();
    $queryOracle = "
                        SELECT D.LINE, E.UPN, E.MO, F.POSITION, F.CPN, E.CREATEDATE, F.CATEGORY,  B.DESCRIPTION
                        FROM SFCMO E, SFCMOITEM F, SFCCATEGORY B,MOPLANNING D
                        WHERE
                        E.MO = F.MO
                        AND D.MO=F.MO
                        AND F.CATEGORY = B.CATEGORY
                        AND  E.MO = :modata                            
                        ORDER BY F.POSITION ASC
                    ";
    
    if ($conexionOracle) {
        $sqlExecute = oci_parse($conexionOracle, $queryOracle);        
        if ($sqlExecute) {
            oci_bind_by_name($sqlExecute, ':modata', $paramReceived);            
            oci_execute($sqlExecute);             
            // set_time_limit(500);                        
        while ($row = oci_fetch_array($sqlExecute)) {                                                           
            $arrayDataByMo[] = array(  
                                        'line' => $row[0], 
                                        'upn' => $row[1], 
                                        'mo' => $row[2], 
                                        'position' => $row[3], 
                                        'cpn' => $row[4], 
                                        'createdate' => $row[5], 
                                        'category' => $row[6], 
                                        'description' => $row[7]
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