<?php

$plugin_admin = new Plugin_Admin();

/**
 * getBoxData function.
 *
 * @access public
 * @return void
 */
function getBoxData()
{
  global $plugin, $plugin_admin;

  $boxData = $plugin->data;

  foreach ($boxData as $key=>$data) {
    $pluginId = (string)$data['id'];
    $plugin_admin->_set($pluginId);

    $required[$pluginId] = $data['required'];

    $boxData[$pluginId]['installed'] = ($plugin_admin->isInstalled());
    $boxData[$pluginId]['plugin_status'] = $plugin_admin->getPluginStatus();
    $boxData[$pluginId]['link_install'] = xtc_href_link(basename($_SERVER['PHP_SELF']), xtc_get_all_get_params().'plg='.$pluginId.'&action=install');
    $boxData[$pluginId]['link_uninstall'] = xtc_href_link(basename($_SERVER['PHP_SELF']), xtc_get_all_get_params().'plg='.$pluginId.'&action=uninstall');
    $boxData[$pluginId]['link_reinstall'] = xtc_href_link(basename($_SERVER['PHP_SELF']), xtc_get_all_get_params().'plg='.$pluginId.'&action=reinstall');
    $boxData[$pluginId]['link_status_active'] = xtc_href_link(basename($_SERVER['PHP_SELF']), xtc_get_all_get_params().'plg='.$pluginId.'&action=update&set_status=1');
    $boxData[$pluginId]['link_status_inactive'] = xtc_href_link(basename($_SERVER['PHP_SELF']), xtc_get_all_get_params().'plg='.$pluginId.'&action=update&set_status=0');

    $boxData[$pluginId]['link_config_panel'] = xtc_href_link(basename($_SERVER['PHP_SELF']), xtc_get_all_get_params().'plg='.$pluginId.'&action=panel');
  }
  // sort array (required plugins first)
  array_multisort($boxData,SORT_STRING,$required,SORT_ASC);

  return $boxData;
}

/**
 * getBoxData function.
 *
 * @access public
 * @return void
 */
function getMenuData()
{
  global $plugin, $plugin_admin;

  $menuData = $plugin_admin->data;

  if ( !is_array( $menuData ) ) return;

  foreach ( $menuData as $key => $data ) {
    $pluginId = ( string )$data['id'];
    $plugin_admin->_set($pluginId);

    $menuData[$pluginId]['installed']   = ( $plugin_admin->isInstalled() );
    $menuData[$pluginId]['link']        = xtc_href_link( basename( $_SERVER['PHP_SELF'] ), 'plg='.$pluginId );
    $menuData[$pluginId]['active']      = ( $_GET['plg'] == $pluginId );
  }

  return $menuData;
}

/**
 * getPluginData function.
 *
 * @access public
 * @return array
 */
function getPluginData( $pluginId )
{
  global $plugin, $plugin_admin;

  $plugin_admin->_set( $pluginId );
  $installed = ( $plugin_admin->isInstalled() );

  if ( $installed ) {
    $pluginData                     = $plugin_admin->getPluginData();
    $pluginData['installed']        = ( $installed );
    $pluginData['link_uninstall']   = getUninstallLink($pluginId);
    $pluginData['link_status_on']   = getStatusLink($pluginId,'1');
    $pluginData['link_status_off']  = getStatusLink($pluginId,'0');
  } else {
    $pluginData                     = $plugin_admin->getPluginData();
    $pluginData['link_install']     = getInstallLink( $pluginId );
  }

  return $pluginData;
}

/**
 * installer function.
 *
 * @access public
 * @param mixed $pluginId
 * @return void
 */
function installer( $pluginId )
{
  global $plugin_admin;
  $plugin_admin->_set($pluginId);

  if ($plugin_admin->isInstalled()) {
    $plugin_admin->addMessage('info','Das Plugin "'.$plugin_admin->getPluginName().'" ist bereits installiert!');
  } else {
    if ($plugin_admin->install()){
      $plugin_admin->addMessage('success','Das Plugin "'.$plugin_admin->getPluginName().'" wurde erfolgreich installiert!');
    } else {
      $plugin_admin->addMessage('error','Das Plugin "'.$plugin_admin->getPluginName().'" konnte nicht installiert werden!');
    }
  }
  xtc_redirect(xtc_href_link(basename($_SERVER['PHP_SELF']), xtc_get_all_get_params(array('action','status','plg'))));
}

/**
 * uninstaller function.
 *
 * @access public
 * @param mixed $pluginId
 * @return void
 */
function uninstaller($pluginId)
{
  global $plugin_admin;
  $plugin_admin->_set($pluginId);

  if ($plugin_admin->uninstall()){
    $plugin_admin->addMessage('success','Das Plugin "'.$plugin_admin->getPluginName().'" wurde erfolgreich deinstalliert!');
  } else {
    $plugin_admin->addMessage('error','Das Plugin "'.$plugin_admin->getPluginName().'" konnte nicht deinstalliert werden!');
  }
  xtc_redirect(xtc_href_link(basename($_SERVER['PHP_SELF']), xtc_get_all_get_params(array('action','status','plg'))));
}

/**
 * reinstaller function.
 *
 * @access public
 * @param mixed $pluginId
 * @return void
 */
function reinstaller($pluginId)
{
  global $plugin_admin;
  $plugin_admin->_set($pluginId);

  if ($plugin_admin->reinstall()){
    $plugin_admin->addMessage('success','Das Plugin "'.$plugin_admin->getPluginName().'" wurde erneut installiert!');
  } else {
    $plugin_admin->addMessage('error','Das Plugin "'.$plugin_admin->getPluginName().'" konnte nicht erneut installiert werden!');
  }
  xtc_redirect(xtc_href_link(basename($_SERVER['PHP_SELF']), xtc_get_all_get_params(array('action','status','plg'))));
}

/**
 * updateStatus function.
 *
 * @access public
 * @param mixed $pluginId
 * @param mixed $status
 * @return void
 */
function updateStatus($pluginId,$status)
{
  global $plugin_admin;
  $plugin_admin->_set($pluginId);

  if ( $plugin_admin->updatePluginStatus($status) ) {
    $plugin_admin->addMessage('success','Plugin Status von:"'.$plugin_admin->getPluginName().'" wurde erfolgreich gesetzt.');
  } else {
    $plugin_admin->addMessage('error','Plugin Status von:"'.$plugin_admin->getPluginName().'" konnte nicht gesetzt werden.');
  }
  xtc_redirect(xtc_href_link(basename($_SERVER['PHP_SELF']), xtc_get_all_get_params(array('action','status','set_status'))));
}

/**
 * updateStatus function.
 *
 * @access public
 * @param mixed $pluginId
 * @param mixed $status
 * @return void
 */
function getTableData($pluginId)
{
  if (!$pluginId) return false;

  global $plugin_admin;
  $plugin_admin->_set($pluginId);

  $dataTables = $plugin_admin->getPluginDbTables($pluginId);
  if (!is_array($dataTables)) return;

  foreach ($dataTables as $table) {
    $query = xtc_db_query("SELECT * FROM " . $table . $groupQuery);

    if (xtc_db_num_rows($query) > 0) {
      while ($result = xtc_db_fetch_array($query))
        $data[$table][] = $result;
    }
  }

  return (is_array($data)) ? $data : false;
}

/**
 * installer function.
 *
 * @access public
 * @param mixed $pluginId
 * @return void
 */
function getConfigPanel($pluginId)
{
  global $plugin_admin;
  $plugin_admin->_set($pluginId);

  if ($plugin_admin->isInstalled())
    $plugin_admin->getPanel();
}

/**
 * getLinks functions.
 *
 * @access public
 * @return string
 */
function getInstallLink($pluginId){
  return xtc_href_link(basename($_SERVER['PHP_SELF']),'plg='.$pluginId.'&action=install');
}
function getUninstallLink($pluginId){
  return xtc_href_link(basename($_SERVER['PHP_SELF']), 'plg='.$pluginId.'&action=uninstall');
}
function getReinstallLink($pluginId){
  return xtc_href_link(basename($_SERVER['PHP_SELF']), 'plg='.$pluginId.'&action=reinstall');
}
function getStatusLink($pluginId,$status){
  return xtc_href_link(basename($_SERVER['PHP_SELF']), 'plg='.$pluginId.'&action=update&set_status='.(int)$status);
}

?>