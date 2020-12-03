<?php
if (isset($hook->hook_box_subnavi_php_top))(eval($hook->hook_box_subnavi_php_top));
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
  
  
  
  	
  if ($device != 'mobile') {
    	
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
    if (isset($hook->hook_box_subnavi_php_cache_settings_after))(eval($hook->hook_box_subnavi_php_cache_settings_after));
    
  	if(!$tpl->is_cached(CURRENT_TEMPLATE.'/boxes/box_subnavi.html',$cache_id) || !$cache) {
  		
  		if(is_array($cPath_array) || $device != 'mobile') {
    		if ($device != 'mobile') {
      		$config = array(
        	  'root' => $cPath_array[0],
        	  'type' => 'list',
        	  'id' => 'subnavi',
        	  'class' => 'nav navtype_default',
        	  'levels' => 4,
        	  'nested' => true,
        	  'dropdown_class' => '',
        	  'link_pre' => '<span class="icon-angle-right"></span>',
        	  'link_post' => ''
        	);
      	} else {
      		$config = array(
        	  'root' => false,
        	  'type' => 'list',
        	  'id' => 'subnavi',
        	  'class' => 'nav navtype_mobile',
        	  'levels' => 50,
        	  'nested' => true,
        	  'dropdown_class' => '',
        	  'link_pre' => '<span class="icon-angle-right"></span>',
        	  'link_post' => ''
        	);
      	}
    		if (isset($hook->subnavi_php_after_config_after))(eval($hook->subnavi_php_after_config_after));
    		
    		$tpl->assign('language',$_SESSION['language']);
    		$tpl->assign('tpl_path',DIR_WS_BASE.'templates/'.CURRENT_TEMPLATE.'/');
    		$tpl->assign('BOX_CONTENT',$categories->HtmlNavigation($config));
    		if (isset($hook->subnavi_php_assign_after))(eval($hook->subnavi_php_assign_after));
    	}
  	}
  
  	if(!$cache) {
  		$sub_categories = $tpl->fetch(CURRENT_TEMPLATE.'/boxes/box_subnavi.html');
  	} else {
  		$sub_categories = $tpl->fetch(CURRENT_TEMPLATE.'/boxes/box_subnavi.html',$cache_id);
  	}
		if (isset($hook->subnavi_php_fetch_after))(eval($hook->subnavi_php_fetch_after));
		
  	$smarty->assign('box_SUBNAVI',$sub_categories);
	}
if (isset($hook->hook_box_subnavi_php_bottom))(eval($hook->hook_box_subnavi_php_bottom));
?>