<?php 

include("eve.php");

$active = "";

if(isset($_GET['login']) && isset($_GET['pwd']))
{
    $_POST['login'] = $_GET['login'];
    $_POST['pwd'] = $_GET['pwd'];
}

if(isset($_POST['login']) && isset($_POST['pwd'])) 
{

$sql = "select count(*) as nbr,utilisateur.* from utilisateur WHERE login= '" . $_POST['login'] . "' AND pwd= '" . $_POST['pwd'] . "'";

$query = $result = connexion::getConnexion()->query($sql);

$result = $query->fetch(PDO::FETCH_OBJ);

if($result->nbr == 1) 
{
  auth::login($result);

  header("Location:index.php");

} 
else
{
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
		<style>
            .login-btn
            {
                border: 1px solid transparent;
                color: rgb(255, 255, 255);
                background-color: rgb(65, 131, 215);
                width: 100%;
                margin-top: 20px;
                border-radius: 8px;
                font-size: 11pt;
            }
            label
            {
                font-size: 1.1rem;
                font-weight: 600;
                display: flex;
                flex-direction: column;
                flex: 1 1 0%;
                position: relative;
            }
            .form-control
            {
                padding:12px;
                font-size: 11pt;
            }
            .forget
            {
                color: rgb(65, 131, 215);
                transition: all 0s ease 0s, all 0.3s ease 0s;
                padding: 0px;
                cursor: pointer;
                border: none;
                background: transparent;
                font-size: 12pt;
            }
            body.background main .container 
            {
                height: unset;
            }
            .card
            {
                box-shadow: none;
            }
        </style>
    </head>
    <body class="background">
        <main>
            <div>
                 <span class="logo-single" style="margin-bottom:10px; height:40px;"></span>
            </div>
            <div class="container" style="padding-top:90px;">
                <div class="row">
                    <div class="col-md-7">
                        <img style="margin-top: 20px;height:420px; width:100%;" src="<?php echo BASE_URL . '/asset/img/office.jpg' ?>">
                    </div>
                    <div class="col-12 col-md-5 ">
                            <div class="card">
                                <div class="card-body">
                                    <h3 style="margin-bottom:30px;font-weight: 900; color: #1b304a; font-size: 16pt;
">Authentification</h3>
                                    <h5 style="color:#a9afb7; margin-bottom:40px;">Connectez-vous pour découvrir toutes nos fonctionnalités.</h5>
                                    <?php if (isset($error)) { ?>

                                        <div class="alert alert-danger alert-dismissible fade show rounded mb-0" role="alert">
                                            <strong>  <?php echo $error; ?></strong>
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">×</span>
                                            </button>
                                        </div>
                                        <br><br>
                                        <?php } ?>
                                    <form method="POST">
                                    <div class="form-group">
                                        <label>Identifiant</label>
                                        <input class="form-control" name="login" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Mot de passe</label>
                                        <input type="password" class="form-control" name="pwd" required>
                                    </div>
                                    <div>
                                        <a class="forget" href="#">Mot de passe oublié?</a>
                                    </div>
                                    <div class="mt-2">
                                        <button class="btn login-btn" type="submit">Se connecter</button>
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