<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content-vw">
 *
 * @package VW Painter
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo( 'charset' ); ?>">
  <meta name="viewport" content="width=device-width">
  <link rel="profile" href="<?php echo esc_url( __( 'http://gmpg.org/xfn/11', 'vw-painter' ) ); ?>">
  <?php wp_head(); ?> 
  <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700&display=swap" rel="stylesheet">  
  <link href="<?php echo get_stylesheet_directory_uri(); ?>/css/responsive.css" rel="stylesheet" type="text/css">
    <link href="<?php echo get_stylesheet_directory_uri(); ?>/css/ionicons.min.css" rel="stylesheet" type="text/css">
</head>
<body <?php body_class(); ?>>
<div class="home-page-header">
	<?php get_template_part('template-parts/header/topbar'); ?>
	<?php //get_template_part('template-parts/header/top-header'); ?>

</div>