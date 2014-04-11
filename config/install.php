<?php require_once 'setup_functions.php'; ?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?php echo 'NanoDoc Installation'; ?></title>

        <!-- Bootstrap -->
        <link href="../admin/css/bootstrap.min.css" rel="stylesheet">
        <!-- NannoDoc custom css -->
        <link rel="stylesheet" href="../admin/css/custom-style.css">
        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
          <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>
    <body>
    <div class="jumbotron config">
        <h1>Welcome to NanoDoc</h1>

<?php if (file_exists('../config.php')) { ?>
    
    <?php if(empty($_GET['step'])) { ?>
        <p>Configuration file already existes. Proceed to installation</p>
        <a class="btn btn-primary btn-lg" href="install.php?step=1">Installation</a>
    <?php } else { ?>

    <?php 
        switch ($_GET['step']) {

            case '1': ?>
                <form id="install-form" action="install.php?step=2" method="post">
                    <table class="table">
                        <tr>
                            <th>Site Title <span>*</span></th>
                            <td><input type="text" class="form-control" name="nd_title"></td>
                            <td>Choose a Title for your Nanodoc.</td>
                        </tr>
                        <tr>
                            <th>Username <span>*</span></th>
                            <td><input type="text" class="form-control" name="nd_user"></td>
                            <td>Choose a username for your Nanodoc.</td>
                        </tr>
                        <tr>
                            <th>Password <span>*</span></th>
                            <td><input type="text" class="form-control" name="nd_pass"></td>
                            <td>Choose a password for your Nanodoc.</td>
                        </tr>
                        <tr>
                            <th>Email <span>*</span></th>
                            <td><input type="text" class="form-control" name="nd_user_email"></td>
                            <td>Your email address.</td>
                        </tr>
                    </table>
                    <span>* required fields</span>
                    <button class="btn btn-primary btn-lg" type="submit">Install Nanodoc</button>
                </form>
                <?php break; 

            case '2':
                install_nanodoc();
                break;
        } // end switch?>

    <?php } // end if empty($_GET['step']) ?>

<?php } else { // end if file_existed ?>

            <p>There is not a configuration file for your NanoDoc. You must create one. Please click on Create Config</p>
            <a class="btn btn-primary btn-lg" href="setup.php?step=1">Create Config</a>
<?php } ?>
    
        </div> <!-- close welcome div -->

    </div>

        <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
        <!-- Include all compiled plugins (below), or include individual files as needed -->
        <script src="../admin/js/bootstrap.min.js"></script>
    </body>
</html>
