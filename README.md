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

**Full width article**

A comprehensive full-width template for articles that provides:
  - **Featured Image Overlay**: Uses WordPress featured image with optional text overlay
    - Media library title → Main heading (white text on image)
    - Media library caption → Subtitle (white text on image)  
    - Media library description → Caption text (white text on image)
    - Text positioned in lower-left with shadows for readability
  - **Title Hero Section**: When no featured image or hidden, displays UFL-styled hero section
    - Light gray background (#f4f6f6) matching UFL Block post header style
    - Centered title and subtitle with orange underline accent
    - Custom "Post Subtitle" meta box for subtitle text
  - **Smart Display Logic**: Handles all scenarios automatically
    - Featured image + overlay text → Full-width image with white text overlay
    - Featured image only → Full-width image, title/subtitle below
    - No featured image → UFL-styled title hero section above content
  - **Related Posts Section**: "You might also like" at page end
    - Tag-based related posts logic (finds posts with shared tags)
    - Displays 3 newest related posts (excludes current post)
    - Uses exact ufclas-newsfeatures styling and layout
    - Bootstrap responsive card grid layout
  - **Full Functionality**: Includes all standard features
    - Show/hide dates, author, socials, featured image
    - Yoast Breadcrumbs integration
    - Social sharing buttons
    - Responsive design for all devices


## Adds the following meta boxes

**Hide Elements**
Controls visibility of post elements for all templates:
  - Hide Date checkbox
  - Hide Author checkbox  
  - Hide Socials checkbox
  - Hide Featured Image checkbox

**Post Subtitle**
Adds subtitle field for posts:
  - Used by "Full width article" template when no featured image
  - Displays below main title in UFL-styled hero section
  - Optional field with placeholder text

## How to use the Full width article template

1. **Create/Edit a Post**: Select "Full width article" from Post Attributes → Template
2. **Set Featured Image**: Use WordPress "Set featured image" in post editor sidebar
3. **Add Image Overlay Text** (optional): 
   - Click "Edit" on your featured image
   - In media details popup, add:
     - **Title**: Main heading text (displays as white H1 on image)
     - **Caption**: Subtitle text (displays as white H2 on image)
     - **Description**: Caption text (displays as white paragraph on image)
4. **Add Post Subtitle** (optional): Use "Post Subtitle" meta box for subtitle when no featured image
5. **Configure Visibility**: Use "Hide Elements" meta box to show/hide date, author, socials, etc.
6. **Publish**: Template automatically handles display logic and shows related posts at the end

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

