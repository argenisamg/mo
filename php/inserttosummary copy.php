<?php            
    $jsonFrom = "";   
    $response = array(); 
    if(isset($_POST["jsonsummary"])) {                                 
        getJSON();        
    } else {                      
        $response = array("boolean" => false, "msg" => "JSON data is empty.");                                           
        echo json_encode($response);                          
        exit();
    }             
    #Recuperar el json:
    function getJSON() {
        include_once("ConnectionDB.php");
        #Create an instance of ConnectionDB:
        $connection = new ConnectionDB();
        $conexionMySQL = $connection->conectarMySQL();  
        if ($conexionMySQL) {
            $jsonFrom = $_POST["jsonsummary"];
            $jsonDecoded = json_decode($jsonFrom, true);                                   
            $lenghtArray = count($jsonDecoded);
            $contInserts = 0;
            $insert = "INSERT INTO summary_mocomparison (upn, mog, dateg, moc, datec, result) VALUES (?, ?, ?, ?, ?, ?)";
            
            #First step to save data is iterate the array by object and, send the object to the function to create tue Query structure:
            for ($i = 0; $i < $lenghtArray ; $i++) {
                //de aqui
                $queryCreado = crearQuery($jsonDecoded[$i]);   
                $stmt = mysqli_prepare($conexionMySQL, $insert);

                if ($stmt) {              
                    mysqli_stmt_bind_param($stmt, "ssssss", ...$queryCreado);                        
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
                //hasta aqui, va dentro del if si el select tiene algo similar
            } #end for

            if ($contInserts === $lenghtArray ) {
                $response = array("boolean" => true, "msg" => "succes");
                $connection->cerrarConexionMySQL();
                echo json_encode($response);                          
                exit();
            } else {
                $response = array("boolean" => false, "msg" => "An error was occurred recording data.");
                mysqli_stmt_close($stmt);                                           
                $connection->cerrarConexionMySQL();
                echo json_encode($response);                          
                exit();
            } #end if $contInserts           
                                                            
            } else {
                $response = array("status" => false, "msg" => "Unable to connect with Data Base.");                    
                echo json_encode($response);                 
                exit(); 
            } # end if $conexionMySQL                                                  
        
    } # end function getJSON       

    function crearQuery($objectArray) {        
		$sqlConcatenar = [];	
        $upn =""; 
        $mog ="";
        $dateg ="";
        $moc ="";
        $datec ="";
        $result = "";		
                   
            foreach ($objectArray as $clave => $valores) {
                if ($clave === "upn") {
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
                
			$sqlConcatenar = [$upn, $mog, $dateg, $moc, $datec, $result];            
			
		return $sqlConcatenar;
	} # end crearQuery                 
?>