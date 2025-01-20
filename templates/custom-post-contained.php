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

nav.breadcrumb-wrapper #breadcrumbs span strong {
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
<!-- wp:create-block/single-post-intro -->
<div class="wp-block-create-block-single-post-intro"><section class="single-news-intro single-news"><div class="date-share-wrapper"><div class="single-news-date">
<?php the_date(); ?></div>
   <div class="single-social-share"><div class="col-12 social-column social-column-grey"><span>Share</span><div class="sharethis-inline-share-buttons"></div></div></div></div></section></div>
<!-- /wp:create-block/single-post-intro -->

<div id="content" class="fullwidth-text-block">



  <div id="primary" class="container px-0">

    <!-- Hook to add something nice -->
    <?php bs_after_primary(); ?>

    


    <main id="main" class="site-main">


      <header class="entry-header">
      <?php the_post(); ?>
        <h1><?php the_title(); ?></h1>
        <?php bootscore_post_thumbnail(); ?>
      </header>

      <div class="entry-content">
        <?php the_content(); ?>
      </div>


      
    </main>

  </div>
</div>

<?php
get_footer();