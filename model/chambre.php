<?php

class chambre extends table
{
  protected $id_chambre;
  protected $numero_chambre;
  protected $metrage;
  protected $caracteristique;
  protected $haute_une_personne;
  protected $basse_une_personne;
  protected $normale_une_personne;
  protected $haute_deux_personnes;
  protected $basse_deux_personnes;
  protected $normale_deux_personnes;
  protected $haute_trois_personnes;
  protected $basse_trois_personnes;
  protected $normale_trois_personnes;
  protected $haute_supplement;
  protected $basse_supplement;
  protected $normale_supplement;
  protected $lit;
  public function get_chambre_cracteristiques()
  {
    $result = connexion::getConnexion()->query("SELECT * FROM chambre_caracteristique ORDER BY label ASC");
    return $result->fetchAll(PDO::FETCH_OBJ);
  }
  public function get_cracteristiques()
  {
    $result = connexion::getConnexion()->query("SELECT * FROM chambre_caracteristique ORDER BY label ASC");
    return $result->fetchAll(PDO::FETCH_OBJ);
  }
  public function get_chambres($requestData)
  {
    $columns = array(
      0 => "id_chambre",
      1 => "numero_chambre",
      2 => "metrage",
      3 => "lit",
    );
    // Connect to your database
    $db = connexion::getConnexion();

    // Define the SQL query
    $sql = "select id_chambre, numero_chambre, metrage, lit from chambre ";

    // Add the WHERE clause for the search
    if (!empty($requestData['search']['value'])) {
      $sql .= " WHERE (" . $columns[0] . " LIKE '%" . $requestData['search']['value'] . "%'";
      $sql .= " OR " . $columns[1] . " LIKE '%" . $requestData['search']['value'] . "%'";
      $sql .= " OR " . $columns[2] . " LIKE '%" . $requestData['search']['value'] . "%'";
      $sql .= " OR " . $columns[3] . " LIKE '%" . $requestData['search']['value'] . "%'";
      $sql .= " OR " . $columns[4] . " LIKE '%" . $requestData['search']['value'] . "%'";
      $sql .= " OR " . $columns[5] . " LIKE '%" . $requestData['search']['value'] . "%'";
      $sql .= " OR " . $columns[6] . " LIKE '%" . $requestData['search']['value'] . "%')";
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
    $result = $db->prepare("select count(*) as total_chambres from chambre");
    $result->execute();
    $total = $result->fetchAll(PDO::FETCH_OBJ);

    // Return the data and the total number of records
    return array(
      'rows' => $data,
      'total' => $total[0]->total_chambres,
    );
  }
}
