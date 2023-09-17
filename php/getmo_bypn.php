<?php
	// include_once("connection.php");
    include_once("ConnectionDB.php");		
	
    $pndata = "";
    $response = array();
    $arrayResponse = array();
    $arrayResponse_ = array();

	if(isset($_POST["pndata"]) && isset($_POST["linedata"])) {
		$pndata = filter_var($_POST["pndata"], FILTER_SANITIZE_STRING);        
        $linedata = filter_var($_POST["linedata"], FILTER_SANITIZE_STRING);        
	 } else {
        $response = array("status" => false, "msg" => "Empty data received !");                    
        echo json_encode($response);  
     }
	  
	
	 if (!empty($pndata) && !empty($linedata)) {        
		#Query para obtener la data con base a la MO:                        
        $queryOracle = "SELECT MO, UPN, CRT_DATE
                        FROM MOPLANNING
                        WHERE LINE = :linedata AND UPN = :mosearch
                        AND CRT_DATE = (
                        SELECT MAX(CRT_DATE)
                        FROM MOPLANNING
                        WHERE LINE = :linedata AND UPN = :mosearch
                        ) ORDER BY MO DESC";        
        
        #Create an instance of ConnectionDB:
        $connection = new ConnectionDB();
        $conexionOracle = $connection->conectarOracle();
        if ($conexionOracle) { 
            $sqlExecute = oci_parse($conexionOracle, $queryOracle);
            if ($sqlExecute) {
                oci_bind_by_name($sqlExecute, ':linedata', $linedata);
                oci_bind_by_name($sqlExecute, ':mosearch', $pndata);     
                oci_execute($sqlExecute);
                //set_time_limit(500);
                
                $dateOnly = "";
                while ($row = oci_fetch_array($sqlExecute)) {                                                           
                    $arrayResponse[] = array( 'mo' => $row['MO']);                    
                    $dateOnly = $row['CRT_DATE'];
                    } #end while
                    
                #realizar el segundo select
                $queryOracleDatesOnly = "SELECT MO, CRT_DATE
                                        FROM MOPLANNING
                                        WHERE LINE = :linedata AND UPN = :mosearch
                                        AND TRUNC(CRT_DATE) != TO_DATE('$dateOnly', 'DD-MON-RR')";
                // $queryOracleDatesOnly = "SELECT CRT_DATE FROM MOPLANNING WHERE UPN = :mosearch";
                $sqlExecute_ = oci_parse($conexionOracle, $queryOracleDatesOnly);
                if ($sqlExecute_) {                                                                                      
                    oci_bind_by_name($sqlExecute_, ':linedata', $linedata);     
                    oci_bind_by_name($sqlExecute_, ':mosearch', $pndata);     
                    oci_execute($sqlExecute_);
                    set_time_limit(500);
                    
                    while ($row = oci_fetch_array($sqlExecute_)) {                                                           
                        $arrayResponse_[] = array( 'dates' => $row['CRT_DATE'], 'mo' => $row['MO']);                                            
                        } #end while
                        
                        if (count($arrayResponse) > 0) {                
                            // echo json_encode($arrayResponse);            
                            $response = array("status" => true, "msg" => "success", "data" => $arrayResponse, "date" => $arrayResponse_);                    
                            echo json_encode($response);      
                            oci_free_statement($sqlExecute);
                            $connection->cerrarConexionOracle();
                            exit();             
                        } else {
                            $response = array("status" => false, "msg" => "MO data is empty.");                    
                            echo json_encode($response);
                            oci_free_statement($sqlExecute);
                            $connection->cerrarConexionOracle();
                            exit(); 
                        } # end count($arrayResponse) > 0

                } else {
                    $response = array("status" => false, "msg" => "An error has occurred while executing the query !");                    
                    echo json_encode($response);  
                    exit();
                } # end if $sqlExecute_                              
            } else {
                $response = array("status" => false, "msg" => "An error has occured trying to create statement !");                    
                echo json_encode($response);                          
                exit();                
            } #end if sqlExecute
                       
            } else {
                $response = array("status" => false, "msg" => "An error has occurred while trying to connect to Data Base !");                    
                echo json_encode($response);                          
                exit();
            }  #end if $conexionOracle  
                             	
	 } else {
        $response = array("status" => false, "msg" => "Data received is empty !");        
        echo json_encode($response);
        exit();
     } #end if !empty
 ?>