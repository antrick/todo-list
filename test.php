<?php
require_once 'config.php';

$db = getDB();
if ($db) {
    echo "Conexión exitosa";
} else {
    echo "Falló la conexión";
}
?>
