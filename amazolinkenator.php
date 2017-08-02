<?php
/*
	Plugin Name: AmazoLinkenator
	Plugin URI: http://cellarweb.com/wordpress-plugins/
	Description: Automatically adds your Amazon Affiliate code to any Amazon link in posts/pages/comments. Optionally shortens those URLs. Adds the affiliate code and shortens on content save, even in visitor comments. Shows count of affiliate codes inserted.
	Text Domain: 
	Author: Rick Hellewell / CellarWeb.com
	Version: 2.10
	Stable tag: 2.10
	Tested up to: 4.8
	Author URI: http://CellarWeb.com
	License: GPLv2 or later
*/

/*
Copyright (c) 2015 by Rick Hellewell and CellarWeb.com
All Rights Reserved

		email: rhellewell@gmail.com

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License, version 2, as
	published by the Free Software Foundation.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA02110-1301USA
	
*/
// ----------------------------------------------------------------
$amazo_version = "Version 2.10 (1 June 2017)";


 function AmazoLinkenator_settings_link($links) { 
	$settings_link = '<a href="options-general.php?page=AmazoLinkenator_settings" title="AmazoLinkenator">AmazoLinkenator Settings</a>'; 
	array_unshift($links, $settings_link); 
	return $links; 
	}
	$plugin = plugin_basename(__FILE__); 
	
	add_filter("plugin_action_links_$plugin", 'AmazoLinkenator_settings_link' );

//	build the class for all of this 
class AmazoLinkenator_Settings_Page
{
 	// Holds the values to be used in the fields callbacks
	private $options;
	// start your engines!
	public function __construct()
	{
		add_action( 'admin_menu', array( $this, 'AmazoLinkenator_add_plugin_page' ) );
		add_action( 'admin_init', array( $this, 'AmazoLinkenator_page_init' ) );
	}

	// add options page
	public function AmazoLinkenator_add_plugin_page()
	{
		// This page will be under "Settings"
		add_options_page(
			'AmazoLinkenator Settings Admin', 
			'AmazoLinkenator Settings', 
			'manage_options', 
			'AmazoLinkenator_settings', 
			array( $this, 'AmazoLinkenator_create_admin_page' )
		);
	}

 // options page callback
	public function AmazoLinkenator_create_admin_page()
	{
		// Set class property
		$this->options = get_option( 'AZLNK_options' );
		?>

<div class="wrap">
	<?php AmazoLinkenator_info_top(); ?>
	<form method="post" action="options.php">
		<?php
						// This prints out all hidden setting fields
						settings_fields( 'AmazoLinkenator_option_group' ); 
						do_settings_sections( 'AmazoLinkenator_setting_admin' );
						submit_button(); 
					?>
	</form>
<?php
		$AZLNK_bitly_token = $AZLNK_options['AZLNK_bitly_token'];
	AZLNK_show_test_button($AZLNK_bitly_token);
	 AmazoLinkenator_info_bottom();		// display bottom info stuff
					?>
</div>
<?php
	}

	// Register and add the settings
	public function AmazoLinkenator_page_init()
	{		
		register_setting(
			'AmazoLinkenator_option_group', // Option group
			'AZLNK_options', // Option name
			array( $this, 'AmazoLinkenator_sanitize' ) // Sanitize
		);

		add_settings_section(
			'AmazoLinkenator_setting_section_id', // ID
			'', // Title
			array( $this, 'AmazoLinkenator_print_section_info' ), // Callback
			'AmazoLinkenator_setting_admin' // Page
		);

		add_settings_field(
			'AZLNK_affiliate_key', 
			'Your Amazon Affiliate Key', 
			array( $this, 'AZLNK_affiliate_key_callback' ), 
			'AmazoLinkenator_setting_admin', 
			'AmazoLinkenator_setting_section_id', // Section		 
			array('fieldtype' => 'input', 'fieldsize' => '50', 'fieldmax' => '50')
		);	

		add_settings_field(
			'AZLNK_enable_affiliator_posts', 
			'Enable AmazoLinkenator for post/page content?', 
			array( $this, 'AZLNK_enable_affiliator_posts_callback' ), 
			'AmazoLinkenator_setting_admin', 
			'AmazoLinkenator_setting_section_id', // Section		 
			array('fieldtype' => 'checkbox', 'fieldsize' => null, 'fieldmax' => null )
		);	

		add_settings_field(
			'AZLNK_AZLNK_enable_comments', 
			'Enable AmazoLinkenator for Comments?', 
			array( $this, 'AZLNK_enable_comments_callback' ), 
			'AmazoLinkenator_setting_admin', 
			'AmazoLinkenator_setting_section_id', // Section		 
			array('fieldtype' => 'checkbox', 'fieldsize' => null, 'fieldmax' => null )
		);	

		add_settings_field(
			'AZLNK_auto_shorten', 
			'Enable automatic shortening of the URL? ', 
			array( $this, 'AZLNK_auto_shorten_callback' ), 
			'AmazoLinkenator_setting_admin', 
			'AmazoLinkenator_setting_section_id', // Section		 
			array('fieldtype' => 'text', 'fieldsize' => null, 'fieldmax' => null )
		);	

		add_settings_field(
			'AZLNK_bitly_token', 
			'Enter your Bit.ly Generic Access Token', 
			array( $this, 'AZLNK_bitly_token_callback' ), 
			'AmazoLinkenator_setting_admin', 
			'AmazoLinkenator_setting_section_id', // Section		 
			array('fieldtype' => 'input', 'fieldsize' => '50', 'fieldmax' => '50')
		);	

		add_settings_field(
			'AZLNK_donate_flag', 
			'Check to donate', 
			array( $this, 'AZLNK_donate_flag_callback' ), 
			'AmazoLinkenator_setting_admin', 
			'AmazoLinkenator_setting_section_id', // Section		 
			array('fieldtype' => 'input', 'fieldsize' => '50', 'fieldmax' => '50')
		);	

		add_settings_field(
			'AZLNK_donate_counter', 
			'Affiliate Donated Counter', 
			array( $this, 'AZLNK_donate_counter_callback' ), 
			'AmazoLinkenator_setting_admin', 
			'AmazoLinkenator_setting_section_id', // Section		 
			array('fieldtype' => 'input', 'fieldsize' => '50', 'fieldmax' => '50')
		);	

		add_settings_field(
			'AZLNK_affiliate_counter', 
			'Affiliate Links Inserted', 
			array( $this, 'AZLNK_affiliate_counter_callback' ), 
			'AmazoLinkenator_setting_admin', 
			'AmazoLinkenator_setting_section_id', // Section		 
			array('fieldtype' => 'input', 'fieldsize' => '50', 'fieldmax' => '50')
		);	

	}

	// sanitize the settings fields on submit
	// 	@param array $input Contains all settings fields as array keys
	public function AmazoLinkenator_sanitize( $input )
	{
		global $AZLNK_affiliate_counter;
		
		$new_input = array();
		if( isset( $input['AZLNK_affiliate_key'] ) )
			$new_input['AZLNK_affiliate_key'] = sanitize_text_field( $input['AZLNK_affiliate_key'] );

		if( isset( $input['AZLNK_enable_comments'] ) ) 
			$new_input['AZLNK_enable_comments'] = "1";

		if( isset( $input['AZLNK_enable_affiliator_posts'] ) ) 
			$new_input['AZLNK_enable_affiliator_posts'] = "1";

		if( isset( $input['AZLNK_auto_shorten'] ) ) 
			$new_input['AZLNK_auto_shorten'] = "1";

		if( isset( $input['AZLNK_donate_flag'] ) ) 
			$new_input['AZLNK_donate_flag'] = "1";

		if( isset( $input['AZLNK_donate_counter'] ) ) 
			$new_input['AZLNK_donate_counter'] = absint($input['AZLNK_donate_counter']);

		if( isset( $input['AZLNK_affiliate_counter'] ) ) 
			$new_input['AZLNK_affiliate_counter'] = absint($input['AZLNK_affiliate_counter']);

		if( isset( $input['AZLNK_bitly_token'] ) ) 
			$new_input['AZLNK_bitly_token'] = sanitize_text_field( $input['AZLNK_bitly_token'] );

		return $new_input;
	}

	// print the Section text
	public function AmazoLinkenator_print_section_info()
	{
		print '<h3><strong>Settings for AmazoLinkenator</strong></h3>';
		print '<p>Save your settings once after upgrading to the latest version.</p>';
	}

	// api key callback
	public function AZLNK_affiliate_key_callback()
	{
		printf(
			'<table><tr><td><input type="text" id="AZLNK_affiliate_key" name="AZLNK_options[AZLNK_affiliate_key]" size="50"maxlength="50" value="%s" ></td><td valign="top">Enter Your Amazon Affiliate Key. <em>Make sure it is correct; it is not validated</em>. </td></tr></table>',
			isset( $this->options['AZLNK_affiliate_key'] ) ? esc_attr( $this->options['AZLNK_affiliate_key']) : ''
		);
	}
	
	// content checkbox callback
	public function AZLNK_enable_affiliator_posts_callback()
	{
		printf(
			"<table><tr><td><input type='checkbox' id='AZLNK_enable_affiliator_posts' name='AZLNK_options[AZLNK_enable_affiliator_posts]' value='1' " . checked( '1', $this->options[AZLNK_enable_affiliator_posts] , false ) . " /></td><td valign='top'>Check if you want to Affiliate the Amazon URLs in page/post content. URLs are affiliated only on content save/update.</td></tr></table> ",
			isset($this->options['AZLNK_enable_affiliator_posts'] ) ?'1' : '0'
		);
	}
	
	// comment checkbox callback
	public function AZLNK_enable_comments_callback()
	{
		printf(
			"<table><tr><td><input type='checkbox' id='AZLNK_enable_comments' name='AZLNK_options[AZLNK_enable_comments]' value='1' " . checked( '1', $this->options[AZLNK_enable_comments] , false ) . " /></td><td valign='top'>Check if you want to Affiliate the Amazon URLs in post comments. URLs in comments are only affiliated when the comment is saved or updated.</td></tr></table> ",
			isset($this->options['AZLNK_enable_comments'] ) ?'1' : '0'
		);
	}
	
	// comment checkbox callback
	public function AZLNK_auto_shorten_callback()
	{
		printf(
			"<table><tr><td valign='top'><input type='checkbox' id='AZLNK_auto_shorten' name='AZLNK_options[AZLNK_auto_shorten]' value='1' " . checked( '1', $this->options[AZLNK_auto_shorten] , false ) . " /></td><td valign='top'>Check if you want to automatically shorten the URL (great for 'hiding' your Affiliate codes). URLs are only shortened when a post/page/comment is saved or updated. Requires you to set up an Bit.ly Application here <a href='https://bitly.com/a/create_oauth_app' target='_blank'>https://bitly.com/a/create_oauth_app</a> , and then get a 'Generic Access Token' here <br><a href='https://bitly.com/a/oauth_apps' target='_blank'>https://bitly.com/a/oauth_apps</a>. <strong>Enter your Bit.ly Generic Access Token below</strong>. You can check any shortened URLs <a href='https://affiliate-program.amazon.com/gp/associates/network/tools/link-checker/main.html' target='_blank'>here</a> for a valid affiliate code.</td></tr></table> ",
			isset($this->options['AZLNK_auto_shorten'] ) ?'1' : '0'
		);
	}
	
	// comment checkbox callback
	public function AZLNK_bitly_token_callback()
	{
		printf(
			'<table><tr><td><input type="text" id="AZLNK_bitly_token" name="AZLNK_options[AZLNK_bitly_token]" size="50"maxlength="50" value="%s" ></td><td valign="top">Enter Your Bit.ly Generic Access Token. Invalid token codes will not shorten the URL. See above for info on getting your token.<br><em>Check your code with the Validate button below (after saving changes)</em>. </td></tr></table>',
			isset( $this->options['AZLNK_bitly_token'] ) ? esc_attr( $this->options['AZLNK_bitly_token']) : ''
		);
	}
	// donate flag callback
	public function AZLNK_donate_flag_callback()
	{
		printf(
			"<table><tr><td><input type='checkbox' id='AZLNK_donate_flag' name='AZLNK_options[AZLNK_donate_flag]' value='1' " . checked( '1', $this->options[AZLNK_donate_flag] , false ) . " /></td><td valign='top'>Check if you allow us to use our Affiliate tag every 100 links - this helps support our plugin efforts.</td></tr></table> ",
			isset($this->options['AZLNK_donate_flag'] ) ?'1' : '0'
		);
	}
	
	// donate counter
	public function AZLNK_donate_counter_callback()
	{
		printf(
			"<table><tr><td><input type='text'id='AZLNK_donate_counter' name='AZLNK_options[AZLNK_donate_counter]' readonlyvalue='" . number_format($this->options[AZLNK_donate_counter]) . "' style='text-align:right' /></td><td valign='top'> Count of how many times you let us use our Affiliate code - <strong>thanks for your support!</strong> </td></tr></table> ",
			isset( $this->options['AZLNK_donate_counter'] ) ? esc_attr( $this->options['AZLNK_donate_counter']) : ''
		);
	}
		// affiliate counter
	public function AZLNK_affiliate_counter_callback()
	{
		printf(
			"<table><tr><td><input type='text' readonly id='AZLNK_affiliate_counter' name='AZLNK_options[AZLNK_affiliate_counter]' value='" . number_format($this->options[AZLNK_affiliate_counter]) . "' style='text-align:right' /></td><td valign='top'> Count of your affiliate links added.</strong> </td></tr></table> ",
			isset( $this->options['AZLNK_affiliate_counter'] ) ? esc_attr( $this->options['AZLNK_affiliate_counter']) : ''
		);
	}

}
// end of the class stuff

if( is_admin() ) {
	
		$my_settings_page = new AmazoLinkenator_Settings_Page();
	// closing bracket after credits
	
	// ---------------------------------------------------------------------------- 
	// supporting functions
	// ---------------------------------------------------------------------------- 
	//	display the top info part of the page	
	// ---------------------------------------------------------------------------- 
	function AmazoLinkenator_info_top() {
		global $amazo_version;
		$image2 = plugin_dir_url( __FILE__ ) . '/assets/banner-772x250.png';
			?>
<div class="wrap">
	<h2></h2>
	<!-- placeholder for WP admin messages -->
	
	<div style="background-color:#9FE8FF;height:auto;padding:15px !important ;">
		<h1 align='center' style="font-size:300%">AmazoLinkenator</h1>
		<h2 align='center'>Adds your Amazon Affiliate Link to all Amazon product links, in posts/pages/comments. </h2>
		<p align='center'>from <a href="http://cellarweb.com" target="_blank">CellarWeb.com</a></p>
		<p> <?php echo $amazo_version; ?></p>
	</div>
	<hr />
	<div style="background-color:#9FE8FF;height:auto;padding:10px 15px !important ;">
		<p><strong>AmazoLinkenator</strong> will automatically (without any effort on your part) use your Amazon Affiliate code in any Amazon URLs. This will happen for pages, posts, and comments (depending on your settings below). Because you don't have to do anything special to affiliate a URL, this plugin is great for sites with lots of authors - especially those that might try to sneak in their Affiliate codes (which can deprive you of your Affiliate revenue).</p>
		<p>And no special steps are needed for any Amazon link. Just paste the Amazon product link in the page/post, and Publish. Your Amazon Affiliate link will automatically be added to the product URL.</p>
		<p>And <strong>AmazoLinkenator</strong> also works with any Amazon product links that your site commenters might add. If anyone includes a link in their comment that has their Amazon Affiliate code, it will be replaced with your affiliate code. All automatically! (It's your site, so you should get the Affiliate revenue!)</p>
		<p>URLs are affiliated only when posts/pages/comments are saved/updated/submitted. Any prior content will have your affiliate code if it is re-saved. A counter on the Settings screen shows the number of times that your Affiliate code is inserted in Amazon URLs.</p>
		<p>All you need is a valid Amazon Affiliate Key; start <a href='https://affiliate-program.amazon.com/gp/associates/network/main.html'target='_blank'>here</a>. There is no check for a valid Amazon Affiliate key. </p>
		<p>Plus, there's an option to automatically shorten the affiliate URL. This is great for 'hiding' your Amazon Affilliate link code. You just need a free Bit.ly Generic Access Token (<a href="https://bitly.com/a/create_oauth_app" target="_blank">start here</a>). Use the Validate button below to for a valid Bit.ly API Key.</p>
	</div>
	<hr />
	<div style="background-color:#9FE8FF;padding:3px 8px 3px 8px;">
		<p><strong>Interested in a plugin that will stop comment spam? Without using captchas, hidden fields, or other things that just don't work (or are irritating)? &nbsp;&nbsp;&nbsp;Check out our nifty <a href="https://wordpress.org/plugins/formspammertrap-for-comments/" target="_blank">FormSpammerTrap for Comments</a>!&nbsp;&nbsp;It just works!</strong></p>
	</div>
	<hr>
	<!--<p><strong>These options are available:</strong></p>--> 
</div>
<?php 
		return;
	}
	// ---------------------------------------------------------------------------- 
	// display the bottom info part of the page
	// ---------------------------------------------------------------------------- 
	function AmazoLinkenator_info_bottom() {
		// print copyright with current year, never needs updating
		$xstartyear = "2014";
		$xname = "Rick Hellewell";
		$xcompanylink1 = ' <a href="http://CellarWeb.com" title="CellarWeb" >CellarWeb.com</a>';
		echo '<hr><div style="background-color:#9FE8FF !important; padding:10px;color:black !important; ">Copyright &copy; ' . $xstartyear . '- ' . date("Y") . ' by ' . $xname . ' and ' . $xcompanylink1 ;
		echo ' , All Rights Reserved. Released under GPL2 license.<br>This plugin, authors, and/or CellarWeb.com has no relationship to, or is not sponsored by, or is not approved by, Amazon or Bit.ly, other than being an Amazon Affiliate. Amazon has their own copyrights; so does Bit.ly</div><hr>';
		return; 
	}
	// endcopyright ---------------------------------------------------------
	
	// ---------------------------------------------------------------------------- 
	// ``end of admin area
	// ---------------------------------------------------------------------------- 
}		// closing bracket for info/credits is_admin section

	// ---------------------------------------------------------------------------- 
	// start of operational area that changes the comments box stuff 		
	// ---------------------------------------------------------------------------- 
	
	$AZLNK_options = get_option( 'AZLNK_options' );
	$AZLNK_affiliate_key = $AZLNK_options['AZLNK_affiliate_key'];
	$AZLNK_donate_counter = $AZLNK_options['AZLNK_donate_counter'];
	$AZLNK_affiliate_counter = $AZLNK_options['AZLNK_affiliate_counter'];
	
	// set up the filters to process things based on the options settings
	
	// preprocess comment after submitted to Affiliate URLs if enabled
	if ($AZLNK_options['AZLNK_enable_comments']) {
		add_filter( 'preprocess_comment' , 'AZLNK_url_affiliate_comment',11 ); 
	}
	
	// preprocess posts/pages after submitted to Affiliate URLs if enabled
	if ($AZLNK_options['AZLNK_enable_affiliator_posts']) {
		add_filter( 'wp_insert_post_data', 'AZLNK_url_affiliate_post', '11', 2 );
	}
	// ---------------------------------------------------------------------------- 
	// end of add_actions and add_filters for posts/pages with comments open
	// ---------------------------------------------------------------------------- 
	
	// ---------------------------------------------------------------------------- 
	// here's where we do the work!
	// ---------------------------------------------------------------------------- 
	
	function AZLNK_url_affiliate_comment ($commentdata ) {	// Affiliate URLs in comments
	
		unset( $commentdata['comment_author_url'] );
		$AZLNK_text = $commentdata['comment_content'];
		$AZLNK_affiliated= AZLNK_find_the_urls($AZLNK_text);
		$commentdata['comment_content']= $AZLNK_affiliated;
		return $commentdata; }
		
	function AZLNK_url_affiliate_post ($data , $postarr) {	// Affiliate URLs in posts and pages
		$AZLNK_text = $data['post_content'];
		// do the work, then make all links clickable
		$data['post_content']= make_clickable(AZLNK_find_the_urls($AZLNK_text));
		return$data; }
		
	// ---------------------------------------------------------------------------- 
	// affiliate the urls
	function AZLNK_find_the_urls( $AZLNK_text ) {
		global $AZLNK_options; 
		$AZLNK_options_array = $AZLNK_options;
		$AZLNK_bitly_token = $AZLNK_options['AZLNK_bitly_token'];	
		$AZLNK_shorten_flag = $AZLNK_options['AZLNK_auto_shorten'];
		$AZLNK_affiliate_key = $AZLNK_options['AZLNK_affiliate_key'];
		// add space to beginning/end of the text to ensure affiliation if very-first/very-last string is a URL (ver 1.10a)
		$AZLNK_text = " " . $AZLNK_text . ' ' ; 
		// regex to find all types of urls in the text
		// this regex was used prior to version 1.12, but didn't work with umlauts
		// $AZLNK_regex = "/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i" ;
// version 1.12 to allow for umlauts (from http://stackoverflow.com/questions/38414371/replace-all-urls-in-text-to-clickable-links-in-php )
		$AZLNK_regex ='@(http(s)?://)?(([a-zA-Z])([-\w]+\.)+([^\s\.]+[^\s]*)+[^,.\s])@';
		preg_match_all($AZLNK_regex, $AZLNK_text, $AZLNK_urls_array) ;// put found urls in the $AZLNK_urls_array array
		// extract the elements of the first element to get a one-dimension array
		$AZLNK_urls_array = $AZLNK_urls_array[0]; 	
		$AZLNK_urls_array = array_unique($AZLNK_urls_array); // remove duplicate URLs
		foreach ($AZLNK_urls_array as $AZLNK_url_item) {
			// check if amzn.to , if so, reset $AZLNK_url_item to uncompressed version
			// urlencode to handle funny chars like umlauts
			$AZLNK_host = AZLNK_getHOst($AZLNK_url_item);
			if ( strpos($AZLNK_host, 'amazn.to')) {
				$AZLNK_url_item =AZLNK_bitly_expand_2016($url);
				$AZLNK_host = AZLNK_getHOst($AZLNK_url_item); // reset the host name after uncomplress
			}
			if ( strpos($AZLNK_host, 'amazon')) {
				if (strpos($AZLNK_text, $AZLNK_url_item)) {
					$AZLNK_affiliated_url = AZLNK_add_affiliate($AZLNK_url_item) ;
					$AZLNK_affiliated_url = str_replace('??','?', $AZLNK_affiliated_url);
					if ($AZLNK_shorten_flag == 1) {
						$AZLNK_new_url =AZLNK_bitly_url_shorten($AZLNK_affiliated_url, $AZLNK_bitly_token);
					} else {$AZLNK_new_url = $AZLNK_affiliated_url; }
					if (is_null($AZLNK_new_url)) {return $AZLNK_text ; } 	// some sort of error, so leave it alone
					$AZLNK_new_url = esc_url_raw($AZLNK_new_url) ; 	// sanitize, just in case
					$AZLNK_text = str_replace($AZLNK_url_item,$AZLNK_new_url,$AZLNK_text);
							}
			}
		}
		return $AZLNK_text ;	
	}
	
	// ---------------------------------------------------------------------------- 
	//	atick in the affiliate value
	function AZLNK_add_affiliate($url) {
		global $AZLNK_options; 
		
		$AZLNK_options_array = $AZLNK_options;
		$AZLNK_affiliate_key = $AZLNK_options['AZLNK_affiliate_key'];
		$AZLNK_bitly_token = $AZLNK_options['AZLNK_bitly_token'];
		$AZLNK_donate_counter = $AZLNK_options['AZLNK_donate_counter'];
		$AZLNK_affiliate_counter = $AZLNK_options['AZLNK_affiliate_counter'];
		$AZLNK_donate_flag = $AZLNK_options['AZLNK_donate_flag']; 

		$url = urldecode($url);		
		// set up the replace tag; check if time for the 'donate' tag (if enabled)
		if ( ($AZLNK_affiliate_counter % 100 == 0 )AND ($AZLNK_donate_flag == true ) ) {
			$AZLNK_donate_counter ++ ;
			$mod = array("tag" => "azlinkplugin-20"); 
			$tag = "azlinkplugin-20";
		} else
			{
			$mod = array("tag" => $AZLNK_affiliate_key); 
			$tag =$AZLNK_affiliate_key; 
			}
		// new function to replace the tag (version 1.10)
		$AZLNK_newURL = html_entity_decode(AZLNK_fix_the_query($url, $tag));
		// update the counters that are shown in the options
		$AZLNK_affiliate_counter ++;
		$AZLNK_options['AZLNK_donate_counter'] = $AZLNK_donate_counter;
		$AZLNK_options['AZLNK_affiliate_counter']= $AZLNK_affiliate_counter;
		$AZLNK_xresults = update_option("AZLNK_options", $AZLNK_options);

		return $AZLNK_newURL; 
	}
	// ---------------------------------------------------------------------------- 
	// from http://www.sanwebe.com/2013/07/shorten-urls-bit-ly-api-php
	
	function AZLNK_bitly_url_shorten($AZLNK_long_URL, $AZLNK_access_token)
	{
	$url = 'https://api-ssl.bitly.com/v3/shorten?access_token='.$AZLNK_access_token.'&longUrl='.urlencode($AZLNK_long_URL);
	try {
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_TIMEOUT, 4);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		$output = json_decode(curl_exec($ch));
	} catch (Exception $e) {
	}
	if(isset($output)){return $output->data->url;} else {return $AZLNK_long_URL . " fail"; }
	}
// ---------------------------------------------------------------------------- 
// expand the bitly url
function AZLNK_bitly_expand_2016($url) 
{
 $ch = curl_init($url);
curl_setopt($ch,CURLOPT_HEADER,true); // Get header information
curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION,false);
$header = curl_exec($ch);

$fields = explode("\r\n", preg_replace('/\x0D\x0A[\x09\x20]+/', ' ', $header)); // Parse information

for($i=0;$i<count($fields);$i++)
{
if(strpos($fields[$i],'Location') !== false)
{
$url = str_replace("Location: ","",$fields[$i]);
}
}
	return $url;
}
// ---------------------------------------------------------------------------- 

	// gets the host name from any url even if a partial host or without protocol
	function AZLNK_getHost($url) {
		$parseUrl = parse_url(trim($url));
				//echo $parseUrl['host']; die();
		return trim($parseUrl['host']);
		}

// ---------------------------------------------------------------------------- 
function AZLNK_unparse_url($parsed_url) {		// from first example in http://php.net/manual/en/function.parse-url.php
	$scheme = isset($parsed_url['scheme']) ? $parsed_url['scheme'] . '://' : '';
	$host = isset($parsed_url['host']) ? $parsed_url['host'] : '';
	$port = isset($parsed_url['port']) ? ':' . $parsed_url['port'] : '';
	$user = isset($parsed_url['user']) ? $parsed_url['user'] : '';
	$pass = isset($parsed_url['pass']) ? ':' . $parsed_url['pass']: '';
	$pass = ($user || $pass) ? "$pass@" : '';
	$path = isset($parsed_url['path']) ? $parsed_url['path'] : '';
	$query= isset($parsed_url['query']) ? '?' . $parsed_url['query'] : '';
	$fragment = isset($parsed_url['fragment']) ? '#' . $parsed_url['fragment'] : '';
	return "$scheme$user$pass$host$port$path$query$fragment";
}
// ---------------------------------------------------------------------------- 
function AZLNK_fix_the_query($url, $tagvalue){
	$url = html_entity_decode($url);
	$url1 = add_query_arg('tag', false, $url); // make sure any tags are removed
	$url2 = add_query_arg( 'tag', $tagvalue, $url1 );	// add the new tag value
return $url2; 
}
	// ----------------------------------------------------------------------------
function AZLNK_show_test_button($AZLNK_bitly_token) {
//	AZLNK_show_test_button($AZLNK_bitly_token);
	?>
<hr />
<h2>Click this button to test for valid Bit.ly API Key (Save Changes first!)</h2>
<form method="post" name="testsettings" id="testsettings" action="" >
	<input type="submit" name="testsettings" id="testsettings" value="Validate Bit.ly API Key" style="background-color:yellow;"/>
</form>
<?php
	 if ($_POST[testsettings] == 'Validate Bit.ly API Key') {
		 AZLNK_token_test();
		 //wp_die();
	 }
return; }	
	// ----------------------------------------------------------------------------
// testing routine/output
function AZLNK_token_test() {
	global $AZLNK_options;
		$AZLNK_bitly_token = $AZLNK_options['AZLNK_bitly_token'];
	$url_to_smash = "http://www.cellarweb.com";
	$smashed_url = AmazoLinkenator_do_url_smash($url_to_smash, $AZLNK_bitly_token);
?>
<hr />
<div style="background-color:#9FE8FF;height:auto;padding:5px 15px;">
	<h2>Results of Bit.ly API Key Validation Test</h2>
	<hr />
	<p><strong>URL before Smashing:</strong> <?php echo make_clickable($url_to_smash);?></p>
	<p><strong>URL after Smashing :</strong> <?php echo make_clickable($smashed_url);?></p>
	<?php
	if (AZLNK_getHost($smashed_url) == "bit.ly") {
		echo "<h3><strong>Huzzah! Valid Bitly API Key found!</strong> (Hopefully, it's yours!)</h3>";
	}
	else {echo "<h3><strong>Bummer! Not a valid Bitly API Key!</strong> Check your Bit.ly Generic Access Token <a href=\"https://bitly.com/a/create_oauth_app\" target=\"_blank\">here</a>.</h3>";
	}
?>
</div>
<?php
return $results; 
}
	// ----------------------------------------------------------------------------
	/* shorten URL with the bit.ly API key
		from http://www.sanwebe.com/2013/07/shorten-urls-bit-ly-api-php */

	function AmazoLinkenator_do_url_smash($url_smasher_long_url, $url_smasher_access_token)
	{
	  $url = 'https://api-ssl.bitly.com/v3/shorten?access_token='.$url_smasher_access_token.'&longUrl='.urlencode($url_smasher_long_url);
	  try {
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_TIMEOUT, 4);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		$output = json_decode(curl_exec($ch));
	  } catch (Exception $e) {
	  }
	  if(isset($output)){return esc_url($output->data->url);} else {return $url_smasher_long_url ; }
	}


// ---------------------------------------------------------------------------- 
// all done!
// ---------------------------------------------------------------------------- 






