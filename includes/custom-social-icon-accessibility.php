<?php
/**
 * Custom Social Icon Accessibility Fix
 * Adds missing alt attributes to UFL Social Custom Icon block images
 *
 * @package UFCLAS_MercuryTemplates
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add alt text to custom social icon images that are missing them
 * This specifically targets the ufl-social-icon-custom block images
 */
add_filter('the_content', 'ufclas_fix_custom_social_icon_alt', 15);
add_filter('widget_text', 'ufclas_fix_custom_social_icon_alt', 15);
add_filter('widget_block_content', 'ufclas_fix_custom_social_icon_alt', 15);
add_filter('widget_custom_html', 'ufclas_fix_custom_social_icon_alt', 15);

function ufclas_fix_custom_social_icon_alt($content) {
    // Quick check - skip if no custom icons
    if (stripos($content, 'ufl-custom') === false) {
        return $content;
    }

    // Pattern to find custom social icons within links
    // This captures: <a href="..."><img class="ufl-brands ufl-custom" src="..."></a>
    $pattern = '/(<a[^>]*href=["\']([^"\']*)["\'][^>]*>)\s*<img([^>]*class=["\'][^"\']*ufl-custom[^"\']*["\'][^>]*)(?![^>]*alt=)([^>]*)\/>/i';

    $content = preg_replace_callback($pattern, function($matches) {
        $link_opening = $matches[1];
        $href = $matches[2];
        $img_attributes = $matches[3] . $matches[4];

        // Detect social platform from the href
        $alt_text = ufclas_detect_social_platform($href, $img_attributes);

        // Create new img tag with appropriate alt text
        $new_img = '<img' . $img_attributes . ' alt="' . esc_attr($alt_text) . '"/>';

        return $link_opening . $new_img;
    }, $content);

    // Also handle custom icons that might not be within links
    $pattern2 = '/<img([^>]*class=["\'][^"\']*ufl-custom[^"\']*["\'][^>]*)(?![^>]*alt=)([^>]*)\/>/i';

    $content = preg_replace_callback($pattern2, function($matches) {
        $full_img_tag = $matches[0];

        // Skip if this was already processed (is within a link)
        if (preg_match('/<a[^>]*>.*?' . preg_quote($full_img_tag, '/') . '/s', $matches[0])) {
            return $full_img_tag;
        }

        // Try to detect platform from img src
        $alt_text = ufclas_detect_social_platform('', $full_img_tag);

        // Add the alt attribute
        $new_img_tag = str_replace('/>', ' alt="' . esc_attr($alt_text) . '"/>', $full_img_tag);

        return $new_img_tag;
    }, $content);

    return $content;
}

/**
 * Detect social platform from URL or image attributes
 *
 * @param string $url The href URL from the link
 * @param string $img_tag The full img tag or attributes
 * @return string The appropriate alt text for the social platform
 */
function ufclas_detect_social_platform($url, $img_tag) {
    // Convert to lowercase for easier matching
    $url_lower = strtolower($url);
    $img_lower = strtolower($img_tag);
    $combined = $url_lower . ' ' . $img_lower;

    // Check for specific social platforms
    if (strpos($combined, 'linkedin') !== false) {
        return 'LinkedIn';
    } elseif (strpos($combined, 'facebook') !== false || strpos($combined, 'fb.com') !== false) {
        return 'Facebook';
    } elseif (strpos($combined, 'twitter') !== false || strpos($combined, 'x.com') !== false) {
        return 'X (Twitter)';
    } elseif (strpos($combined, 'instagram') !== false) {
        return 'Instagram';
    } elseif (strpos($combined, 'youtube') !== false || strpos($combined, 'youtu.be') !== false) {
        return 'YouTube';
    } elseif (strpos($combined, 'tiktok') !== false) {
        return 'TikTok';
    } elseif (strpos($combined, 'pinterest') !== false) {
        return 'Pinterest';
    } elseif (strpos($combined, 'snapchat') !== false) {
        return 'Snapchat';
    } elseif (strpos($combined, 'whatsapp') !== false) {
        return 'WhatsApp';
    } elseif (strpos($combined, 'telegram') !== false) {
        return 'Telegram';
    } elseif (strpos($combined, 'discord') !== false) {
        return 'Discord';
    } elseif (strpos($combined, 'reddit') !== false) {
        return 'Reddit';
    } elseif (strpos($combined, 'tumblr') !== false) {
        return 'Tumblr';
    } elseif (strpos($combined, 'vimeo') !== false) {
        return 'Vimeo';
    } elseif (strpos($combined, 'twitch') !== false) {
        return 'Twitch';
    } elseif (strpos($combined, 'spotify') !== false) {
        return 'Spotify';
    } elseif (strpos($combined, 'soundcloud') !== false) {
        return 'SoundCloud';
    } elseif (strpos($combined, 'apple') !== false && strpos($combined, 'music') !== false) {
        return 'Apple Music';
    } elseif (strpos($combined, 'bluesky') !== false || strpos($combined, 'bsky') !== false) {
        return 'Bluesky';
    } elseif (strpos($combined, 'threads') !== false) {
        return 'Threads';
    } elseif (strpos($combined, 'mastodon') !== false) {
        return 'Mastodon';
    } elseif (strpos($combined, 'wechat') !== false || strpos($combined, 'weixin') !== false) {
        return 'WeChat';
    } elseif (strpos($combined, 'line') !== false) {
        return 'LINE';
    } elseif (strpos($combined, 'viber') !== false) {
        return 'Viber';
    } elseif (strpos($combined, 'slack') !== false) {
        return 'Slack';
    } elseif (strpos($combined, 'teams') !== false && strpos($combined, 'microsoft') !== false) {
        return 'Microsoft Teams';
    } elseif (strpos($combined, 'github') !== false) {
        return 'GitHub';
    } elseif (strpos($combined, 'gitlab') !== false) {
        return 'GitLab';
    } elseif (strpos($combined, 'bitbucket') !== false) {
        return 'Bitbucket';
    } elseif (strpos($combined, 'behance') !== false) {
        return 'Behance';
    } elseif (strpos($combined, 'dribbble') !== false) {
        return 'Dribbble';
    } elseif (strpos($combined, 'medium') !== false) {
        return 'Medium';
    } elseif (strpos($combined, 'quora') !== false) {
        return 'Quora';
    } elseif (strpos($combined, 'yelp') !== false) {
        return 'Yelp';
    } elseif (strpos($combined, 'tripadvisor') !== false) {
        return 'TripAdvisor';
    } elseif (strpos($combined, 'goodreads') !== false) {
        return 'Goodreads';
    } elseif (strpos($combined, 'strava') !== false) {
        return 'Strava';
    } elseif (strpos($combined, 'flickr') !== false) {
        return 'Flickr';
    } elseif (strpos($combined, '500px') !== false) {
        return '500px';
    }

    // Generic fallback
    return 'Social media';
}