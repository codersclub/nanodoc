<?php
require_once '../nd_functions.php'; 
check_login_session();

require_once 'lib/Parsedown.php';
$markdown = new Parsedown();
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?php echo 'Edit Page &rsaquo; ' . $nd_sqlite->getOption('nd_title'); ?></title>

        <!-- Bootstrap -->
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <!-- NannoDoc custom css -->
        <link rel="stylesheet" href="css/custom-style.css">
        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src="../libs/html5shiv/3.7.0/html5shiv.js"></script>
          <script src="../libs/respond.js/1.4.2/respond.min.js"></script>
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
                            <p><a href="<?php echo $nd_sqlite->getOption('nd_url'); ?>"><?php echo $nd_sqlite->getOption('nd_title'); ?></a></p>
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
                                    <?php echo $_SESSION['login'];?>
                                    <span class="caret"></span>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-left">
                                    <li><a href="user.php">Account</a></li>
                                    <li><a href="settins.php">Settings</a></li>
                                    <li><a href="index.php?action=logout">Logout</a></li>
                                </ul>
                            </li>
                        </ul>   
                    </ul>       
                </div>
            </div>
        </header>

        <section id="edit-page">
            <div class="panel panel-primary">

                <?php if(isset($_GET['p'])) { ?>

                <div class="panel-heading">Edit Your Page</div>
                <div class="panel-body">

                    <?php if (isset($_GET['action']) && isset($_POST['page_title']) && isset($_POST['page_content']) && $_GET['action']=='edit') {
                        $nd_sqlite->editPage($_GET['p'], $_POST['page_title'], $_POST['page_content']);
                    } ?>

                    <?php if (!empty($_GET['action'])) { ?>

                        <div class="alert alert-success alert-dismissable">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            <p>Page updated</p>
                        </div>

                    <?php } ?>

                    <form action="edit.php?p=<?php echo $_GET['p']; ?>&action=edit" method="post">

                        <?php $page = $nd_sqlite->getPage($_GET['p']); ?>

                        <h4>Page Title</h4>
                        <input type="text" class="form-control" name="page_title" placeholder="Title" value="<?php echo $page['page_name'] ?>" required>
                        <h4>Page Content</h4>

                        <textarea name="page_content" cols="30" rows="10" class="form-control"><?php echo $page['page_content']; ?></textarea>
                        <button type="submit" name="submit" class="btn btn-primary btn-lg">Save Changes</button>
                        <a class="btn btn-primary btn-lg" id="markdown-help" target="_blank" href="mastering-markdown/">Help with markdown ?</a>
                    </form>
                </div>

                <?php } else { ?>

                <div class="panel-heading">Add New Page</div>
                <div class="panel-body">

                <?php if (isset($_POST['page_title']) && isset($_POST['page_content'])) { 
                    $nd_sqlite->addPage($_POST['page_title'], $_POST['page_content'], $_SESSION['login']);
                } ?>

                <?php if (!empty($_GET['action'])) { ?>

                    <div class="alert alert-success alert-dismissable">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <p>Page created</p>
                    </div>

                <?php } ?>

                    <form action="edit.php?action=add" method="post">
                        <h4>Page Title</h4>
                        <input type="text" class="form-control" name="page_title" placeholder="Title" value="<?php echo isset($_POST['page_title']) ? $_POST['page_title'] : "" ; ?>" required>
                        <h4>Page Content</h4>
                        <textarea name="page_content" cols="30" rows="10" class="form-control"><?php echo isset($_POST['page_content']) ? $_POST['page_content'] : "" ; ?></textarea>
                        <button type="submit" class="btn btn-primary btn-lg">Save Changes</button>
                        <a class="btn btn-primary btn-lg" id="markdown-help" target="_blank" href="mastering-markdown/">Help with markdown ?</a>
                    </form>
                </div>

                <?php } ?>

        </section>
        <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
        <script src="../libs/jquery/1.11.0/jquery.min.js"></script>
        <!-- Include all compiled plugins (below), or include individual files as needed -->
        <script src="../libs/bootstrap/3.1.1/bootstrap.min.js"></script>
    </body>
</html>
