    <div class="col-md-3">
        <div id="toc" class="bs-docs-sidebar">
            <ul class="nav">                    
                <?php 
                $pages = $nd_sqlite->getPages(true, "all"); 
                if ($pages) {
                    foreach ($pages as $page) { ?>

                        <li><a href="#<?php echo text_for_id($page['page_name']); ?>"><?php echo $page['page_name']; ?></a></li>

                    <?php }
                } ?>    
            </ul>
            <a class="back-to-top" href="#top">Back to top</a>
        </div>
    </div>