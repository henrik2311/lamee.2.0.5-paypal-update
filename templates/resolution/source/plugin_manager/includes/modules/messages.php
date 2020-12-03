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
  
  $module_messages = new Smarty;
  
  // assign plugin data
  if(isset($_SESSION['plugin_messages'])) {
    $module_messages->assign('plugin_messages', $_SESSION['plugin_messages']);
    $plugin_admin->removeMessages();
  }
  
	if (isset($hook->plugin_manager_panel_php_before_fetch))(eval($hook->plugin_manager_panel_php_before_fetch));
	
	$module_messages_module = $module_messages->fetch( $registry->plugin_manager['fs_app_template'].'modules/messages.html' );
	$smarty->assign( 'plugin_messages',$module_messages_module );
  
	if (isset($hook->plugin_manager_panel_php_bottom))(eval($hook->plugin_manager_panel_php_bottom));
?>