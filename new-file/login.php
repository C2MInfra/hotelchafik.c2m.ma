<?php 
session_start ();
include("eve.php");
$active = "";
if(isset($_POST['active']) && !empty($_POST['active'])) {
$sql = "update serial_code set code='" . $_POST['active'] . "' where id=0";
$result = connexion::getConnexion()->query($sql);
}
if(serial_code::validateDate(serial_code::decrypter(serial_code::selectCode()->valeur_key, serial_code::selectMaxSerial()->code)) == 1 and
(date("y-m-d", strtotime(serial_code::decrypter(serial_code::selectCode()->valeur_key, serial_code::selectMaxSerial()->code)))) >= date("y-m-d")) {
if(isset($_POST['login']) && isset($_POST['pwd'])) {
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
} else {
$active = "Erreur d'activation de logiciel, il faut appeler <br> C2M System Obligatoirement ....<br> Tel : 05 22 01 07 08 <br> E-mail : Contact@c2msystem.com ";
}
?>
<!DOCTYPE html>
<html lang="en" style="opacity: 1">
    <head>
        <meta charset="UTF-8">
        <title>Arborescence </title>
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
                                <?php echo ' <a href="./logout.php">Déconnection</a>'; ?>
                                <span class="logo-single"></span>
                                
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
                                        <button class="btn btn-primary btn-lg btn-shadow" type="submit">LOGIN</button>
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