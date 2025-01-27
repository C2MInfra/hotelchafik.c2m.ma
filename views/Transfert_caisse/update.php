<?php
if (isset($_POST['ajax'])) {
    include('../../evr.php');
}
$transfert_caisse = new transfert_caisse();
$id = explode('?id=', $_SERVER["REQUEST_URI"]);
$oldvalue = $transfert_caisse->selectById($id[1]);
?>

<style>
    .input-group-text {
        cursor: pointer;
        color: black;
        transition: all .3s linear;
    }

    .input-group-text:hover {
        background-color: red;
    }
</style>
<div class="container-fluid disable-text-selection">
    <div class="row">
        <div class="col-12">
            <div class="mb-2">
                <h1>Transfert caisse </h1>
                <div class="float-sm-right text-zero">
                    <button type="button" class="btn btn-success  url notlink" data-url="transfert_caisse/index.php"> <i
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
                    <h5 class="mb-4">Modification Transfert Caisse</h5>
                    <form id="addform" method="post" name="form_transfert_caisse" enctype="multipart/form-data">
                        <input type="hidden" name="act" value="update">
                        <input type="hidden" name="id" value="<?php echo $id[1]; ?>">
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label for="designation">Désignation</label>
                                <input name="designation" type="text" class="form-control" id="designation"
                                    placeholder="Désignation transfert_caisse"
                                    value="<?php echo $oldvalue['designation'] ?>">
                            </div>
                            <!-- <div class="form-group col-md-4">
                                                <label for="mode_reg">Mode de r&eacute;glement : </label>
                                                <select name="mode_reg" class="form-control" id="mode_reg">
                                                    <option value=""> Choix</option>
                                                    <option value="débit" <?php echo $oldvalue['type_reg'] == 'effet' ? 'selected' : '' ?> > Effet</option>
                                                    <option value="crédit" <?php echo $oldvalue['type_reg'] == 'cheque' ? 'selected' : '' ?> > Chèque</option>                                               
                                                    <option value="crédit" <?php echo $oldvalue['type_reg'] == 'espece' ? 'selected' : '' ?> > Espèce</option>
                                                    </select> 
                                            </div> -->
                            <!-- <div class="form-group col-md-4">
                                                <label for="montant" class="col-form-label">Montant</label>
                                                <input type="text" class="form-control" id="montant" placeholder="Montant" name="montant" value="<?php echo $oldvalue['montant'] ?>">
                                            </div> -->
                            <div class="form-group col-md-4">
                                <label for="date_transfert_caisse">Date transfert_caisse</label>
                                <input name="date_transfert_caisse" type="text" class="form-control  datepicker"
                                    placeholder="2019-01-03" value="<?php echo $oldvalue['date_transfert_caisse'] ?>" />
                            </div>
                            <div class="form-group col-md-4 espece">
                                <label for="espece">Espèce</label>
                                <input name="montant_espece" type="text" class="form-control" id="montant_espece"
                                    placeholder="Montant En Espèce"  value="<?php echo $oldvalue['montant_espece'] ?>">
                            </div>
                            <div class='col-md-4'>
                                <label for="id_fournisseur">Devis : </label>
                                <select class="form-control select2-single " name="devise" id="devise">
                                    <option value="1" <?php echo $oldvalue['devise'] == '1' ? "selected" : "" ?> >MAD</option>
                                    <option value="9.8" <?php echo $oldvalue['devise'] == '9.8' ? "selected" : "" ?> >USD</option>
                                    <option value="9" <?php echo $oldvalue['devise'] == '9' ? "selected" : "" ?>  >EUR</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="cout_devise"> Cout devise : </label>
                                <input name="" type="text" class="form-control" value=1 id="cout_devise" />
                            </div>
                            <div class="form-group mb-10 col-md-4">
                                <label>Piéce jointe</label>
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="image" name="image">
                                        <label class="custom-file-label" for="inputGroupFile04">Choisir piéce jointe
                                            transfert caisse</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 effet shadow-lg ">
                                <div class="card ">
                                    <div class="card-body">
                                        <div class="input-group mb-3">
                                            <input type="hidden" name="effet_input_nbr[] value = 1 ">
                                            <div class="col-md-5">
                                                <label for="inputEffet1">Effet :</label>
                                                <input type="text" placeholder="Effet" class="form-control"
                                                    name="inputEffet1">
                                            </div>
                                            <div class="col-md-5">
                                                <label for="num_effet">Num&eacute;ro : </label>
                                                <input type="text" name="num_effet" class="form-control" id="num_effet1"
                                                    placeholder="Numéro" />
                                            </div>
                                            <!-- <div class="ml-2">
                                                      <label for="">&nbsp; </label>
                                                      <div>
                                                          <span class="input-group-text"><i class="fas fa-trash delete-icon"></i></span>
                                                      </div>
                                                  </div> -->
                                        </div>
                                        <button class="btn btn-primary fas fa-plus float-right"
                                            id="addButton1"></button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 cheque shadow-lg ">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="input-group mb-3">
                                            <input type="hidden" name="cheque_input_nbr[] value = 1 ">
                                            <div class="col-md-5">
                                                <label for="inputCheque1">Chèque :</label>
                                                <input type="text" placeholder="Chèque" class="form-control"
                                                    name="inputCheque1">
                                            </div>
                                            <div class="col-md-5">
                                                <label for="num_cheque">Num&eacute;ro : </label>
                                                <input type="text" name="num_cheque" class="form-control"
                                                    id="num_cheque1" placeholder="Numéro" />
                                            </div>
                                            <!-- <div class="ml-2">
                                                      <label for="">&nbsp; </label>
                                                      <div>
                                                          <div></div>
                                                          <span class="input-group-text"><i class="fas fa-trash delete-icon"></i></span>
                                                      </div>
                                                  </div> -->
                                        </div>
                                        <button class="btn btn-primary fas fa-plus float-right"
                                            id="addButton2"></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group mt-2">
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
        function add_new_input() {
            console.log("message ")
            let counter1 = 1;
            let counter2 = 1;
            // Add button click event for Card 1
            $('#addButton1').on('click', function (e) {
                counter1++;
                e.preventDefault();
                const newInputField = `
              <div class="input-group mb-3">
                                                  <input type="hidden" name="effet_input_nbr[] value = 1 ">
                                                  <div class="col-md-5">
                                                      <label for="inputEffet1">Effet :</label>
                                                      <input type="text" placeholder="Effet" class="form-control" name="inputEffet1">
                                                  </div>
                                                  <div  class="col-md-5">
                                                      <label for="num_effet">Num&eacute;ro : </label>
                                                      <input type="text" name="num_effet" class="form-control" id="num_effet1" placeholder="Numéro" />
                                                  </div>
                                                  <div class="ml-2">
                                                      <label for="">&nbsp; </label>
                                                      <div >
                                                          <span class="input-group-text"><i class="fas fa-trash delete-icon"></i></span>
                                                      </div>
                                                  </div>
                                              </div>
                `;
                $(newInputField).insertBefore('#addButton1');
            });
            // Add button click event for Card 2
            $('#addButton2').on('click', function (e) {
                counter2++;
                e.preventDefault();
                const newInputField = `
              <div class="input-group mb-3">
                                                  <input type="hidden" name="cheque_input_nbr[] value = 1 ">
                                                  <div class="col-md-5">
                                                      <label for="inputCheque1">Chèque :</label>
                                                      <input type="text" placeholder="Chèque" class="form-control" name="inputCheque1">
                                                  </div>
                                                  <div   class="col-md-5">
                                                      <label for="num_cheque">Num&eacute;ro : </label>
                                                      <input type="text" name="num_cheque" class="form-control" id="num_cheque1" placeholder="Numéro" />
                                                  </div>
                                                  <div  class="ml-2">
                                                      <label for="">&nbsp; </label>
                                                      <div >
                                                        <div></div>
                                                        <span class="input-group-text"><i class="fas fa-trash delete-icon"></i></span>
                                                      </div>
                                                  </div>
                                              </div>
                `;
                $(newInputField).insertBefore('#addButton2');
            });
        }
        $(document).on('click', '.input-group-text', function () {
            $(this).closest('.input-group').remove();
        });
        $(document).ready(function () {
            add_new_input();
        })
          $('#cout_devise').val($('#devise').val());
        $('#devise').change(() => {
            $('#cout_devise').val($('#devise').val());
        })
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
                url: "<?php echo BASE_URL . 'views/transfert_caisse/'; ?>controle.php",
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
                url: "<?php echo BASE_URL . 'views/transfert_caisse/'; ?>controle.php",
                data: new FormData(this),
                dataType: 'text',  // what to expect back from the PHP script, if anything
                cache: false,
                contentType: false,
                processData: false,
                success: function (data) {
                    if (data.indexOf("success") >= 0) {
                        swal(
                            'Modification',
                            'transfert caisse a ete bien modifie',
                            'success'
                        ).then((result) => {
                            $.ajax({
                                method: 'POST',
                                data: { ajax: true },
                                url: `<?php echo BASE_URL . "views/transfert_caisse/index.php"; ?>`,
                                context: document.body,
                                success: function (data) {
                                    history.pushState({}, "", `<?php echo BASE_URL . "transfert_caisse/index.php"; ?>`);
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