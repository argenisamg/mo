<?php
	// include_once("connection.php");		
	include_once("ConnectionDB.php");	        
	    
    $response = array();
    $arrayallUpn = array();     
    
    $getAllupnQuery = "
                        SELECT MO, UPN, MAX(CREATEDATE) AS \"DATE\" 
                            FROM SFCMO 
                            WHERE CREATEDATE >= SYSDATE - 7 
                            AND UPN IN (
                                SELECT UPN 
                                FROM SFCMODEL X, ERPITEMMASTER Y 
                                WHERE X.UPN = Y.CPN 
                                AND UPPER(X.CUSTOMER) = 'MONICA' 
                                AND Y.CATEGORY = 'S-'
                            ) 
                            GROUP BY MO, UPN 
                            ORDER BY \"DATE\" DESC
                        ";
                        
                                                        
        #Create an instance of ConnectionDB:
        $connection = new ConnectionDB();
        $conexionOracle = $connection->conectarOracle();
        if ($conexionOracle) {
            $sqlExecute = oci_parse($conexionOracle, $getAllupnQuery);
            if ($sqlExecute) {                
                oci_execute($sqlExecute);                
                                
                while ($row = oci_fetch_array($sqlExecute)) {   
                    $upn = $row['UPN'];
                    $date = $row['DATE'];                                                                                                                                                                                                                           
                    $arrayallUpn[] = array( 'upn' => $upn, 'date' => $date);                                                                                                
                    } #end while                                                        
                

                    /**Get server time */
                    date_default_timezone_set('America/Denver');
                    $dateServer = date('Y-m-d H:i:s');    
                    // $hour_actually = date('H:i:s', strtotime($dateServer));    
                    $hour_actually = date('H:i:s', strtotime($dateServer . ' +1 hour'));
                    // $hour_actually = date('d-m-Y');        

                    $response = array("boolean" => true, "msg" => "success", 'allupn' => $arrayallUpn, 'hora' => $hour_actually);        
                    echo json_encode($response);                        
                    $connection->cerrarConexionOracle();
                    exit();
                                                                    
            } else {
                $response = array("boolean" => false, "msg" => "An error has occured trying to execute query!");                    
                echo json_encode($response);                          
                exit();                
            } #end if sqlExecute
                       
            } else {
                $response = array("boolean" => false, "msg" => "An error has occurred while trying to connect to Data Base !");                    
                echo json_encode($response);                          
                exit();
            }  #end if $conexionOracle                               		    
 ?>