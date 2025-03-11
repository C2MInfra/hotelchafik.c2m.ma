<?php

class reservation extends table
{


  protected $id_reservation;
  protected $date_reservation;
  protected $id_client;
  protected $remarque;
  protected $montant_total;

  function getLastReservationId()
  {
    return $this->selectChamps('id_reservation', '', '', 'id_reservation DESC', '', '1');
  }
  public function get_reservations($requestData)
  {
    $columns = array(
      0 => "r.id_reservation",
      1 => "c.cin",
      2 => "r.date_reservation",
      3 => "r.remarque",
    );
    // Connect to your database
    $db = connexion::getConnexion();

    // Define the SQL query
    $sql = "SELECT count(distinct f.id_facture) as nbr_facture,f.id_facture,IFNULL(r.montant_total-sum(rr.montant),r.montant_total) as rest, r.montant_total, r.id_reservation,r.date_reservation,r.remarque,concat(c.nom,' (',c.cin,')') as cin
    FROM reservation r 
    INNER JOIN client c ON c.id_client = SUBSTRING_INDEX(r.id_client, ',', 1)
    lEFT JOIN reg_reservation rr ON rr.id_reservation = r.id_reservation
    lEFT JOIN facture f ON f.id_vente = r.id_reservation
    GROUP BY r.id_reservation";

    // Add the WHERE clause for the search
    if (!empty($requestData['search']['value'])) {
      $sql .= " WHERE (" . $columns[0] . " LIKE '%" . $requestData['search']['value'] . "%'";
      $sql .= " OR " . $columns[1] . " LIKE '%" . $requestData['search']['value'] . "%'";
      $sql .= " OR " . $columns[2] . " LIKE '%" . $requestData['search']['value'] . "%'";
      $sql .= " OR " . $columns[3] . " LIKE '%" . $requestData['search']['value'] . "%')";
    }

    // Add the ORDER BY clause
    $sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . " " . $requestData['order'][0]['dir'];

    // Add the LIMIT clause for pagination
    $sql .= " LIMIT " . $requestData['start'] . ", " . $requestData['length'];

    // Execute the query
    $stmt = $db->prepare($sql);
    $stmt->execute();

    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    //Get the total number of records in the table
    $result = $db->prepare("select count(*) as total_reservations from reservation");
    $result->execute();
    $total = $result->fetchAll(PDO::FETCH_OBJ);

    // Return the data and the total number of records
    return array(
      'rows' => $data,
      'total' => $total[0]->total_reservations,
    );
  }
  function get_client_byid_reservation($id_reservation)
  {
    $query = "SELECT c.*,p.nom as pays, n.nom as nationalite FROM reservation r 
    INNER JOIN client c ON c.id_client = r.id_client
    INNER JOIN pays p on c.id_pays = p.id_pays
    INNER JOIN nationalite n on c.id_nationalite = n.id_nationalite
    WHERE r.id_reservation = $id_reservation";
    $result = connexion::getConnexion()->query($query);
    return $result->fetchAll(PDO::FETCH_ASSOC)[0];

  }
  function get_nombre_nuits_par_pays($date_debut, $date_fin,$pays,$nombre_nuits)
  {
    $filter_by_pays_or_nb_nuits = '';
    if($pays){
      $filter_by_pays_or_nb_nuits .= " AND FIND_IN_SET(p.id_pays, '$pays')>0 ";
    }
    if($nombre_nuits){
      $filter_by_pays_or_nb_nuits .= " AND dr.nombre_nuits = $nombre_nuits ";
    }
    $query = "SELECT
    c.id_pays,
    p.nom AS pays,
    dr.nombre_nuits,
    COUNT(dr.nombre_nuits) AS total
    FROM
        reservation r
    INNER JOIN CLIENT c ON
        FIND_IN_SET(c.id_client, r.id_client) > 0
    INNER JOIN pays p ON
        c.id_pays = p.id_pays
    INNER JOIN detail_reservation dr ON
        r.id_reservation = dr.id_reservation
    WHERE
        dr.checkin = 1 
        AND r.date_reservation BETWEEN '$date_debut' AND '$date_fin'
        $filter_by_pays_or_nb_nuits
    GROUP BY
    c.id_pays,
    dr.nombre_nuits";
    $result = connexion::getConnexion()->query($query);
    return $result->fetchAll(PDO::FETCH_ASSOC);
  }
  function get_reservations_etat($date_debut, $date_fin)
  {
    $query = "SELECT IFNULL(sum(rr.montant),0) as regelement,IFNULL(r.montant_total-sum(rr.montant),r.montant_total) as rest, r.montant_total, r.id_reservation,r.date_reservation,r.remarque,c.nom 
    FROM reservation r 
    INNER JOIN client c ON  c.id_client = r.id_client
    lEFT JOIN reg_reservation rr ON rr.id_reservation = r.id_reservation
    WHERE r.date_reservation BETWEEN '$date_debut' AND '$date_fin'
    GROUP BY r.id_reservation";
    $result = connexion::getConnexion()->query($query);
    return $result->fetchAll(PDO::FETCH_ASSOC);
  }
}

?>