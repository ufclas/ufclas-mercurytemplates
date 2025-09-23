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
  - **ACF-Powered Header System**: Uses Advanced Custom Fields for flexible header content
    - Header Image field → Full-width background hero with left-aligned white text overlay
    - Header Title field → Custom title (falls back to post title if empty)
    - Header Subtitle field → Optional subtitle for both hero and simple headers
    - Image caption → Displays as caption text below title/subtitle on hero images
    - **Conditional Fields**: ACF fields only appear when "Full width article" template is selected
  - **Smart Display Logic**: Handles header scenarios automatically
    - Header image present → Full-width image with left-aligned white text (title, subtitle, caption) and dark gradient
    - No header image → Clean left-aligned header with light gray background (no orange underline)
    - Tight spacing between title and subtitle for better visual hierarchy
  - **Post Header Block Design**: Follows UFL design specifications
    - Professional typography with IBM Plex Sans font family
    - Left-aligned text layout for consistent visual flow
    - Responsive design with mobile-optimized breakpoints
    - UFL brand colors and styling consistency
  - **Enhanced Content Structure**: 
    - Post intro section with date/author in single line format (matches "No Sidebar Inc. Breadcrumbs")
    - Social sharing buttons in optimized single-row layout
    - Main content area with proper typography
    - Streamlined layout without post footer
  - **Related Posts Section**: "You might also like" at page end
    - Tag-based related posts logic (finds posts with shared tags)
    - Displays 3 newest related posts (excludes current post)
    - Uses exact ufclas-newsfeatures styling and layout
    - Bootstrap responsive card grid layout
  - **Full Functionality**: Includes all standard features
    - Show/hide dates, author, socials controls
    - Yoast Breadcrumbs integration
    - Social sharing buttons with forced horizontal layout
    - Responsive design for all devices
    - No dependency on WordPress featured images
    - Automatic ACF field registration (no manual setup required)


## Adds the following meta boxes

**Hide Elements**
Controls visibility of post elements for all templates:
  - Hide Date checkbox
  - Hide Author checkbox
  - Hide Featured Image checkbox

**Social Sharing**
Individual control over social sharing buttons on posts:
  - Facebook checkbox - Show/hide Facebook share button
  - Twitter checkbox - Show/hide Twitter share button
  - Email checkbox - Show/hide Email share button
  - LinkedIn checkbox - Show/hide LinkedIn share button
  - Bluesky checkbox - Show/hide Bluesky share button
  - Note: These work in conjunction with block-level controls (see Block Enhancements below)

**Post Header Fields** (ACF)
Advanced Custom Fields for header content:
  - Header Image: Upload background image for hero section
  - Header Title: Custom title text (falls back to post title)
  - Header Subtitle: Optional subtitle for simple headers
  - Uses image caption for highlighted excerpt section

## How to use the Full width article template

### Prerequisites
- Install and activate Advanced Custom Fields (ACF) plugin
- ACF fields are automatically registered when this plugin is active

### Usage Steps
1. **Create/Edit a Post**: Select "Full width article" from Post Attributes → Template
2. **Configure Header Content**: The "Post Header Fields" section will automatically appear:
   - **Header Image** (optional): Upload an image for full-width hero background
   - **Header Title** (optional): Enter custom title text, or leave blank to use post title
   - **Header Subtitle** (optional): Add subtitle text (displays on both hero and simple headers)
3. **Add Image Caption** (optional): If using header image, add caption text in the media library - this will appear as white caption text below the subtitle on the hero image
4. **Configure Visibility**: Use "Hide Elements" meta box to show/hide date, author, socials, etc.
5. **Publish**: Template automatically handles display logic and shows related posts at the end

### Header Display Logic
- **With Header Image**: Full-width background image with left-aligned white text (title, subtitle, caption) and dark gradient overlay
- **Without Header Image**: Clean left-aligned header with light gray background (no decorative elements)
- **Text Hierarchy**: Title → Subtitle → Caption (when image is used), with optimized spacing between elements

## Block Enhancements

### Post Intro Block Override
The plugin enhances the Post Intro Block with additional social sharing controls:

**Features:**
- **Social Sharing Toggle Controls**: Individual on/off toggles for each social platform in the block editor
- **Bluesky Integration**: Native Bluesky share button alongside ShareThis buttons
- **Two-Layer Control System**:
  - Post-level controls: Set in the Social Sharing meta box (posts only)
  - Block-level controls: Set in the block's inspector panel
  - For posts: Both levels must be enabled for buttons to appear
  - For pages: Only block-level controls apply (no post meta)
- **Smart Visibility Logic**: Buttons only appear when enabled at the appropriate level(s)
- **Responsive Design**: Maintains single-row layout with proper spacing

**How it Works:**
1. For posts: Social buttons require both post meta AND block settings to be enabled
2. For pages: Social buttons only require block settings (since pages don't have post meta)
3. The block shows a helpful note on posts reminding users to check both settings

## Administrator Restrictions

For enhanced security and simplified user experience, the following areas are restricted for non-superadmin users:

**Hidden Customizer Sections:**
- Site Identity settings
- Analytics Settings
- Alternate Logo sections
- Footer Logo options

**Restricted Widget Areas:**
- Global Alert (top-nav)
- Footer Link Columns 1-4
- Copyright widget area
- These areas are hidden in the Widgets admin but remain visible on the frontend

**Hidden Menu Items:**
- Themes submenu removed from Appearance menu for non-superadmins

**Benefits:**
- Prevents accidental changes to critical site elements
- Simplifies the admin interface for regular administrators
- Maintains site consistency and branding

## Adds the following helper classes

  - remove-bullets - add to query loops to remove bullets
  - remove-email - hides email address on the Faculty Bio block
  - responsive - stack all table cells for tables with class of "responsive" added
  - five-across - smaller thumbnails on galleries for galleries with a class of "five-across" added
  - top-align - class "top-align" for moving up cards
  - bullets - make a bulletted query loop list work


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
  - Remove "Category" and "Tag" from before the category Title in archives
  - Shortcode to dynamically update year in the footer (`[year]`)
  - Disable core block patterns
  - Make it so regular Administrators don't wipe out iframes when editing pages (unfiltered_html capability)
  - Add Google Analytics and Google Tag Manager fields to the Customizer
  - Add custom class to style Gravity Forms buttons with animated border style
  - Prevent long Instagram posts from corrupting social banner's layout in mobile
  - Clean up button with underline style appearance
  - Remove empty paragraph elements from above the LiveWhale feed block
  - Make the WP standard details block visually pleasing with custom styling
  - Reduce space on filter dropdown menus
  - Make break tags in menu items spaced properly for better readability
  - Hide submenus in the footer for cleaner navigation
  - Social sharing buttons with individual visibility controls and Bluesky support
  - Widget area restrictions for non-superadmin users (admin only, preserves frontend)
  - Full-width block quotes in article template for better visual impact
  - Customizer section restrictions for enhanced security
  - Themes menu access control for non-superadmins
