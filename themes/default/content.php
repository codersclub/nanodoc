        
    <section id="content" class="container">

        <div class="row">
            
            <div class="col-md-9">    
                <?php $pages = $nd_mysql->getPagesInfo();
                      
                if ($pages) {
                    foreach ($pages as $page) { ?>

                    <article id="<?php echo text_for_id($page['page_name']); ?>" class="page">
                        <header>
                            <h1><a href="<?php echo $page['page_url']; ?>"><?php echo $page['page_name']; ?></a></h1>
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

        </div>
    </section>