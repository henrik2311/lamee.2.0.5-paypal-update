<?php 
  
  function getSettingsLink () {
    $link = xtc_href_link('plugin_manager.php','plg=template');
    return '<a class="btn btn-lg btn-default" target="_blank" href="'.$link.'"><span class="icon-white icon-cog"></span>Einstellungen</a>';
  }
  
?>