<?php
/* -----------------------------------------------------------------------------------------
   $Id: admin.php 1262 2005-09-30 10:00:32Z mz $   

   XT-Commerce - community made shopping
   http://www.xt-commerce.com

   Copyright (c) 2003 XT-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommercebased on original files from OSCommerce CVS 2.2 2002/08/28 02:14:35 www.oscommerce.com 
   (c) 2003	 nextcommerce (admin.php,v 1.12 2003/08/13); www.nextcommerce.org

   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/

  $box_smarty = new smarty;
  $box_content='';
  $box_smarty->assign('tpl_path','templates/'.CURRENT_TEMPLATE.'/');
	$box_smarty->assign('language', $_SESSION['language']);
  /*
  if (is_array($plugin->data)) {
    $i=sizeof($plugin->data);
    foreach ($plugin->data as $plg) {
      if ($plg['installed']) $i--;
    }
    $notInstalledPlugins = $i;
  }
  */
	if (isset($hook->hook_box_admin_php_top))(eval($hook->hook_box_admin_php_top));
  if (is_array($plugin->data)) {
    $i=0;
    foreach ($plugin->data as $plg) {
      if ( $plg['required']=='1' && !$plg['installed'] )
        $i++;
    }
    $notInstalledPlugins = $i;
  }
  
  
  if ($product->isProduct()) {
    $product_edit = array(
      'link' => xtc_href_link_admin(FILENAME_EDIT_PRODUCTS, 'cPath=' . $cPath . '&amp;pID=' . $product->data['products_id']) . '&amp;action=new_product',
      'linkparams' => ' onclick="window.open(this.href); return false;"',
      'linktext' => IMAGE_BUTTON_PRODUCT_EDIT
    );
  	$box_smarty->assign('product_edit', $product_edit);
  }
	
	if (isset($hook->hook_box_admin_php_assign_before))(eval($hook->hook_box_admin_php_assign_before));
	$box_smarty->assign('BOX_CONTENT', $box_content);
	
	$box_smarty->assign('plugin_count', $notInstalledPlugins);
	$box_smarty->assign('plugin_link', xtc_href_link('plugin_manager.php'));
	$box_smarty->assign('admin_link', xtc_href_link_admin(FILENAME_START));
	$box_smarty->assign('status', $admin_links);
	$box_smarty->assign('stats', $admin_stats);
	if (isset($hook->hook_box_admin_php_assign_after))(eval($hook->hook_box_admin_php_assign_after));

	$box_smarty->caching = 0;
	$box_admin = $box_smarty->fetch(CURRENT_TEMPLATE.'/boxes/box_admin.html');
	$smarty->assign('box_ADMIN',$box_admin);
	
	if (isset($hook->hook_box_admin_php_bottom))(eval($hook->hook_box_admin_php_bottom));
?>