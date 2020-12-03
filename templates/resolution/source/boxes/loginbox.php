<?php
if (isset($hook->hook_box_loginbox_php_top))(eval($hook->hook_box_loginbox_php_top));
  /* -----------------------------------------------------------------------------------------
   $Id: loginbox.php 3072 2012-06-18 15:01:13Z hhacker $

   modified eCommerce Shopsoftware
   http://www.modified-shop.org

   Copyright (c) 2009 - 2013 [www.modified-shop.org]
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommercebased on original files from OSCommerce CVS 2.2 2002/08/28 02:14:35 www.oscommerce.com 
   (c) 2003 nextcommerce (loginbox.php,v 1.10 2003/08/17); www.nextcommerce.org
   (c) 2006 XT-Commerce

   Released under the GNU General Public License 
   -----------------------------------------------------------------------------------------
   Third Party contributions:
   Loginbox V1.0          Aubrey Kilian <aubrey@mycon.co.za>

   Released under the GNU General Public License
   ---------------------------------------------------------------------------------------*/

  if (!isset($_SESSION['customer_id'])) {
    require_once (DIR_FS_INC.'xtc_image_submit.inc.php');
    require_once (DIR_FS_INC.'xtc_draw_password_field.inc.php');
    require_once (DIR_FS_INC.'secure_form.inc.php');

    if (isset($hook->hook_box_loginbox_php_includes_after))(eval($hook->hook_box_loginbox_php_includes_after));

    $box_smarty = new smarty;
    $box_smarty->assign('tpl_path',DIR_WS_BASE.'templates/'.CURRENT_TEMPLATE.'/');
    // $box_smarty->assign('FORM_ACTION', xtc_draw_form('loginbox', xtc_href_link(FILENAME_LOGIN, 'action=process', 'SSL'), 'post', 'class="box-login"'));
    $box_smarty->assign('FORM_ACTION', xtc_draw_form('loginbox', xtc_href_link(FILENAME_LOGIN, 'action=process', 'SSL'), 'post', 'class="box-login"').secure_form());
    $box_smarty->assign('FIELD_EMAIL', xtc_draw_input_field('email_address', '', 'maxlength="50" id="email_address" class="input-large"'));
    $box_smarty->assign('FIELD_PWD', xtc_draw_password_field('password', '', 'maxlength="30" id="password" class="input-large"'));
    $box_smarty->assign('BUTTON', xtc_image_submit('button_login_small.gif', IMAGE_BUTTON_LOGIN));
    $box_smarty->assign('LINK_LOST_PASSWORD', xtc_href_link(FILENAME_PASSWORD_DOUBLE_OPT, '', 'SSL'));
    $box_smarty->assign('FORM_END', '</form>');
    $box_smarty->assign('BOX_CONTENT', '');
    if (isset($hook->hook_box_loginbox_php_assign_after))(eval($hook->hook_box_loginbox_php_assign_after));
    $box_smarty->caching = 0;
    $box_smarty->assign('language', $_SESSION['language']);
    $box_loginbox = $box_smarty->fetch(CURRENT_TEMPLATE.'/boxes/box_login.html');
    if (isset($hook->hook_box_loginbox_php_fetch_after))(eval($hook->hook_box_loginbox_php_fetch_after));
    
    $smarty->assign('box_LOGIN', $box_loginbox);
  }
if (isset($hook->hook_box_loginbox_php_bottom))(eval($hook->hook_box_loginbox_php_bottom));
?>