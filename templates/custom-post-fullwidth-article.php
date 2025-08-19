<?php
$hide_date = get_post_meta($post->ID, 'hide_date', true);
$hide_socials = get_post_meta($post->ID, 'hide_socials', true);
$hide_featured_image = get_post_meta($post->ID, 'hide_featured_image', true);
$hide_author = get_post_meta($post->ID, 'hide_author', true);
$author_id = get_post_field('post_author', $post->ID);
$authorFirstName = get_the_author_meta('first_name', $author_id);
$authorLastName  = get_the_author_meta('last_name', $author_id);

// Get featured image and media library text fields
$featured_image_id = get_post_thumbnail_id($post->ID);
$featured_image_url = get_the_post_thumbnail_url($post->ID, 'full');

// Get custom subtitle for posts (fallback when no featured image)
$post_subtitle = get_post_meta($post->ID, 'post_subtitle', true);

// Get image data from media library (when featured image exists)
$image_title = '';
$image_caption = '';
$image_description = '';

if ($featured_image_id) {
    $image_data = wp_get_attachment_metadata($featured_image_id);
    $attachment = get_post($featured_image_id);
    
    $image_title = get_the_title($featured_image_id); // Media library title
    $image_caption = wp_get_attachment_caption($featured_image_id); // Media library caption
    $image_description = $attachment->post_content; // Media library description
}

get_header();  
?>

<nav aria-label="breadcrumb" class="breadcrumb-wrapper">
<?php
if ( function_exists('yoast_breadcrumb') ) {
  yoast_breadcrumb( '<p id="breadcrumbs">','</p>' );
}
?>
</nav>

<?php if ($featured_image_url && $hide_featured_image !== "1"): ?>
<!-- Full Width Featured Image Hero Section -->
<section class="fullwidth-article-hero">
    <div class="hero-image-wrapper">
        <img src="<?php echo esc_url($featured_image_url); ?>" alt="<?php echo esc_attr($image_title ? $image_title : get_the_title()); ?>" class="hero-image">
        <?php if ($image_title || $image_caption || $image_description): ?>
        <div class="hero-overlay">
            <div class="hero-content">
                <?php if ($image_title): ?>
                    <h1 class="hero-title"><?php echo esc_html($image_title); ?></h1>
                <?php endif; ?>
                <?php if ($image_caption): ?>
                    <h2 class="hero-subtitle"><?php echo esc_html($image_caption); ?></h2>
                <?php endif; ?>
                <?php if ($image_description): ?>
                    <p class="hero-caption"><?php echo esc_html($image_description); ?></p>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
</section>
<?php else: ?>
<!-- Full Width Title Hero Section (when no featured image or hidden) -->
<section class="fullwidth-article-hero fullwidth-title-hero">
    <div class="hero-title-wrapper">
        <div class="hero-content">
            <h1 class="hero-title"><?php the_title(); ?></h1>
            <?php if ($post_subtitle): ?>
                <h2 class="hero-subtitle"><?php echo esc_html($post_subtitle); ?></h2>
            <?php endif; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<?php 
if ($hide_date == "1" && $hide_socials == "1" && $hide_author == "1") {
    // All elements are hidden, don't show the intro section
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

<div id="content" class="fullwidth-text-block fullwidth-article">
  <div id="primary" class="container px-0">
    <!-- Hook to add something nice -->
    <?php bs_after_primary(); ?>
    <main id="main" class="site-main">
      <header class="entry-header">
        <?php the_post(); ?>
        <?php if ($featured_image_url && $hide_featured_image !== "1" && (!$image_title && !$image_caption && !$image_description)): ?>
            <!-- Featured image exists but no overlay text, show title below image -->
            <h1><?php the_title(); ?></h1>
            <?php if ($post_subtitle): ?>
                <h2 class="post-subtitle"><?php echo esc_html($post_subtitle); ?></h2>
            <?php endif; ?>
        <?php endif; ?>
      </header>
      <div class="entry-content">
        <?php the_content(); ?>
      </div>
      
      <?php
      // Get related posts based on tags
      $current_post_id = get_the_ID();
      $post_tags = wp_get_post_tags($current_post_id);
      
      if ($post_tags) {
          // Extract tag IDs
          $tag_ids = array();
          foreach($post_tags as $tag) {
              $tag_ids[] = $tag->term_id;
          }
          
          // Query for related posts
          $related_posts_args = array(
              'tag__in' => $tag_ids,
              'post__not_in' => array($current_post_id),
              'posts_per_page' => 3,
              'post_status' => 'publish',
              'orderby' => 'date',
              'order' => 'DESC'
          );
          
          $related_posts = new WP_Query($related_posts_args);
          
          if ($related_posts->have_posts()) :
      ?>
      <!-- You might also like section -->
      <div id="ufl-news-content" class="site-content news">
          <div id="ufl-news-primary" class="news-landing-body content-area">
              <div class="container">
                  <div class="row">
                      <div class="title-wrapper">
                          <h2 class="font-heading">You might also like</h2>
                          <hr/>
                      </div>
                  </div>
              </div>
          </div>

          <div class="container">
              <div id="ufl_posts_wrap" class="row position-relative news-row">
                  <?php
                  while ($related_posts->have_posts()) :
                      $related_posts->the_post();
                      $featured_img_url = get_the_post_thumbnail_url(get_the_ID(), 'full');
                      $post_date = get_the_date('l F j, Y');
                      $filter_date = get_the_date('Y');
                  ?>
                  <div class="col-sm-6 col-lg-4 mb-4 news-col <?php echo 'year-' . $filter_date; ?>">
                      <div class="card news-card">
                          <a href="<?php the_permalink(); ?>" class="news-card-link" alt="<?php the_title(); ?>">
                              <?php if ($featured_img_url) : ?>
                                  <img src="<?php echo esc_url($featured_img_url); ?>" class="card-img-top" alt="<?php the_title(); ?>">
                              <?php endif; ?>
                              <div class="card-body">
                                  <h3 class="card-title"><?php the_title(); ?></h3>
                                  <p class="card-date"><?php echo $post_date; ?></p>
                                  <p class="card-text"><?php the_excerpt(); ?></p>
                              </div>
                          </a>
                      </div>
                  </div>
                  <?php
                  endwhile;
                  wp_reset_postdata();
                  ?>
              </div>
          </div>
      </div>
      <?php
          endif;
      }
      ?>
    </main>
  </div>
</div>

<?php
get_footer();
?>
