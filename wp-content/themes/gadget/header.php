<?php
/**
 * The Header template for our theme
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package WordPress

 */
?><!DOCTYPE html>
<!--[if IE 7]>
<html class="ie ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html class="ie ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 7) & !(IE 8)]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>" />
    <meta name="viewport" content="width=device-width" />
    <title><?php wp_title( '|', true, 'right' ); ?></title>
    <link rel="stylesheet" href="<?php bloginfo('template_url'); ?>/css/bootstrap.min.css">
    <link href='http://fonts.googleapis.com/css?family=Lato' rel='stylesheet' type='text/css'>
    <link rel="profile" href="http://gmpg.org/xfn/11" />
    <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
    <?php // Loads HTML5 JavaScript file to add support for HTML5 elements in older IE versions. ?>
    <!--[if lt IE 9]>
    <script src="<?php echo get_template_directory_uri(); ?>/js/html5.js" type="text/javascript"></script>
    <![endif]-->
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<div id="page" class="hfeed">
    <header id="masthead" class="site-header" role="banner">
        <nav class="navbar navbar-default">
            <div class="container-fluid">
                <div class="container">
                    <!-- Brand and toggle get grouped for better mobile display -->
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1"> <span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
                        <a class="navbar-brand" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><img src="<?php header_image(); ?>" alt="logo" title="Gadget Recharge"></a> </div>

                    <!-- Collect the nav links, forms, and other content for toggling -->
                    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                        <ul class="nav navbar-nav navbar-right">
                            <?php dynamic_sidebar( "header-right"); ?>
                        </ul>

                    </div>
                </div>
                <!-- /.container-fluid -->
        </nav>
        <div class="header_bottom">
            <div class="container">
                <div class="row">
                    <?php wp_nav_menu( array( 'theme_location' => 'primary', 'menu_class' => 'nav-menu' ) ); ?>
                </div>
            </div>
        </div>



<?php 
if( is_home() ):?>
        <div id="banner">
            <div class="banner">
                <div class="mbl-functn">
                    <div class="lg-circle">
                        <div class="sm-circle">
                            COMPARE
                        </div>


                        <div class="sm-crcl"><a href="#">3G</a></div>
                        <div class="sm1-crcl"><a href="#">WiFi</a></div>
                        <div class="sm2-crcl"><a href="#">Dual sim</a></div>
                        <div class="sm3-crcl"><a href="#">Qwerty</a></div>
                        <div class="sm4-crcl"><a href="#">Touch</a></div>
                        <div class="sm5-crcl"><a href="#">Slim</a></div>
                        <div class="sm6-crcl"><a href="#">windows phone</a></div>
                        <div class="sm7-crcl"><a href="#">Android</a></div>
                    </div>
                </div>

                <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">

                    <ol class="carousel-indicators">
                        <li data-target="#carousel-example-generic" data-slide-to="0" class="active"></li>
                        <li data-target="#carousel-example-generic" data-slide-to="1"></li>
                        <li data-target="#carousel-example-generic" data-slide-to="2"></li>
                    </ol>

                    <?php $args = array(
                        'posts_per_page'   => 3,
                        'category_id'      => 10,
                        'category_name'    => 'Slider',
                        'post_type'        => 'post',
                        'post_status'      => 'publish',
                        'suppress_filters' => true ); ?>
                    <?php query_posts($args); ?>
                    <!-- Do special_cat stuff... -->

                    <!-- Wrapper for slides -->
                    <div class="carousel-inner" role="listbox">
                        <?php while (have_posts()) : the_post(); ?>
                            <div class="item ">
                            <?php the_post_thumbnail('full');?>
                            <div class="carousel-caption">
                                <div class="banner_text">
                                <h2><?php the_title();?></h2><dfn><?php the_content();?></dfn>
                                    <a href="#">COMPARE NOW <i> &nbsp;</i></a>
                                </div>
                            </div></div><?php endwhile;?>
                         
                    </div>
                        <!-- Controls -->
                        <a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">
                            <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                            <span class="sr-only">Previous</span>
                        </a>
                        <a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">
                            <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                            <span class="sr-only">Next</span>
                        </a>
                    </div>
                    <script>
                      
                       <!-- $(window).load(function(){-->
                            jQuery( ".carousel-inner .item:first-child" ).addClass( "active" );
                        <!--});-->
                    </script>
                  
                </div>
   
          <?php endif; ?>  
    </header><!-- #masthead -->

    <div id="main" class="wrapper main-content">