<?php 
/**
 *
 * Generate shortcode to show twitter feeds plugin
 *
 * @package    BlossomThemes_Instagram_Feed
 * @subpackage BlossomThemes_Instagram_Feed/includes
 * @author    blossomthemes
 */
class BlossomThemes_Instagram_Feed_Shortcodes
{
	public function init(){
		add_shortcode( 'blossomthemes_instagram_feed', array( $this, 'display' ) );
	}

	function display(){

		if( is_admin() ) return;

		ob_start();

        $settings = BlossomThemes_Instagram_Feed_Settings::get_settings();
		$instaUrl = 'https://www.instagram.com/';
		$instaUrl .= $settings['username'];

		$this->api = Blossomthemes_Instagram_Feed_API::get_instance();

		$items = $this->api->get_items( $settings['photos'] );

		if ( ! is_array( $items ) ) {
			if ( current_user_can( 'edit_theme_options' ) ) {
				echo '<p>'.__( 'BlossomThemes Social Feed misconfigured, check plugin &amp; widget settings.',
                'blossomthemes-instagram-feed' ).'</p>';
            } else {
				echo '<b style="color:red;">'.__('No posts available!','blossomthemes-instagram-feed').'</b>';
			}
		} else {
			require plugin_dir_path( BTIF_FILE_PATH ) . 'public/partials/blossomthemes-instagram-feed-public-display.php';
		}

		$output = '<div class="btif-instagram-feed">' . ob_get_contents() . '</div>';
		ob_end_clean();
		return apply_filters( 'btif_instagram_shortcode_filter', $output );
	}
}
