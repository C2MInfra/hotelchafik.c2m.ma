<?php
include("../../evr.php");
function dateFormat($dat)
{
  $date = new DateTime($dat);
  return $date->format('d-m-Y');
}
function design_ar($design, $design_ar)
{
  if ($design == '' && $design_ar != '') {
    echo $design_ar;
  }
  if ($design != '' && $design_ar != '') {
    echo "/ " . $design_ar;
  }
}

?>
<!DOCTYPE html
  PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <link rel="icon" href="<?php echo BASE_URL . 'asset/img/icon.png' ?>" type="image/x-icon">
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title></title>
  <style type="text/css">
    .tableform {
      background-color: #999999;
      width: 400px;
      margin: 150px auto;
    }

    .inputText {
      height: 22px;
      width: 80%;
      border-radius: 3px;
      margin-top: 10px;
    }

    .button {
      height: 25px;
      width: 30%;
      border-radius: 3px;
      margin-top: 10px;
      font-weight: bold;
    }

    .button:hover {
      color: #666666;
      cursor: pointer;
    }

    h3 {
      text-decoration: underline;
      text-transform: uppercase;
    }

    .datatables {
      border-collapse: collapse;
      width: 100%;
      font-size: 20px;
    }

    .row {
      background-color: #CCCCCC;
    }

    .montant {
      text-align: right;
    }

    .center {
      text-align: center;
    }
  </style>
</head>

<body style="width:1500px;margin:auto;">
  <?php
  $all_countries = connexion::getConnexion()->query("select * from pays")->fetchAll(PDO::FETCH_ASSOC);

  if (isset($_POST['dd'])) {
    $pays = implode(',', $_POST["id_pays"]);
    if ($pays) {
      $countries = connexion::getConnexion()->query("select * from pays where FIND_IN_SET(id_pays,'" . $pays . "')>0")->fetchAll(PDO::FETCH_ASSOC);

    } else {
      $countries = $all_countries;
    }

    $reservation = new reservation();
    $data = $reservation->get_nombre_nuits_par_pays($_POST['dd'], $_POST['df'], $pays, $_POST['nombre_nuits']);
    ?>
    <h3 align="center"> Etat des reservations
      <?php echo dateFormat($_POST['dd']); ?> a
      <?php echo dateFormat($_POST['df']); ?> .
    </h3>
    <table class="datatables" border=1>
      <tr>
        <td></td>
        <?php
        $total_nombre_nuits = [];
        $total_des_totals = 0;
        for ($i = 1; $i < 32; $i++) {
          $total_nombre_nuits[$i] = 0;
        }
        for ($i = 1; $i < 32; $i++) {
          echo "<td class=\"center\" width=\"35px\">$i</td>";
        }
        foreach ($countries as $key => $value) {
          $total_par_pays = 0;
          echo "<tr>
            <td>$value[nom]</td>";
          for ($i = 1; $i < 32; $i++) {
            $td = "<td></td>";
            foreach ($data as $a) {
              if ($a['pays'] == $value['nom'] && $a['nombre_nuits'] == $i) {
                $td = "<td class=\"center\">" . $a['total'] . "</td>";
                $total_nombre_nuits[$i] += $a['total'];
                $total_des_totals += $a['total'];
                $total_par_pays += $a['total'];
                break;
              }
            }
            echo $td;
          }
          if ($total_par_pays != 0)
            echo "<td class=\"center\">$total_par_pays</td>";
          else
            echo "<td class=\"center\">-</td>";
          echo "</tr>";
        }
        echo "<tr>
          <td>Total</td>";
        foreach ($total_nombre_nuits as $t) {
          if ($t != 0)
            echo "<td class=\"center\">$t</td>";
          else
            echo "<td class=\"center\">-</td>";
        }
        echo "<td class=\"center\">$total_des_totals</td></tr>";
        ?>
    </table>
    <?php
  } else {
    include("form_date.php");
  } ?>
</body>

</html>