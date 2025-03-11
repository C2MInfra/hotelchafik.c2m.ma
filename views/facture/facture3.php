<?php
include('../../evr.php');
include("../../model/convert.php");

$id = $_GET['id'];
$detail_reservation = new detail_reservation();
$detail_reservation = $detail_reservation->getReservationDetailsValid($id);
$reservation = new reservation();
$client = $reservation->get_client_byid_reservation($id);
$reserv = $reservation->selectById($id);
$facture = new facture();
$fact = $facture->selectById($_GET["idf"]);
$societe = connexion::getConnexion()->query("SELECT * FROM societe")->fetch(PDO::FETCH_OBJ);

$code = 'FV.' . $fact["num_fact"] . ' - ' . date("Y");
$total = 0;

?>
<!DOCTYPE html>
<html>

<head>
   <meta charset="UTF-8">
   <title>Facture</title>
   <style>
      body {
         font-family: DejaVu Sans, sans-serif;
      }

      .container {
         width: 90%;
         margin: auto;
      }

      .header,
      .footer {
         text-align: center;
         margin-bottom: 20px;
      }

      table {
         width: 100%;
         border-collapse: collapse;
         margin-bottom: 20px;
      }

      th,
      td {
         border: 1px solid black;
         padding: 8px;
         text-align: center;
      }

      th {
         background-color: #2597d5;
         color: white;
      }
   </style>
</head>

<body>
   <div class="container">
      <div class="header">
         <h2>Café * Hotel * Chafik</h2>
         <p>12 Bd Bir Anzarane, OUJDA</p>
         <p>Tel: 05 36 68 29 50 | Fax: 05 36 68 84 34</p>
      </div>

      <h3>Facture N°: <?php echo $code; ?></h3>

      <table>
         <tr>
            <td><strong>Client:</strong> <?php echo $client['nom']; ?></td>
            <td><strong>ICE:</strong> <?php echo $client['ice']; ?></td>
            <td><strong>Adresse:</strong> <?php echo trim($client['adresse']); ?></td>
         </tr>
      </table>

      <table>
         <tr>
            <th>Numéro chambre</th>
            <th>Prix</th>
            <th>Nombre de nuits</th>
            <th>Nombre de personnes</th>
            <th>Date d'arrivée</th>
            <th>Date de départ</th>
            <th>Montant</th>
         </tr>
         <?php foreach ($detail_reservation as $ligne) { ?>
            <tr>
               <td><?php echo $ligne["numero_chambre"]; ?></td>
               <td><?php echo number_format($ligne['montant'], 2); ?></td>
               <td><?php echo $ligne["nombre_nuits"]; ?></td>
               <td><?php echo $ligne["nombre_personnes"]; ?></td>
               <td><?php echo $ligne["date_arriver"]; ?></td>
               <td><?php echo $ligne["date_depart"]; ?></td>
               <td><?php echo number_format($ligne['montant'] * $ligne['nombre_nuits'], 2); ?></td>
            </tr>
            <?php $total += $ligne['montant'] * $ligne['nombre_nuits'];
         } ?>
      </table>

      <table>
         <tr>
            <th>Total</th>
            <td><?php echo number_format($total, 2, ".", " "); ?> DH</td>
         </tr>
      </table>

      <div class="footer">
         <p>Merci pour votre visite!</p>
      </div>
   </div>
</body>

</html>