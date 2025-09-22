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
            <a class="bluesky-share-btn"
               target="_blank"
               title="Share on Bluesky"
               href="https://bsky.app/intent/compose?text=<?php echo urlencode(get_the_title() . ' ' . get_permalink()); ?>">
                <svg width="24" height="24" version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 21.2">
                    <path d="M5.429 1.761C8.089 3.758 10.95 7.807 12 9.98c1.05 -2.173 3.911 -6.222 6.571 -8.218C20.49 0.32 23.6 -0.794 23.6 2.753c0 0.708 -0.406 5.952 -0.644 6.803 -0.828 2.959 -3.846 3.714 -6.53 3.257 4.692 0.799 5.886 3.444 3.308 6.089 -4.896 5.024 -7.036 -1.26 -7.585 -2.871 -0.101 -0.295 -0.148 -0.433 -0.148 -0.316 -0.001 -0.117 -0.048 0.021 -0.148 0.316 -0.549 1.61 -2.689 7.894 -7.585 2.871 -2.578 -2.645 -1.384 -5.29 3.308 -6.089 -2.684 0.457 -5.702 -0.298 -6.53 -3.257C0.806 8.705 0.4 3.461 0.4 2.753c0 -3.547 3.11 -2.433 5.029 -0.992z" fill="#0021A5"/>
                </svg>
            </a>
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