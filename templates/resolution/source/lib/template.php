<?php
if (isset($hook->hook_template_php_top))(eval($hook->hook_template_php_top));
  
  // get registry
  require_once (DIR_FS_CATALOG . 'templates/' . CURRENT_TEMPLATE . '/source/lib/classes/class.registry.php');
	$registry = Registry::getInstance();
	$hook = Registry::getInstance();

	// register paths
	$registry->boxes	= DIR_FS_CATALOG . 'templates/' . CURRENT_TEMPLATE . '/source/boxes/';
	$registry->lib 		= DIR_FS_CATALOG . 'templates/' . CURRENT_TEMPLATE . '/source/lib/';
	$registry->html		= DIR_FS_CATALOG . 'templates/' . CURRENT_TEMPLATE . '/';
	$registry->tpl 		= 'templates/' . CURRENT_TEMPLATE . '/';
	$registry->js 		= 'templates/' . CURRENT_TEMPLATE . '/javascript/';
	$registry->css 		= 'templates/' . CURRENT_TEMPLATE . '/css/';
	$registry->img 		= 'templates/' . CURRENT_TEMPLATE . '/img/';
	$registry->plg 		= 'templates/' . CURRENT_TEMPLATE . '/plugins/';
	$registry->ws_tpl	= CURRENT_TEMPLATE . '/';
	$registry->ws_plg	= CURRENT_TEMPLATE . '/plugins/';
	
  // settings
	$sections = array(
		// add pages > sections
		'checkout' 			=> array('checkout_shipping', 'checkout_payment', 'checkout_confirmation', 'checkout_success', 'checkout_shipping_address', 'checkout_payment_address'),
		'account' 			=> array('account', 'account_edit', 'address_book', 'address_book_process', 'account_password', 'account_delete', 'account_history', 'account_history_info'),
		'product_list'	=> array('categorie_listing', 'product_listing', 'specials', 'products_new', 'manufacturers_product_listing'),
		'cms' 					=> array('shop_content'),
		'start' 				=> array('index')
	);
	
	// include classes
  include_once ($registry->lib . 'classes/class.categories.php');
  include_once ($registry->lib . 'classes/class.resource.php');
  include_once ($registry->lib . 'classes/class.plugin.php');
  include_once ($registry->lib . 'classes/class.template.php');
  include_once ($registry->lib . 'classes/class.mobile_detect.php');
  include_once ($registry->lib . 'helper/helper.mobile_detect.php');
  
	// create objcets
	$resources        = new Resource();
	$plugin           = new Plugin();
	$categories       = new Categories();
	$template         = new Template($sections);
	$detect           = new Device();
	
	// create vars
	$page = $template->getPage();
	if ($page == 'newsletter' && $_GET['type'] == 'account')
	  $sections['account'][] = 'newsletter';
	$section = $template->getSection();
  $device = helperDevice();
	$plugin->initPlugins();
  
	// create template vars
	$smarty->assign('page',$page);
	$smarty->assign('has_subcategories', $template->checkSubcategories());
	$smarty->assign('section',$section);
	$smarty->assign('registry',$registry);
	$smarty->assign('device',$device);
	$smarty->assign('page_settings','section_'.$section.' device_'.$device.'');

if (isset($hook->hook_template_php_bottom))(eval($hook->hook_template_php_bottom));
?>