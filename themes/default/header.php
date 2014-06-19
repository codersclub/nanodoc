<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?php echo $nd_sqlite->getOption('nd_title'); ?></title>

        <!-- Bootstrap -->
        <link href="<?php echo $nd_sqlite->getOption('nd_url') . 'admin/css/bootstrap.min.css'; ?>" rel="stylesheet">
        <!-- NannoDoc custom css -->
        <link rel="stylesheet" href="<?php echo $nd_sqlite->getOption('nd_url') . 'admin/css/bs-docs.css'; ?>">
        <!-- Deafult theme css -->
        <link rel="stylesheet" href="<?php echo $nd_sqlite->getOption('nd_url') . 'themes/default/css/style.css'; ?>">
        <style>


        </style>
        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src="../libs/html5shiv/3.7.0/html5shiv.js"></script>
          <script src="../libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>
    <body data-spy="scroll" data-target="#toc">
    <header id="top" class="navbar navbar-static-top">
        <div class="container">
            <div class="navbar-header">
                <button class="navbar-toggle" data-target="#default-header" data-toggle="collapse" type="button">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a href="<?php echo $nd_sqlite->getOption('nd_url'); ?>" class="navbar-brand"><?php echo $nd_sqlite->getOption('nd_title'); ?></a>
            </div>

            <div id="default-header" class="collapse navbar-collapse" role="navigation">
                <p class="navbar-text navbar-right">
                    <?php echo isset($_SESSION['login']) ? "<a href='admin/'>Hello, {$_SESSION['login']}</a>" : "<a href='login.php'>Login</a>" ?>
                </p>
            </div>

        </div>
    </header>
    <section id="logo">
        <div class="container">
            <h1><?php echo $nd_sqlite->getOption('nd_title'); ?></h1>
            <p><?php echo $nd_sqlite->getOption('nd_description'); ?></p>
        </div>
    </section>