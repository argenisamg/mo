<?php

class ConnectionDB {
    private $oracleConnection;
    private $conexionMySQL;
    
    public function conectarOracle() {
        #conectarOracle($host, $puerto, $nombreBD, $usuario, $contrasena)
        $host = '10.250.200.41';
        $port = '1527';
        $dbname = 'WMXBO912';
        $user = 'SFCFA912';
        $password = 'SFCFA912';        
        #Open the connection channel to Oracle:	
        $connectionString = "(DESCRIPTION=(ADDRESS_LIST=(ADDRESS=(PROTOCOL=TCP)(HOST=$host)(PORT=$port)))(CONNECT_DATA=(SID=$dbname)))";
        $this->oracleConnection = oci_connect($user, $password, $connectionString);
        
        if (!$this->oracleConnection) {
            $error = oci_error();
            echo "Error connecting to Oracle: " . $error['message'];
            return false;
        }
        
        return $this->oracleConnection;
    } #end function conectarOracle
    
    public function conectarMySQL() {
        $host_db = "127.0.0.1";
        $user_db = "root";
        $pass_db = "";
        $db_name = "monica";
        $this->conexionMySQL = mysqli_connect($host_db, $user_db, $pass_db, $db_name);
        
        if (!$this->conexionMySQL) {
            echo "Error connecting to MySQL: " . mysqli_connect_error();
            return false;
        }
        
        return $this->conexionMySQL;
    } #end function conectarMySQL
    
    public function cerrarConexionOracle() {
        oci_close($this->oracleConnection);
    } #end function cerrarConexionOracle
    
    public function cerrarConexionMySQL() {
        mysqli_close($this->conexionMySQL);
    } #end function cerrarConexionMySQL
}
?>