<?php
require_once 'nd_functions.php';

/*vot*/ $error = '';
/*vot*/ if ($_SERVER['REQUEST_METHOD'] == 'POST') {
/*vot*/     $error = $nd_sqlite->checkLogin($_POST['nd_user'], $_POST['nd_pass']);
/*vot*/     if(!$error) {
/*vot*/        header("Location: admin/");
/*vot*/	    }
/*vot*/ }
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?php echo $nd_sqlite->getOption('nd_title') . ' &rsaquo; login'; ?></title>

        <!-- Bootstrap -->
<!--vot--><link href="<?php echo ROOT_URL . "/admin/css/bootstrap.min.css"; ?>" rel="stylesheet">
        <!-- NannoDoc custom css -->
<!--vot--><link rel="stylesheet" href="<?php echo ROOT_URL . "/admin/css/custom-style.css"; ?>">
        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src="libs/html5shiv/3.7.0/html5shiv.js"></script>
          <script src="libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>
    <body>
        <div class="login panel panel-primary">
            <div class="panel-heading">Login form</div>
            <div class="panel-body">
            <form action="login.php" id="login-form" method="post">
                <p>Username<input type="text" class="form-control" name="nd_user" placeholder="Username" value="<?php echo isset($_POST['nd_user']) ? $_POST['nd_user'] : '' ; ?>" required autofocus></p>
                <p>Password<input type="password" class="form-control" name="nd_pass" placeholder="Password" required></p>

<?/*vot*/	if($error) {
                        echo "<span class='help-block'>$error</span>";
/*vot*/		} ?>

                <button type="submit" class="btn btn-primary btn-lg">Login</button>
            </form>
            </div>
            </div>
        </div>
        <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
        <script src="libs/jquery/1.11.0/jquery.min.js"></script>
        <!-- Include all compiled plugins (below), or include individual files as needed -->
<!--vot--><script src="<?php echo ROOT_URL . "/libs/bootstrap/3.1.1/bootstrap.min.js"; ?>"></script>
    </body>
</html>