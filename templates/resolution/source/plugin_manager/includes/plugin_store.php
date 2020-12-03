<?php
	if (isset($hook->plugin_manager_plugin_store_php_top))(eval($hook->plugin_manager_plugin_store_php_top));
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
  
  $page_dashboard = new Smarty;
  
  $plugin_store_domain = 'support.squidio.de/helpcenters/';
  $_tpl_data = i();
  
  $plugin_store_link = $plugin_store_domain.$_tpl_data['template_id'].'/'.$_tpl_data['shop_system'].'/'.$_tpl_data['shop_version'].'/';
  
  // DEV
  $plugin_store_link = 'http://www.squidio.de/products/modified-Templates/modified-Template-Evolution.html';
  
  // assign plugin data
  $page_dashboard->assign( 'plugin_store_link', $plugin_store_link );
  
	if (isset($hook->plugin_manager_plugin_store_php_before_fetch))(eval($hook->plugin_manager_plugin_store_php_before_fetch));
	
	$page_dashboard_module = $page_dashboard->fetch( $registry->plugin_manager['fs_app_template'].'pluginstore.html' );
	$smarty->assign( 'app_content',$page_dashboard_module );

	if (isset($hook->plugin_manager_plugin_store_php_bottem))(eval($hook->plugin_manager_plugin_store_php_bottem));
?>