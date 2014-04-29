<?php
require_once 'nd_functions.php';
check_login_session();

require_once 'nd_class_db.php';

if (file_exists(ABSPATH . '/nanodoc.sq3')) {
    $nd_mysql = new nd_db;
    $nd_mysql->checkDatabase();
} else {
    header('Location: ../config/install.php');
} ?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?php echo $nd_mysql->getOption('nd_title') . ' &rsaquo; Administration'; ?></title>

        <!-- Bootstrap -->
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <!-- NannoDoc custom css -->
        <link rel="stylesheet" href="css/custom-style.css">
        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
          <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>
        <header id="admin-header" class="navbar navbar-default navbar-static-top">
            <div class="container"> 
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#top-collapse">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                </div>
                <div id="top-collapse" class="navbar-collapse collapse">
                    <ul class="nav">
                        <li class="admin-title navbar-left">
                            <span class="glyphicon glyphicon-home"></span>
                            <p><a href="<?php echo $nd_mysql->getOption('nd_url'); ?>"><?php echo $nd_mysql->getOption('nd_title'); ?></a></p>
                        </li>
                        <li class="admin-page-name">
                            <span class="glyphicon glyphicon-cog"></span>
                            <p><a href="index.php">Nanodoc Dashboard</a></p>
                        </li>
                        <ul class="admin-welcome nav navbar-nav navbar-right">
                            <li class="dropdown">
                                <span class="glyphicon glyphicon-user"></span><br>
                                Welcome, 
                                <a href="#" data-toggle="dropdown" class="dropdown-toggle">                     
                                    <?php echo $_SESSION['login']; ?>
                                    <span class="caret"></span>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-left">
                                    <li><a href="user.php">Account</a></li>
                                    <li><a href="settings.php">Settings</a></li>
                                    <li><a href="index.php?action=logout">Logout</a></li>
                                </ul>
                            </li>
                        </ul>   
                    </ul>       
                </div>
            </div>
        </header>


        <section id="user-account">
            <div id="pages" class="panel panel-primary">
                <div class="panel-heading">Your Settings</div>
                <div class="panel-body">

                <?php if (isset($_GET['action']) && isset($_POST['nd_title']) && isset($_POST['nd_description']) && $_GET['action']=='settings-update') {
                    $updated = $nd_mysql->updateOptions($_POST['nd_title'], $_POST['nd_description']); ?>

                    <?php if ($updated) { ?>

                        <div class="alert alert-success alert-dismissable">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            <p>Settings updated</p>
                        </div>

                    <?php } else { ?>

                        <div class="alert alert-success alert-danger">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            <p>Error: Please Enter A Title</p>
                        </div>

                    <?php } ?>

                <?php } ?> 

                    <form id="settings-update-form" action="settings.php?action=settings-update" method="post">
                        <div class="table-responsive">

                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Nanodoc URL</th>
                                        <th>Nanodoc Title</th>
                                        <th>Nanodoc Description</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php 
                                $settings = $nd_mysql->getOptions();

                                if($settings) { ?>
                                    <tr>
                                        <td><?php echo $settings['nd_url']; ?></td>
                                        <td><input type="text" class="form-control" name="nd_title" placeholder="Nanodoc Title" value="<?php echo $settings['nd_title']; ?>" required></td>
                                        <td><input type="text" class="form-control" name="nd_description" placeholder="Nanodoc Description" value="<?php echo $settings['nd_description']; ?>"></td>
                                    </tr>
                                <?php } ?>

                                </tbody>
                            </table>
                        </div>
                        <button class="btn btn-primary btn-lg" type="submit">Update Your Settings</button>
                    </form>
                </div>
            </div>
        </section>

        <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
        <!-- Include all compiled plugins (below), or include individual files as needed -->
        <script src="js/bootstrap.min.js"></script>
    </body>
</html>