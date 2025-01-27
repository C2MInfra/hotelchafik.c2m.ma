<?php
if (isset($_POST['ajax'])) {
  include('../../evr.php');
}

$chambre = new chambre();
$chambres = $chambre->selectChamps('count(distinct numero_chambre)');
$totalproduits = $chambre->selectChamps("count(distinct numero_chambre) as total");

$client = new client();
$clients = $client->selectClientFidele();
$totalclients = $client->selectChamps("count(*) as total");
$detail_vente = new detail_vente();

$Total_vents = connexion::getConnexion()->query("SELECT v.id_vente,
DATE_FORMAT(v.date_vente,'%d-%m-%Y')as date_vente,
v.remarque , 
sum(dv.prix_produit*dv.qte_vendu*(1-dv.remise/100) ) as montantv ,
sum(dv.prix_produit*(if(dv.valunit=0,dv.qte_vendu,dv.valunit))*(1-dv.remise/100) ) as motunitv 

from vente v  
 left join detail_vente dv on dv.id_vente=v.id_vente
 inner join produit p on dv.id_produit=p.id_produit
 where  v.numbon <> 0
 group by  v.id_vente  
 order by id_vente asc ")->fetchAll(PDO::FETCH_OBJ);

$montant = 0;
foreach ($Total_vents as $rep) {
  if (!empty($rep->motunitv) || $rep->motunitv != 0) {
    $montant += ($rep->motunitv);
    $montv = $rep->motunitv;
  } else {
    $montant += ($rep->montantv);
    $montv = $rep->montantv;
  }
}


$reg_a = new reg_vente();


$result =  connexion::getConnexion()->query("select sum(reg_vente.montant) as total from reg_vente,vente where reg_vente.id_vente = vente.id_vente ");
$totalreg = $result->fetch(PDO::FETCH_OBJ);




$year = date('Y');
$datasource = '';
$data_vante = "";
$data_achat = "";
$tventes = "";
$tachats = "";
$tot_v = 0;
$tot_a = 0;
for ($j = 0; $j <= 6; ++$j) {

  $result_achat = connexion::getConnexion()->query("
        select COUNT(*) As total from achat a 
        where WEEKDAY(a.date_achat)=$j
        and YEARWEEK(a.date_achat, 1) = YEARWEEK(CURDATE(), 1)
    ")->fetch(PDO::FETCH_OBJ);

  $result_vente = connexion::getConnexion()->query("
    select COUNT(*) As total from vente v 
    where WEEKDAY(v.date_vente)=$j
    and YEARWEEK(v.date_vente, 1) = YEARWEEK(CURDATE(), 1)
    ")->fetch(PDO::FETCH_OBJ);

  $data_vante .= "$result_vente->total,";
  $data_achat .= "$result_achat->total,";

  //ventes
  $query = "SELECT t1.montantv,t2.avance from 
        (select v.id_vente,v.numbon,DATE_FORMAT(v.date_vente,'%d-%m-%Y')as date_vente,concat_ws(' ',c.nom,c.prenom)  as client
            ,c.id_client,c.nom_prenom_ar,v.remarque   ,sum(dv.prix_produit*dv.qte_vendu*(1-dv.remise/100)) as montantv ,
        sum(dv.prix_produit*(if(dv.valunit=0,dv.qte_vendu,dv.valunit))*(1-dv.remise/100) )as motunitv from vente v 
        left join client c on c.id_client=v.id_client 
        inner join detail_vente dv on dv.id_vente=v.id_vente 
        inner join produit p on dv.id_produit=p.id_produit
        where WEEKDAY(v.date_vente)=$j
        and YEARWEEK(v.date_vente, 1) = YEARWEEK(CURDATE(), 1)
        order by id_vente desc  ) as t1 
        left join (select id_vente,ifnull(sum(montant),0) as avance 
        from reg_vente group by id_vente ) as t2 on t2.id_vente=t1.id_vente
    ";

  $ventes_r = connexion::getConnexion()->query($query)->fetch(PDO::FETCH_OBJ);
  $tventes .= ($ventes_r->montantv != NULL) ? "$ventes_r->montantv," : "0,";
  $tot_v += ($ventes_r->montantv != NULL) ? (int)$ventes_r->montantv : 0;

  //achat
  $achat_query = "select a.id_achat,a.valide, a.date_achat,f.raison_sociale as fournisseur,f.id_fournisseur,a.remarque   ,sum(da.`prix_produit`*da.`qte_achete`)as
    montant from achat a left join  fournisseur f on  f.id_fournisseur=a.id_fournisseur left join detail_achat da on da.id_achat=a.id_achat
    where WEEKDAY(a.date_achat)=$j
    and YEARWEEK(a.date_achat, 1) = YEARWEEK(CURDATE(), 1)
    order by id_achat desc";

  $achat_r = connexion::getConnexion()->query($achat_query)->fetch(PDO::FETCH_OBJ);
  $tachats .= ($achat_r->montant != NULL) ? "$achat_r->montant," : "0,";
  $tot_a += ($achat_r->montant != NULL) ? (int)$achat_r->montant : 0;
}

$chambre = new produit();
$alertproduitc = count($chambre->selectAlertQte());
$totalproduit = (int)connexion::getConnexion()->query("SELECT COUNT(*) FROM produit")->fetch(PDO::FETCH_COLUMN);
$suff = (int)($totalproduit - $alertproduitc);
$per = (int)(($alertproduitc / $totalproduit) * 100);

$ca = connexion::getConnexion()->query("SELECT sum(montant * nombre_nuits) from detail_reservation where checkin = 1")->fetch(PDO::FETCH_COLUMN);

//recette
$recette = connexion::getConnexion()->query("select IF(sum(reg_vente.montant) IS NULL, 0, sum(reg_vente.montant)) as total from reg_vente,vente where reg_vente.id_vente = vente.id_vente
  AND MONTH(date_reg) = MONTH(now()) AND YEAR(date_reg) = YEAR(now())")->fetch(PDO::FETCH_COLUMN);

//reg_f
$reg_f = connexion::getConnexion()->query("select IF(sum(reg_achat.montant) IS NULL, 0, sum(reg_achat.montant)) as total from reg_achat,achat where reg_achat.id_achat = achat.id_achat
  AND MONTH(date_reg) = MONTH(now()) AND YEAR(date_reg) = YEAR(now())")->fetch(PDO::FETCH_COLUMN);

//charges
$charges = connexion::getConnexion()->query("select IF(sum(montant) IS NULL, 0, sum(montant)) as total from charge where 
   MONTH(date_charge) = MONTH(now()) AND YEAR(date_charge) = YEAR(now())")->fetch(PDO::FETCH_COLUMN);

//depense
$depense = $reg_f + $charges;

//emoji
if ($recette > $depense) {
  $emoji = 'emoji-happy.png';
} else {
  $emoji = 'emoji-unhappy.png';
}
?>
<style>
  .ctm-card {
    display: flex;
  }

  .ctm-icon {
    padding: 12px;
    margin: 0px 10px;
    font-size: 16pt;
    border-radius: 6px;
  }

  .ctm-icon-1 {
    color: #1648cf;
    background: #ece7ff;
  }

  .ctm-icon-2 {
    color: #459fc2;
    background: #ebf2f5;
  }

  .ctm-icon-3 {
    color: #f6c813;
    background: #fcfde2;
  }

  .ctm-icon-4 {
    color: #c24065;
    background: #ffeff2;
  }

  .ctm-muted-text {
    font-size: 11pt;
    color: #999;
    font-weight: 600;
  }

  .point-1 {
    background: #59bbf2;
    width: 12px;
    height: 12px;
    border-radius: 8px;
  }

  .point-2 {
    background: #1751b8;
    width: 12px;
    height: 12px;
    border-radius: 8px;
  }

  .donut-inner {
    margin-top: -110px;
    margin-bottom: 110px;

  }

  .donut-inner h5 {
    margin-bottom: 5px;
    margin-top: 0;
    text-align: center;
  }

  .donut-inner span {
    text-align: center;
    display: block;
  }

  .ctm-statistiques {
    background-color: #0153fd;
    border-radius: 4px;
  }

  .ctm-header>h3 {
    color: white;
  }

  .ctm-header>span {
    color: #92b3ea;
    font-size: 11pt;
    margin-bottom: 6px;
    display: block;
  }

  .badge-primary {
    background-color: #59bbf2 !important;
  }

  .ca-container {
    background: #e25682;
    color: white;
    padding: 22px;
  }

  .ca-item {
    padding: 20px 0px;
    margin: 0px 20px;
    font-size: 12pt;
    font-weight: 700;
  }

  .ca-item-1 {
    color: #358cbd;
    border-bottom: 1px solid #e3e3e3;
  }

  .ca-item-2 {
    color: #42bd7e;
    border-bottom: 1px solid #e3e3e3;
  }

  .ca-item-3 {
    color: #dd2020;
    border-bottom: 1px solid #e3e3e3;
  }

  .ctm-card strong {
    font-size: 11pt;
  }
</style>
<div class="container-fluid disable-text-selection">
  <div class="row d-none">
    <div class="col-12">
      <div class="mb-2">
        <h1>Tableau de bord</h1>
      </div>
      <div class="separator mb-5"></div>
    </div>
  </div>
  <div class="row ">

    <div class="col-xl-3 col-lg-6 mb-4">
      <div class="card">
        <div class="card-body ctm-card">
          <div class="ctm-icon ctm-icon-1">
            <i class="iconsmind-Shop-4"></i>
          </div>
          <div>
            <h4><strong><?php echo $totalproduits[0]->total ?></strong></h4>
            <span class="ctm-muted-text">Chambre</span>
          </div>
        </div>
      </div>
    </div>
    <div class="col-xl-3 col-lg-6 mb-4">
      <div class="card">
        <div class="card-body ctm-card">
          <div class="ctm-icon ctm-icon-2">
            <i class="iconsmind-Business-ManWoman"></i>
          </div>
          <div>
            <h4><strong><?php echo $totalclients[0]->total ?></strong></h4>
            <span class="ctm-muted-text">Clients</span>
          </div>
        </div>
      </div>
    </div>
    <div class="col-xl-3 col-lg-6 mb-4">
      <div class="card">
        <div class="card-body ctm-card">
          <div class="ctm-icon ctm-icon-3">
            <i class="iconsmind-Shopping-Cart"></i>
          </div>
          <div>
            <h4><strong><?php echo number_format($montant - $totalreg->total, 2, '.', ' '); ?> DH</strong></h4>
            <span class="ctm-muted-text">En attente</span>
          </div>
        </div>
      </div>
    </div>
    <div class="col-xl-3 col-lg-6 mb-4">
      <div class="card">
        <div class="card-body ctm-card">
          <div class="ctm-icon ctm-icon-4">
            <i class="iconsmind-Money-2"></i>
          </div>
          <div>
            <h4><strong><?php echo number_format($totalreg->total, 2, '.', ' '); ?> DH</strong></h4>
            <span class="ctm-muted-text">Réglé</span>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-md-8 col-sm-12 mb-4">
      <div class="card dashboard-filled-line-chart" style="height:100%;">
        <div class="">
          <div class="" style="padding: 30px 30px 60px;">
            <div class="d-flex justify-content-between">
              <h5 class="d-inline">Les Ventes et achats</h5>
              <div class="d-flex">
                <div class="d-flex align-items-center mr-3">
                  <div class="point-1 mr-2"></div> Achats
                </div>
                <div class="d-flex align-items-center">
                  <div class="point-2 mr-2"></div> Ventes
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="chart card-body pt-0">
          <canvas id="VenteChart" width="400" height="400"></canvas>
        </div>
      </div>
    </div>
    <div class="col-md-4 col-sm-12 mb-4">
      <div class="card dashboard-filled-line-chart" style="height: unset;">
        <div class="card-body p-0">
          <div class="ca-container">
            <div class="d-flex justify-content-between">
              <div>
                <label>Chiffre d'affaires </label>
              </div>
              <div>
                <img id="emoji" height="40px" src="<?php echo BASE_URL . '/asset/img/' . $emoji ?>">
              </div>

            </div>

            <h2 class="mt-2">
              <strong id="ca1"><?php echo number_format($ca, 2) ?> DH</strong>
            </h2>
          </div>
          <div class="ca-body">
            <div class="ca-filter" style="padding: 0px 20px; padding-top: 10px; ">
              <select class="form-control select-2" id="filter_ca">
                <option value="0" <?php echo (date('m') == 0) ? 'selected' : '' ?>>Tous</option>
                <option value="1" <?php echo (date('m') == 1) ? 'selected' : '' ?>>Janvier</option>
                <option value="2" <?php echo (date('m') == 2) ? 'selected' : '' ?>>Février</option>
                <option value="3" <?php echo (date('m') == 3) ? 'selected' : '' ?>>Mars</option>
                <option value="4" <?php echo (date('m') == 4) ? 'selected' : '' ?>>Avril</option>
                <option value="5" <?php echo (date('m') == 5) ? 'selected' : '' ?>>Mai</option>
                <option value="6" <?php echo (date('m') == 6) ? 'selected' : '' ?>>Juin</option>
                <option value="7" <?php echo (date('m') == 7) ? 'selected' : '' ?>>Juillet</option>
                <option value="8" <?php echo (date('m') == 8) ? 'selected' : '' ?>>Août</option>
                <option value="9" <?php echo (date('m') == 9) ? 'selected' : '' ?>>Septembre</option>
                <option value="10" <?php echo (date('m') == 10) ? 'selected' : '' ?>>Octobre</option>
                <option value="11" <?php echo (date('m') == 11) ? 'selected' : '' ?>>Novembre</option>
                <option value="12" <?php echo (date('m') == 12) ? 'selected' : '' ?>>Décembre</option>
              </select>
            </div>
            <div class="d-flex justify-content-between ca-item ca-item-1">
              <div>CA HT</div>
              <div id="ca2"><?php echo number_format($ca, 2) ?> DH</div>
            </div>
            <div class="d-flex justify-content-between ca-item ca-item-2">
              <div>Recettes TTC</div>
              <div id="recette"><?php echo number_format($recette, 2) ?> DH</div>
            </div>

            <?php
            $pvs = connexion::getConnexion()->query("SELECT * FROM client WHERE (pv_guid IS NOT NULL AND pv_guid != '')")->fetchAll(PDO::FETCH_OBJ);

            $recettes_pv = [];


            foreach ($pvs as $pv) {

              $rec = connexion::getConnexion()->query("SELECT SUM(montant) AS recette FROM cloturages WHERE pv_guid = '$pv->pv_guid' AND created_at BETWEEN '".date('Y-m')."-01' AND '".date('Y-m')."-30'")->fetchColumn();

          ?>

              <div class="d-flex justify-content-between ca-item ca-item-2">
                <div>Recette <?php echo $pv->nom ?> </div>
                <div id="recette"><?php echo $rec ?> DH</div>
              </div>


            <?php
            }

            ?>

            <div class="d-flex justify-content-between ca-item ca-item-3">
              <div>Dépenses</div>
              <div id="depense"><?php echo number_format($depense, 2) ?> DH</div>
            </div>
            <div class="d-flex justify-content-between ca-item ca-item-3">
              <div>Réglements</div>
              <div id="reg_f"><?php echo number_format($reg_f, 2) ?> DH</div>
            </div>
            <div class="d-flex justify-content-between ca-item ca-item-3 border-0">
              <div>Charges</div>
              <div id="charges"><?php echo number_format($charges, 2) ?> DH</div>
            </div>
            <div class="d-flex justify-content-between ca-item" style="background: #f7f1f3; margin: 0; padding: 20px;">
              <div>Revenu</div>
              <div id="result"><?php echo number_format($recette - $depense, 2) ?> DH</div>
            </div>
          </div>
        </div>

      </div>
    </div>
    <div class="col-md-8 mb-4">
      <div class="card ctm-statistiques">
        <div class="card-body">
          <div class="d-flex justify-content-between">
            <div class="ctm-header">
              <span>Total ventes</span>
              <h3><strong><?php echo number_format($tot_v, 2) ?> DH</strong></h3>
            </div>
            <div class="ctm-header">
              <span>Total achats</span>
              <h3><strong><?php echo number_format($tot_a, 2) ?> DH</strong></h3>
            </div>
          </div>
          <div>
            <canvas id="tventes" width="400" height="180"></canvas>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-4 col-sm-12 mb-4">
      <div class="card dashboard-filled-line-chart" style="height:100%;">
        <div class="card-body pb-0">
          <div class="">
            <div class="d-flex justify-content-between">
              <h5 class="d-inline">Insuffisants en stock</h5>
            </div>
          </div>
        </div>
        <div class="chart card-body pt-0">
          <canvas id="mynewChart" width="400" height="400"></canvas>
          <div class="donut-inner">
            <h5><?php echo $per . '%' ?></h5>
            <span><?php echo "($alertproduitc / $totalproduit)" ?></span>
          </div>
        </div>
      </div>
    </div>

    <div class="col-md-6 col-sm-12 mb-4 d-none">
      <div class="card dashboard-filled-line-chart">
        <div class="card-body">
          <div class="float-left float-none-xs">
            <div class="d-inline-block">
              <h5 class="d-inline">Nombre Des Achats</h5>

            </div>
          </div>
          <div class="btn-group float-right mt-2 float-none-xs">
            <button class="btn btn-outline-primary btn-xs dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              Cette Semaine
            </button>
            <div class="dropdown-menu">
              <a class="dropdown-item" href="#">La semaine dernière</a>
              <a class="dropdown-item" href="#">Ce Mois</a>
            </div>
          </div>
        </div>
        <div class="chart card-body pt-0">
          <canvas id="AchatChart"></canvas>
        </div>
      </div>
    </div>
  </div>
  <?php
  $chambre = new produit();
  $alertproduit = $chambre->selectAlertQte();
  if (count($alertproduit) > 0) {
    # code...
  ?>
    <div class="row mt-2">
      <div class="col-md-12 mb-4">
        <div class="card h-100">
          <div class="card-body">
            <h5 class="card-title">Liste des produits insuffisants en stock</h5>

            <div class="table-responsive">
              <table class="table  table-striped table-bordered " id="myTable">
                <thead>
                  <tr>
                    <th width="30%">D&eacute;signation</th>
                    <th width="15%"> Q_stck</th>
                    <th width="15%"> alerte Qte</th>
                  </tr>
                </thead>
                <tbody>
                  <?php

                  foreach ($alertproduit as $ligne) { ?>

                    <tr>
                      <td>
                        <a href="javascript:void(0)" class="badge badge-primary mb-1 url notlink" data-url="produit/update.php?id=<?php echo $ligne->id_produit; ?>"><?php echo $ligne->designation; ?></a>
                      </td>
                      <td style="text-align: center;"> <?php echo $ligne->qte_actuel; ?> </td>
                      <td style="text-align: right;"> <?php echo $ligne->minqte; ?> </td>
                    </tr>
                  <?php } ?>
                </tbody>
              </table>
            </div>

          </div>
        </div>
      </div>
    </div>

  <?php }  ?>
  <div class="row d-none">
    <div class="col-xl-6 col-lg-12 mb-4">
      <div class="card h-100">
        <div class="card-body">
          <h5 class="card-title">Les produits les plus vendus</h5>

          <div class="table-responsive">
            <table class="table  table-striped table-bordered">
              <thead>
                <tr>
                  <th> Designation </th>
                  <th> Poid </th>
                  <th> Qte Vendu </th>
                  <th> Catégorie </th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($chambres as $ligne) { ?>

                  <tr>
                    <td>
                      <a href="javascript:void(0)" class="badge badge-primary mb-1 url notlink" data-url="produit/update.php?id=<?php echo $ligne->id_produit; ?>"><?php echo $ligne->designation; ?></a>
                    </td>
                    <td><?php echo $ligne->poid; ?>g</td>
                    <td><?php echo $ligne->qtevendu; ?></td>
                    <td><?php echo $ligne->nom; ?></td>
                  </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>

        </div>
      </div>
    </div>
    <div class="col-xl-6 col-lg-12 mb-4">
      <div class="card h-100">
        <div class="card-body">
          <h5 class="card-title">Les clients fidèles</h5>
          <div class="table-responsive">

            <table class="table   table-striped table-bordered" id="datatables_client">
              <thead>
                <tr>
                  <th> Client</th>
                  <th> Téléphone</th>
                  <th> Nbr Pcs achete </th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($clients as $ligne) { ?>

                  <tr>
                    <td>
                      <a href="javascript:void(0)" class="badge badge-primary mb-1 url notlink" data-url="client/update.php?id=<?php echo $ligne->id_client; ?>"><?php echo $ligne->nomclient; ?></a>
                    </td>
                    <td><?php echo $ligne->telephone; ?></td>
                    <td><?php echo $ligne->nbr_pcs_vendu; ?></td>
                  </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>

</div>

<script type="text/javascript">
  $(document).ready(function() {


    $('#myTable').DataTable({
      responsive: true,
      oLanguage: {
        sSearch: "Filtrer: ",
        sLengthMenu: "Afficher _MENU_ lignes"
      }
    });

    $("#year").on("change", function(event) {
      event.preventDefault();

      var select = $(this);
      $.ajax({
        type: "GET",
        url: "<?php echo BASE_URL . 'views/chart/'; ?>controle.php?act=vente_achat&year=" + select.val(),
        dataType: 'text',
        cache: false,
        contentType: false,
        processData: false,
        success: function(data) {

          $("#spanyear").html('<i class="glyph-icon simple-icon-clock"></i> ' + select.val());


          myChart.data.datasets = JSON.parse(data);
          myChart.update();

        }
      });

    });

    Chart.defaults.global.defaultFontFamily = "'Nunito', sans-serif";
    Chart.defaults.LineWithShadow = Chart.defaults.line, Chart.controllers.LineWithShadow = Chart.controllers.line.extend({
      draw: function(e) {
        Chart.controllers.line.prototype.draw.call(this, e);
        var t = this.chart.ctx;
        t.save(), t.shadowColor = "rgba(0,0,0,0.15)", t.shadowBlur = 10, t.shadowOffsetX = 0, t.shadowOffsetY = 10, t.responsive = !0, t.stroke(), Chart.controllers.line.prototype.draw.apply(this, arguments), t.restore()
      }
    });
    var S = {
      backgroundColor: "#fff",
      titleFontColor: "#636363",
      borderColor: "#d7d7d7",
      borderWidth: .5,
      bodyFontColor: "#636363",
      bodySpacing: 10,
      xPadding: 15,
      yPadding: 15,
      cornerRadius: .15
    };



    var VeChart = document.getElementById("VenteChart").getContext("2d");
    var Achatr = document.getElementById("AchatChart").getContext("2d");

    var venteChart = new Chart(VeChart, {
      type: "bar",
      options: {
        plugins: {
          datalabels: {
            display: !1
          }
        },
        responsive: !0,
        maintainAspectRatio: !1,
        scales: {
          yAxes: [{
            gridLines: {
              display: !0,
              lineWidth: 1,
              color: "rgba(0,0,0,0.1)",
              drawBorder: !1
            },

          }],
          xAxes: [{
            gridLines: {
              display: !1
            },
            maxBarThickness: 16,
          }]
        },
        legend: {
          display: !1
        },
        tooltips: S
      },
      data: {
        labels: ["Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi", "Samedi", "Dimanche", ],
        datasets: [{
            label: "Achats",
            data: [<?php echo $data_achat ?>],
            borderColor: "#59bbf2",
            pointBackgroundColor: "#fff",
            pointBorderColor: "#008EE6",
            pointHoverBackgroundColor: "#008EE6",
            pointHoverBorderColor: "#fff",
            pointRadius: 4,
            pointBorderWidth: 2,
            pointHoverRadius: 5,
            fill: !0,
            borderWidth: 2,
            backgroundColor: "#59bbf2"

          },
          {
            label: "Ventes",
            data: [<?php echo $data_vante ?>],
            borderColor: "#1751b8",
            pointBackgroundColor: "#fff",
            pointBorderColor: "#008EE6",
            pointHoverBackgroundColor: "#008EE6",
            pointHoverBorderColor: "#fff",
            pointRadius: 4,
            pointBorderWidth: 2,
            pointHoverRadius: 5,
            fill: !0,
            borderWidth: 2,
            backgroundColor: "#1751b8"
          },

        ]
      }
    });

    var ctx_ch = document.getElementById("mynewChart");
    var myDoughnutChart = new Chart(ctx_ch, {
      type: 'doughnut',
      data: {
        labels: [
          "Insuffisants",
          "Suffisants",
        ],
        datasets: [{
          data: [<?php echo $alertproduitc ?>, <?php echo $suff ?>],
          backgroundColor: [
            "#e25682",
            "#e3e3e5"
          ],
          hoverBackgroundColor: [
            "#e25682",
            "#e3e3e5"
          ],
          color: 'red'
        }]
      },
      options: {
        plugins: {
          datalabels: {
            display: false,
          },
          doughnutlabel: {
            labels: [{
              text: '550',
              font: {
                size: 20,
                weight: 'bold'
              }
            }, {
              text: 'total'
            }]
          }
        },
        cutoutPercentage: 80,
        rotation: 1 * Math.PI,
        circumference: 1 * Math.PI,

      }
    });

    var ctx_tv = document.getElementById("tventes");
    var myDoughnutChart = new Chart(ctx_tv, {
      type: 'line',
      data: {
        labels: ["Lun", "Mar", "Mer", "Jeu", "Ven", "Sam", "Dim", ],
        datasets: [{
            label: "Ventes",
            data: [<?php echo $tventes ?>],
            borderColor: "#59bbf2",
            pointBackgroundColor: "#fff",
            pointBorderColor: "#008EE6",
            pointHoverBackgroundColor: "#008EE6",
            pointHoverBorderColor: "#fff",
            pointRadius: 4,
            pointBorderWidth: 2,
            pointHoverRadius: 5,
            fill: !0,
            backgroundColor: "rgba(0,0,0,0)",
            borderWidth: 2,
          },
          {
            label: "Achats",
            data: [<?php echo $tachats ?>],
            borderColor: "#e25682",
            pointBackgroundColor: "#fff",
            pointBorderColor: "#e25682",
            pointHoverBackgroundColor: "#e25682",
            pointHoverBorderColor: "#fff",
            pointRadius: 4,
            pointBorderWidth: 2,
            pointHoverRadius: 5,
            fill: !0,
            backgroundColor: "rgba(0,0,0,0)",
            borderWidth: 2,
          }
        ],
      },
      options: {
        legend: {
          labels: {
            fontColor: 'white'
          }
        }
      }

    });
  });

  $('#filter_ca').change(function() {
    let month = $(this).val();

    $.ajax({
      type: 'post',
      url: "<?php echo BASE_URL . '/views/Acceuil/controle.php' ?>",
      data: {
        act: 'filter_ca',
        month: month
      },
      dataType: 'json',
      success: function(data) {
        $('#ca1').text(data.ca);
        $('#ca2').text(data.ca);
        $('#recette').text(data.recette);
        $('#depense').text(data.depense);
        $('#reg_f').text(data.reg_f);
        $('#charges').text(data.charges);
        $('#result').text(data.result);
        $('#emoji').attr('src', data.emoji);
      }
    });
  });
</script>
