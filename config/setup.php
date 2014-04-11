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

<?php if (!file_exists('../config.php')) { ?>

    <?php if(empty($_GET['step'])) { ?>
        <p>Welcome to Nanodoc please click on start to begin installation process.</p>
        <a class="btn btn-primary btn-lg" href="setup.php?step=1">Start</a>
    <?php } else { ?>

    <?php 
        switch ($_GET['step']) {

            case '1': ?>
                <p>First you need to create a configuration file.<br>Please provide the informations below.</p>
                <form id="install-form" action="setup.php?step=2" method="post">
                    <table class="table">
                        <tr>
                            <th>Database Name <span>*</span></th>
                            <td><input class="form-control" type="text" name="db_name" value=""></td>
                            <td>The name of the database you want to install nanodoc.</td>
                        </tr>
                        <tr>
                            <th>User Name <span>*</span></th>
                            <td><input class="form-control" type="text" name="db_user"></td>
                            <td>Your MySQL username.</td>
                        </tr>
                        <tr>
                            <th>Password <span>*</span></th>
                            <td><input class="form-control" type="text" name="db_pass"></td>
                            <td>Your MySQL password.</td>
                        </tr>
                        <tr>
                            <th>Database Host <span>*</span></th>
                            <td><input class="form-control" type="text" name="db_host" value="localhost"></td>
                            <td>localhost is what you need. If not then get this information from your web host.</td>
                        </tr>
                    </table>
                    <span>* required fields</span>
                    <button class="btn btn-primary btn-lg" type="submit">Create Config</button>
                </form>
                <?php break; 

            case '2':
                check_setup();
                break;
        } // end switch?>

    <?php } // end if empty($_GET['step']) ?>

<?php } else { // end if file_existed ?>

            <p>Configuration file already existes. Proceed to installation</p>
            <a class="btn btn-primary btn-lg" href="install.php?step=1">Start Installation</a>
<?php } ?>
    
        </div> <!-- close welcome div -->

        <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
        <!-- Include all compiled plugins (below), or include individual files as needed -->
        <script src="../admin/js/bootstrap.min.js"></script>
    </body>
</html>
