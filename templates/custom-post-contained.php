<?php

    get_header(); ?>



    <main>

        <!-- Your custom post content structure here -->

        <?php while ( have_posts() ) : the_post(); ?>

            <article>

                <h2><?php the_title(); ?> Hello World</h2>

                <?php the_content(); ?>

            </article>

        <?php endwhile; ?>

    </main>



    <?php get_footer(); ?>