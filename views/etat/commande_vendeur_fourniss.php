<?php
include("../../evr.php");


function dateFormat($dat)
{

    $date = new DateTime($dat);
    return $date->format('d-m-Y');
}

function dateFormat1($dat)
{

    $date = new DateTime($dat);
    return $date->format('Y-m-d');
}

$poids = 0;
$qte = 0;
$client = new client();
$res = $client->selectById($_GET["id"]);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title></title>

    <style type="text/css">
        .row {
            background-color: #28a745 !important;
            color: white;
        }

        .tableform {
            background-color: #999999;
            width: 400px;
            margin: 150px auto;
        }

        .inputText {
            height: 22px;
            width: 80%;
            border-radius: 3px;
            margin-top: 10px;
        }

        .button {
            height: 25px;
            width: 30%;
            border-radius: 3px;
            margin-top: 10px;
            font-weight: bold;
        }

        .button:hover {
            color: #666666;
            cursor: pointer;
        }

        h3 {
            text-transform: uppercase;
            color: #666;
        }

        .datatables {
            border-collapse: collapse;
            width: 100%;
        }

        .row {
            background-color: #CCCCCC;
        }

        .montant {
            text-align: right;
        }

        .date {
            color: #28a745;
        }

        th,
        td {
            padding: 6px;
        }
    </style>
</head>

<body style="width:950px;margin:auto;">

    <?php if (isset($_POST['submit'])) {


        if ($_POST['dd'] == "") {
            $_POST['dd'] = '2000-01-01';
            $is_all = true;
        }
        if ($_POST['df'] == "") {
            $_POST['df'] = '2100-12-31';
        }



        $query = "SELECT p.designation,dbv.prix_produit,v.date_vente,dbv.qte_vendu,dbv.qte_actuel,p.qte_actuel as stock 
FROM produit p 
JOIN fournisseur f ON p.fournisseur = f.id_fournisseur 
JOIN detail_vente dv ON p.id_produit = dv.id_produit 
JOIN vente v ON v.id_vente = dv.id_vente 
JOIN detail_bon_vendeur dbv ON dbv.id_bon = v.id_bon
JOIN boncommandevendeur bv ON bv.id_bon = dbv.id_bon 
WHERE bv.id_vendeur = " . $_GET['id'] . " AND p.fournisseur =" . $_POST['fournisseur'] . " AND v.date_vente BETWEEN '" . dateFormat1($_POST['dd']) . "' AND '" . dateFormat1($_POST['df']) . "'";



        $result = connexion::getConnexion()->query($query)->fetchAll(PDO::FETCH_OBJ);

        $founr = new fournisseur();
        $four = $founr->selectById($_POST['fournisseur']);



    ?>


        <?php
        if ($is_all) {
        ?>


            <h3 align="center" style="border: 2px solid black;
    padding: 6px; color:black;"> Etat des ventes du GLOBAL </span>
            </h3>
        <?php
        } else {

        ?>
            <h3 align="center" style="border: 2px solid black;
    padding: 6px; color:black;"> Etat des ventes (Commandes vendeurs) du <span class="date"><?php echo dateFormat($_POST['dd']); ?></span> A <span class="date"><?php echo dateFormat($_POST['df']); ?></span>
            </h3>
        <?php

        }

        ?>





        <h4 align="center" style="border: 2px solid black;
    padding: 6px; color:black;"> Fournisseur : <span class="date"><?php print_r($four['raison_sociale']) ?></span>
        </h4>

        <h3 align="center" style="
    padding: 6px; color:black;"><span class="date"><?php echo $res["nom"] . " " . $res["prenom"] ?></span>
        </h3>
        <table class="datatables" border=1 style="border-style:none;">
            <tr class="row">
                <th scope="col">Produit</th>
                <th scope="col">Prix Vente</th>
                <th scope="col">Date Vente</th>
                <th width="10%" scope="col">QTE Vendu</th>
                <th width="10%" scope="col">Restante</th>
                <th width="10%" scope="col">Monatant Stock</th>
                <th scope="col">Stock Actuel</th>
            </tr>

            <?php
            $total_qte_vendu = 0;
            $total_qte_qte_restante = 0;
            $total_qte_mt_stock = 0;
            $total_stok_actuel = 0;
            foreach ($result as $key => $value) {
            ?>
                <tr>
                    <td><?php echo $value->designation ?></td>
                    <td align="right"><?php echo $value->prix_produit ?></td>
                    <td align="center"><?php echo $value->date_vente ?></td>
                    <td align="right"><?php echo $value->qte_vendu ?></td>
                    <td align="right"><?php echo $value->qte_actuel ?></td>
                    <td align="right"><?php echo $value->qte_actuel * $value->prix_produit ?></td>
                    <td align="right"><?php echo $value->stock ?></td>
                </tr>

            <?php

                $total_qte_vendu += (float)$value->qte_vendu;
                $total_qte_qte_restante += (float)$value->qte_actuel;
                $total_qte_mt_stock += (float)$value->qte_actuel * $value->prix_produit;
                $total_stok_actuel += (float)$value->stock;
            }

            ?>


            <tr class="row">
                <th scope="col">Total : </th>
                <th scope="col"></th>
                <th scope="col"></th>
                <th align="right" width="10%" scope="col"><?php echo number_format($total_qte_vendu, 2, ',', '.') ?></th>
                <th align="right" width="10%" scope="col"><?php echo number_format($total_qte_qte_restante, 2, ',', '.') ?></th>
                <th align="right" width="10%" scope="col"><?php echo number_format($total_qte_mt_stock, 2, ',', '.') ?></th>
                <th align="right" scope="col"><?php echo number_format($total_stok_actuel, 2, ',', '.') ?></th>
            </tr>


        </table>
        <br />

        <!-- <table class="datatables" border=1 style="border-style:none; width:30%;">
            <tr>
                <td width="50%" style="background-color:#28a745; color:white;">Reste précédent</td>
                <td align="right">
                </td>
            </tr>
            <tr>
                <td width="50%" style="background-color:#28a745; color:white;">Total ventes</td>
                <td align="right">
                </td>
            </tr>
            <tr>
                <td style="background-color:#28a745; color:white;">Total Reg</td>
                <td align="right">
                </td>
            </tr>
            <tr>
                <td style="background-color:#28a745; color:white;">Reste</td>
                <td align="right">
                </td>
            </tr>
        </table> -->
        <br><br><br>
    <?php
    } else {
        include("form_date_fournisseur.php");
    } ?>

    </center>
</body>

</html>