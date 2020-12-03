<?php
	if (isset($hook->plugin_manager_applicantion_php_top))(eval($hook->plugin_manager_applicantion_php_top));
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
  
  
  
  
	
  error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT);
  ini_set('display_errors', true);
  
	if ( !$valid_call ) {
    echo '<div style="padding: 15px 35px; border: 1px solid transparent; border-radius: 4px; color: #a94442; background-color: #f2dede; border-color: #ebccd1;"><h1>Diese Seite ist nicht direkt aufrufbar! </h1></div>';
    exit;
	}
	define ( '_VALID_XTC',true );
	
  $smarty = new Smarty;
  
  require_once (DIR_WS_INCLUDES . 'classes/language.php');
  require_once (DIR_FS_CATALOG . 'templates/' . CURRENT_TEMPLATE . '/source/lib/template.php');
  require_once ($registry->lib . 'classes/class.plugin_admin.php');
  require_once ($registry->lib . 'helper/helper.plugin_admin.php');
  
  $registry->__set('plugin_manager', array(
    'fs_app'              => DIR_FS_CATALOG.'templates/'.CURRENT_TEMPLATE.'/source/plugin_manager/',
    'fs_app_includes'     => DIR_FS_CATALOG.'templates/'.CURRENT_TEMPLATE.'/source/plugin_manager/includes/',
    'fs_app_template'     => DIR_FS_CATALOG.'templates/'.CURRENT_TEMPLATE.'/source/plugin_manager/template/',
    'ws_app'              => DIR_WS_CATALOG.'templates/'.CURRENT_TEMPLATE.'/source/plugin_manager/',
    'ws_app_includes'     => DIR_WS_CATALOG.'templates/'.CURRENT_TEMPLATE.'/source/plugin_manager/includes/',
    'ws_app_template'     => DIR_WS_CATALOG.'templates/'.CURRENT_TEMPLATE.'/source/plugin_manager/template/',
    'ws_tpl_path'         => DIR_WS_CATALOG.'templates/'.CURRENT_TEMPLATE.'/'
  ));
  
  if ( file_exists( $registry->plugin_manager['fs_app_includes'].'lang/'.$_SESSION['language_code'].'/lang.php' ) )
    include_once ($registry->plugin_manager['fs_app_includes'].'lang/'.$_SESSION['language_code'].'/lang.php');
  else
    include_once ($registry->plugin_manager['fs_app_includes'].'lang/de/lang.php');
  
	$pluginId   = (!empty( $_GET['plg'] ) ) ? (string)$_GET['plg'] : false;
	$action     = (  !empty( $_GET['action'] ) ) ? (string)$_GET['action'] : false;
  $pageType   = ( $pluginId ) ? ( $plugin->isInstalled( $pluginId ) ) ? 'panel' : 'install' : 'dashboard';
  
  if ( !$_GET['page'] )
    $app_page = 'plugin_manager';
  elseif ( $_GET['page'] == 'template_helpcenter' )
    $app_page = 'template_helpcenter';
  elseif ( $_GET['page'] == 'plugin_store' )
    $app_page = 'plugin_store';
  $smarty->assign( 'app_page',$app_page );
  
  $main_menu_data = array(
    'plugin_manager'      => array( 'link' => xtc_href_link( 'plugin_manager.php' ),'link_text' => global_linktext_plugin_manager, 'icon' => 'icon-list', 'current' => ( $app_page == 'plugin_manager' ) )// ,
    // 'template_helpcenter' => array( 'link' => xtc_href_link( 'plugin_manager.php','page=template_helpcenter' ),'link_text' => global_linktext_template_helpcenter, 'icon' => 'icon-question-sign', 'current' => ( $app_page == 'template_helpcenter' ) ),
    // 'plugin_store'        => array( 'link' => xtc_href_link( 'plugin_manager.php','page=plugin_store' ),'link_text' => global_linktext_plugin_store, 'icon' => 'icon-shopping-cart', 'current' => ( $app_page == 'plugin_store' ) ),
  );
  $smarty->assign( 'main_menu_data', $main_menu_data );
  $smarty->assign( 'link_plugin_overview', xtc_href_link( 'plugin_manager.php' ) );
  
  $content_file = ( $app_page == 'plugin_manager' ) ? $pageType.'.php' : $app_page.'.php';
  include_once ($registry->plugin_manager['fs_app_includes'].'modules/header.php');
  include_once ($registry->plugin_manager['fs_app_includes'].$content_file);
  include_once ($registry->plugin_manager['fs_app_includes'].'modules/footer.php');
  include_once ($registry->plugin_manager['fs_app_includes'].'modules/messages.php');
  
  // installer actions
  if ($action && $action == 'uninstall')
    uninstaller( $pluginId );
  
  if ($action && $action == 'reinstall')
    reinstaller( $pluginId );
  
  if($action == 'update' && (isset($_GET['set_status'])))
    updateStatus($pluginId, (int)$_GET['set_status']);
  
  $smarty->assign( 'plugin_data', getPluginData( $pluginId ) );
	$smarty->assign( 'menu_data', getMenuData() );
  
  $smarty->assign('language', $_SESSION['language']);
  $smarty->assign('ws', HTTP_SERVER.DIR_WS_CATALOG.'templates/'.CURRENT_TEMPLATE.'/source/plugin_manager/');
  $smarty->assign('tplpath', HTTP_SERVER.DIR_WS_CATALOG.'templates/'.CURRENT_TEMPLATE.'/');
  $smarty->assign('link', 'templates/'.CURRENT_TEMPLATE.'/source/plugin_manager/');
  $smarty->assign('imgpath', 'templates/'.CURRENT_TEMPLATE.'/source/plugin_manager/');
  $smarty->assign('page_type', $pageType);
  
  $smarty->display( $registry->plugin_manager['fs_app_template'] . 'application.html' );
  
	if (isset($hook->plugin_manager_applicantion_php_bottom))(eval($hook->plugin_manager_applicantion_php_bottom));
?>