<?php
require_once ABSPATH . '/admin/nd_class_db.php';

require_once ABSPATH . '/admin/nd_functions.php';

function get_header() {
      
    start_login_session();

    $nd_sqlite = new nd_db; ?>

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
          <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
          <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
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
                <p class="navbar-text"><?php echo $nd_sqlite->getOption('nd_description'); ?></p>
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
            <img src="<?php echo $nd_sqlite->getOption('nd_url') . 'themes/default/images/opentech.png'; ?>" alt="logo">
        </div>
    </section>

<?php }


function get_footer() { 

    $nd_sqlite = new nd_db; ?>

        <footer id="footer"></footer>

        <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
        <!-- Include all compiled plugins (below), or include individual files as needed -->
        <script src="<?php echo $nd_sqlite->getOption('nd_url') . 'admin/js/bootstrap.min.js'; ?>"></script>
        <script src="<?php echo $nd_sqlite->getOption('nd_url') . 'themes/default/js/default.js'; ?>"></script>
    </body>
</html>

<?php }

function get_content($numberOfPages) { 

    $nd_sqlite = new nd_db; ?> 
        
    <section id="content" class="container">

        <div class="row">
            
            <div class="col-md-9">    
                <?php $pages = $nd_sqlite->getPages(true, $numberOfPages);
                      
                if ($pages) {
                    foreach ($pages as $page) { ?>

                    <article id="<?php echo text_for_id($page['page_name']); ?>" class="page">
                        <header>
                            <h1><?php echo $page['page_name']; ?></a></h1>
                            <p>
                                <span class="glyphicon glyphicon-time"></span><?php echo $page['page_date']; ?>
                                <span class="glyphicon glyphicon-user"></span><?php echo $page['user_login'] ?>
                            </p>
                        </header>
                        <div class="page-content">
                            <?php echo $page['page_content']; ?>
                        </div>
                    </article>
                    
                    <?php }
                } ?>

            </div>

            <?php get_toc($pages); ?>

        </div>
    </section>
<?php }

function get_toc($pages) { ?>

    <div class="col-md-3">
        <div id="toc" class="bs-docs-sidebar">
            <ul class="nav">                    
                <?php if ($pages) {
                    foreach ($pages as $page) { ?>

                        <li><a href="#<?php echo text_for_id($page['page_name']); ?>"><?php echo $page['page_name']; ?></a></li>

                    <?php }
                } ?>    
            </ul>
            <a class="back-to-top" href="#top">Back to top</a>
        </div>
    </div>
 <?php }

function get_categories() {
    
}

function get_tags() {
    
}

function get_title() {

}

function get_logged_in_user() {

}

function get_site_url() {

}

function start_page() {;

}