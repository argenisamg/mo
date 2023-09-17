<?php            
    include_once("ConnectionDB.php");

    $jsonFrom = "";   
    $response = array(); 
    if(isset($_POST["jsonsummary"])) {                                 
        getJSON();        
    } elseif(isset($_POST["idsummary"])) {                      
        $idPost = $_POST["idsummary"];
        approveMo($idPost);
    } else {
        $response = array("boolean" => false, "msg" => "JSON data is empty.");                                           
        echo json_encode($response);                          
        exit();
    }         
    #Recuperar el json:
    function getJSON() {
        #Create an instance of ConnectionDB:
        $connection = new ConnectionDB();
        $conexionMySQL = $connection->conectarMySQL();  
        if ($conexionMySQL) {
                $jsonFrom = $_POST["jsonsummary"];
                $jsonDecoded = json_decode($jsonFrom, true);                                   
                $lenghtArray = count($jsonDecoded);
                $contInserts = 0;
                $contSelects = 0;
                $insert = "INSERT INTO summary_mocomparison (lineprod, upn, mog, dateg, moc, datec, result) VALUES (?, ?, ?, ?, ?, ?, ?)";
                $exist = null;
                #First step to save data is iterate the array by object and, send the object to the function to create tue Query structure:
                for ($i = 0; $i < $lenghtArray ; $i++) {               
                    $exist = askForExist($jsonDecoded[$i], $conexionMySQL);   
                    if (!$exist && $exist !== null) {
                        //de aqui
                        $queryCreado = crearQuery($jsonDecoded[$i]);   
                        $stmt = mysqli_prepare($conexionMySQL, $insert);
                            if ($stmt) {              
                                mysqli_stmt_bind_param($stmt, "sssssss", ...$queryCreado);                        
                                $insertquery = mysqli_stmt_execute($stmt);                    
                                    if ($insertquery) {
                                        mysqli_stmt_close($stmt);
                                        $contInserts++;								
                                    } 
                            } else {
                                $response = array("boolean" => false, "msg" => "Stament error.");                                           
                                echo json_encode($response);                          
                                exit();
                            }                         
                        } else {
                            #this variable is to respond if there are records like the ones received
                            $contSelects++;
                        }#end if !$exist                
                } #end for

                #This next code is to compare if all data into the JSON was recorded correctly:
                if ($contInserts === $lenghtArray ) {
                    $response = array("boolean" => true, "msg" => "succes");
                    $connection->cerrarConexionMySQL();
                    echo json_encode($response);                          
                    exit();
                } elseif ($contSelects === $lenghtArray) {
                    $response = array("boolean" => true, "msg" => "succes");
                    $connection->cerrarConexionMySQL();
                    echo json_encode($response);                          
                    exit();                
                } else {
                    $response = array("boolean" => false, "msg" => "An error was occurred getting the data.");                                                           
                    $connection->cerrarConexionMySQL();
                    echo json_encode($response);                          
                    exit();
                }#end if $contInserts           
                                                            
            } else {
                $response = array("status" => false, "msg" => "Unable to connect with Data Base.");                    
                echo json_encode($response);                 
                exit(); 
            } # end if $conexionMySQL                                                  
        
    } # end function getJSON 
    
    function approveMo($id_mo) {
        $connection = new ConnectionDB();
        $conexionMySQL = $connection->conectarMySQL();
        if ($conexionMySQL) {
            $updateQuery = "UPDATE `summary_mocomparison` SET `result`='green' WHERE `id_sum` = ?";
            $stmt = mysqli_prepare($conexionMySQL, $updateQuery);
                if ($stmt) {              
                    mysqli_stmt_bind_param($stmt, "i", $id_mo);                        
                    $updateReg = mysqli_stmt_execute($stmt);                    
                        if ($updateReg) {
                            mysqli_stmt_close($stmt);
                            $response = array("boolean" => true, "msg" => "success");                                           
                            $connection->cerrarConexionMySQL();
                            echo json_encode($response);                          
                            exit();                                        							
                        } else {
                            mysqli_stmt_close($stmt);
                            $response = array("boolean" => false, "msg" => "Error while updating regiters.");                                           
                            $connection->cerrarConexionMySQL();
                            echo json_encode($response);                          
                            exit();
                        }
                } else {
                    $response = array("boolean" => false, "msg" => "Stament error.");                                           
                    $connection->cerrarConexionMySQL();
                    echo json_encode($response);                          
                    exit();
                } # end if $stmt
        } 
        else {
            $response = array("status" => false, "msg" => "Unable to connect with Data Base.");                    
            echo json_encode($response);                 
            exit(); 
        } # end if $conexionMySQL
       
    } # end approveMo

    function askForExist($objectArray, $conexionMySQL) {
        $moc ="";
        $datec ="";        
        foreach ($objectArray as $key => $value) {
            if ($key === "moc") {
                $moc = $value;
            } elseif ($key === "datec") {
                $datec = $value;
            }            
        }

        $queryExist = "SELECT `moc`, `datec` FROM `summary_mocomparison` WHERE `moc` = '{$moc}' AND `datec` = '{$datec}'";
        $stmt = mysqli_prepare($conexionMySQL, $queryExist);  
        mysqli_stmt_execute($stmt);
        $executeQuery = mysqli_stmt_get_result($stmt);
        $responseQuery = (mysqli_num_rows($executeQuery) > 0) ? true : false ;
        mysqli_stmt_close($stmt);                
        return $responseQuery;

    } #end function askForExist

    function crearQuery($objectArray) {        
		$sqlConcatenar = [];	
        $lineprod =""; 
        $upn =""; 
        $mog ="";
        $dateg ="";
        $moc ="";
        $datec ="";
        $result = "";		
                   
            foreach ($objectArray as $clave => $valores) {
                if ($clave === "line") {
                    $lineprod = $valores;
                } elseif ($clave === "upn") {
                    $upn = $valores;
                } elseif ($clave === "mog") {
                    $mog = $valores;                
                } elseif ($clave === "dateg") {
                    $dateg = $valores;                
                } elseif ($clave === "moc") {
                    $moc = $valores;                                
                } elseif ($clave === "datec") {
                    $datec = $valores;                                
                } elseif ($clave === "result") {
                    $result = $valores;                
                }                
            } # end foreach          		
                
			$sqlConcatenar = [$lineprod, $upn, $mog, $dateg, $moc, $datec, $result];            
			
		return $sqlConcatenar;
	} # end function crearQuery                 
?>