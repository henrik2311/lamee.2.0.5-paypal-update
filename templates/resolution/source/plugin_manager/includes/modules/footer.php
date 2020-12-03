<?php
	if (isset($hook->plugin_manager_footer_php_top))(eval($hook->plugin_manager_footer_php_top));
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
  
  $footer_smarty = new Smarty;
  
  // asiign to template
  $footer_smarty->assign( 'plg', $registry->plugin_manager );
  
	if (isset($hook->plugin_manager_footer_php_before_fetch))(eval($hook->plugin_manager_footer_php_before_fetch));
	
  // fetch template
	$footer_module = $footer_smarty->fetch( $registry->plugin_manager['fs_app_template'].'modules/footer.html' );
	$smarty->assign( 'app_footer',$footer_module );
  
	if (isset($hook->plugin_manager_footer_php_bottom))(eval($hook->plugin_manager_footer_php_bottom));
?>