<?php
/**
 * Generic page template — used for any Page that isn't the Home
 * (front-page.php) or Contact (page-contact.php) layout.
 *
 * @package trueview-watchtower
 */

get_header();
?>

<main class="tv-content">
<?php
while ( have_posts() ) :
	the_post();
	?>
	<h1 class="tv-content__title"><?php the_title(); ?></h1>
	<div class="tv-content__body">
		<?php the_content(); ?>
	</div>
	<?php
endwhile;
?>
</main>

<?php get_footer(); ?>
