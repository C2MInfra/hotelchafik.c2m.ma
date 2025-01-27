<?php

include('../../evr.php');

if ($_POST['act'] == 'filter') {

    $vente = new tracking_vente();

    if ($_POST['anne'] != 0) {
        $data = $vente->selectAll3($_POST['anne'] . "-" . $_POST['mois']);
    }
    if ($_POST['anne'] == 0) {
        $data = $vente->selectAll3all();
    }
?>

    <table class="table  responsive table-striped table-bordered table-hover" id="datatables">

        <thead>


            <tr>
                <th scope="col">Num Ticket</th>

                <th scope="col">Produit </th>

                <th class="nowrap"> Date</th>

                <th scope="col"> Qte Retour </th>

                <th scope="col"> Prix </th>

                <th scope="col"> Point Vente</th>

                <!-- <th scope="col">Actions</th> -->

            </tr>


        </thead>

        <tbody>

            <?php
            foreach ($data as $ligne) {
            ?>


                <tr>
                    <td>
                        <?php echo $ligne->num_ticket ?>
                    </td>
                    <td>
                        <?php echo $ligne->designation ?>
                    </td>
                    <td>
                        <?php echo $ligne->date_vente ?>
                    </td>
                    <td>
                        <?php echo -$ligne->qte_vendu ?>
                    </td>
                    <td>
                        <?php echo -$ligne->prix ?>
                    </td>
                    <td>
                        <?php echo $ligne->nom ?>
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
