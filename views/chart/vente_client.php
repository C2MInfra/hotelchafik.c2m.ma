<?php
if (isset($_POST['ajax'])) {
include('../../evr.php');
}

function random_color() {
	 $rand = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'a', 'b', 'c', 'd', 'e', 'f');
    return $color = '#'.$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)].$rand[rand(0,15)];
}
$year=date('Y');
$datasource='';
$query=$result=connexion::getConnexion()->query("SELECT count(id)as nbr_user from utilisateur");
$result=$query->fetch(PDO::FETCH_OBJ); 

$query=$result=connexion::getConnexion()->query("SELECT id,login  from utilisateur");
$result_user=$query->fetchAll(PDO::FETCH_OBJ); 
foreach($result_user as $rep){
$data_vante = '';
$borderColor = random_color();
for($j=1;$j<=12;++$j){

$result=connexion::getConnexion()->query("select count(id_vente) as nbr_vente 
from vente where numbon >0 and month(date_vente)=$j and year(date_vente)=$year and id_user=".$rep->id);
$data=$result->fetch(PDO::FETCH_OBJ);
 

  $data_vante .="$data->nbr_vente," ;

 }
 $datasource.= 			"{  label: '$rep->login',
                            borderColor: '$borderColor',
                            backgroundColor: '$borderColor',
                            data: [$data_vante],
                            borderWidth: 2
                        },";
}

?>

<div class="container-fluid disable-text-selection">
    <div class="row">
        <div class="col-12">
            <div class="mb-2">
                <h1>Vente par employé</h1>
                
                
            </div>
            
            <div class="separator mb-5"></div>
        </div>
    </div>
    <div class="row">
        
        
					<div class="col-xl-12 col-lg-12 mb-4">
						<div class="card mb-4">
							<div class="card-body" id="etat">
								<h3 class="mb-4" >  Vente par employé <span id="spanyear" class="badge badge-pill badge-primary mb-1"><i class="glyph-icon simple-icon-clock"></i> <?php echo $year?></span> </h3>
								
                                    <div class="form-group row">
                                    <label class=" col-form-label col-sm-1">Year :</label>
									<select id="year" class="form-control col-sm-2">
										<?php for ($i=date('Y'); $i >=2010; $i--) {
                                            echo "<option value='$i'>$i</option>";
                                        }
                                        ?>
									</select>
                                </div>
								
								<div class="chart-container">
									<canvas id="Chart"></canvas>
								</div>
							</div>
						</div>
					</div>


            <script type="text/javascript">
            
            $(document).ready(function () {

    
     $("#year" ).on( "change", function( event ) {
             event.preventDefault();

             var select = $( this );
             $.ajax({
                type: "GET",
                url: "<?php echo BASE_URL.'views/chart/' ;?>controle.php?act=vente_client&year="+select.val(),
                dataType: 'text',  
                cache: false,
                contentType: false,
                processData: false,
                success: function (data) {
                  
                        $("#spanyear").html('<i class="glyph-icon simple-icon-clock"></i> '+select.val());
                      myChart.data.datasets=JSON.parse(data);
                      myChart.update();
                                              
                        }
                    });
                   
         });

            Chart.defaults.BarWithShadow = Chart.defaults.bar,
            Chart.controllers.BarWithShadow = Chart.controllers.bar.extend({
                draw: function(e) {
                    Chart.controllers.bar.prototype.draw.call(this, e);
                    var t = this.chart.ctx;
                    t.save(), t.shadowColor = "rgba(0,0,0,0.15)", t.shadowBlur = 12, t.shadowOffsetX = 5, t.shadowOffsetY = 10, t.responsive = !0, Chart.controllers.bar.prototype.draw.apply(this, arguments), t.restore()
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
            if (document.getElementById("Chart")) {
                var Cha = document.getElementById("Chart").getContext("2d");
  var myChart = new Chart(Cha, {
                    type: "BarWithShadow",
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
                                }
                            }]
                        },
                        legend: {
                            position: "bottom",
                            labels: {
                                padding: 30,
                                usePointStyle: !0,
                                fontSize: 12
                            }
                        },
                        tooltips: S
                    },
                    data: {
                        labels: ["janvier","février","mars","avril","mai","juin","juillet","août","septembre","octobre","novembre","décembre"],
                        datasets: [<?php echo $datasource?>]
                    }
                })
     
     }        });

            </script>