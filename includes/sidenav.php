<?php
$page = 'Acceuil';
if (isset($_GET['p'])) {
  $page = $_GET['p'];
} ?>

<div class="sidebar">
  <div class="main-menu">
    <div class="scroll">
      <ul class="list-unstyled">
        <li class="<?= $page == 'Acceuil' ? 'active' : '' ?>">
          <a href="javascript:void(0)" data-url="Acceuil/index.php" class="url">
            <i class="glyph-icon iconsmind-Home"></i>
            Accueil
          </a>
        </li>
        <li id="client" class="<?= $page == 'client' || $page == 'utilisateur' || $page == 'fournisseur' || $page == 'reg_client'   ? 'active' : '' ?>">
          <a href="#client" class="submenu">
            <i class="glyph-icon iconsmind-Business-ManWoman"></i> Personnel
          </a>
        </li>
        <li id="vente" class="<?= $page == 'vente' || $page == 'achat' || $page == 'reg_achat' || $page == 'facture'  || $page == 'detail_achat' || $page == 'reg_vente' || $page == 'detail_vente'   || $page == 'reg_avoir' || $page == 'avoir' || $page == 'detail_avoir' || $page == 'regelement'   ? 'active' : '' ?>">
          <a href="#vente" class="submenu">
            <i class="iconsmind-Digital-Drawing"></i> Opérations
          </a>
        </li>

        <li id="Configuration" class="<?= $page == 'parametrage' || $page == 'categorie'  || $page == 'compte'  || $page == 'depot'  || $page == 'societe'  || $page == 'design_charges'  ? 'active' : '' ?>">
          <a href="#Configuration" class="submenu">
            <i class="glyph-icon iconsmind-Gear"></i> Configuration
          </a>
        </li>
        <li id="Etat" class="<?= $page == 'chart' ? 'active' : '' ?>">
          <a href="#Etat" class="submenu">
            <i class="glyph-icon iconsmind-Bar-Chart"></i> État
          </a>
        </li>

        <?php
        if ((int)auth::user()['produit'] == 1 || auth::user()['privilege'] == "Admin") {
        ?>
          <li class="<?= $page == 'produit' ? 'active' : '' ?>">
            <a href="javascript:void(0)" data-url="produit/index.php" class="url">
              <i class="glyph-icon iconsmind-Shop-4 "></i> Produit
            </a>
          </li>

        <?php
        }
        ?>
      </ul>
    </div>
  </div>

  <div class="sub-menu sub-hidden">
    <div class="scroll">


      <ul class="list-unstyled" data-link="client">

        <?php
        if ((int)auth::user()['client'] == 1 || auth::user()['privilege'] == "Admin") {
        ?>

          <li>
            <a href="javascript:void(0)" data-url="client/index.php" class="url sub">
              <i class="glyph-icon iconsmind-Business-Man"></i> Client
            </a>
          </li>

        <?php
        }
        ?>


        <?php
        if ((int)auth::user()['fournisseur'] == 1 || auth::user()['privilege'] == "Admin") {
        ?>


          <li>
            <a href="javascript:void(0)" data-url="fournisseur/index.php" class="url sub">
              <i class="glyph-icon iconsmind-Talk-Man"></i> Fournisseur
            </a>
          </li>


        <?php
        }
        ?>




        <li>
          <a href="javascript:void(0)" data-url="utilisateur/index.php" class="url sub">
            <i class="iconsmind-MaleFemale"></i> Utilisateur
          </a>
        </li>

      </ul>
      <ul class="list-unstyled" data-link="vente">
        <!-- <li>
                    <a href="javascript:void(0)" data-url="depots/index.php" class="url sub">
                        <i class="glyph-icon iconsmind-Shopping-Cart"></i> Depots
                    </a>
                </li> -->
        <!-- <li>
                    <a href="javascript:void(0)" data-url="ancien_vente/add.php" class="url sub">
                        <i class="glyph-icon iconsmind-Shopping-Cart"></i>Ancienne Vente</a>
                </li> -->
        <!-- <li>
                    <a href="javascript:void(0)" data-url="devis/index.php" class="url sub">
                        <i class="glyph-icon iconsmind-Shopping-Cart"></i> Devis</a>
                </li> -->
        <li>
          <a href="javascript:void(0)" data-url="chambre/index.php" class="url sub">
            <i class="glyph-icon iconsmind-Shopping-Cart"></i> Chambre</a>
        </li>
        <li>
          <a href="javascript:void(0)" data-url="reservation/index.php" class="url sub">
            <i class="glyph-icon iconsmind-Shopping-Cart"></i> Reservation</a>
        </li>
        <li>
          <a href="javascript:void(0)" data-url="detail_reservation/checkin.php" class="url sub">
            <i class="glyph-icon iconsmind-Shopping-Cart"></i> Checkin</a>
        </li>
        <li>
          <a href="javascript:void(0)" data-url="detail_reservation/checkout.php" class="url sub">
            <i class="glyph-icon iconsmind-Shopping-Cart"></i> Checkout</a>
        </li>
        <?php
        if ((int)auth::user()['vente'] == 1 || auth::user()['privilege'] == "Admin") {
        ?>
          <li>
            <a href="javascript:void(0)" data-url="vente/index.php" class="url sub">
              <i class="glyph-icon iconsmind-Shopping-Cart"></i> Vente</a>
          </li>
        <?php
        }
        ?>
        <!-- <?php
              if (((int)auth::user()['vente'] == 1 && (int)auth::user()['achat'] == 1) || auth::user()['privilege'] == "Admin") {
              ?>
                    <li>
                        <a href="javascript:void(0)" data-url="achat-vente/add.php" class="url sub">
                            <i class="glyph-icon iconsmind-Shopping-Cart"></i> Achat Vente</a>
                    </li>
                <?php
              }
                ?>
                <li> -->
        <!-- <a href="javascript:void(0)" data-url="bon-commande/index.php" class="url sub">
                        <i class="glyph-icon iconsmind-Shopping-Cart"></i> Bon commande</a>
                </li>
                <li>
                    <a href="javascript:void(0)" data-url="commande-vendeurs/index.php" class="url sub">
                        <i class="glyph-icon iconsmind-Shopping-Cart"></i> Commande vendeur</a>
                </li>
                <li> <a href="javascript:void(0)" data-url="vente/bon.php" class="url sub">
                        <i class="glyph-icon iconsmind-Shopping-Cart"></i>Bon de livraison </a>
                </li> -->

        <?php
        if ((int)auth::user()['achat'] == 1 || auth::user()['privilege'] == "Admin") {
        ?>
          <li><a href="javascript:void(0)" data-url="achat/index.php" class="url sub">
              <i class="glyph-icon iconsmind-Add-Bag"></i> Achat</a> </li>
        <?php
        }
        ?>

        <!-- <?php
              if ((int)auth::user()['avoir'] == 1 || auth::user()['privilege'] == "Admin") {
              ?>

                    <li><a href="javascript:void(0)" data-url="avoir/index.php" class="url sub">
                            <i class="glyph-icon iconsmind-Shopping-Cart"></i> Avoir</a> </li>

                <?php
              }
                ?>
                <li>
                    <a href="javascript:void(0)" data-url="regelement/index.php" class="url sub">
                        <i class="glyph-icon iconsmind-Money-2"></i> Règlement</a>
                </li>
                <li>
                    <a href="javascript:void(0)" data-url="facture/index.php" class="url sub">
                        <i class="glyph-icon  iconsmind-Billing"></i> Facture</a>
                </li> -->


        <?php
        if ((int)auth::user()['charge'] == 1 || auth::user()['privilege'] == "Admin") {
        ?>
          <li>
            <a href="javascript:void(0)" data-url="charge/index.php" class="url sub">
              <i class="glyph-icon  iconsmind-Billing"></i> Charges</a>
          </li>
        <?php
        }
        ?>

        <li>
          <a href="javascript:void(0)" data-url="caisse/index.php" class="url sub">
            <i class="glyph-icon  iconsmind-Billing"></i> Caisse</a>
        </li>

        <li>
          <a href="javascript:void(0)" data-url="transfert_caisse/index.php" class="url sub">
            <i class="glyph-icon  iconsmind-Billing"></i> Transfert Caisse</a>
        </li>


        <li>
          <a href="javascript:void(0)" data-url="recette/index.php" class="url sub">

            <i class="glyph-icon  iconsmind-Billing"></i> Recettes Points De Vente</a>
        </li>

        <li>
          <a href="javascript:void(0)" data-url="annulations/index.php" class="url sub">

            <i class="glyph-icon  iconsmind-Billing"></i>Annulations </a>
        </li>
      </ul>

      <ul class="list-unstyled" data-link="Configuration">

        <li><a href="javascript:void(0)" data-url="categorie/index.php" class="url sub"> <i class="glyph-icon simple-icon-settings"></i>Catégories</a>
        </li>
        <li><a href="javascript:void(0)" data-url="ingredient/index.php" class="url sub"> <i class="glyph-icon simple-icon-settings"></i>Composants</a>
        </li>
        <li>
          <a href="javascript:void(0)" data-url="societe/index.php" class="url sub"> <i class="glyph-icon simple-icon-settings"></i>Société</a>
        </li>
        <li><a href="javascript:void(0)" data-url="depot/index.php" class="url sub"> <i class="glyph-icon simple-icon-settings"></i>Dépôt</a>
        </li>
        <li><a href="javascript:void(0)" data-url="design_charges/index.php" class="url sub"> <i class="glyph-icon simple-icon-settings"></i>Paramétrage des charges</a>
        </li>
        <li>
          <a href="javascript:void(0)" data-url="parametrage/index.php" class="url sub"> <i class="glyph-icon simple-icon-settings"></i>Paramétrages</a>
        </li>
        <li><a href="javascript:void(0)" data-url="compte/index.php" class="url sub"> <i class="glyph-icon simple-icon-settings"></i>Comptes bancaires</a>
        </li>

        <li>
          <a href="javascript:void(0)" data-url="parametrage/pv.php" class="url sub">
            <i class="glyph-icon simple-icon-settings"></i>Config Point de Vente
          </a>
        </li>

      </ul>

      <ul class="list-unstyled" data-link="Etat">
        <li><a target="_blank" href="<?php echo BASE_URL . 'views/etat/moveCaisse.php' ?>"><i class="glyph-icon  iconsmind-Billing"></i> Mouvement caisse</a> </li>
        <!-- <li><a target="_blank" href="<?php echo BASE_URL . 'views/etat/etat_vente.php' ?>"> <i class="glyph-icon  iconsmind-Money-2"></i> État vente</a></li>
                <li><a target="_blank" href="<?php echo BASE_URL . 'views/etat/etat_bl.php' ?>"> <i class="glyph-icon  iconsmind-Money-2"></i> État BL</a></li>
                <li><a target="_blank" href="<?php echo BASE_URL . 'views/etat/etat_cmd_vendeurs.php' ?>"> <i class="glyph-icon  iconsmind-Money-2"></i> État commande vendeurs</a></li>
                <li><a target="_blank" href="<?php echo BASE_URL . 'views/etat/etat_vente_impayes.php' ?>"> <i class="glyph-icon  iconsmind-Money-2"></i> État ventes Impayées</a></li>
                <li><a target="_blank" href="<?php echo BASE_URL . 'views/etat/etat_achat.php' ?>"> <i class="glyph-icon  iconsmind-Billing"></i> État achat </a></li> -->
        <!-- <li><a target="_blank" href="<?php echo BASE_URL . 'views/etat/etat_vent_achat.php' ?>"> <i class="glyph-icon  iconsmind-Billing"></i> État vente-achat </a></li>
                <li><a target="_blank" href="<?php echo BASE_URL . 'views/etat/magasinier.php' ?>"> <i class="glyph-icon  iconsmind-Billing"></i> Magasinier </a></li> -->

        <!-- <li><a target="_blank" href="<?php echo BASE_URL . 'views/etat/cheque_vente.php' ?>"><i class="glyph-icon  iconsmind-Billing"></i> Chèque V</a> </li>
                <li><a target="_blank" href="<?php echo BASE_URL . 'views/etat/cheque_achat.php' ?>"><i class="glyph-icon  iconsmind-Billing"></i> Chèque A</a> </li> -->

        <!-- <li><a href="javascript:void(0)" data-url="chart/vente_client.php" class="url sub"> <i class="glyph-icon simple-icon-chart"></i> Vente par employé</a>
                </li> -->
        <!--         <li><a href="javascript:void(0)" data-url="chart/vente_categorie.php" class="url sub" > <i class="glyph-icon iconsmind-Bar-Chart3"></i> Vente produit par Categorie</a>
                   </li>
 -->
        <!-- <li><a href="javascript:void(0)" data-url="chart/vente_achat.php" class="url sub"> <i class="glyph-icon iconsmind-Line-Chart4"></i> Vente / Achat</a></li>

                <li><a a target="_blank" href="<?php echo BASE_URL . 'views/etat/etat_vente_categorie.php' ?>"> <i class="glyph-icon iconsmind-Pie-Chart2"></i>Vente par catégorie</a>
 -->
        <!-- 
                </li> -->

        <li><a target="_blank" href="<?php echo BASE_URL . 'views/etat/inventaire_stock_par_category.php' ?>"><i class="glyph-icon  iconsmind-Billing"></i> Inventaire Stock </a></li>
        <li><a target="_blank" href="<?php echo BASE_URL . 'views/etat/etat_reservation_par_pays.php' ?>"><i class="glyph-icon  iconsmind-Billing"></i> Total nuits par pays </a></li>
        <li><a target="_blank" href="<?php echo BASE_URL . 'views/etat/form_date_reservation.php' ?>"><i class="glyph-icon  iconsmind-Billing"></i> Reservation </a></li>

        <li><a target="_blank" href="<?php echo BASE_URL . 'views/etat/stock_par_category.php' ?>"><i class="glyph-icon  iconsmind-Billing"></i>Stock par catégorie</a></li>


      </ul>
    </div>
  </div>
</div>