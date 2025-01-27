<?php
if (isset($_POST['ajax'])) {
include('../../evr.php');
}


$year=date('Y');
$datasource='';
$data_vante ="" ;
$data_achat ="" ;
for($j=1;$j<=12;++$j){
 $result=connexion::getConnexion()->query("select 
ifnull((select sum(dv.qte_vendu)  from vente v inner join detail_vente dv on dv.id_vente=v.id_vente where  v.numbon>0 and month(v.date_vente)=$j and year(v.date_vente)=$year),0)  as vente,
ifnull((select sum(da.id_achat) from achat a inner join detail_achat da on da.id_achat=a.id_achat where month(a.date_achat)=$j and year(a.date_achat)=$year),0)  as achat"); 
 $data=$result->fetch(PDO::FETCH_OBJ);

 $data_vante .="$data->vente," ;
 $data_achat .="$data->achat," ;

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
								<h3 class="mb-4" >  Developpement d'achat et Vente pour l'annee  <span id="spanyear" class="badge badge-pill badge-primary mb-1"><i class="glyph-icon simple-icon-clock"></i> <?php echo $year?></span> </h3>
								
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
                url: "<?php echo BASE_URL.'views/chart/' ;?>controle.php?act=vente_achat&year="+select.val(),
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


            if (document.getElementById("Chart")) {
                var T = document.getElementById("Chart").getContext("2d");
               var myChart = new Chart(T, {
                    type: "LineWithShadow",
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
                        datasets: [{
                            label: "Vente",
                            data: [<?php echo $data_vante ?>],
                            borderColor: "#008EE6",
                            pointBackgroundColor: "#fff",
                            pointBorderColor: "#008EE6",
                            pointHoverBackgroundColor: "#008EE6",
                            pointHoverBorderColor: "#fff",
                            pointRadius: 4,
                            pointBorderWidth: 2,
                            pointHoverRadius: 5,
                            fill: !0,
                            borderWidth: 2,
                            backgroundColor: "rgba(0, 142, 230,0.1)"
                        },{
                            label: "Achat",
                            data: [<?php echo $data_achat ?>],
                            borderColor: "#004D8C",
                            pointBackgroundColor: "#fff",
                            pointBorderColor: "#004D8C",
                            pointHoverBackgroundColor: "#004D8C",
                            pointHoverBorderColor: "#fff",
                            pointRadius: 4,
                            pointBorderWidth: 2,
                            pointHoverRadius: 5,
                            fill: !0,
                            borderWidth: 2,
                            backgroundColor: "rgba(0, 77, 140,0.1)"
                        }]
                    }
                })
            }

            });

            </script>