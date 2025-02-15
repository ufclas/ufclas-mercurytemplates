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

nav.breadcrumb-wrapper #breadcrumbs span.breadcrumb_last, nav.breadcrumb-wrapper #breadcrumbs span.breadcrumb_last strong {
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
<div id="content" class="site-content news">
  <div id="primary" class="news-landing-body content-area">
    
    <div class="container">
      <div class="row">
        <div class="title-wrapper">
          <h2 class="font-heading"><?php the_archive_title(); ?></h2>
          <hr/>
          <?php the_archive_description('<div class="archive-description">', '</div>'); ?>

        </div>

        <form id="misha_filters" action="#">
          <div class="filter-wrapper">
              <div class="select-wrapper">
                  <div class="dropdown">
                      <button type="button" class="filter-button btn dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" data-name="categoryfilter" data-value="">Filters</button>
                      <ul class="dropdown-menu button-group">
                          <li><button type="button" class="filter-button" data-name="categoryfilter" data-value="">All</button></li>
                          <?php
                          // Get the current category on the archive page
                          $current_category = get_queried_object();
                          if ($current_category && !is_wp_error($current_category)) {
                              $current_category_id = $current_category->term_id;

                              // Debugging: Print the current category ID
                              echo '<pre>Current Category ID: ' . $current_category_id . '</pre>';

                              // Get subcategories of the current category
                              $subcategories = get_categories([
                                  "orderby" => "name",
                                  "order" => "ASC",
                                  "hide_empty" => true,
                                  "parent" => $current_category_id,
                              ]);

                              // Debugging: Print the subcategories array
                              echo '<pre>';
                              print_r($subcategories);
                              echo '</pre>';

                              foreach ($subcategories as $subcategory) {
                                  echo '<li><button type="button" class="filter-button" data-name="categoryfilter" data-value="' .
                                      $subcategory->term_id .
                                      '">' .
                                      $subcategory->name .
                                      "</button></li>";
                              }
                          } else {
                              echo '<pre>No current category found.</pre>';
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
      $category = get_queried_object();
      $params = [
        "posts_per_page" => 100,
        "cat" => $category->term_id,
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
  
  
  
  
  
  

<?php
get_footer();