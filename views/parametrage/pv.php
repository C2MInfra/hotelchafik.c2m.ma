<?php
if (isset($_POST['ajax'])) {
include('../../evr.php');
}

?>

<div class="container-fluid disable-text-selection">
    <div class="row">
        <div class="col-12">
            <div class="mb-2">
                <h1>Configuration point de vente </h1>


            </div>

            <div class="separator mb-5"></div>
        </div>
    </div>

    <div class="row">
        <div class="col align-self-start">
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="mb-4">Point de vente</h5>
                    <form id="addform" method="post" name="form_produit" enctype="multipart/form-data">
                        <input type="hidden" name="act" value="insert">
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="guid_depot"> GUID (Id Unique Pour Chaque Depot) :</label>
                                <input readonly type="text" class="form-control" id="guid_depot" name="guid_depot"
                                    placeholder="">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="nom_depot">Nom depot :</label>
                                <input type="text" class="form-control" id="nom_depot" name="nom_depot"
                                    placeholder="Nom depot">
                            </div>
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
    function uuidv4() {
        return ([1e7] + -1e3 + -4e3 + -8e3 + -1e11).replace(/[018]/g, c =>
            (c ^ crypto.getRandomValues(new Uint8Array(1))[0] & 15 >> c / 4).toString(16)
        );
    }

    $(document).ready(function () {

        let uuid = uuidv4();

        let uuid_field = document.getElementById("guid_depot");

        uuid_field.value=uuid;

    })
</script>