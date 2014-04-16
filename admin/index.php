<?php
require_once 'nd_functions.php'; 
check_login_session();

require_once 'nd_class_db.php';

$nd_mysql = new nd_db;

$nd_mysql->checkDatabase(); ?>

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
    <body>
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
        
        <?php if (isset($_GET['action']) && $_GET['action']=='logout') {
            end_login_session();
        } ?>
    
        <?php if (isset($_GET['action']) && $_GET['action']=='delete') {
            $deleted = $nd_mysql->deletePage($_GET['p']);
        } ?>

        <section id="admin-content">
            <div id="pages" class="panel panel-primary">
                <div class="panel-heading">Your pages</div>
                <div class="panel-body">

                    <?php if (!empty($deleted)) { ?>

                        <div class="alert alert-success alert-dismissable">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            <p>Page deleted</p>
                        </div>

                    <?php } ?>

                    <div class="table-responsive">

                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Author</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php 
                            $pages = $nd_mysql->getPagesInfo($getContent=false);

                            if($pages) {
                                foreach ($pages as $page) { ?>
                                    <tr>
                                        <td><?php echo $page['page_name']; ?></td>
                                        <td><?php echo $page['user_login']; ?></td>
                                        <td><?php echo $page['page_date']; ?></td>
                                        <td>
                                            <a href="<?php echo $page['page_url']; ?>" class="btn btn-primary btn-xs">View</a>
                                            <a href="edit.php?p=<?php echo $page['page_id']; ?>" class='btn btn-primary btn-xs'>Edit</a> 
                                            <a href="index.php?p=<?php echo $page['page_id']; ?>&action=delete" class='btn btn-primary btn-xs'>Delete</a></td>
                                        </td>
                                    </tr>
                                <?php }
                            } ?>

                            </tbody>
                        </table>
                    </div>
                    <div id="new-page-button"><a href='edit.php' class='btn btn-primary btn-xs'>Add new page</a></div>
                </div>
            </div>
            <div id="users"></div>
        </section>

                
        <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
        <!-- Include all compiled plugins (below), or include individual files as needed -->
        <script src="js/bootstrap.min.js"></script>
    </body>
</html>
