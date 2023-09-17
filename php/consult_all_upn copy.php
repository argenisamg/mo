<?php
	// include_once("connection.php");		
	include_once("ConnectionDB.php");	
    
    /**Get server time */
    date_default_timezone_set('America/Denver');
    $dateServer = date('Y-m-d H:i:s');        
    $actualDate = date('d-m-Y');

    #destructure the date on individual variables:
    $parts = explode('-', $actualDate);
    $day = $parts[0];
    $month = $parts[1];
    $year = $parts[2];

    $concatServerDate = $day.$month; #variable para concatenar unicamente el mes y el dia del server, no tomo en cuenta el anio
    //0806
	
    // $pndata = "";
    $response2 = array();
    $arrayallUpn = array();    
    $arrayMonths = array('01' => 'JAN', '02' => 'FEB', '03' => 'MAR', '04' => 'APR', '05' => 'MAY', '06' => 'JUN',
                        '07' => 'JUL', '08' => 'AUG', '09' => 'SEP', '10' => 'OCT', '11' => 'NOV', '12' => 'DEC');
    
    $getAllupnQuery = "
                        SELECT MO,UPN,max(CREATEDATE)AS 'DATE' FROM SFCMO WHERE CREATEDATE >= SYSDATE -7 
                        and UPN IN(SELECT UPN FROM SFCMODEL X, ERPITEMMASTER Y WHERE X.UPN = Y.CPN AND UPPER(X.CUSTOMER) = 'MONICA' AND Y.CATEGORY = 'SA' )
                        group by MO, UPN 
                        ORDER BY 'DATE' DESC    
                    ";
            
        // $getAllupnQuery = "
        //                 SELECT UPN, CREATEDATE
        //                 FROM SFCMO
        //                 WHERE 
        //                 CREATEDATE >= SYSDATE -7 AND 
        //                 UPN IN (SELECT UPN FROM SFCMODEL X, ERPITEMMASTER Y WHERE X.UPN = Y.CPN AND UPPER(X.CUSTOMER) = 'MONICA' AND Y.CATEGORY = 'SA')                        
        //                  ";
                         /*"
                        SELECT MO, UPN, max(CREATEDATE) AS DATE 
                        FROM SFCMO 
                        WHERE CREATEDATE >= SYSDATE -7 AND 
                        UPN IN (SELECT UPN FROM SFCMODEL X, ERPITEMMASTER Y WHERE X.UPN = Y.CPN AND UPPER(X.CUSTOMER) = 'MONICA' AND Y.CATEGORY = 'SA')
                        GROUP BY MO, UPN 
                        ORDER BY CREATEDATE DESC";  */              
                                                        
        #Create an instance of ConnectionDB:
        $connection = new ConnectionDB();
        $conexionOracle = $connection->conectarOracle();
        if ($conexionOracle) {
            $sqlExecute = oci_parse($conexionOracle, $getAllupnQuery);
            if ($sqlExecute) {                
                oci_execute($sqlExecute);                
                
                //$dateOnly = "";
                while ($row = oci_fetch_array($sqlExecute)) {   
                    $upn = $row['UPN'];
                    $date = $row['CREATEDATE'];                    
                    #destructure the date on individual variables:
                    $datedestructured = explode('-', $date);
                    $dayDB = $datedestructured[0];  //08                   
                    $monthDB = $datedestructured[1]; //JUN                   
                        foreach ($arrayMonths as $key => $value) {
                                if ($key === $month && $value === $monthDB) {
                                    $monthDB = $key;
                                    $concatDbDate = $dayDB.$monthDB; //0806                                                                                         
                                    if ($concatDbDate == $concatServerDate) {                                        
                                        $arrayallUpn[] = array( 'upn' => $upn, 'date' => $date);                   
                                    } #end if
                                } #end if
                            } #end foreach
                    } #end while                                                        
                
                    print_r($arrayallUpn);
                    $connection->cerrarConexionOracle();
                    exit();
                                                                    
            } else {
                $response2 = array("boolean" => false, "msg" => "An error has occured trying to execute query!");                    
                echo json_encode($response2);                          
                exit();                
            } #end if sqlExecute
                       
            } else {
                $response2 = array("boolean" => false, "msg" => "An error has occurred while trying to connect to Data Base !");                    
                echo json_encode($response2);                          
                exit();
            }  #end if $conexionOracle                               		    
 ?>