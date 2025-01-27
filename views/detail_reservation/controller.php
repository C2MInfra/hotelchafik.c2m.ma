<?php
include('../../evr.php');
  if ($_POST['act'] == 'get_reservation_details') {
    $reservation = new detail_reservation();
    $reservation_details = $reservation->getReservationDetails($_POST['id_reservation']);
    echo json_encode($reservation_details);
  }else if ($_POST['act'] == 'get_reservation_details_par_date_checkin') {
    $reservation = new detail_reservation();
    $reservation_details = $reservation->getReservationDetailsParDateCheckin($_POST['date_checkin']);
    echo json_encode($reservation_details);
  }
  else if ($_POST['act'] == 'get_reservation_details_par_date_checkout') {
    $reservation = new detail_reservation();
    $reservation_details = $reservation->getReservationDetailsParDateCheckout($_POST['date_checkout']);
    echo json_encode($reservation_details);
  }
  else if ($_POST['act'] == 'update_checkin') {
    $reservation = new detail_reservation();
    $reservation->update_checkin($_POST['id_detail_reservation']);
  }
  else if ($_POST['act'] == 'update_checkout') {
    $reservation = new detail_reservation();
    $reservation->update_checkout($_POST['id_detail_reservation']);
    echo json_encode("checkout updated");
  }
?>