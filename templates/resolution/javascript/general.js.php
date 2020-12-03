<?php if (!isset($resources)) include(DIR_FS_CATALOG . 'templates/' . CURRENT_TEMPLATE . '/source/lib/template.php'); ?>
<?php define('DIR_TMPL_JS', 'templates/'.CURRENT_TEMPLATE. '/javascript/'); ?>
<script type="text/javascript">
  var DIR_WS_BASE="<?php echo DIR_WS_BASE ?>"
</script>

<?php
/* -----------------------------------------------------------------------------------------
   $Id: general.js.php 1262 2005-09-30 10:00:32Z mz $

   XT-Commerce - community made shopping
   http://www.xt-commerce.com

   Copyright (c) 2003 XT-Commerce
   -----------------------------------------------------------------------------------------
   Released under the GNU General Public License
   -----------------------------------------------------------------------------------------
	 @descriprion "add your template specific js scripts here"
   ---------------------------------------------------------------------------------------*/
?>

<?php if (isset($hook->hook_general_js_php_top))(eval($hook->hook_general_js_php_top)); ?>

<?php // framework js ?>
<?php $resources->add($registry->js.'resolution/jquery.1.8.2.js', 10); ?>
<?php $resources->add($registry->js.'resolution/fw.js', 20); ?>

<?php // template js ?>
<?php $resources->add($registry->js.'template/equalizer.js', 60); ?>
<?php $resources->add($registry->js.'template/jquery.rating.js', 70); ?>
<?php $resources->add($registry->js.'template/custom.plugin.js', 40); ?>
<?php $resources->add($registry->js.'template/custom.js', 50); ?>

<?php $resources->add($registry->js.'default/jquery.colorbox.min.js', 65); ?>
<?php $resources->add($registry->js.'default/jquery.alerts.min.js', 66); ?>
<?php $resources->add($registry->js.'default/jquery.bxslider.min.js', 67); ?>
<?php $resources->add($registry->js.'default/jquery.unveil.min.js', 68); ?>

<?php 
// BOF hk 2017-10-17: Produkt-Optionsbild
if(isset($_GET['products_id'])) {
	$resources->add($registry->js.'k1mediendesign/k1_optionsimages/k1_productimageslider.js', 80);
} 
// EOF hk 2017-10-17: Produkt-Optionsbild
?>

<?php if (isset($hook->hook_general_js_php_resources))(eval($hook->hook_general_js_php_resources)); ?>

<?php $resources->showResources('js'); ?>


<?php if (isset($hook->hook_general_js_php_bottom))(eval($hook->hook_general_js_php_bottom)); ?>
