<?php
date_default_timezone_set('America/Denver');
$horaServidor = date('Y-m-d H:i:s'); // Formato de la hora
echo json_encode(['hora' => $horaServidor]);
?>
