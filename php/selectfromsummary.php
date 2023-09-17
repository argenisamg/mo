<?php		
	include_once("ConnectionDB.php");		
	    
    $response = array();
    $arrayFromDb = array();    
	
        #Create an instance of ConnectionDB:
        $connection = new ConnectionDB();
        $conexionMySQL = $connection->conectarMySQL();
        if ($conexionMySQL) {
            $selectQuery= "SELECT * FROM `summary_mocomparison`";
            $stmt = mysqli_prepare($conexionMySQL, $selectQuery);            
            if ($stmt) {                                                  
                mysqli_stmt_execute($stmt);
                $executeQuery = mysqli_stmt_get_result($stmt);
                if (!$executeQuery) {
                    $response = array("boolean" => false, "msg" => "Error exceuting query.");                                           
                    echo json_encode($response);
                    exit();           
                } else {
                    if (mysqli_num_rows($executeQuery) > 0) {
                        while ($row = mysqli_fetch_array($executeQuery)) {                                                                                   
                            $element = $row['result'];                            
                        
                            $arrayFromDb[] = array(
                                                    'id' => $row['id_sum'],
                                                    'line' => $row['lineprod'],
                                                    'upn' => $row['upn'],
                                                    'mog' => $row['mog'],
                                                    'dateg' => $row['dateg'],
                                                    'moc' => $row['moc'],
                                                    'datec' => $row['datec'],
                                                    'result' => $element,                                                    
                                                );

                            } #end while 
                            
                            for ($i=0; $i < count($arrayFromDb); $i++) {                                                                 
                                $id = $arrayFromDb[$i]['id'];                            
                                $color = $arrayFromDb[$i]['result'];                            
                                $mog = $arrayFromDb[$i]['mog'];                            
                                $moc = $arrayFromDb[$i]['moc'];                            
                                if ($color === 'red') {
                                    $arrayFromDb[$i]['actions'] = '<spam id="red" idsummary="'.$id.'" trmog="'.$mog.'" trmoc="'.$moc.'" onclick="clickEvent(this);">SHOW RESULTS</spam>';
                                } else {                            
                                    $arrayFromDb[$i]['actions'] = '';
                                }
                            }
                                                           
                            $response = array("boolean" => true, "msg" => "success", "data" => $arrayFromDb);                                                                    
                            echo json_encode($response);
                            exit();                  
                    } else {       
                        $response = array("boolean" => false, "msg" => "There isn't records into Data Base");                                           
                        echo json_encode($response);
                        exit();                  
                    } # end if mysqli_num_rows
                }
                                                                                                                                                                                                                                 
            } else {
                $response = array("boolean" => false, "msg" => "Stament error");                                           
                echo json_encode($response);                          
                exit();               
            } #end if sqlExecute
                       
        } else {
            $response = array("boolean" => false, "msg" => "An error has occurred while trying to connect to Data Base");                    
            echo json_encode($response);                          
            exit();
        }  #end if $conexionMySQL                               	
    
 ?>