<?php
$featured_img_url = get_the_post_thumbnail_url(get_the_ID(), 'full');
$post_date = get_the_date('l F j, Y');
$filter_date = get_the_date('Y');
$categories = get_the_category();
$hide_date = get_post_meta($post->ID, 'hide_date', true);

?>

<div class="col-sm-6 col-lg-4 mb-4 news-col <?php if (!empty($categories)) {
} ?> <?= 'year-'.$filter_date; ?>">
    <div class="card news-card">
        <a href="<?php the_permalink(); ?>" class="news-card-link" alt="..">
            <?php if ($featured_img_url) { ?>
                <img src="<?= $featured_img_url ?>" class="card-img-top" alt="...">
            <?php } ?>
            <div class="card-body">
                <h3 class="card-title"><?php the_title(); ?></h3>	<?php
                
                if ($hide_date !== "1") {
                   ?> <p class="card-date"><?php echo $post_date; ?></p><?php
        }
?>

				
                <p class="card-text"><?php the_excerpt(); ?></p>
            </div>
        </a>
    </div>
</div>
