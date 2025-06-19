<?php
/**
 * Single view Company information box
 *
 * Hooked into single_job_listing_start priority 30
 *
 * This template can be overridden by copying it to yourtheme/job_manager/content-single-job_listing-company.php.
 *
 * @see         https://wpjobmanager.com/document/template-overrides/
 * @author      Automattic
 * @package     wp-job-manager
 * @category    Template
 * @since       1.14.0
 * @version     1.32.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! get_the_company_name() ) {
	return;
}
?>
<div class="company">
	<?php the_company_logo(); ?>

	<?php if ( candidates_can_apply() ) : ?>
		<div class="company_apply">
			<?php get_job_manager_template( 'job-application.php' ); ?>
		</div>
	<?php endif; ?>

	<div class="company_contacts">
		<h5><?php esc_html_e( 'Contact Us', 'anesta' ); ?></h5>
		<ul>
			<?php
			$company_name = get_the_company_name();
			if ( !empty($company_name) ) {
				the_company_name( '<li>', '</li>' );
			}
			
			$company_twitter = get_the_company_twitter();
			if ( !empty($company_twitter) ) {
				the_company_twitter( '<li>', '</li>' );
			}

			if ( $website = get_the_company_website() ) { ?>
				<li class="website"><a href="<?php echo esc_url( $website ); ?>" rel="nofollow"><?php echo esc_url( anesta_remove_protocol_from_url($website) ); ?></a></li>
			<?php } ?>
		</ul>
	</div>
</div>
