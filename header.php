<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="author" content="">
<link rel="icon" type="image/x-icon" href="<?php echo get_stylesheet_directory_uri(); ?>>/favicon.ico" />
<link rel="icon" type="image/png" href="<?php echo get_stylesheet_directory_uri(); ?>/favicon.png" />
<title><?php wp_title( ' | ', true, 'right' ); ?><?php bloginfo('name'); ?></title>

<!-- Google fonts -->
<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,600,400italic,300italic,300,600italic,700,700italic' rel='stylesheet' type='text/css'>

<!-- Bootstrap -->
<link href="<?php echo get_stylesheet_directory_uri(); ?>/css/bootstrap.css" rel="stylesheet">
<link href="<?php echo get_stylesheet_directory_uri(); ?>/css/bootstrap-select.min.css" rel="stylesheet">

<!-- Leaflet -->
<link rel="stylesheet" href="http://cdn.leafletjs.com/leaflet-0.7.3/leaflet.css" />

<!-- Custom styles -->
<link href="<?php echo get_stylesheet_directory_uri(); ?>/css/style.css?v=<?php echo time(); ?>" rel="stylesheet">

<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->




<?php include( TEMPLATEPATH . '/constants.php' ); ?>

<?php include( TEMPLATEPATH . '/oipa-functions.php' ); ?>


<script type="text/javascript">
var _gaq = _gaq || [];
_gaq.push(['_setAccount', '<?php echo GOOGLE_ANALYTICS_CODE; ?>']);
_gaq.push(['_trackPageview']);
(function() {
var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
})();
</script>


<script>
   var search_url = '<?php echo OIPA_URL; ?>';
   var home_url = "<?php echo bloginfo("url"); ?>";
   var site_url = home_url;
   var template_directory = "<?php echo bloginfo("template_url"); ?>";
   var site_title = "<?php echo wp_title(''); ?>";
   var standard_basemap = "zimmerman2014.hmpkg505";
   var ajaxurl = "<?php echo admin_url('admin-ajax.php'); ?>";
   var ajax_path = "/wp-admin/admin-ajax.php?action=refresh_elements";
   var theme_path = "<?php echo get_stylesheet_directory_uri(); ?>";
</script>

<?php // include( TEMPLATEPATH . '/oipa-ajax-functions.php' ); ?>

<link rel="stylesheet" type="text/css" href="<?php echo get_stylesheet_uri(); ?>" />
<?php wp_head(); ?>

</head>
<body>

<!-- Fixed navbar -->
<div class="navbar navbar-fixed-top" role="navigation">
   <div class="container">
      <div class="navbar-header">
         <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse"> <span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
         <a class="navbar-brand" href="<?php bloginfo('url'); ?>"><img src="<?php echo get_stylesheet_directory_uri(); ?>/img/un-habitat-logo.png" alt="UN-Habitat - Home" class="img-responsive"/><span><?php bloginfo('name'); ?></span></a> </div>
      <div class="navbar-collapse collapse">

         <?php 
            $settings = array(
               'theme_location'  => 'main-menu',
               'menu'            => '',
               'container'       => '',
               'container_class' => '',
               'container_id'    => '',
               'menu_class'      => 'menu',
               'menu_id'         => '',
               'echo'            => true,
               'fallback_cb'     => 'wp_page_menu',
               'before'          => '',
               'after'           => '',
               'link_before'     => '',
               'link_after'      => '',
               'items_wrap'      => '<ul id="%1$s" class="%2$s nav navbar-nav top-nav">%3$s</ul>',
               'depth'           => 0,
               'walker'          => ''
            );
         
            wp_nav_menu( $settings );
         ?>
         
         <div class="navbar-right">
            <div class="nav-search">
               <form class="searchbox" action="<?php echo home_url(); ?>/projects/" method="get" role="search">
                  <input type="search" placeholder="Search..." name="query" class="searchbox-input" onkeyup="buttonUp();" required>
                  <input type="submit" class="searchbox-submit" value="Search">
                  <span class="searchbox-icon"><img src="<?php echo get_stylesheet_directory_uri(); ?>/img/search.png" width="37" height="28" alt=""/></span>
               </form>
            </div>
         </div>
      </div>
      <!--/.nav-collapse --> 
      
   </div>
   <!--/.container --> 
</div>



