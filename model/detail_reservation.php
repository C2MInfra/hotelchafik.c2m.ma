<?php

class detail_reservation extends table
{

    protected $id_detail_reservation;
    protected $id_reservation;
    protected $montant;
    protected $date_arriver;
    protected $date_depart;
    protected $nombre_nuits;
    protected $nombre_personnes;
    protected $id_chambre;
    protected $checkin;
    protected $checkout;

    public function delete($id)
	{
		$statut = connexion::getConnexion()->exec("delete from detail_reservation where id_reservation='" . $id . "'");
		return $statut;
	}
    function format_date($date){
        $date = new DateTime($date);
        return $date->format('Y-m-d');
    }
    function insert_details($details, $reservation_id)
    {
        try {
            foreach ($details as $key => $value) {
                //fix date format before inserting
                $date_arriver = $this->format_date($value['date_arriver']);
                $date_depart = $this->format_date($value['date_depart']);
                //fix date format before inserting
                $query = "insert into detail_reservation (id_reservation,montant,date_arriver,date_depart,nombre_nuits,nombre_personnes,id_chambre,checkin,checkout)
                 values($reservation_id,$value[montant],'$date_arriver','$date_depart',$value[nombre_nuits],$value[nombre_personnes],$value[id_chambre],0,0)";
                $statut = connexion::getConnexion()->exec($query);
            }
        } catch (PDOException $e) {
            die(handle_sql_errors($statut, $e->getMessage()));
        }
    }
    function getReservationDetails($id_reservation){
		$result = connexion::getConnexion()->query("SELECT dr.*,c.numero_chambre FROM detail_reservation dr
		INNER JOIN chambre c ON dr.id_chambre = c.id_chambre
		WHERE id_reservation = $id_reservation");
        return $result->fetchAll(PDO::FETCH_ASSOC);
	}
  function getReservationDetailsParDateCheckin($date){
		$result = connexion::getConnexion()->query("SELECT dr.*,c.numero_chambre, client.nom, client.cin FROM detail_reservation dr
		inner join reservation on dr.id_reservation = reservation.id_reservation
    left join client on  client.id_client = SUBSTRING_INDEX(reservation.id_client, ',', 1)
    INNER JOIN chambre c ON dr.id_chambre = c.id_chambre
		WHERE dr.date_arriver = '$date'
      AND dr.checkin = 0");
        return $result->fetchAll(PDO::FETCH_ASSOC);
	}
  function getReservationDetailsParDateCheckout($date){
    $result = connexion::getConnexion()->query("SELECT dr.*,c.numero_chambre, client.nom, client.cin FROM detail_reservation dr
    inner join reservation on dr.id_reservation = reservation.id_reservation
    left join client on  client.id_client = SUBSTRING_INDEX(reservation.id_client, ',', 1)
		INNER JOIN chambre c ON dr.id_chambre = c.id_chambre
		WHERE dr.date_arriver = '$date'
      AND dr.checkout = 0
      AND dr.checkin = 1");
        return $result->fetchAll(PDO::FETCH_ASSOC);
	}
  function getReservationDetailsValid($id_reservation){
		$result = connexion::getConnexion()->query("SELECT dr.*,c.numero_chambre FROM detail_reservation dr
		INNER JOIN chambre c ON dr.id_chambre = c.id_chambre
		WHERE id_reservation = $id_reservation
      AND dr.checkin = 1");
        return $result->fetchAll(PDO::FETCH_ASSOC);
	}
  function update_checkin($id_detail_reservation){
    $query = "update detail_reservation set checkin = IF(checkin = 0, 1, 0) where id_detail_reservation = $id_detail_reservation";
      $statut = connexion::getConnexion()->exec($query);
  }
  function update_checkout($id_detail_reservation){
    $query = "update detail_reservation set checkout = IF(checkout = 0, 1, 0) where id_detail_reservation = $id_detail_reservation";
      $statut = connexion::getConnexion()->exec($query);
  }
}

?>