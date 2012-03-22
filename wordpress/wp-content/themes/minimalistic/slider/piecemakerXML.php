<?php 
echo '<?xml version="1.0" encoding="utf-8" ?>';
	require_once( '../../../../wp-load.php' );
	GLOBAL $shortname;	
	$imageWidth = get_option($shortname.'_imageWidth');
	$imageHeight = get_option($shortname.'_imageHeight');
	$segments = get_option($shortname.'_segments');
	$tweenTime = get_option($shortname.'_tweenTime');
	$tweenDelay = get_option($shortname.'_tweenDelay');
	$tweenType = get_option($shortname.'_tweenType');
	$zDistance = get_option($shortname.'_zDistance');
	$expand = get_option($shortname.'_expand');
	$innerColor = get_option($shortname.'_innerColor');
	$textBackground = get_option($shortname.'_textBackground');
	$shadowDarkness = get_option($shortname.'_shadowDarkness');
	$textDistance = get_option($shortname.'_textDistance');	
	$autoPlay = get_option($shortname.'_autoplay');

	$m = 0;
	$get_custom_options = get_option($shortname.'_slider_cp');
	if ($get_custom_options) {
	echo '
	<Piecemaker>
	<Settings>
		<imageWidth>'. $imageWidth . '</imageWidth>
		<imageHeight>'. $imageHeight . '</imageHeight>
		<segments>'. $segments . '</segments>
		<tweenTime>'. $tweenTime . '</tweenTime>
		<tweenDelay>'. $tweenDelay . '</tweenDelay>
		<tweenType>'. $tweenType . '</tweenType>
		<zDistance>'. $zDistance . '</zDistance>
		<expand>'. $expand . '</expand>
		<innerColor>'. $innerColor . '</innerColor>
		<textBackground>'. $textBackground . '</textBackground>
		<shadowDarkness>' . $shadowDarkness . '</shadowDarkness>
		<textDistance>'. $textDistance . '</textDistance>
		<autoplay>' . $autoPlay .  '</autoplay>
	</Settings>
	';
	
		//$get_custom_options = get_option($shortname.'_slider_cp');
		for($i = 1; $i <= 100; $i++) 
		{
			if ($get_custom_options[$shortname.'_slider_cp_url_'.$i])
			{
				echo '
					<Image Filename="'.$get_custom_options[$shortname.'_slider_cp_url_'.$i].'">
					</Image>
				';
				$m = $m + 1;
			}
		}
	}
	
	if ( $m == 0 )
	{	
	echo '
	<Piecemaker>
		<Settings>
		<imageWidth>900</imageWidth>
		<imageHeight>400</imageHeight>
		<segments>7</segments>
		<tweenTime>1.2</tweenTime>
		<tweenDelay>0.1</tweenDelay>
		<tweenType>easeInOutBack</tweenType>
		<zDistance>0</zDistance>
		<expand>20</expand>
		<innerColor>0x111111</innerColor>
		<textBackground>0x0064C8</textBackground>
		<shadowDarkness>100</shadowDarkness>
		<textDistance>25</textDistance>
		<autoplay>12</autoplay>
		</Settings>
			
		<Image Filename="image_1.jpg">
		</Image>

		<Image Filename="image_2.jpg">
		</Image>

		<Image Filename="image_3.jpg">
		</Image>

		<Image Filename="image_4.jpg">
		</Image>';
	}	
	echo '
	</Piecemaker>';
?>