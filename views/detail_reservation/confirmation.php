<?php
// Include autoloader if you installed via Composer
include_once('..\..\eve.php');


// Get data
$reservation_id = $_GET['id'];
$reservation = new reservation();
$clients_data = $reservation->selectQuery("select reservation.*, client.*, nationalite.nom as nationalite_nom, pays.nom as pays_nom from reservation 
left join client on FIND_IN_SET(client.id_client, reservation.id_client) > 0
left join pays on client.id_pays = pays.id_pays
left join nationalite on client.id_nationalite = nationalite.id_nationalite
where id_reservation = '$reservation_id'");
$detail_reservation = $reservation->selectQuery("
select *, chambre.numero_chambre from detail_reservation
left join chambre on detail_reservation.id_chambre = chambre.id_chambre
inner join reservation on detail_reservation.id_reservation = reservation.id_reservation
where reservation.id_reservation = '$reservation_id'");

//send content type header set to json
// header('Content-Type: application/json');
// echo json_encode($data);
// die();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice Document</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .invoice-container {
            // display: grid;
            // grid-template-rows: auto auto auto auto auto;
            gap: 20px;
            padding: 20px;
            max-width: 800px;
            margin: auto;
        }

        .header {
            text-align: center;
            font-size: 24px;
            font-weight: bold;
        }

        .details {
            // display: grid;
            // grid-template-columns: auto;
        }

        .details .date {
            font-size: 14px;
        }

        .details .location {
            font-size: 14px;
            margin-top: 5px;
        }

        .client-table,
        .rooms-table,
        .totals-table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 10px;
        }

        .client-table th,
        .client-table td,
        .rooms-table th,
        .rooms-table td,
        .totals-table th,
        .totals-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        .totals-table {
            float: right;
            width: auto;
        }

        .totals-table th {
            font-weight: bold;
        }

        .totals-table td {
            text-align: right;
        }

        .rooms-table th {
            background-color: #f2f2f2;
        }
    </style>
</head>

<body>
    <div class="invoice-container">
        <!-- Row 1: Header -->
        <div class="header">Confirmation</div>

        <!-- Row 2: Date and Location -->
        <div class="details">
            <div class="location">Casablanca</div>
            <div class="date">le <?php echo date('d-m-Y') ?></div>
        </div>

        <!-- Row 3: Client Information Table -->
        <table class="client-table">
            <thead>
                <tr>
                    <th>Client</th>
                    <th>CIE</th>
                    <th>Téléphone</th>
                    <th>Pays</th>
                    <th>Nationalité</th>
                    <th>Adresse</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($clients_data as $client) { ?>
                    <tr>
                        <td><?php echo $client->nom . ' (' . $client->cin ?>)</td>
                        <td><?php echo $client->cie?></td>
                        <td><?php echo $client->telephone?></td>
                        <td><?php echo $client->pays_nom?></td>
                        <td><?php echo $client->nationalite_nom?></td>
                        <td><?php echo $client->adresse?></td>
                    </tr>
                    <?php
                }
                ?>

            </tbody>
        </table>

        <!-- Row 4: Rooms Information Table -->
        <table class="rooms-table">
            <thead>
                <tr>
                    <th>Numero chambre</th>
                    <th>Prix</th>
                    <th>Nbr nuits</th>
                    <th>Nbr personnes</th>
                    <th>Date d\'arrivé</th>
                    <th>Date depart</th>
                    <th>CheckIn</th>
                    <th>CheckOut</th>
                </tr>
            </thead>
            <tbody>
            <?php
            $montant_total = 0;
                foreach ($detail_reservation as $detail) { ?>
                    <tr>
                        <td><?php echo $detail->numero_chambre ?></td>
                        <td><?php echo $detail->montant * $detail->nombre_nuits ?></td>
                        <td><?php echo $detail->nombre_nuits?></td>
                        <td><?php echo $detail->nombre_personnes?></td>
                        <td><?php echo $detail->date_arriver?></td>
                        <td><?php echo $detail->date_depart?></td>
                        <td><?php echo $detail->checkin?"OUI":"NON"?></td>
                        <td><?php echo $detail->checkout?"OUI":"NON"?></td>
                    </tr>
                    <?php
                    $montant_total += $detail->nombre_nuits * $detail->montant;
                }
                ?>
            </tbody>
        </table>

        <!-- Row 5: Totals Table -->
        <table class="totals-table">
            <tbody>
                <tr>
                    <th>Montant</th>
                    <td><?php echo $montant_total ?></td>
                </tr>
            </tbody>
        </table>
    </div>
</body>

</html>