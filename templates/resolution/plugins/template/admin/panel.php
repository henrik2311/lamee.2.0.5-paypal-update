<?php
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
  
  
  
  


  define('text_plugin_config','Template-Einstellungen f&uuml;r verschiedene Bereiche');
  
  function updateTableData($pluginId,$table,$data)
  {
    global $plugin_admin;
    
    if (is_array($data)) {
      foreach($data as $key=>$val) {
        if (xtc_db_query("UPDATE ".$table." SET config_value='".$val."' WHERE config_key='".$key."'"))
          $query = true;
        else
          $query = false;
          
        if (!$query) {
          $plugin_admin->addMessage('error','Fehler: Konnte :"'.$key.'" nicht nicht schreiben.');
          return true;
        }
      }
      
      if ($query) {
        $plugin_admin->addMessage('success','Plugin Einstellungen von:"'.$plugin_admin->getPluginName().'" wurde erfolgreich gespeichert.');
        xtc_redirect(xtc_href_link(basename($_SERVER['PHP_SELF']), xtc_get_all_get_params()));
        return true;
      }
    }
    
    $plugin_admin->addMessage('error','Plugin Einstellungen von:"'.$plugin_admin->getPluginName().'" konnten nicht gespeichert werden!');
    return false;
  }
  
  
  $panel_content = new Smarty;
  
  $dbData = getTableData($pluginId);
  if (is_array($dbData)) {
    foreach($dbData as $table=>$data) {
      foreach($data as $index=>$value) {
        $panelData[$value['config_key']] = $value['config_value'];
      }
    }
  }
  
  if ($_POST && $_POST['plugin_update']=='1') {
    /** update plugin table **/
    unset($_POST['plugin_update']);
    updateTableData($pluginId,'plugin_'.$pluginId.'_data',$_POST);
  }
  
	$panel_content->assign('form_action', xtc_href_link(basename($_SERVER['PHP_SELF']), xtc_get_all_get_params()));
	$panel_content->assign('panel_data', $panelData);

	$panel_content->caching = 0;
	$panel = $panel_content->fetch(dirname(__FILE__).'/panel.html');
	$page_panel->assign( 'panel_content',$panel );
  
?>
