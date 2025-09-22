<?php
$hide_date = get_post_meta($post->ID, 'hide_date', true);
$hide_socials = get_post_meta($post->ID, 'hide_socials', true);
$hide_author = get_post_meta($post->ID, 'hide_author', true);
$author_id = get_post_field('post_author', $post->ID);
$authorFirstName = get_the_author_meta('first_name', $author_id);
$authorLastName  = get_the_author_meta('last_name', $author_id);

// Get ACF fields for header content
$header_image = get_field('header_image');
$header_title = get_field('header_title');
$header_subtitle = get_field('header_subtitle');

// Use post title as fallback if no custom header title
$display_title = !empty($header_title) ? $header_title : get_the_title();

// Get header image URL and caption
$header_image_url = '';
$header_image_caption = '';
if ($header_image) {
    $header_image_url = $header_image['url'];
    $header_image_caption = $header_image['caption'];
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

<!-- POST HEADER SECTION -->
<section class="wp-block-create-block-post-header">
    <?php if ($header_image_url): ?>
        <!-- Hero Header with Background Image -->
        <div class="single-news-hero" style="background-image: url('<?php echo esc_url($header_image_url); ?>')">
            <div class="hero-content-wrapper">
                <h1 class="hero-title"><?php echo esc_html($display_title); ?></h1>
                <?php if ($header_subtitle): ?>
                    <h2 class="hero-subtitle"><?php echo esc_html($header_subtitle); ?></h2>
                <?php endif; ?>
                <?php if ($header_image_caption): ?>
                    <p class="hero-caption"><?php echo esc_html($header_image_caption); ?></p>
                <?php endif; ?>
            </div>
        </div>
    <?php else: ?>
        <!-- Simple Header without Background Image -->
        <div class="title-block w-100">
            <div class="container-fluid news-title-container">
                <div class="title-wrapper">
                    <h1><?php echo esc_html($display_title); ?></h1>
                    <?php if ($header_subtitle): ?>
                        <h2 class="header-subtitle"><?php echo esc_html($header_subtitle); ?></h2>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>
</section>

<?php 
if ($hide_date == "1" && $hide_socials == "1" && $hide_author == "1") {
    // All elements are hidden, don't show the intro section
} else {
?>
<!-- POST INTRO SECTION -->
<section class="wp-block-create-block-single-post-intro">
    <div class="single-news-intro">
        <!-- Date and Share Section -->
        <div class="date-share-wrapper">
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
                           <svg width="24" height="24" version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 21.2"><path d="M5.429 1.761C8.089 3.758 10.95 7.807 12 9.98c1.05 -2.173 3.911 -6.222 6.571 -8.218C20.49 0.32 23.6 -0.794 23.6 2.753c0 0.708 -0.406 5.952 -0.644 6.803 -0.828 2.959 -3.846 3.714 -6.53 3.257 4.692 0.799 5.886 3.444 3.308 6.089 -4.896 5.024 -7.036 -1.26 -7.585 -2.871 -0.101 -0.295 -0.148 -0.433 -0.148 -0.316 -0.001 -0.117 -0.048 0.021 -0.148 0.316 -0.549 1.61 -2.689 7.894 -7.585 2.871 -2.578 -2.645 -1.384 -5.29 3.308 -6.089 -2.684 0.457 -5.702 -0.298 -6.53 -3.257C0.806 8.705 0.4 3.461 0.4 2.753c0 -3.547 3.11 -2.433 5.029 -0.992z" fill="#0021A5"/></svg>
                        </a>
                    </div>
                    <?php
                }
                ?>
            </div>
        </div>
    </div>
</section>
<?php 
}
?>

<!-- MAIN CONTENT SECTION -->
<section class="post-content-section">
    <div class="container">
        <div class="post-content">
            <?php the_post(); ?>
            <?php the_content(); ?>
        </div>
    </div>
</section>

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


<?php
get_footer();
?>
