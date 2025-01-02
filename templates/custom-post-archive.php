<?php
get_header();
$postid = get_option('page_for_posts');
?>

<div id="content" class="site-content news">
  <div id="primary" class="news-landing-body content-area">
    <?php the_content(); ?>

    <div class="container">
      <div class="row">
        <div class="title-wrapper">
          <h2 class="font-heading">
            
          <?php
            $selected_category_slug = get_post_meta(get_the_ID(), 'selected-category', true);

            if ($selected_category_slug) {
                $category = get_category_by_slug($selected_category_slug);
                if ($category) {
                    echo '<h2 class="font-heading">' . esc_html($category->name) . '</h2>';
                } else {
                    echo '<h2 class="font-heading">Uncategorized</h2>';
                }
            } else {
                echo '<h2 class="font-heading">' . get_the_title() . '</h2>';
            }
          ?>
          
          </h2>
          <hr/>
        </div>

        <form id="misha_filters" action="#">
          <div class="filter-wrapper">
            <div class="select-wrapper">
              <div class="dropdown">
                <button type="button" class="filter-button btn dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" data-name="categoryfilter" data-value="">Filter</button>
                <ul class="dropdown-menu button-group">
                  <li><button type="button" class="filter-button" data-name="categoryfilter" data-value="">All</button></li>
                  <?php

                  // Get the ID of the parent category "Uncategorized"
                  $parent_category_id = get_cat_ID('Uncategorized');

                  // Get categories that have the parent category for Uncategorized
                  $categories = get_categories([
                      "orderby" => "name",
                      "order" => "ASC",
                      "hide_empty" => true,
                      "parent" => $parent_category_id, // Add parent category ID
                    ]);

                  foreach ($categories as $category) {
                    echo '<li><button type="button" class="filter-button" data-name="categoryfilter" data-value="' .
                      $category->term_id .
                      '">' .
                      $category->name .
                      "</button></li>";
                  }
                  ?>
                </ul>
                <input type="hidden" name="categoryfilter" id="categoryfilter" value="">
              </div>
            </div>



          </div> <!-- End Filter Wrapper -->

          <!-- required hidden field for admin-ajax.php -->
          <input type="hidden" name="action" value="mishafilter">
          <button id="submitFilter" style="display:none;" type="submit">Apply Filters</button>
        </form>
      </div>
    </div>
  </div>

  <div class="container">
    <div id="misha_posts_wrap" class="row position-relative news-row" data-masonry="{&quot;percentPosition&quot;: true }">
      <?php
      $params = [
        "posts_per_page" => 15,
        "category_name" => "Uncategorized",
      ];

      query_posts($params);

      global $wp_query;

      if (have_posts()) :
        while (have_posts()) :
          the_post();

          include($_SERVER['DOCUMENT_ROOT']."/wp-content/plugins/ufclas-mercurytemplates/template-parts/content-post.php");
          
        endwhile;
      else :
        $posts_html = "<p>Nothing found for your criteria.</p>";
      endif;
      ?>
    </div>
  </div>

  <!-- Pagination -->
  <div class="d-flex flex-wrap justify-content-center button-wrapper my-4">
    <?php
    if ($wp_query->max_num_pages > 1) {
      echo '<div class="button animated-border-button button-border-orange button-text-dark" id="misha_loadmore">More posts</div>'; // you can use <a> as well
    }
    ?>
  </div>

  <script>
    // Attach click event handlers to the filter buttons
    document.querySelectorAll('.filter-button').forEach(function(button) {
      button.addEventListener('click', selectFilter);
    });

    // Function to select a filter option
    function selectFilter() {
      var name = this.getAttribute('data-name');
      var value = this.getAttribute('data-value');
      var input = document.getElementById(name);
      input.value = value;
      document.querySelectorAll('[data-name="' + name + '"]').forEach(function(button) {
        button.classList.remove('selected');
      });
      if (value) {
        this.classList.add('selected');
        //this.parentNode.parentNode.querySelector('button[data-name="' + name + '"][data-value=""]').innerHTML = this.innerHTML;
      } else {
        //this.parentNode.parentNode.querySelector('button[data-name="' + name + '"][data-value=""]').innerHTML = 'Select ' + name.substring(0, name.length - 6) + '...';
      }
    }
  </script>

  <?php get_footer();
