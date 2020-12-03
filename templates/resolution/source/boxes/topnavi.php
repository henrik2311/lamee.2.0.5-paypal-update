<?php
if (isset($hook->hook_box_topnavi_php_top))(eval($hook->hook_box_topnavi_php_top));
  /*[[[[[[[[[[[[[[[[[     ]]]]]]]]]]]]]]]]]]]
  [:::::::::::::::::[     ]:::::::::::::::::]
  [:::::::::::::::::[     ]:::::::::::::::::]
  [::::::[[[[[[[[[[[[     ]]]]]]]]]]]]::::::]
  [:::::[                             ]:::::]
  [:::::[          SQUIDIO.DE         ]:::::]
  [:::::[      http://squidio.de      ]:::::]
  [:::::[                             ]:::::]
  [::::::[[[[[[[[[[[[     ]]]]]]]]]]]]::::::]
  [:::::::::::::::::[     ]:::::::::::::::::]
  [:::::::::::::::::[     ]:::::::::::::::::]
  [[[[[[[[[[[[[[[[[[[     ]]]]]]]]]]]]]]]]]*/
  
  
	$tpl = new smarty;
  
	if(!CacheCheck() && !FORCE_CACHE) {
		$cache = false;
		$tpl->caching = 0;
	} else {
		$cache = true;
		$tpl->caching = 1;
		$tpl->cache_lifetime = CACHE_LIFETIME;
		$tpl->cache_modified_check = CACHE_CHECK;
		$cache_id = $_SESSION['language'].$_SESSION['customers_status']['customers_status_id'].$cPath.$device;
	}
  if (isset($hook->hook_box_topnavi_php_cache_settings_after))(eval($hook->hook_box_topnavi_php_cache_settings_after));
  
	if (!$tpl->is_cached(CURRENT_TEMPLATE.'/boxes/box_topnavi.html',$cache_id) || !$cache) {
		
		$navtype = (defined('navtype') && (navtype=='mega' || navtype=='dropdown')) ? navtype : 'default';
		if ( $device == 'mobile' || $device == 'tablet' )
  		$navtype = 'mobile';
    if (isset($hook->hook_box_topnavi_php_navitype_settings_after))(eval($hook->hook_box_topnavi_php_navitype_settings_after));
  
  	$config = array(
  	  'root' => 0,
  	  'type' => 'list',
  	  'id' => 'topnavi',
  	  'class' => 'nav navtype_'.$navtype,
  	  'levels' => ( $navtype == 'mobile' ) ? false : 3,
  	  'nested' => false,
  	  'dropdown_class' => 'dropdown',
  	  'items_per_row' => 5,
  	  'link_pre' => '',
  	  'link_post' => ''
  	);
		if (isset($hook->hook_box_topnavi_php_after_config_after))(eval($hook->hook_box_topnavi_php_after_config_after));
		
		$tpl->assign('language',$_SESSION['language']);
		$tpl->assign('tpl_path',DIR_WS_BASE.'templates/'.CURRENT_TEMPLATE.'/');
		$tpl->assign('BOX_CONTENT',$list_wrap_pre.$categories->HtmlNavigation($config).$list_wrap_post);
		
		if (isset($hook->hook_box_topnavi_php_assign_after))(eval($hook->hook_box_topnavi_php_assign_after));
	}
  
	if (!$cache) {
		$top_categories = $tpl->fetch(CURRENT_TEMPLATE.'/boxes/box_topnavi.html');
	} else {
		$top_categories = $tpl->fetch(CURRENT_TEMPLATE.'/boxes/box_topnavi.html',$cache_id);
	}
	if (isset($hook->hook_box_topnavi_php_fetch_after))(eval($hook->hook_box_topnavi_php_fetch_after));
	$smarty->assign('box_TOPNAVI',$top_categories);
if (isset($hook->hook_box_topnavi_php_bottom))(eval($hook->hook_box_topnavi_php_bottom));
?>