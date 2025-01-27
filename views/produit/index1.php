<?php include('../../evr.php');

$societe = new societe();
$info = $societe->selectById(1);
if($_SESSION['gs_produit'] != 1) {
echo('<script> alert("Vous n\'avez pas le  droit    pour acceder a cette page");window.history.back(); </script>');
}



$_SESSION['LIMIT'] = 10 ;
 $limit = $_SESSION['LIMIT'];
if($_GET['datatables_length']!='')
{
  $_SESSION['LIMIT'] = $_GET['datatables_length'];
}
$page = 'produit';
$produit = new produit();
if(isset($_GET["id_categorie"])) {
$data = $produit->selectAllByCat($_GET["id_categorie"]);
} else if(isset($_POST["emplacement"])) {
$data = $produit->selectAllByEmp($_POST["emplacement"]);
} else {
$data = $produit->selectAllNonArchive();
}



                          $pagConfig = array(
                             'totalRows' =>count($data),
                             'perPage' => $limit,
                             'link_func' => 'searchFilter'
                          );
                          
                          $pagination = new Pagination($pagConfig);
                           $pagination->createLinks();

?>
<div class="container-fluid disable-text-selection">
            <div class="row">
                <div class="col-12">
                    <div class="mb-2">
                        <h1>Layout List</h1>
                        <div class="float-sm-right text-zero">
                            <button type="button" class="btn btn-primary btn-lg top-right-button mr-1">ADD NEW</button>

                            <div class="btn-group">
                                <div class="btn btn-primary btn-lg pl-4 pr-0 check-button">
                                    <label class="custom-control custom-checkbox mb-0 d-inline-block">
                                        <input type="checkbox" class="custom-control-input" id="checkAll">
                                        <span class="custom-control-label"></span>
                                    </label>
                                </div>
                                <button type="button" class="btn btn-lg btn-primary dropdown-toggle dropdown-toggle-split pl-2 pr-2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <span class="sr-only">Toggle Dropdown</span>
                                </button>
                                <div class="dropdown-menu dropdown-menu-right" x-placement="bottom-end" style="position: absolute; transform: translate3d(74px, 42px, 0px); top: 0px; left: 0px; will-change: transform;">
                                    <a class="dropdown-item" href="#">Action</a>
                                    <a class="dropdown-item" href="#">Another action</a>
                                </div>
                            </div>
                        </div>
                        <nav class="breadcrumb-container d-none d-sm-block d-lg-inline-block" aria-label="breadcrumb">
                            <ol class="breadcrumb pt-0">
                                <li class="breadcrumb-item">
                                    <a href="#">Home</a>
                                </li>
                                <li class="breadcrumb-item">
                                    <a href="#">Library</a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">Data</li>
                            </ol>
                        </nav>
                    </div>

                    <div class="mb-2">
                        <a class="btn pt-0 pl-0 d-inline-block d-md-none" data-toggle="collapse" href="#displayOptions" role="button" aria-expanded="true" aria-controls="displayOptions">
                            Display Options
                            <i class="simple-icon-arrow-down align-middle"></i>
                        </a>
                        <div class="collapse d-md-block" id="displayOptions">
                            <span class="mr-3 mb-2 d-inline-block float-md-left">
                                <a href="#" class="mr-2 active">
                                    <i class="simple-icon-menu view-icon s"></i>
                                </a>
                                <a href="#" class="mr-2">
                                    <i class="simple-icon-list view-icon"></i>
                                </a>
                                <a href="#" class="mr-2">
                                    <i class="simple-icon-grid view-icon s"></i>
                                </a>
                            </span>
                            <div class="d-block d-md-inline-block">
                                <div class="btn-group float-md-left mr-1 mb-1">
                                    <button class="btn btn-outline-dark btn-xs dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Order By
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="#">Action</a>
                                        <a class="dropdown-item" href="#">Another action</a>
                                    </div>
                                </div>
                                <div class="search-sm d-inline-block float-md-left mr-1 mb-1 align-top">
                                    <input placeholder="Search...">
                                </div>
                            </div>
                            <div class="float-md-right">
                                <span class="text-muted text-small">Displaying 1-10 of 210 items </span>
                                <button class="btn btn-outline-dark btn-xs dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    20
                                </button>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <a class="dropdown-item" href="#">10</a>
                                    <a class="dropdown-item active" href="#">20</a>
                                    <a class="dropdown-item" href="#">30</a>
                                    <a class="dropdown-item" href="#">50</a>
                                    <a class="dropdown-item" href="#">100</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="separator mb-5"></div>
                </div>
            </div>

            <div class="row">

                <div class="col-12 list" data-check-all="checkAll">

<div class="card d-flex flex-row mb-3">
                        <div class="d-flex flex-grow-1 min-width-zero">
                            <div class="card-body align-self-center d-flex flex-column flex-md-row justify-content-between min-width-zero align-items-md-center">

<a class="list-item-heading mb-1 truncate w-40 w-xs-100" href="#">
                                R&eacute;f
                                </a>
                                <a class="list-item-heading mb-1 truncate w-15 w-xs-100" href="#">
                                D&eacute;signation
                                </a>
                                <a class="list-item-heading mb-1 truncate w-40 w-xs-100" href="#">
                                /<?php if($info['etat_unite'] == 1) {echo $info['unite_util']; } ?>
                                </a>
                                <a class="list-item-heading mb-1 truncate w-15 w-xs-100" href="#">
                                Q_stck
                                </a>
                                <a class="list-item-heading mb-1 truncate w-15 w-xs-100" href="#">
                                P.achat
                                </a>
                                <a class="list-item-heading mb-1 truncate w-15 w-xs-100" href="#">
                                P.vente
                                </a>
                                <a class="list-item-heading mb-1 truncate w-15 w-xs-100" href="#">
                                Emplacemt
                                </a>
                                <a class="list-item-heading mb-1 truncate w-15 w-xs-100" href="#">
                               Cat&egrave;gorie
                                </a>
                                <a class="list-item-heading mb-1 truncate w-15 w-xs-100" href="#">
                               Actions
                                </a>
                            </div>
                         </div>
                        </div>
                    <?php if(count($data) > 0) {

                     foreach($data as $ligne) { ?>
                    <div class="card d-flex flex-row mb-3">
                        <div class="d-flex flex-grow-1 min-width-zero">
                            <div class="card-body align-self-center d-flex flex-column flex-md-row justify-content-between min-width-zero align-items-md-center">

                                <a class="list-item-heading mb-1 truncate w-15 w-xs-100" href="#">
                                  <?php echo $ligne->code_bar; ?>
                                </a>
                                <a class="list-item-heading mb-1 truncate w-40 w-xs-100" href="#">
                                  <?php echo $ligne->designation;
                                           if($ligne->designation_ar != "") {
                                            echo "/" . $ligne->designation_ar;
                                                 } ?>
                                </a>
                               <p class="mb-1 text-muted text-small w-15 w-xs-100"> <?php echo $ligne->poid; ?> </p>
            <p class="mb-1 text-muted text-small w-15 w-xs-100"> <?php echo $ligne->qte_actuel; ?> </p>
            <p class="mb-1 text-muted text-small w-15 w-xs-100"> <?php echo $ligne->prix_achat; ?> </p>
            <p class="mb-1 text-muted text-small w-15 w-xs-100"> <?php echo $ligne->prix_vente; ?> </p>
            <p class="mb-1 text-muted text-small w-15 w-xs-100"> <?php echo $ligne->emplacement; ?> </p>
            <p class="mb-1 text-muted text-small w-15 w-xs-100"> <?php echo $ligne->categorie; ?> </p>
                                <div class="w-15 w-xs-100">
                                    <span class="badge badge-pill badge-secondary">ON HOLD</span>
                                </div>
                            </div>
                        </div>
                    </div>


<?php } echo $pagination->createLinks(); ?>
<!-- 
                    <nav class="mt-4 mb-3">
                        <ul class="pagination justify-content-center mb-0">
                            <li class="page-item">
                                <a class="page-link first" href="#">
                                    <i class="simple-icon-control-start"></i>
                                </a>
                            </li>
                            <li class="page-item">
                                <a class="page-link prev" href="#">
                                    <i class="simple-icon-arrow-left"></i>
                                </a>
                            </li>
                            <li class="page-item active">
                                <a class="page-link" href="#">1</a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="#">2</a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="#">3</a>
                            </li>
                            <li class="page-item">
                                <a class="page-link next" href="#" aria-label="Next">
                                    <i class="simple-icon-arrow-right"></i>
                                </a>
                            </li>
                            <li class="page-item">
                                <a class="page-link last" href="#">
                                    <i class="simple-icon-control-end"></i>
                                </a>
                            </li>
                        </ul>
                    </nav> -->

                    <?php } ?>
                </div>
            </div>
        </div>
  <div class="col-xl-6 col-lg-12 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title">Best Sellers</h5>
                            <table class="data-table responsive nowrap" id="datatables" >
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Sales</th>
                                        <th>Stock</th>
                                        <th>Category</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <p class="list-item-heading">Marble Cake</p>
                                        </td>
                                        <td>
                                            <p class="text-muted">1452</p>
                                        </td>
                                        <td>
                                            <p class="text-muted">62</p>
                                        </td>
                                        <td>
                                            <p class="text-muted">Cupcakes</p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <p class="list-item-heading">Fruitcake</p>
                                        </td>
                                        <td>
                                            <p class="text-muted">1245</p>
                                        </td>
                                        <td>
                                            <p class="text-muted">65</p>
                                        </td>
                                        <td>
                                            <p class="text-muted">Desserts</p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <p class="list-item-heading">Chocolate Cake</p>
                                        </td>
                                        <td>
                                            <p class="text-muted">1200</p>
                                        </td>
                                        <td>
                                            <p class="text-muted">45</p>
                                        </td>
                                        <td>
                                            <p class="text-muted">Desserts</p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <p class="list-item-heading">Bebinca</p>
                                        </td>
                                        <td>
                                            <p class="text-muted">1150</p>
                                        </td>
                                        <td>
                                            <p class="text-muted">4</p>
                                        </td>
                                        <td>
                                            <p class="text-muted">Cupcakes</p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <p class="list-item-heading">Napoleonshat</p>
                                        </td>
                                        <td>
                                            <p class="text-muted">1050</p>
                                        </td>
                                        <td>
                                            <p class="text-muted">41</p>
                                        </td>
                                        <td>
                                            <p class="text-muted">Cakes</p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <p class="list-item-heading">Magdalena</p>
                                        </td>
                                        <td>
                                            <p class="text-muted">998</p>
                                        </td>
                                        <td>
                                            <p class="text-muted">24</p>
                                        </td>
                                        <td>
                                            <p class="text-muted">Cakes</p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <p class="list-item-heading">Salzburger Nockerl</p>
                                        </td>
                                        <td>
                                            <p class="text-muted">924</p>
                                        </td>
                                        <td>
                                            <p class="text-muted">20</p>
                                        </td>
                                        <td>
                                            <p class="text-muted">Desserts</p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <p class="list-item-heading">Soufflé</p>
                                        </td>
                                        <td>
                                            <p class="text-muted">905</p>
                                        </td>
                                        <td>
                                            <p class="text-muted">64</p>
                                        </td>
                                        <td>
                                            <p class="text-muted">Cupcakes</p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <p class="list-item-heading">Cremeschnitte</p>
                                        </td>
                                        <td>
                                            <p class="text-muted">845</p>
                                        </td>
                                        <td>
                                            <p class="text-muted">12</p>
                                        </td>
                                        <td>
                                            <p class="text-muted">Desserts</p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <p class="list-item-heading">Cheesecake</p>
                                        </td>
                                        <td>
                                            <p class="text-muted">830</p>
                                        </td>
                                        <td>
                                            <p class="text-muted">36</p>
                                        </td>
                                        <td>
                                            <p class="text-muted">Desserts</p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <p class="list-item-heading">Gingerbread</p>
                                        </td>
                                        <td>
                                            <p class="text-muted">807</p>
                                        </td>
                                        <td>
                                            <p class="text-muted">21</p>
                                        </td>
                                        <td>
                                            <p class="text-muted">Cupcakes</p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <p class="list-item-heading">Goose Breast</p>
                                        </td>
                                        <td>
                                            <p class="text-muted">795</p>
                                        </td>
                                        <td>
                                            <p class="text-muted">9</p>
                                        </td>
                                        <td>
                                            <p class="text-muted">Cupcakes</p>
                                        </td>
                                    </tr>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>


          <script type="text/javascript">


        

        $(document).ready(function () {

            function searchFilter(page_num) {
            page_num = page_num ? page_num : 0;
            var keywords = $('#keywords').val();
            var depot = $('#depot').val();
            var categorie = $('#categorie').val();
            var stock = $('#stock').val();
            var prix = $('#prix').val();
            var operateur = $('#operateur').val();


            var sortBy = $('#sortBy').val();
            $.ajax({
                type: 'POST',
                url: 'getdata.php',
                data: 'page=' + page_num + '&keywords=' + keywords + '&depot=' + depot + '&categorie=' + categorie + '&stock=' + stock + '&sortBy=' + sortBy +'&prix='+prix+'&operateur='+ operateur,
                beforeSend: function () {
                    $('.loading-overlay').show();
                },
                success: function (html) {
                    $('#posts_content').html(html);
                    $('.loading-overlay').fadeOut("slow");
                }
            });
        }

            $('#datatables').dataTable({});

            $('#datatables_length').change(function() {
         document.forms['limit'].submit()
    });
        })

        function confirmation(id) {
            if (confirm("Voulez vous vraiment Supprimer ce produit !") == true) {
                window.location.href = "delete.php?id=" + id;
            }
        }

        function confirmation_archive(id) {

            if (confirm("Voulez vous vraiment Archiver ce produit !") == true) {
                window.location.href = "Archiver.php?id=" + id + "&val=1";
            }
        }


    </script>