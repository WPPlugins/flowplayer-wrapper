<?php
/*
Plugin Name: flowplayer-wrapper
Plugin URI: http://www.ramgad.com/software/wordpress/wordpress-plugins/
Description: Including flowplayer (flowplayer.org). Call FPW (flowplayer-wrapper) by adding [fpw width=x height=x splash=pathtosplashimg.jpg]path_to_your_video[/fpw]  to your content. A couple of options can be customized in the settings session. If you want to embed flickr.com slideshows, please have a look at http://www.ramgad.com/board/topic/37-plugins-fssw-fpw-hfw/. This version has only be tested with the commercial version of flowplayer, but shall work as well with the light version. Version 1.1.2 are requiring PHP5!
Version: 1.1.5
Author: Jeannot Muller
Author URI: http://www.ramgad.com/
Min WP Version: 2.5
Max WP Version: 3.1.1
*/

// Update routines
	if ('insert' == $_POST['action_fpw']) {
		update_option("fpw_use_js", $_POST['fpw_use_js']);
		update_option("fpw_use_streaming", $_POST['fpw_use_streaming']);
		update_option("fpw_width",$_POST['fpw_width']);
		update_option("fpw_height",$_POST['fpw_height']);
		update_option("fpw_license",$_POST['fpw_license']);
		update_option("fpw_autoplay",$_POST['fpw_autoplay']);
		update_option("fpw_autobuffer",$_POST['fpw_autobuffer']);
		update_option("fpw_allowfs",$_POST['fpw_allowfs']);
		update_option("fpw_bgcolor",$_POST['fpw_bgcolor']);
		update_option("fpw_root_path",$_POST['fpw_root_path']);
		update_option("fpw_player",$_POST['fpw_player']);
		update_option("fpw_streaming",$_POST['fpw_streaming']);
		update_option("fpw_js",$_POST['fpw_js']);
                update_option("fpw_playpicpath",$_POST['fpw_playpicpath']);
		// update_option("fpw_css",$_POST['fpw_css']);
}

if (!class_exists('fpw_main')) {
	class fpw_main {
		/**
		* PHP 4 Compatible Constructor
		*/
		function fpw_main(){$this->__construct();}
		
		/**
		* PHP 5 Constructor
		*/		
		function __construct(){
			// Registrieren der WordPress-Hooks
			add_action('admin_menu', 'fpw_description_add_menu');
			add_action('wp_head', 'fpw_add_head_content');
			//add_action('wp_footer', 'fpw_add_end_of_body_content');
			add_shortcode('fpw', 'get_flowplayer_data_parsed');
			//add_filter('the_content', 'get_flowplayer_data_parsed');
		}
		// Registration of WordPress-Hooks
	}

function fpw_description_option_page() {
	?>

	<!-- Start Options Adminarea (xhtml) -->
		<div class="wrap">
		<h2>Flowplayer-Wrapper Options</h2>
		<div style="margin-top:20px;">
		<form method="post" action="<?php echo $_SERVER["REQUEST_URI"]; ?>&amp;updated=true">
			<div style="">
			<table class="form-table">
				<tr><th scope="col" colspan="3" cellpadding="15">Settings</th></tr>
				<tr><th scope="row">Use Flowplayer JavaScript?</th><td>
				<select name="fpw_use_js">
					<option value="0" <?= fpw_option_selected(get_option("fpw_use_js"),0);?>>No</option>
					<option value="1" <?= fpw_option_selected(get_option("fpw_use_js"),1);?>>Yes</option>
				</select></td></tr>
				<tr><th scope="row">Use Flowplayer Pseudostreaming Plugin?</th><td>
				<select name="fpw_use_streaming">
					<option value="0" <?= fpw_option_selected(get_option("fpw_use_streaming"),0);?>>No</option>
					<option value="1" <?= fpw_option_selected(get_option("fpw_use_streaming"),1);?>>Yes</option>
				</select></td></tr>
				<tr><th scope="row">Width:</th><td>
				<input name="fpw_width" size="3" value="<?=get_option("fpw_width");?>" type="text" />
					<span style="color:red;">	Example: 480</span></td></tr>
				<tr><th scope="row">Height:</th><td>
				<input name="fpw_height" size="3" value="<?=get_option("fpw_height");?>" type="text" />
					<span style="color:red;">	Example: 272</span></td></tr>
				<tr><th scope="row">License Key:</th><td>
				<input name="fpw_license" size="20" value="<?=get_option("fpw_license");?>" type="text" />
					<span style="color:red;">	Example: $xyz</span>(please use <a href="http://www.flowplayer.org/" target="_blank">flowplayer.org</a> to get a license key)</td></tr>
				<tr><th scope="row">AutoPlay (true|false):</th><td>
				<select name="fpw_autoplay">
					<option value="false" <?= fpw_option_selected(get_option("fpw_autoplay"),"false");?> >false</option>
					<option value="true" <?= fpw_option_selected(get_option("fpw_autoplay"),"true");?> >true</option>
				</select></td></tr>
				<tr><th scope="row">AutoBuffer (true|false):</th><td>
				<select name="fpw_autobuffer">
					<option value="false" <?= fpw_option_selected(get_option("fpw_autobuffer"),"false");?> >false</option>
					<option value="true" <?= fpw_option_selected(get_option("fpw_autobuffer"),"true");?> >true</option>
				</select></td></tr>
				<tr><th scope="row">Fullscreen (true|false):</th><td>
				<select name="fpw_allowfs">
					<option value="false" <?= fpw_option_selected(get_option("fpw_allowfs"),"false");?> >false</option>
					<option value="true" <?= fpw_option_selected(get_option("fpw_allowfs"),"true");?> >true</option>
				</select></td></tr>
				<tr><th scope="row">Backgroundcolor</th><td>
				<input name="fpw_bgcolor" size="7" value="<?=get_option("fpw_bgcolor");?>" type="text" />
					<span style="color:red;">	Example: #000000</span></td></tr>
				<tr><th scope="row">Flowplayer Root Path:</th><td>
				<input name="fpw_root_path" size="60" value="<?=get_option("fpw_root_path");?>" type="text" />
					<span style="color:red;">	Example: /wp-content/flowplayer</span></td></tr>
				<tr><th scope="row">Flowplayer SWF File:</th><td>
				<input name="fpw_player" size="60" value="<?=get_option("fpw_player");?>" type="text" />Relative to Flowplayer Root Path
					<span style="color:red;">	Example: flowplayer-3.1.4.swf</span></td></tr>
				<tr><th scope="row">Flowplayer PseudoStreaming Plugin:</th><td>
				<input name="fpw_streaming" size="60" value="<?=get_option("fpw_streaming");?>" type="text" />Relative to Flowplayer Root Path
					<span style="color:red;">	Example: flowplayer.pseudostreaming-3.1.3.swf</span></td></tr>
				<tr><th scope="row">Flowplayer Javascript Path:</th><td>
				<input name="fpw_js" size="60" value="<?=get_option("fpw_js");?>" type="text" />Relative to Flowplayer Root Path
					<span style="color:red;">Example: /js/flowplayer-3.1.4.min.js</span></td></tr>
                           </table>
			</div>
			<br />
			<p class="submit_fpw"><input name="submit_fpw" type="submit" id="submit_fpw" value="Save changes &raquo;">
			<input class="submit" name="action_fpw" value="insert": type="hidden" /></p>
		</form>
		</div>
		</div>
		<p style="text-align:justify;">Call FPW (flowplayer-wrapper) by adding [fpw width=x height=x splash=pathtosplashimg.jpg]path_to_your_video[/fpw] to your content. The parameters aren't mandatory!
		<p style="text-align:justify;">If you have problems with FPW (flowplayer-wrapper), please feel free to drop me a comment at: <a href="http://www.ramgad.com/software/wordpress/wordpress-plugins/">http://www.ramgad.com/software/wordpress/wordpress-plugins/</a></p>

<?php

} // End Function fssw_description_option_page()

// Adminmenu Optionen erweitern
function fpw_description_add_menu() {
	  global $fpw_width, $fpw_height, $fpw_license, $fpw_autoplay, $fpw_autobuffer,
			$fpw_allowfs, $fpw_bgcolor, $fpw_root_path, $fpw_use_js, $fpw_use_css, $fpw_player, $fpw_js, $fpw_css, $fpw_splash;
	  add_options_page('FPW', 'FPW', 9, __FILE__, 'fpw_description_option_page'); //add option side
}

function fpw_add_head_content() {   
	$js_path  = get_option("fpw_js");
	$use_js = get_option("fpw_use_js");
	// $css_path = get_option("fpw_css");
	// $use_css = get_option("fpw_use_css");
	$fpw_root_path = get_option("fpw_root_path");


	$out = "";
	if ($js_path != null && $use_js) {
		$out  .= '<script type="text/javascript" src="' . $fpw_root_path .  '/' . $js_path . '"></script>';
	}
	echo $out;
}

function fpw_option_selected($option, $value){
	if($option == $value) {
	   return 'selected="selected"';
	}
	return '';
}

function get_flowplayer_data_parsed($atts, $content = null) {

		$fpw_license       = get_option('fpw_license');
		$fpw_root_path     = get_option('fpw_root_path');
		$fpw_use_js    	   = get_option('fpw_use_js');
		$fpw_player        = get_option('fpw_player');
		$fpw_streaming     = get_option('fpw_streaming');
		$fpw_use_streaming = get_option('fpw_use_streaming');
                $fpw_license       = get_option('fpw_license');


		extract(shortcode_atts(array(
			'width'		  => get_option('fpw_width'),
			'height'	  => get_option('fpw_height'),
			'autoplay'	  => get_option('fpw_autoplay'),
			'autobuffer'      => get_option('fpw_autobuffer'),
			'allowfs'	  => get_option('fpw_allowfs'),
			'bgcolor'	  => get_option('fpw_bgcolor'),
			'video'	          => '',
			'splash'          => get_option('fpw_splash'),
		), $atts));

		// standard initialisation (default values) 
		 if ( $width == null or $height == null) {
			$width	= "480";
			$height = "272";		
		 }
		
		// Check which type of shortcode we're using 
		if (is_null($content)) {
			//$content is null so we better have a video specified in the video attribute
			if (is_null($video)) { return; }
			$video = esc_html($video);
		}
		else {
			$video = esc_html($content);
		}
	
	// If we're using Javascript build up the output with <a> tags and JS
	// Otherwise create an <object>

        $button_img_path = WP_PLUGIN_URL . '/flowplayer-wrapper/pictures/play.png';
        $button_height = 83; //px
        $button_offseth  = ($height - $button_height) / 2;
        $button_offsetw  = ($width -  $button_height) / 2;

	if ($fpw_use_js) {
		$output = '<div style="clear: both;">';
		$output .= '<a class="fpw" href="'.$video.'" style="display:block;width:'.$width.'px;height:'.$height.'px;';
		
		// Check if we want to use a splash picture
		    if ($splash) {
                        $output .= 'background-image:url(\''.$splash.'\')"';
	                $output .= ' <img src="'.$button_img_path.'" style="margin-top:'.$button_offseth.'px;margin-left:'. $button_offsetw .'px; " alt="video"';
				}		
                    else {
                        $output .= '"';
                    }                
		$output .= '></a>';
		$output .= 'flowplayer("a.fpw", "'.$fpw_root_path.'/'.$fpw_player.'", {';
                // Check if we want to load pseudo streaming
                if ($fpw_license) {
                      $output .= 'key: \'';
                      $output .= $fpw_license.'\',';
                }





		$output .= 'plugins: {';
		// Check if we want to load pseudo streaming
		if ($fpw_use_streaming) {
			$output .= 'pseudo: { url: "' . $fpw_root_path .'/'.$fpw_streaming . '" }';
		} 

		// End plugins 
		$output .= '},';

		// Clip properties
		$output .=	'clip: {
			                autoPlay: '.$autoplay.',
			                autoBuffering: '.$autobuffer;
			
		if ($fpw_use_streaming)	{ $output .= ',provider: \'pseudo\'';}
		
		$output .= '}
		}
		);
		</script>';
		$output .= '</div>';
	}
	else {
		$output = '<object class="fpw" width="' . $width.'" height="' . $height .'" data="'. $fpw_root_path.'/'.$fpw_player . '" type="application/x-shockwave-flash">';
		$output .= '<param name="allowfullscreen" value="' . $allowfs .'" />';
		$output .= '<param name="autoplay" value="'. $autoplay .'" />';
		$output .= '<param name="bgcolor" value="'. $bgcolor .'" />';
		$output .= '<param name="flashvars" value=\'config={"key":"' . $fpw_license .'","clip":{"url":"' . $video . '","autoPlay":' . $autoplay . ', "autoBuffering":' . $autobuffer . '}}\' />'; 
		$output .= '</object>';
	}

return $output;
}
}
//instantiate the class
if (class_exists('fpw_main')) {
	$fpw_main = new fpw_main();
}
?>
