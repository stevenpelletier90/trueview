<?php
/**
 * Fallback template — required by WordPress. Handles anything not covered
 * by a more specific template (archives, search, blog index, 404s).
 *
 * @package trueview-watchtower
 */

get_header();
?>

<main class="tv-content">
<?php if ( have_posts() ) : ?>
	<?php
	while ( have_posts() ) :
		the_post();
		?>
		<article class="tv-post">
			<h2 class="tv-post__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
			<div class="tv-post__excerpt"><?php the_excerpt(); ?></div>
		</article>
		<?php
	endwhile;
	?>
	<div class="tv-pagination"><?php the_posts_pagination(); ?></div>
<?php else : ?>
	<h1 class="tv-content__title">Nothing found</h1>
	<p class="tv-content__body">The page you're looking for isn't here. <a class="tv-link" href="<?php echo esc_url( home_url( '/' ) ); ?>">Return home</a>.</p>
<?php endif; ?>
</main>

<?php get_footer(); ?>
