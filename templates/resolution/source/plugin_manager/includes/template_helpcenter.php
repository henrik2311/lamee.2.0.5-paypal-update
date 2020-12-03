<?php
	if (isset($hook->plugin_manager_template_helpcenter_php_top))(eval($hook->plugin_manager_template_helpcenter_php_top));
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
  
  $_helpcenter_domain = 'support.squidio.de/helpcenters/';
  $_tpl_data = i();
  
  $helpcenter_link = $_helpcenter_domain.$_tpl_data['template_id'].'/'.$_tpl_data['shop_system'].'/'.$_tpl_data['shop_version'].'/';
  
  // DEV
  $helpcenter_link = 'http://support.squidio.de/helpcenters/veyton4014/evolution_1/';
  
  // assign plugin data
  $page_dashboard->assign( 'helpcenter_link', $helpcenter_link );
  
	if (isset($hook->plugin_manager_template_helpcenter_php_before_fetch))(eval($hook->plugin_manager_template_helpcenter_php_before_fetch));
	
	$page_dashboard_module = $page_dashboard->fetch( $registry->plugin_manager['fs_app_template'].'helpcenter.html' );
	$smarty->assign( 'app_content',$page_dashboard_module );

	if (isset($hook->plugin_manager_template_helpcenter_php_bottom))(eval($hook->plugin_manager_template_helpcenter_php_bottom));
?>