<?php
	if (isset($hook->plugin_manager_header_php_top))(eval($hook->plugin_manager_header_php_top));
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
  
  $header_smarty = new Smarty;
  
  // asiign to template
  $header_smarty->assign( 'plg', $registry->plugin_manager );
  
	if (isset($hook->plugin_manager_header_php_before_fetch))(eval($hook->plugin_manager_header_php_before_fetch));
	
  // fetch template
	$header_module = $header_smarty->fetch( $registry->plugin_manager['fs_app_template'].'modules/header.html' );
	$smarty->assign( 'app_header',$header_module );
	
	if (isset($hook->plugin_manager_header_php_bottom))(eval($hook->plugin_manager_header_php_bottom));
?>