<?php
	if (isset($hook->plugin_manager_dashboard_php_top))(eval($hook->plugin_manager_dashboard_php_top));
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
  
  // assign plugin data
  $page_dashboard->assign( 'plugin_data', getBoxData() );
	$page_dashboard->assign( 'menu_data', getMenuData() );
  
	if (isset($hook->plugin_manager_dashboard_php_before_fetch))(eval($hook->plugin_manager_dashboard_php_before_fetch));
	
	$page_dashboard_module = $page_dashboard->fetch( $registry->plugin_manager['fs_app_template'].'dashboard.html' );
	$smarty->assign( 'app_content',$page_dashboard_module );
  
	if (isset($hook->plugin_manager_dashboard_php_bottom))(eval($hook->plugin_manager_dashboard_php_bottom));
?>