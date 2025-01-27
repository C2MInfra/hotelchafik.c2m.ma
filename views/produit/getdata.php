<?php include('../../evr.php');

if(isset($_POST['page'])){
//Include pagination class file
$produit = new produit();
$start = !empty($_POST['page']) ? $_POST['page'] : 0;
$limit = $_SESSION['LIMIT'] ;
//set conditions for search
$depot_cat_sql = '';
$whereSQL = $orderSQL = '';
$keywords = $_POST['keywords'];
$sortBy = $_POST['sortBy'];
$prix = $_POST['prix'];
$operateur = $_POST['operateur'];

$categorie = $_POST['categorie'];
$depot = $_POST['depot'];
$stock = $_POST['stock'];

if($prix != '') {
   $depot_cat_sql .= " AND p.prix_vente $operateur $prix ";
}
if($stock == '0') {
   $depot_cat_sql .= " AND p.qte_actuel < 1 ";

}
if($stock == '1') {
   $depot_cat_sql .= " AND p.qte_actuel > 0 ";
}
if($stock == 'all') {
   $depot_cat_sql .= "";
}

if($depot != '0') {
   $depot_cat_sql .= " AND p.emplacement = '$depot' ";
}
if($categorie != '0') {
   $depot_cat_sql .= " AND p.rayon = '$categorie' ";
}

if(!empty($keywords)) {
   $whereSQL = "WHERE Archive = 0 " . $depot_cat_sql . " AND (designation LIKE '%" . $keywords . "%' OR code_bar = '" . $keywords . "')";
} else {
   $whereSQL = "WHERE Archive = 0 " . $depot_cat_sql . " ";
}
if(!empty($sortBy)) {
   $orderSQL = " ORDER BY code_bar " . $sortBy;
} else {
   $orderSQL = " ORDER BY code_bar ";
}

$orderSQL = " ORDER BY id_produit DESC ";
//get number of rows
$queryNum = $produit->selectQuery("SELECT COUNT(*) as postNum FROM produit p 
	$whereSQL $orderSQL");

$resultNum = $queryNum->fetch_assoc();
$rowCount = $resultNum['postNum'];

//initialize pagination class
$pagConfig = array(
   'currentPage' => $start,
   'totalRows' => $rowCount,
   'perPage' => $limit,
   'link_func' => 'searchFilter'
);
$pagination = new Pagination($pagConfig);

//get rows
//$query = $produit->selectQuery("SELECT * FROM produit $whereSQL $orderSQL LIMIT $start,$limit");
$query = $produit->selectQuery("select p.* from produit p $whereSQL order by p.designation asc LIMIT $start,$limit");


if($query->num_rows > 0){ ?>