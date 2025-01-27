<?php

include('../../evr.php');

if ($_POST['act'] == 'filter') {

    $vente = new cloturages();

    if ($_POST['anne'] != 0) {
        $data = $vente->selectAll3($_POST['anne'] . "-" . $_POST['mois']);
    }
    if ($_POST['anne'] == 0)
        $data = $vente->selectAll3all();

?>

    <table class="table  responsive table-striped table-bordered table-hover" id="datatables">

        <thead>

            <tr>
                <th scope="col">Id</th>

                <th scope="col">Client</th>

                <th class="nowrap"> Date</th>

                <th scope="col"> Montant</th>

                <th scope="col"> Nombre Op</th>

                <th scope="col"> Carte</th>

                <th scope="col"> Espece</th>

                <th scope="col"> Compte</th>

                <th scope="col"> Offert</th>

                <!-- <th scope="col">Actions</th> -->

            </tr>

        </thead>

        <tbody>

            <?php
            foreach ($data as $ligne) {
            ?>


                <tr>
                    <td>
                        <?php echo $ligne->id ?>
                    </td>
                    <td>
                        <?php echo $ligne->nom ?>
                    </td>
                    <td>
                        <?php echo $ligne->created_at ?>
                    </td>
                    <td>
                        <?php echo $ligne->montant ?>
                    </td>
                    <td>
                        <?php echo $ligne->nombreOperation ?>
                    </td>
                    <td>
                        <?php echo $ligne->montant_carte ?> DH
                    </td>
                    <td>
                        <?php echo $ligne->montant_espece ?> DH
                    </td>
                    <td>
                        <?php echo $ligne->montant_compte ?> DH
                    </td>
                    <td>
                        <?php echo $ligne->montant_offert ?> DH
                    </td>
                    <!-- <td class="nowrap">



                        <?php
                        if (auth::user()['privilege'] == 'Admin') {


                        ?>
                            <a class="badge badge-danger mb-2 delete" data-id="<?php echo $ligne->id; ?>" style="color: white;cursor: pointer;" title="Supprimer" href='javascript:void(0)'>

                                <i class="simple-icon-trash" style="font-size: 15px;"></i>

                            </a>

                        <?php

                        }

                        ?>



                        <a class="badge badge-warning mb-2  url notlink" data-url="recette/update.php?id=<?php echo $ligne->id; ?>" style="color: white;cursor: pointer;" title="Modifier" href="javascript:void(0)">

                            <i class="iconsmind-Pen-5" style="font-size: 15px;"> </i>

                        </a>






                        <a class="badge badge-success mb-2 duplicate" data-id="<?php echo $ligne->id_vente ?>" style="color: white;cursor: pointer;" title="Dupliquer" href='javascript:void(0)'>
                            <i class="fa-solid fa-copy" style="font-size: 15px;"></i>
                        </a>










                    </td> -->
                </tr>


            <?php
            }
            ?>


        </tbody>

    </table>


<?php



}
if ($_POST['act'] == 'insert') {
    try {
        $_POST['updated_at'] = $_POST['created_at'];
        $categorie = new cloturages();
        $res = $categorie->insert();
        if ($res) {
            die('success');
        }
        die("Erreur");
    } catch (Exception $e) {
        die($e);
    }
}
if ($_POST['act'] == 'delete') {

    try {
        $produit = new cloturages();
        $produit->delete($_POST["id"]);
        die('success');
    } catch (Exception $e) {
        die($e);
    }
}
