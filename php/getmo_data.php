<?php	
    include_once("ConnectionDB.php");
    
    $modataone = "";
    $modatatwo = "";
    // $modataone = "000010082724";
    // $modatatwo = "000010082538";
        
	if(isset($_POST["modataone"])) {
		$modataone = filter_var($_POST["modataone"], FILTER_SANITIZE_STRING);
	 }

	if(isset($_POST["modatatwo"])) {
		$modatatwo = filter_var($_POST["modatatwo"], FILTER_SANITIZE_STRING);
	 }    
	    
	 if (!empty($modataone) && !empty($modatatwo)) {   
        $response = array(); 
        $dataOne = array(); 
        $dataTwo = array(); 
        $status = false;
        $msg = "" ;

        #Create an instance of ConnectionDB:
        $connection = new ConnectionDB();
        $conexionOracle = $connection->conectarOracle();
        if ($conexionOracle) {            
            $responseOne = getInformation($modataone, $conexionOracle);
            $status = $responseOne['status'];        
            if ($status) {         
                $msg = $responseOne['msg'];                              
                $dataOne = $responseOne['data'];

                $responseTwo = getInformation($modatatwo, $conexionOracle);        
                $status = $responseTwo['status'];            
                if ($status) {
                    $msg = $responseTwo['msg'];    
                    $dataTwo = $responseTwo['data'];
                    $response = array("status" => $status, "msg" => $msg, "dataone" => $dataOne, "datatwo" => $dataTwo);                    
                    echo json_encode($response); 
                    $connection->cerrarConexionOracle();
                    exit(); 
                    // oci_close($conexionOracle);      
                } else {
                    $response = array("status" => $status, "msg" => $msg);                    
                    echo json_encode($response); 
                    $connection->cerrarConexionOracle();
                    exit();
                    // oci_close($conexionOracle);             
                } # end if $responseTwo                 
            } else {
                $response = array("status" => $status, "msg" => $msg);                    
                echo json_encode($response); 
                $connection->cerrarConexionOracle();
                exit();
            } # end if $responseOne
            
                        
        } else {
            $response = array("status" => false, "msg" => "An error has occurred connecting to Data Base.");                    
            echo json_encode($response); 
            $connection->cerrarConexionOracle();
            exit(); 
        } # end if $conexionOracle                                                             	
	} else {
        $response = array("status" => false, "msg" => "Data received is empty !");        
        echo json_encode($response);
        exit();
     } #end if !empty

function getInformation($paramReceived, $conexionOracle) {
    $arrayResponse = array();
    $return = array();
    // $queryOracle = "SELECT E.UPN, E.MO, F.POSITION, F.CPN, E.CREATEDATE, F.CATEGORY,  B.DESCRIPTION
    //                     FROM SFCMO E, SFCMOITEM F, SFCCATEGORY B
    //                     WHERE
    //                     E.MO = F.MO
    //                     AND F.CATEGORY = B.CATEGORY
    //                     AND  E.MO = :modata
    //                     AND E.CREATEDATE >= SYSDATE -160
    //                     ORDER BY F.POSITION ASC";
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
            $arrayResponse[] = array(  
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
            if (count($arrayResponse) > 0) {                
                // echo json_encode($arrayResponse);            
                $return  = array("status" => true, "msg" => "success", 'data' => $arrayResponse);                
            } else {
                $return  = array("status" => false, "msg" => "An error has occurred while getting the MO data.");                
            } # end if count($arrayResponse) > 0                
        } else {
            $return = array("status" => false, "msg" => "An error has occurred while executing query.");            
        } # end if $sqlExecute
                      
        } else {
            $return = array("status" => false, "msg" => "An error has occurred connecting to Data Base.");                                
        }  #end if $conexionOracle 
    oci_free_statement($sqlExecute);
    return $return;

} # end function getInformation
 ?>