<?php
/*
	Plugin Name: OWMS for WordPress
	
	Plugin URI: http://
	Description: Inserts OWMS metadata as meta tags into posts, pages and custom post types. Provides options screen in admin area. Based on the excellent "Dublin Core for WP"-plugin.
	
	Version: 0.1.1
	
	Author: Mark P. Lindhout
	
	Compatible: 2.9.2
	
	Tags: OWMS, Overheid Web Metadata Standaard, Overheid, Dublin Core, metadata
	
	Copyright 2011 Mark P. Lindhout  (email : info@langdradig.nl)
    
    The Wordpress OWMS plugin is released under the GNU General Public
	License (GPL) http://www.gnu.org/licenses/gpl2.txt
*/

/*
    Setup of the plugin
*/

// Load plugin text domain from the ./languages folder
load_plugin_textdomain( 'owms', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

include 'owms-terms.php';

/*
   Build the tables with general options (these apply to all posts)
   These values can either be used as settings that apply
   to all content, or as reasonable defaults for new content
*/

// Ensure records are in wp_options */
function owms_add_options() {
	
	// Make the array available inside this function.
	global $OWMS_terms;

	// Loop though the array and add options where neccesary.
	foreach ((array) $OWMS_terms as $key => $val ) {
			
			if ($val[4] == 'option' ) {
				add_option( $key );
			}
		
	}
	
}
owms_add_options();

/*
   Generate the HTML for the front-end
*/

// Write a meta tag if the content is non-empty.
function owms_writeMetaTag( $name, $content="", $scheme="" ) {

	if($scheme) { $scheme = 'scheme="' . $scheme . '" '; }
	
	if($content) {
		echo "\n\t";
		echo '<meta name="' . $name . '" content="' . $content . '" ' . $scheme . '/>';
	}

}

// Gather and output the OWMS metadata.
function owms_write_html_metadata() {

	global $wp_query, $post, $wpdb, $OWMS_terms;

	$wp_query->the_post(); //load the $post variable
	
	$wp_query->rewind_posts(); //reset so the loop doesn't freak out later on

	// Add a message that states where this info is coming from :)
	echo "\n\n\t" . '<!-- ' . __('Generated through OWMS for Wordpress (Mark Lindhout, 2011)', 'owms') . ' -->' . "\n";

	// First build the OWMS namespace profile <link>
	echo "\t" . '<link rel="schema.OVERHEID" href="http://standaarden.overheid.nl/owms/terms/" />' . "\n";
	echo "\t" . '<link rel="schema.XSD" href="http://www.w3.org/2001/XMLSchema#" />' . "\n";
	echo "\t" . '<link rel="schema.DC" href="http://purl.org/dc/elements/1.1/" />' . "\n";
	
	// Then build the OWMS  meta tags
	echo "\t" . '<!-- ' . __('OWMS Terms', 'owms') . ' -->';
	foreach ($OWMS_terms as $key=>$val ) {
	
		$hasmeta = get_post_meta($post->ID, $key, true);
		
		// Use the post's meta field if available...
		if ( $hasmeta != '' ) {
			owms_writeMetaTag( $val[1], get_post_meta($post->ID, $key, true), $val[5] );
		}
		// otherwise use the general default
		else {
			owms_writeMetaTag( $val[1], get_option( $key ), $val[5] );
		}

	}
	// some breaks for nice source formatting
	echo "\n\n";
}
add_action('wp_head', 'owms_write_html_metadata');


/*
    Administration Options Page
*/

// Build the options page
function owms_options_page() {
	global $wpdb, $OWMS_terms;
	$updated = false;
	
	// check for nonce
	if( wp_verify_nonce($_POST['owms_nonce_field'], 'owms_nonce_action') ) {
		
		foreach ($OWMS_terms as $key=>$val) {
			// only add 'options'.
			if ($val[4] == 'option') {
				update_option( $key, $_POST[ $val[0] ]);
			}

		}
		// set updated to true
		$updated = true;
	}	 

	// Display message only if the post is updated
	if ( $updated ) {
?>
			<div class="updated">
				<p><?php _e('The OWMS options were succesfully saved.', 'owms'); ?></p>
			</div>
<?php
	}
?>
			<div class="wrap">
				<h2><?php _e('OWMS for Wordpress', 'owms'); ?></h2>
				<form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
					<fieldset class="options">
					
						<h3><?php _e('Content defaults', 'owms'); ?></h3>
						
						<p><?php _e('Here you can set the site-wide OWMS default values. If a post has no OWMS values of its own, these defaults will be used instead.', 'owms'); ?></p>
						
						<h4><?php _e('OWMS Core', 'owms'); ?></h4>
						
						<table class="form-table">
							<tbody>
							
							<?php
								foreach( $OWMS_terms as $key=>$val ) {
									// Loop through the OWMS core options
									if ($val[2] == 'core') {
										if ($val[4] == 'option') {
							?>
									<tr valign="top">
										<th scope="row"><label for="<?php echo $val[0]; ?>"><?php echo $val[1]; ?></label></th>
										<td>
											<input name="<?php echo $val[0]; ?>" id="<?php echo $val[0]; ?>" type="text" value="<?php echo get_option( $key ); ?>" class="regular-text code" title="<?php echo $val[1]; ?>" />
										</td>
										<td>
											<span class="description"><?php echo $val[3]; ?></span>
										</td>
									</tr>
							<?php
										}
									}
								}
							?>
							
							</tbody>
						</table>
						
						<h4><?php _e('OWMS Extended', 'owms'); ?></h4>
						
						<table class="form-table">
							<tbody>

							<?php
								foreach( $OWMS_terms as $key=>$val ) {
									// Loop through the OWMS extended options
									if ($val[2] == 'extended') {
										if ($val[4] == 'option') {
							?>
									<tr valign="top">
										<th scope="row"><label for="<?php echo $val[0]; ?>"><?php echo $val[1]; ?></label></th>
										<td>
											<input name="<?php echo $val[0]; ?>" id="<?php echo $val[0]; ?>" type="text" value="<?php echo get_option( $key ); ?>" class="regular-text code" title="<?php echo $val[1]; ?>" />
										</td>
										<td>
											<span class="description"><?php echo $val[3]; ?></span>
										</td>
									</tr>
							<?php
										}
									}
								}
							?>

							</tbody>
						</table>																						
					</fieldset>
					
					<p class="submit">
						<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
					</p>

					<?php
						// Add a Nonce field to the form
						wp_nonce_field( 'owms_nonce_action', 'owms_nonce_field', true, true );
					?>	
				</form>
			</div>
<?php
}

/* Register the options page. */
function owms_admin_menu() {
	add_options_page( __('OWMS Options', 'owms'), __('OWMS', 'owms'), 'manage_options', basename(__FILE__), 'owms_options_page');
}
add_action('admin_menu', 'owms_admin_menu');

/*
    Add a custom meta box to the admin side for the OWMS meta-data
*/

// Create the meta box
function owms_create_metabox() {

	global $post, $OWMS_terms;

	// The nonce verification field
	wp_nonce_field('owms_nonce_action', 'owms_nonce_field');

	?>
	
		<table width="100%">
			<tbody>
				<tr>
				
					<td valign="top">
		
						<h4><?php _e('OWMS Core','owms'); ?></h4>
						
						<?php
							foreach ( $OWMS_terms as $key=>$val ) {
								// loop through the core term entries
								if ($val[2] == 'core') {
						?>
							<p><strong><?php echo $val[1]; ?></strong>: <?php echo $val[3]; ?></p>
							<label class="screen-reader-text" for="<?php echo $val[0]; ?>"><?php echo $val[1]; ?> <?php echo $val[3]; ?></label>
							<input type="text" id="<?php echo $val[0]; ?>" name="<?php echo $val[0]; ?>" value="<?php if( get_post_meta( $post->ID, $key, true) ) { echo get_post_meta( $post->ID, $key, true); } elseif ( get_option( $key ) ) { echo get_option( $key ); } ?>" size="25" style="width: 90%;" />
						<?php
								}
							}
						?>
			
					</td>
					
					<td valign="top">

						<h4><?php _e('OWMS Extended','owms'); ?></h4>

						<?php
							foreach ( $OWMS_terms as $key=>$val ) {
								// loop through the extended term entries
								if ($val[2] == 'extended') {
						?>
							<p><strong><?php echo $val[1]; ?></strong>: <?php echo $val[3]; ?></p>
							<label class="screen-reader-text" for="<?php echo $val[0]; ?>"><?php echo $val[1]; ?> <?php echo $val[3]; ?></label>
							<input type="text" id="<?php echo $val[0]; ?>" name="<?php echo $val[0]; ?>" value="<?php if( get_post_meta( $post->ID, $key, true) ) { echo get_post_meta( $post->ID, $key, true); } elseif ( get_option( $key ) ) { echo get_option( $key ); } ?>" size="25" style="width: 90%;" />
						<?php
								}
							}
						?>
			
					</td>
				</tr>
			</tbody>
		</table>
<?php }

// Adds a box to the main column on the edit screen
function owms_add_metabox() {

	// Add a meta box for each public post type, including sutom ones.
	foreach( get_post_types( array( 'public' => true ) ) as $post_type ) {
		add_meta_box( 'owms_metabox', __( 'OWMS Meta-data', 'owms' ), 'owms_create_metabox', $post_type, 'normal', 'default' );
	}

}
add_action('add_meta_boxes', 'owms_add_metabox');

// When the post is saved, saves our custom data
function owms_save_postdata( $post_id ) {

	global $OWMS_terms;

	// verify this came from the our screen and with proper authorization, because save_post can be triggered at other times
	if ( !wp_verify_nonce( $_POST['owms_nonce_field'], 'owms_nonce_action' ) ) {
		return $post_id;
	}

	// verify if this is an auto save routine. If it is our form has not been submitted, so we dont want to do anything
	if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) {
		return $post_id;
	}
	
	// Check permissions
	if ( !current_user_can( 'edit_post', $post_id ) ) {
		return $post_id;
	}

	// OK, we're authenticated: we need to find and save the data
	foreach ( $OWMS_terms as $key=>$val ) {
		if ( isset( $_POST[$val[0]] ) ) {
			update_post_meta( $post_id, $key, $_POST[ $val[0] ] );
		}
	}
	update_post_meta( $post_id, '_owms_dc_identifier', $_POST['owms_dc_identifier'] );

}
add_action('save_post', 'owms_save_postdata');
