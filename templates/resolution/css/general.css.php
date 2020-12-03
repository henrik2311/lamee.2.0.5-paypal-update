<?php if (!isset($resources)) include(DIR_FS_CATALOG . 'templates/' . CURRENT_TEMPLATE . '/source/lib/template.php'); ?>
<?php
/* -----------------------------------------------------------------------------------------
   $Id: general.css.php 1262 2005-09-30 10:00:32Z mz $

   XT-Commerce - community made shopping
   http://www.xt-commerce.com

   Copyright (c) 2003 XT-Commerce
   -----------------------------------------------------------------------------------------
   Released under the GNU General Public License
   -----------------------------------------------------------------------------------------
	 @descriprion "CSS-files will be loaded at the TOP of every page"
   ---------------------------------------------------------------------------------------*/
?>

<?php if (isset($hook->hook_general_css_php_top))(eval($hook->hook_general_css_php_top)); ?>

<?php // load browser related stylesheets ?>
<!--[if IE 7.0]>
  <link href="//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome-ie7.min.css" rel="stylesheet">
<![endif]-->

<?php // framework css ?>
<?php $resources->add($registry->css.'resolution/fw.css', 10); ?>
<?php $resources->add($registry->css.'resolution/fw.icons.css', 30); ?>
<?php $resources->add($registry->css.'resolution/thickbox.css', 40); ?>
<?php $resources->add($registry->css.'resolution/helper.css', 50); ?>
<?php $resources->add($registry->css.'resolution/jquery.rating.css', 60); ?>

<?php // template css ?>
<?php $resources->add($registry->css.'template/fw.custom.css', 30); ?>
<?php $resources->add($registry->css.'template/template.css', 900); ?>

<?php $resources->add($registry->css.'default/jquery.colorbox.css', 65); ?>
<?php $resources->add($registry->css.'default/jquery.alerts.css', 66); ?>
<?php $resources->add($registry->css.'default/jquery.bxslider.css', 67); ?>

<?php // k1 css ?>
<?php $resources->add($registry->css.'k1_mediendesign/k1_kategorien.css', 30); ?>

<?php // load fonts ?>
<?php // $resources->add('//fonts.googleapis.com/css?family=Lato:300,400,900', 9000, 'css'); ?>
<?php // $resources->add($registry->css.'template/webfont.css', 9000); ?>
<link href="https://fonts.googleapis.com/css?family=Lato:300,400,900" rel="stylesheet">

<?php // stylesheet.css always last ?>
<?php $resources->add($registry->tpl.'stylesheet.css', 10000); ?>

<?php if (isset($hook->hook_general_css_php_resources))(eval($hook->hook_general_css_php_resources)); ?>

<?php $resources->showResources('css'); ?>

<?php if (isset($hook->hook_general_css_php_bottom))(eval($hook->hook_general_css_php_bottom)); ?>