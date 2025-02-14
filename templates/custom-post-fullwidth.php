<?php

get_header();  
?>


<nav aria-label="breadcrumb" class="breadcrumb-wrapper"><?php
if ( function_exists('yoast_breadcrumb') ) {
  yoast_breadcrumb( '<p id="breadcrumbs">','</p>' );
}
?></nav>

<div id="content" class="single-news">
    <!-- Hook to add something nice -->
    <?php bs_after_primary(); ?>
    <?php the_content(); ?>
    <div class="mobile-related single-news-related-content"></div>
  </div>
<?php
get_footer();