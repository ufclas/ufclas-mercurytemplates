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
<?php the_post(); ?>
<!-- wp:create-block/single-post-intro -->
<div class="wp-block-create-block-single-post-intro"><section class="single-news-intro"><div class="date-share-wrapper"><div class="single-news-date">
<?php date(); ?></div>
   <div class="single-social-share"><div class="col-12 social-column social-column-grey"><span>Share</span><div class="sharethis-inline-share-buttons"></div></div></div></div></section></div>
<!-- /wp:create-block/single-post-intro -->

<div id="content" class="site-content container py-5 mt-4">



  <div id="primary" class="content-area">

    <!-- Hook to add something nice -->
    <?php bs_after_primary(); ?>

    


    <main id="main" class="site-main">

      <header class="entry-header">
        <?php bootscore_category_badge(); ?>
        <h1><?php the_title(); ?></h1>
        <p class="entry-meta">
          <small class="text-muted">
            <?php
              bootscore_date();
              bootscore_author();
              bootscore_comment_count();
            ?>
          </small>
        </p>
        <?php bootscore_post_thumbnail(); ?>
      </header>

      <div class="entry-content">
        <?php the_content(); ?>
      </div>

      <footer class="entry-footer clear-both">
        <div class="mb-4">
          <?php bootscore_tags(); ?>
        </div>
        <nav aria-label="bS page navigation">
          <ul class="pagination justify-content-center">
            <li class="page-item">
              <?php previous_post_link('%link'); ?>
            </li>
            <li class="page-item">
              <?php next_post_link('%link'); ?>
            </li>
          </ul>
        </nav>
        <?php comments_template(); ?>
      </footer>
      
    </main>

  </div>
</div>

<?php
get_footer();