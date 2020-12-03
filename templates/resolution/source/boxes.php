<?php
/* -----------------------------------------------------------------------------------------
   $Id: boxes.php 3409 2012-08-10 12:47:17Z web28 $

   modified eCommerce Shopsoftware
   http://www.modified-shop.org

   Copyright (c) 2009 - 2013 [www.modified-shop.org]
   -----------------------------------------------------------------------------------------
   based on:
   (c) 2006 XT-Commerce
   
   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/
	
	/* modified coryright
	 * auskommentiert für "anzeigen"
	 * einkommentiert für "nicht anzeigen" 
	 */
	/* define('RM',true); */
	
// -----------------------------------------------------------------------------------------
//	include template functions & settings
// -----------------------------------------------------------------------------------------
	//require(DIR_FS_CATALOG . 'templates/' . CURRENT_TEMPLATE . '/source/settings.php');
	require(DIR_FS_CATALOG . 'templates/' . CURRENT_TEMPLATE . '/source/lib/template.php');
	
	if (isset($hook->hook_boxes_php_top))(eval($hook->hook_boxes_php_top));

// BOF - Tomcraft - 2009-10-27 - Prevent duplicate content, see: http://www.gunnart.de/tipps-und-tricks/doppelten-content-vermeiden-productredirect-fuer-xtcommerce/
  require_once (DIR_FS_CATALOG . 'templates/' . CURRENT_TEMPLATE . '/source/inc/gunnart_productRedirect.inc.php');
// EOF - Tomcraft - 2009-10-27 - Prevent duplicate content, see: http://www.gunnart.de/tipps-und-tricks/doppelten-content-vermeiden-productredirect-fuer-xtcommerce/

// -----------------------------------------------------------------------------------------
//	Immer sichtbar
// -----------------------------------------------------------------------------------------
  define('FORCE_CACHE', false);
	
  require_once($registry->boxes . 'topnavi.php');
	
  if ( $page != 'manufacturers_product_listing' && $page != 'advanced_search_result' && $page != 'product_info' )
  	require_once($registry->boxes . 'subnavi.php');
  
  if (show_box_manufacturers!='false')
  	require_once($registry->boxes . 'manufacturers.php');
  if (show_box_search!='false')
    require_once($registry->boxes . 'search.php');
  if (show_box_content!='false')
    require_once($registry->boxes . 'content.php');
  if (show_box_information!='false')
    require_once($registry->boxes . 'information.php');
  if (show_box_languages!='false')
    require_once($registry->boxes . 'languages.php'); 
  if (show_box_newsletter!='false')
    require_once($registry->boxes . 'newsletter.php');
  if (show_box_currencies!='false' && substr(basename($PHP_SELF), 0, 8) != 'checkout')
    require_once($registry->boxes . 'currencies.php');
  
  require_once(DIR_FS_BOXES . 'miscellaneous.php');
  if (defined('MODULE_TS_TRUSTEDSHOPS_ID')&& (MODULE_TS_WIDGET == '1'|| (MODULE_TS_REVIEW_STICKER != '' && MODULE_TS_REVIEW_STICKER_STATUS == '1'))) {
    require_once(DIR_FS_BOXES . 'trustedshops.php');
  }

  require_once($registry->boxes . 'loginbox.php');
// -----------------------------------------------------------------------------------------
//	Nur, wenn Preise sichtbar
// -----------------------------------------------------------------------------------------
  if ($_SESSION['customers_status']['customers_status_show_price'] == 1) {
    require_once($registry->boxes . 'shopping_cart.php');

    if (defined('MODULE_WISHLIST_SYSTEM_STATUS') && MODULE_WISHLIST_SYSTEM_STATUS == 'true') {
      require_once(DIR_FS_BOXES . 'wishlist.php');
    }
  }
// -----------------------------------------------------------------------------------------
//	Nur fuer Admins
// -----------------------------------------------------------------------------------------
  if ($_SESSION['customers_status']['customers_status_id'] == 0) {
    require_once($registry->boxes . 'admin.php');
    $smarty->assign('is_admin', true);
  }
// -----------------------------------------------------------------------------------------
//	Nur fuer eingeloggte Besucher
// -----------------------------------------------------------------------------------------
  if (isset($_SESSION['customer_id'])) {
    require_once($registry->boxes . 'order_history.php');
  }
// -----------------------------------------------------------------------------------------
//EOC require boxes

// -----------------------------------------------------------------------------------------
// Smarty Zuweisung Startseite
// -----------------------------------------------------------------------------------------
$smarty->assign('home', strpos($PHP_SELF, 'index')!==false && !isset($_GET['cPath']) && !isset($_GET['manufacturers_id']) ? 1 : 0);
// -----------------------------------------------------------------------------------------

$smarty->assign('tpl_path',DIR_WS_BASE.'templates/'.CURRENT_TEMPLATE.'/');

if (isset($hook->hook_boxes_php_bottom))(eval($hook->hook_boxes_php_bottom));
?>