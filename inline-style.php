<?php
	
	/*---------------------------First highlight color-------------------*/

	$vw_painter_first_color = get_theme_mod('vw_painter_first_color');

	$custom_css = '';

	if($vw_painter_first_color != false){
		$custom_css .='.cat-btn a:hover, #footer input[type="submit"], #sidebar .tagcloud a:hover, #footer .tagcloud a:hover, .scrollup i, #header .nav ul.sub-menu li a:hover, input[type="submit"]{';
			$custom_css .='background-color: '.esc_html($vw_painter_first_color).';';
		$custom_css .='}';
	}
	if($vw_painter_first_color != false){
		$custom_css .='#comments input[type="submit"].submit{';
			$custom_css .='background-color: '.esc_html($vw_painter_first_color).'!important;';
		$custom_css .='}';
	}
	if($vw_painter_first_color != false){
		$custom_css .='a, #header .nav ul li a:hover, #header .current-menu-item, .post-navigation a:hover .post-title, .post-navigation a:focus .post-title, .woocommerce-message::before, #footer li a:hover{';
			$custom_css .='color: '.esc_html($vw_painter_first_color).';';
		$custom_css .='}';
	}
	if($vw_painter_first_color != false){
		$custom_css .='.cat-btn a:hover{';
			$custom_css .='border-color: '.esc_html($vw_painter_first_color).'!important;';
		$custom_css .='}';
	}
	if($vw_painter_first_color != false){
		$custom_css .='.post-info hr, .woocommerce-message{';
			$custom_css .='border-top-color: '.esc_html($vw_painter_first_color).';';
		$custom_css .='}';
	}
	if($vw_painter_first_color != false){
		$custom_css .='#topbar, #footer-2, .home-page-header{
		background-image: linear-gradient(to right, #00d2a0, '.esc_html($vw_painter_first_color).', #e89314);
		}';
	}
	if($vw_painter_first_color != false){
		$custom_css .='#slider .carousel-caption{
		border-image: linear-gradient(to bottom, #00d2a0, '.esc_html($vw_painter_first_color).', #e89314) 1 100%;
		}';
	}
	/*---------------------------Width Layout -------------------*/

	$theme_lay = get_theme_mod( 'vw_painter_width_option','Full Width');
    if($theme_lay == 'Boxed'){
		$custom_css .='body{';
			$custom_css .='max-width: 1140px; width: 100%; padding-right: 15px; padding-left: 15px; margin-right: auto; margin-left: auto;';
		$custom_css .='}';
	}else if($theme_lay == 'Wide Width'){
		$custom_css .='body{';
			$custom_css .='width: 100%;padding-right: 15px;padding-left: 15px;margin-right: auto;margin-left: auto;';
		$custom_css .='}';
	}else if($theme_lay == 'Full Width'){
		$custom_css .='body{';
			$custom_css .='max-width: 100%;';
		$custom_css .='}';
	}

	/*--------------------------- Slider Opacity -------------------*/

	$theme_lay = get_theme_mod( 'vw_painter_slider_opacity_color','0.5');
	if($theme_lay == '0'){
		$custom_css .='#slider img{';
			$custom_css .='opacity:0';
		$custom_css .='}';
		}else if($theme_lay == '0.1'){
		$custom_css .='#slider img{';
			$custom_css .='opacity:0.1';
		$custom_css .='}';
		}else if($theme_lay == '0.2'){
		$custom_css .='#slider img{';
			$custom_css .='opacity:0.2';
		$custom_css .='}';
		}else if($theme_lay == '0.3'){
		$custom_css .='#slider img{';
			$custom_css .='opacity:0.3';
		$custom_css .='}';
		}else if($theme_lay == '0.4'){
		$custom_css .='#slider img{';
			$custom_css .='opacity:0.4';
		$custom_css .='}';
		}else if($theme_lay == '0.5'){
		$custom_css .='#slider img{';
			$custom_css .='opacity:0.5';
		$custom_css .='}';
		}else if($theme_lay == '0.6'){
		$custom_css .='#slider img{';
			$custom_css .='opacity:0.6';
		$custom_css .='}';
		}else if($theme_lay == '0.7'){
		$custom_css .='#slider img{';
			$custom_css .='opacity:0.7';
		$custom_css .='}';
		}else if($theme_lay == '0.8'){
		$custom_css .='#slider img{';
			$custom_css .='opacity:0.8';
		$custom_css .='}';
		}else if($theme_lay == '0.9'){
		$custom_css .='#slider img{';
			$custom_css .='opacity:0.9';
		$custom_css .='}';
		}

	/*---------------------------Slider Content Layout -------------------*/

	$theme_lay = get_theme_mod( 'vw_painter_slider_content_option','Right');
    if($theme_lay == 'Left'){
		$custom_css .='#slider .carousel-caption, #slider .inner_carousel, #slider .inner_carousel h2{';
			$custom_css .='text-align:left; left:15%; right:45%;';
		$custom_css .='}';
		$custom_css .='#slider .carousel-caption{';
			$custom_css .='padding-left: 15px; border-left: solid 12px; border-right: none;';
		$custom_css .='}';
	}else if($theme_lay == 'Center'){
		$custom_css .='#slider .carousel-caption, #slider .inner_carousel, #slider .inner_carousel h2{';
			$custom_css .='text-align:center; left:20%; right:20%;';
		$custom_css .='}';
		$custom_css .='#slider .carousel-caption{';
			$custom_css .='border-left: none; border-right: none;';
		$custom_css .='}';
	}else if($theme_lay == 'Right'){
		$custom_css .='#slider .carousel-caption, #slider .inner_carousel, #slider .inner_carousel h2{';
			$custom_css .='text-align:right; left:45%; right:15%;';
		$custom_css .='}';
	}