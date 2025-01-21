<?php

get_header();  
?>
<style>
  nav.breadcrumb-wrapper #breadcrumbs span {
    font-size: 16px;
    line-height: 24px;
    color: #fa4616;
    font-family: 'gentonamedium';
}

nav.breadcrumb-wrapper #breadcrumbs span a {
    color: #000;
    text-decoration: none;
    font-family: "gentonalight";
}

nav.breadcrumb-wrapper #breadcrumbs span.breadcrumb_last {
  font-family: 'gentonamedium';
    font-weight: normal;
    color: #000;
}

nav.breadcrumb-wrapper #breadcrumbs {
    padding: 10px 0;
}
</style>

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