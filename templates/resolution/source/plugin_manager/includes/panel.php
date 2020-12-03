<?php
	if (isset($hook->plugin_manager_panel_php_top))(eval($hook->plugin_manager_panel_php_top));
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
  
  
  
  $page_panel = new Smarty;
  
  $pluginId = (string)$_GET['plg'];
    
  // get panel
  $plugin_admin->_set( $pluginId );
  $panel_file = $plugin_admin->getPanel();
	require_once( $panel_file);
  
	if (isset($hook->plugin_manager_panel_php_before_fetch))(eval($hook->plugin_manager_panel_php_before_fetch));
	
	$page_panel_module = $page_panel->fetch( $registry->plugin_manager['fs_app_template'].'panel.html' );
	$smarty->assign( 'app_content',$page_panel_module );
  
	if (isset($hook->plugin_manager_panel_php_bottom))(eval($hook->plugin_manager_panel_php_bottom));
?>