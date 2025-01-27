<?php

include('../../evr.php');
if ($_POST['act'] == 'addProdAchatVente') {

    if (!isset($_SESSION['rand_v_er']) || $_SESSION['rand_v_er'] === "") {

        $_SESSION['rand_v_er'] = rand(10, 1000);
    }

    $_POST["id_user"] = auth::user()["id"];

    $somme_poid = 0;



    $_POST["id_vente"] = "-1" . $_SESSION['rand_v_er'];

    $_POST['qte_restante'] = $_POST['qte_vendu'];


    $detail_vente = new detail_vente();




    // $query = "SELECT * FROM detail_vente WHERE id_produit =".$_POST['id_produit']." AND id_vente = ".$_POST['id_vente'];


    // $result = connexion::getConnexion()->query($query)->fetch(PDO::FETCH_OBJ);




    // if($result->id_vente){
    //   $query = "UPDATE detail_vente SET qte_vendu = qte_vendu + ".$_POST['qte_vendu']." WHERE id_vente = " . $_POST['id_vente'];
    //   connexion::getConnexion()->query($query)->execute();
    // }
    // else{
    //   $detail_vente->insert();
    // }


    $detail_vente->insert();

    $data = $detail_vente->selectAllNonValide();

    $total = 0;




    // Code Achat

    if (!isset($_SESSION['rand_a_er']) || $_SESSION['rand_a_er'] === "") {
        $_SESSION['rand_a_er'] = rand(10, 1000);
    }

    $_POST["id_user"] = auth::user()["id"];

    $somme_poid = 0;

    $_POST["id_achat"] = "-1" . $_SESSION['rand_a_er'];

    $pu = $_POST["prix_produit"];
    $cd = $_POST["cout_device"];
    $fa = $_POST["f_approch"];

    // $_POST['prix_produit'] = $pu * $cd * $fa;
    $_POST['prix_produit'] = $pu * $fa;

    $detail_achat = new detail_achat();

    $detail_achat->insert();

    $data1 = $detail_achat->selectAllNonValide();

    $total = 0;



    //HTML TABLE


    foreach ($data1 as $index => $ligne) {


?>

        <tr>

            <td>
                <?php echo $ligne->designation; ?>
            </td>

            <td>
                <?php echo $ligne->depot ?>
            </td>

            <td>
                <?php echo $ligne->prix_produit; ?>
            </td>


            <td>
                <?php echo $ligne->qte_achete ?>
            </td>
            <td>
                <?php echo $data[$index]->qte_vendu ?>
            </td>





            <td> <a class="badge badge-danger mb-2 delete" data-id="<?php echo $ligne->id_detail; ?>" style="color: white;cursor: pointer;" title="Supprimer" href='javascript:void(0)'>

                    <i class="simple-icon-trash" style="font-size: 15px;"></i>

                </a>

            </td>

        </tr>

    <?php

    }

    ?>

    <tr>

        <td colspan="4" style="text-align: center;font-size: 15px;"> <b>Total</b> </td>

        <td style="text-align: right;" colspan="3"> <b style="font-size: 15px;color: green;text-align: right;">
                <?php echo number_format($total, 2, '.', ' '); ?>
            </b></td>



    </tr>

<?php


}

if ($_POST['act'] == 'insertAchatVente') {

    // $_POST["date_achat"]=date("Y-m-d");

    $_POST["id_user"] = auth::user()["id"];

    $achat = new achat();

    if (isset($_POST["id_fournisseur"])) {

        $achat->insert();

        connexion::getConnexion()->exec("UPDATE  detail_achat  SET detail_achat.id_achat =(SELECT max(achat.id_achat) FROM achat)   WHERE detail_achat.id_achat=-1" . $_SESSION["rand_a_er"]);

        unset($_SESSION['rand_a_er']);

        // $query=$result=connexion::getConnexion()->query("SELECT max(id_achat) as dernier_achat FROM achat ");

        // $result=$query->fetch(PDO::FETCH_OBJ);

        // $dernier_achat=$result->dernier_achat;
        /*
		$result2=connexion::getConnexion()->query("select da.id_produit,sum(da.qte_achete)as qte_achete from detail_achat da inner join achat a on a.id_achat=da.id_achat

		where a.id_achat=$dernier_achat group by  da.id_produit");

		$data=$result2->fetchAll(PDO::FETCH_OBJ);

		foreach($data as $ligne)

			{

		connexion::getConnexion()->exec("UPDATE produit SET qte_actuel=qte_actuel+".$ligne->qte_achete." WHERE  id_produit =".$ligne->id_produit);

			}
			*/
    }



    die("success");
}
