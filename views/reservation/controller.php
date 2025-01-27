<?php
include('../../evr.php');

if ($_POST['act'] == 'add_reservation') {
    $reservation = new reservation();
    $reservation->insert();
    $reservation_id = $reservation->laset_insert();
    $detail_reservation = new detail_reservation();
    $detail_reservation->insert_details($_POST['reservation_detail'], $reservation_id);
}
else if ($_POST['act'] == 'update_reservation') {
    $id_reservation = $_POST['id_reservation'];
    $reservation = new reservation();
    $reservation->update($id_reservation);
    $detail_reservation = new detail_reservation();
    $status = $detail_reservation->delete($_POST['id_reservation']);
    $status = $detail_reservation->insert_details($_POST['reservation_detail'], $id_reservation);
}
else if ($_POST['act'] == 'delete_reservation') {
    $id_reservation = $_POST['id_reservation'];
    $reservation = new reservation();
    $reservation->delete($id_reservation);
    $detail_reservation = new detail_reservation();
    $status = $detail_reservation->delete($_POST['id_reservation']);
}
else if ($_POST['act'] == 'get_reservations_datatable') {
    $reservation = new reservation();
    $requestData = $_REQUEST;
    $chambres = $reservation->get_reservations($requestData);
    echo json_encode(array(
        "draw" => intval($requestData['draw']),
        "recordsTotal" => $chambres['total'],
        "recordsFiltered" => $chambres['total'],
        "data" => $chambres['rows'],
    ));
}
?>