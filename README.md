UF CLAS Mercury Templates and Styles
====================================

A WordPress plugin that provides complementary functionality and styles to the UF Mercury template for CLAS sites.

## Adds the following templates

**No Sidebar Inc. Breadcrumbs**

A Post template replacing the "No Sidebar" template that adds functionality to:
  - Show/hide dates
  - Show/hide author
  - Show/hide socials
  - Show/hide featured image
  - Include Yoast Breadcrumbs

This template also inherits functionality from "No Sidebar" which automatically inserts the page title in H1 format and limits the content width to 1074 pixels.

**Default Inc. Breadcrumbs**

A full-width, default-style template for posts that adds functionality to:
  - Include Yoast Breadcrumbs


## Adds the following helper classes

  - remove-bullets - add to query loops to remove bullets
  - remove-email - hides email address on the Faculty Bio block
  - responsive - stack all table cells for tables with class of "responsive" added
  - five-across - smaller thumbnails on galleries for galleries with a class of "five-across" added
  - top-align - class "top-align" for moving up cards

## Styles the custom pattern "Two highlighted posts"

See below for custom pattern code:

```
<!-- wp:spacer {"height":"54px"} -->
<div style="height:54px" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer -->

<!-- wp:heading {"textAlign":"center"} -->
<h2 class="wp-block-heading has-text-align-center">What Students are Saying</h2>
<!-- /wp:heading -->

<!-- wp:spacer {"height":"20px"} -->
<div style="height:20px" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer -->

<!-- wp:query {"queryId":9,"query":{"perPage":2,"pages":0,"offset":0,"postType":"post","order":"desc","orderBy":"date","author":"","search":"","exclude":[],"sticky":"","inherit":false,"taxQuery":{"category":[34]},"parents":[],"format":[]},"metadata":{"categories":["posts"],"patternName":"core/query-grid-posts","name":"Grid"}} -->
<div class="wp-block-query"><!-- wp:post-template {"className":"remove-bullets max-width vertical","layout":{"type":"grid","columnCount":2,"minimumColumnWidth":null}} -->
<!-- wp:columns -->
<div class="wp-block-columns"><!-- wp:column {"width":"25%"} -->
<div class="wp-block-column" style="flex-basis:25%"><!-- wp:post-featured-image {"isLink":true} /--></div>
<!-- /wp:column -->

<!-- wp:column {"width":"75%"} -->
<div class="wp-block-column" style="flex-basis:75%"><!-- wp:post-title {"level":3,"isLink":true,"style":{"color":{"text":"#000000"},"elements":{"link":{"color":{"text":"#000000"}}}}} /-->

<!-- wp:post-excerpt {"moreText":"\u003cbr\u003eRead more →"} /--></div>
<!-- /wp:column --></div>
<!-- /wp:columns -->
<!-- /wp:post-template -->

<!-- wp:spacer {"height":"20px"} -->
<div style="height:20px" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer -->

<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons"><!-- wp:button {"textAlign":"center","className":"is-style-animated-border"} -->
<div class="wp-block-button is-style-animated-border"><a class="wp-block-button__link has-text-align-center wp-element-button" href="https://beyond120.clas.ufl.edu/category/student-blog/">Read the Student Blog</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:query -->

<!-- wp:spacer {"height":"67px"} -->
<div style="height:67px" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer -->
```

## Tweaks the following
  - remove "Category" and "Tag" from before the category Title in archives
  - shortcode to dynamically update year in the footer
  - Disable core blocks.
  - Make it so regular Adminstrators don't wipe out iframes when editing pages
  - Add Google Analytics and Google Tag Manager to the Customizer
  - Add custom class to style the Gravity forms button
  - prevent long ig posts from corrupting social banner's layout in mobile
  - clean up button with underline style
  - remove weird paragraph from above the livewhale feed block
  - Make the WP standard details block visually pleasing

