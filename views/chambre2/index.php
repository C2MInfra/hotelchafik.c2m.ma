<?php
if (isset($_POST['ajax'])) {
  include('../../evr.php');
}
?>
<div class="container-fluid disable-text-selection">
  <div class="row">
    <div class="col-12">
      <div class="mb-2">
        <h1>Liste Des Chambres</h1>
        <div class="float-sm-right text-zero">
          <button type="button" class="btn btn-primary btn-lg  mr-1 url notlink" data-url="chambre/add.php">AJOUTER</button>
        </div>
      </div>
      <div class="separator mb-5"></div>
    </div>
  </div>
  <div class="row">
    <div class="col-xl-12 col-lg-12 mb-4">
      <div class="card h-100">
        <div class="card-body">
          <div id="results">
            <table class="table  responsive table-striped table-bordered table-hover" id="chambres-table">
              <thead>
                <tr>
                  <th scope="col" width="1px">Id</th>
                  <th scope="col">N° Chambre</th>
                  <th>Métrage</th>
                  <th style="text-align:center"scope="col">Prix 1</th>
                  <th style="text-align:center"scope="col">Prix 2</th>
                  <th style="text-align:center"scope="col">Prix 3</th>
                  <th style="text-align:center" scope="col">Lit</th>
                  <th scope="col">Actions</th>
                </tr>
              </thead>
              <tbody>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php
include('js\index.js.php');
?>