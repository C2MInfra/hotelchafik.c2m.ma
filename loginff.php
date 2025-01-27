<?php 



include("eve.php");

$active = "";


if(isset($_POST['login']) && isset($_POST['pwd'])) 
{

$sql = "select count(*) as nbr,utilisateur.* from utilisateur WHERE login= '" . $_POST['login'] . "' AND pwd= '" . $_POST['pwd'] . "'";

$query = $result = connexion::getConnexion()->query($sql);

$result = $query->fetch(PDO::FETCH_OBJ);

if($result->nbr == 1) {

auth::login($result);





  header("Location:index.php");

} else {

$error = "Login ou Mot de passe est incorrecte ..... ! ";

}

}

?>

<!DOCTYPE html>

<html lang="en" style="opacity: 1">

    <head>

        <meta charset="UTF-8">
        <link rel = "icon" href ="<?php echo BASE_URL . 'asset/img/icon.png' ?>"
        type = "image/x-icon">
        <title>Khadim</title>

        <?php include('includes/style.php') ;?>

        
		
        

    </head>

    <body class="background">

        <div class="fixed-background"></div>

        <main>

            <input type="hidden" name="active" value="<?php echo $active;?>">

            <div class="container">

                <div class="row h-100">

                    <div class="col-12 col-md-4 mx-auto my-auto">

                        <div class="card auth-card">

                           

                            <div class="form-side">

                                <?php if (isset($error)) { ?>

                                <div class="alert alert-danger alert-dismissible fade show rounded mb-0" role="alert">

                                    <strong>  <?php echo $error; ?></strong>

                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">

                                    <span aria-hidden="true">×</span>

                                    </button>

                                </div>

                                <br><br>

                                <?php } ?>

                                <span class="logo-single" style="margin-bottom:10px;"></span>
                                <?php
								   $soc = connexion::getConnexion()->query('select * from societe')->fetch(PDO::FETCH_OBJ);
								?>
								<h3 style="
    text-align: center;
    font-size: 13pt;
    font-weight: 800;
    color: #1f4599;
    margin-bottom:30px;
"><?php echo $soc->raisonsocial ?></h3>
                                

                                <h6 class="mb-4">Login</h6>

                                <form method="POST">

                                    

                                    <label class="form-group has-float-label mb-4">

                                        <input class="form-control" name="login">

                                        <span>User Name</span>

                                    </label>

                                    <label class="form-group has-float-label mb-4">

                                        <input class="form-control" type="password" placeholder="" name="pwd">

                                        <span>Password</span>

                                    </label>

                                    <div class="d-flex justify-content-between align-items-center">

                                        <a href="#">Forget password?</a>

                                        <button class="btn btn-primary btn-lg btn-shadow ml-2" type="submit" style="background-color:#104d9e; border-color:#104d9e;">LOGIN</button>

                                    </div>

                                </form>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </main>

        

        <?php include("includes/script.php") ;?>

      

    </body>

</html>