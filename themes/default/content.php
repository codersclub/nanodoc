<?php
    $markdown = new Parsedown();
    
    if(!isset($_GET['p'])) { ?>    
        <section id="content" class="container">

            <div class="row">
                
                <div class="col-md-9">    
                    <?php $pages = $nd_sqlite->getPages(true, "all");  
                          
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
                                <?php 
                                $page['page_content'] = $markdown->parse($page['page_content']);      
                                echo $page['page_content']; ?>
                            </div>
                        </article>
                        
                        <?php } //end foreach
                    } //end if ?>

                </div>

                <?php get_template_part("toc"); ?>

            </div>
        </section>

    <?php } else { ?>

        <section id="content" class="container">

            <div class="row">
                
                <div class="col-md-9">    
                    <?php $page = $nd_sqlite->getPage($_GET['p']);  
                          
                    if ($page) { ?>

                        <article id="<?php echo text_for_id($page['page_name']); ?>" class="page">
                            <header>
                                <h1><a href="<?php echo $page['page_url']; ?>"><?php echo $page['page_name']; ?></a></h1>
                                <p>
                                    <span class="glyphicon glyphicon-time"></span><?php echo $page['page_date']; ?>
                                    <span class="glyphicon glyphicon-user"></span><?php echo $page['user_login'] ?>
                                </p>
                            </header>
                            <div class="page-content">
                                <?php 
                                $page['page_content'] = $markdown->parse($page['page_content']);
                                echo $page['page_content']; ?>
                            </div>
                        </article>
                        
                <?php } //end if 
    } //end else ?> 

                </div>
            </div>
        </section>