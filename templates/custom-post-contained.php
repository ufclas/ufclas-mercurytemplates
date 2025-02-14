<?php
$hide_date = get_post_meta($post->ID, 'hide_date', true);
$hide_socials = get_post_meta($post->ID, 'hide_socials', true);
$hide_featured_image = get_post_meta($post->ID, 'hide_featured_image', true);
$hide_author = get_post_meta($post->ID, 'hide_author', true);
$author_id = get_post_field('post_author', $post->ID);
$authorFirstName = get_the_author_meta('first_name', $author_id);
$authorLastName  = get_the_author_meta('last_name', $author_id);

get_header();  
?>



<nav aria-label="breadcrumb" class="breadcrumb-wrapper">
<?php
if ( function_exists('yoast_breadcrumb') ) {
  yoast_breadcrumb( '<p id="breadcrumbs">','</p>' );
}
?>
</nav>

<?php 
if ($hide_date == "1" && $hide_socials == "1" && $hide_socials == "1") {

} else {
?>
<!-- wp:create-block/single-post-intro -->
<div class="wp-block-create-block-single-post-intro">
  <section class="single-news-intro single-news">
    <div class="date-share-wrapper" style="padding-bottom: 0">
      <div class="single-news-date">
        <?php 
        if ($hide_date !== "1") {
          the_date();
        }
        if ($hide_author !== "1") {
          ?> / <?php echo $authorFirstName; ?> <?php echo $authorLastName;
        }
        ?>
      </div>
      <div class="single-social-share">
        <?php 
        if ($hide_socials !== "1") {
          ?>
          <div class="col-12 social-column social-column-grey">
            <span>Share</span>
            <div class="sharethis-inline-share-buttons"></div>
          </div>
          <?php 
        }
        ?>
      </div>
    </div>
  </section>
</div>
<!-- /wp:create-block/single-post-intro -->
<?php 
}
?>
<div id="content" class="fullwidth-text-block">
  <div id="primary" class="container px-0">
    <!-- Hook to add something nice -->
    <?php bs_after_primary(); ?>
    <main id="main" class="site-main">
      <header class="entry-header">
        <?php the_post(); ?>
        <h1><?php the_title(); ?></h1>
        <?php 
if ($hide_featured_image !== "1") {
  bootscore_post_thumbnail(); 
  }?>
      </header>
      <div class="entry-content">
        <?php the_content(); ?>
      </div>
    </main>
  </div>
</div>

<?php
get_footer();
?>