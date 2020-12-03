<?php
/* -----------------------------------------------------------------------------------------
   $Id: general_bottom.css.php 4200 2013-01-10 19:47:11Z Tomcraft1980 $

   modified eCommerce Shopsoftware
   http://www.modified-shop.org

   Copyright (c) 2009 - 2013 [www.modified-shop.org]
   -----------------------------------------------------------------------------------------
   based on:
   (c) 2006 XT-Commerce

   Released under the GNU General Public License
   ---------------------------------------------------------------------------------------*/

   // This CSS file get includes at the BOTTOM of every template page in shop
   // you can add your template specific css scripts here
?>
<?php if (isset($hook->hook_general_bottom_css_php_top))(eval($hook->hook_general_bottom_css_php_top)); ?>

<!--[if lte IE 8]>
  <link rel="stylesheet" property="stylesheet" href="<?php echo DIR_WS_BASE.'templates/'.CURRENT_TEMPLATE; ?>/css/default/ie8fix.css" type="text/css" media="screen" />
<![endif]-->

<?php if (isset($hook->hook_general_bottom_css_php_bottom))(eval($hook->hook_general_bottom_css_php_bottom)); ?>
