<?php
use function PHPSTORM_META\type;
if (isset($_POST['ajax'])) {
    include('../../evr.php');
}
$charge = new charge();
$exploded = explode('?id=', $_SERVER["REQUEST_URI"]);
$exploded_ids = explode('&id_achat=', $exploded[1]);
$oldvalue = (array) $charge->selectById($exploded_ids[0])[0];
$charge = new charge();
$data = $charge->selectDesignation();
// var_dump($data) ;  
//  die() ; 
?>
<div class="container-fluid disable-text-selection">
    <div class="row">
        <div class="col-12">
            <div class="mb-2">
                <h1>charges d'achat </h1>
                <div class="float-sm-right text-zero">
                    <button type="button" class="btn btn-success  url notlink"
                        data-url="charge_achat/index.php?id=<?php echo $exploded_ids[1]; ?>"> <i
                            class="glyph-icon simple-icon-arrow-left"></i></button>
                </div>
            </div>
            <div class="separator mb-5"></div>
        </div>
    </div>
    <div class="row">
        <div class="col align-self-start">
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="mb-4">Modification charge</h5>
                    <form id="addform" method="post" name="form_charge" enctype="multipart/form-data">
                        <input type="hidden" name="act" value="update">
                        <input type="hidden" name="id" value="<?php echo $exploded_ids[0]; ?>">
                        <input type="hidden" name="id_achat" value="<?php echo $exploded_ids[1]; ?>">
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="id_fournisseur">Fournisseur : </label>
                                <input type="text" name="fournisseur" id="fournisseur"
                                    value="<?php echo $oldvalue['fournisseur']; ?>" class="form-control"
                                    placeholder="Fournisseur">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="designation">Désignation</label>
                                <input type="text" class="form-control" id="designation" placeholder="Designation"
                                    value="<?php echo $oldvalue['designation'] ?>" name="designation">
                                <!-- <select title="D&eacute;signation" class="form-control" name="designation" id="designation">
                                    <option></option>
                                    <?php
                                    //     foreach($data as $ligne){
                                    //         if ($oldvalue['designation'] == $ligne->libele ) {
                                    //     echo "<option value='$ligne->libele' selected>".$ligne->libele."</option>";
                                    // }
                                    // else {
                                    //      echo "<option value='$ligne->libele'>".$ligne->libele."</option>";
                                    // }
                                    //     }
                                    ?>
                                </select> -->
                            </div>
                            <div class="form-group col-md-4">
                                <label for="mode_reg">Mode de r&eacute;glement : </label>
                                <select name="mode_reg" class="form-control" id="mode_reg" onchange="if(this.value=='Espece'){
                                    document.getElementById('num_cheque').disabled='false';
                                    document.getElementById('num_cheque').value='';
                                    }else{
                                    document.getElementById('num_cheque').disabled='';
                                    }
                                    if (this.value=='Effet' || this.value=='Cheque'){
                                    document.getElementById('date_validation_tr').style.display = 'block';
                                    }else {
                                    document.getElementById('date_validation_tr').style.display = 'none';
                                    }">
                                    <option value=""> Choix</option>
                                    <option value="Espece" <?php echo $oldvalue['mode_reg'] == 'Espece' ? 'selected' : '' ?>> Espece</option>
                                    <option value="Cheque" <?php echo $oldvalue['mode_reg'] == 'Cheque' ? 'selected' : '' ?>> Cheque</option>
                                    <option value="Virement" <?php echo $oldvalue['mode_reg'] == 'Virement' ? 'selected' : '' ?>> Virement</option>
                                    <option value="Effet" <?php echo $oldvalue['mode_reg'] == 'Effet' ? 'selected' : '' ?>> Effet</option>
                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="num_cheque">Num&eacute;ro : </label>
                                <input type="text" name="num_cheque" class="form-control" id="num_cheque" <?php echo $oldvalue['num_cheque'] == '' ? 'disabled' : '' ?>
                                    value="<?php echo $oldvalue['num_cheque'] ?>" />
                            </div>
                            <div class="form-group col-md-4">
                                <label for="montant" class="col-form-label">Montant</label>
                                <input type="text" class="form-control" id="montant" placeholder="Montant"
                                    name="montant" value="<?php echo $oldvalue['montant'] ?>">
                            </div>
                            <div class='col-md-4'>
                                <label for="id_fournisseur">Devis : </label>
                                <select class="form-control select2-single " name="devise" id="devise">
                                    <option value="1" <?php echo $oldvalue['devise'] == '1' ? "selected" : "" ?>>MAD
                                    </option>
                                    <option value="9.8" <?php echo $oldvalue['devise'] == '9.8' ? "selected" : "" ?>>USD
                                    </option>
                                    <option value="9" <?php echo $oldvalue['devise'] == '9' ? "selected" : "" ?>>EUR
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="cout_devise"> Cout devise : </label>
                                <input name="" type="text" class="form-control" value=1 id="cout_devise" />
                            </div>
                            <div class="form-group col-md-4">
                                <label for="date_charge">Date Charge</label>
                                <input name="date_charge" type="text" class="form-control datepicker"
                                    placeholder="2019-01-03" value="<?php echo $oldvalue['date_charge'] ?>" />
                            </div>
                            <div class="form-group mb-10 col-md-4">
                                <label>Piéce jointe</label>
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="image" name="image">
                                        <label class="custom-file-label" for="inputGroupFile04">Choisir piéce jointe
                                            charge</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-md-4" id="date_validation_tr">
                                <label for="date_validation">Date d'&eacute;ch&eacute;cance: </label>
                                <input type="text" autocomplete="off" name="date_validation"
                                    class="form-control datepicker" id="date_validation"
                                    value="<?php echo $oldvalue['date_validation'] ?>" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="remarque"> Remarque : </label>
                            <textarea class="form-control" name="remarque"
                                id="remarque"><?php echo $oldvalue['remarque']; ?></textarea>
                        </div>
                        <div class="float-sm-right text-zero">
                            <button type="submit" class="btn btn-primary btn-lg  mr-1 ">Enregistrer</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        $(".select2-single").select2({
            theme: "bootstrap",
            placeholder: "",
            maximumSelectionSize: 6,
            containerCssClass: ":all:"
        });
        $("#id_categorie").change(function () {
            var id_categorie = $(this).val();
            $.ajax({
                type: "POST",
                url: "<?php echo BASE_URL . 'views/charge_achat/'; ?>controle.php",
                data: { act: "getcat", id_categorie: id_categorie },
                success: function (data) {
                    $('#code_bar').val(data);
                }
            });
        });
        $("#addform").on("submit", function (event) {
            event.preventDefault();
            var form = $(this);
            $.ajax({
                type: "POST",
                url: "<?php echo BASE_URL . 'views/charge_achat/'; ?>controle.php",
                data: new FormData(this),
                dataType: 'text',  // what to expect back from the PHP script, if anything
                cache: false,
                contentType: false,
                processData: false,
                success: function (data) {
                    if (data.indexOf("success") >= 0) {
                        swal(
                            'Modification',
                            'charge a ete bien modifie',
                            'success'
                        ).then((result) => {
                            $.ajax({
                                method: 'POST',
                                data: { ajax: true },
                                url: `<?php echo BASE_URL . "views/charge_achat/index.php?id=$exploded_ids[1]"; ?>`,
                                context: document.body,
                                success: function (data) {
                                    history.pushState({}, "", `<?php echo BASE_URL . "index.php?id=$exploded_ids[1];" ?>`);
                                    $("#main").html(data);
                                }
                            });
                        });
                    }
                    else {
                        form.append(` <div id="alert-danger" class="alert  alert-danger alert-dismissible fade show rounded mb-0" role="alert">
                                <strong>${data}</strong> 
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                            </div>`);
                    }
                }
            });
        });
    });
</script>