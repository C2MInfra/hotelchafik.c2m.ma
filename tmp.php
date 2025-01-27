<?php
include('./evr.php');
$result = connexion::getConnexion()->query("select * from detail_reservation;");
header('Content-Type: application/json');
echo json_encode($result->fetchAll(PDO::FETCH_OBJ));
