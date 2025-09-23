<?php
/**
 * Enhanced render callback for Post Intro Block with Bluesky support
 */

function ufclas_render_single_post_intro_block_enhanced($attributes, $content) {
    // Extract block attributes
    $text = isset($attributes['text']) ? $attributes['text'] : '';

    // Handle social sharing attributes with proper defaults
    $showFacebook = isset($attributes['showFacebook']) ? (bool)$attributes['showFacebook'] : true;
    $showTwitter = isset($attributes['showTwitter']) ? (bool)$attributes['showTwitter'] : true;
    $showEmail = isset($attributes['showEmail']) ? (bool)$attributes['showEmail'] : true;
    $showLinkedin = isset($attributes['showLinkedin']) ? (bool)$attributes['showLinkedin'] : true;
    $showBluesky = isset($attributes['showBluesky']) ? (bool)$attributes['showBluesky'] : true;

    // Check if any social buttons are enabled
    $show_any_social = $showFacebook || $showTwitter || $showEmail || $showLinkedin || $showBluesky;

    // Get the current post URL and title for sharing
    $post_title = get_the_title();
    $post_url = get_permalink();
    $share_text = urlencode($post_title . ' ' . $post_url);

    // Get current post type
    $post_type = get_post_type();

    // Check post meta for Bluesky only if it's a post
    if ($post_type === 'post') {
        // For posts, check both block setting AND post meta
        $post_allows_bluesky = get_post_meta(get_the_ID(), 'show_bluesky', true);
        $showBlueskyfinal = $showBluesky && $post_allows_bluesky;
    } else {
        // For pages and other post types, only check block setting
        $showBlueskyfinal = $showBluesky;
    }

    // Add data attributes for hiding ShareThis buttons via block settings
    $sharethis_data_attrs = '';
    $sharethis_data_attrs .= !$showFacebook ? ' data-show-facebook="false"' : '';
    $sharethis_data_attrs .= !$showTwitter ? ' data-show-twitter="false"' : '';
    $sharethis_data_attrs .= !$showEmail ? ' data-show-email="false"' : '';
    $sharethis_data_attrs .= !$showLinkedin ? ' data-show-linkedin="false"' : '';

    ob_start();
    ?>
    <div class="wp-block-create-block-single-post-intro">
        <section class="single-news-intro">
            <div class="date-share-wrapper">
                <div class="single-news-date">
                    <?php
                    // Extract the post-date block from saved content
                    if (preg_match('/<div class="wp-block-post-date">.*?<\/div>/s', $content, $matches)) {
                        echo $matches[0];
                    }
                    ?>
                </div>
                <div class="single-social-share">
                    <?php if ($show_any_social) : ?>
                    <div class="col-12 social-column social-column-grey">
                        <span>Share</span>
                        <?php if ($showBlueskyfinal) : ?>
                        <a class="bluesky-share-btn"
                           target="_blank"
                           rel="noopener noreferrer"
                           title="Share on Bluesky"
                           href="https://bsky.app/intent/compose?text=<?php echo $share_text; ?>">
                            <svg width="24" height="24" version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 21.2">
                                <path d="M5.429 1.761C8.089 3.758 10.95 7.807 12 9.98c1.05 -2.173 3.911 -6.222 6.571 -8.218C20.49 0.32 23.6 -0.794 23.6 2.753c0 0.708 -0.406 5.952 -0.644 6.803 -0.828 2.959 -3.846 3.714 -6.53 3.257 4.692 0.799 5.886 3.444 3.308 6.089 -4.896 5.024 -7.036 -1.26 -7.585 -2.871 -0.101 -0.295 -0.148 -0.433 -0.148 -0.316 -0.001 -0.117 -0.048 0.021 -0.148 0.316 -0.549 1.61 -2.689 7.894 -7.585 2.871 -2.578 -2.645 -1.384 -5.29 3.308 -6.089 -2.684 0.457 -5.702 -0.298 -6.53 -3.257C0.806 8.705 0.4 3.461 0.4 2.753c0 -3.547 3.11 -2.433 5.029 -0.992z" fill="#0021A5"/>
                            </svg>
                        </a>
                        <?php endif; ?>
                        <div class="sharethis-inline-share-buttons"<?php echo $sharethis_data_attrs; ?>></div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php if ($text) : ?>
                <div class="single-news-highlight">
                    <p><?php echo wp_kses_post($text); ?></p>
                    <hr />
                </div>
            <?php endif; ?>
        </section>
    </div>
    <?php
    return ob_get_clean();
}