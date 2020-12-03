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
  
  
  
    
  
  define('system_snippet_group','system');
  
  define('text_plugin_heading','Template Sprachkonstanten');
  define('text_no_own_items','Keine eigenen Sprachkonstanten hinterlegt!');
  define('text_add_new_entry','Neuen Eintrag anlegen');
  define('text_key','Schl&uuml;ssel');
  define('text_group','Gruppe');
  define('text_text','Text');
  define('text_template_tag','Template-Tag');
  define('text_edit','bearbeiten');
  define('text_remove','l&ouml;schen');
  define('text_info_system_text','l&ouml;schen');

  define('text_edit_snippet','Sprachkonstante bearbeiten');
  define('text_new_snippet','Neue Sprachkonstante anlegen');
  define('text_system_snippets','System Sprach-Konstanten');
  define('text_my_snippets','Eigene Sprach-Konstanten');

  define('button_new_snippet','Neue Sprachkonstante anlegen');
  
  define('placeholder_add_description','Beschreibung eingeben...');
  define('placeholder_choose_group','Gruppe ausw&auml;hlen...');
  define('placeholder_add_new_group','neue Gruppe anlegen...');
  define('placeholder_add_text','Text eingeben...');
  
  define('help_key_heading','Schl&uuml;ssel');
  define('help_key_content','Format: &quot;name_des_schluessels&quot; keine Umlaute oder Leerzeichen erlaubt!');
  define('help_description_heading','Beschreibung (optional)');
  define('help_description_content','Beschreiben Sie die Konstante. Die Beschreibung wird nur intern verwendet.');
  define('help_group_heading','Gruppe');
  define('help_group_content','W&auml;hlen Sie eine Gruppe aus der Liste aus oder legen Sie eine neue an. Format: &quot;name_der_gruppe&quot; keine Umlaute oder Leerzeichen erlaubt!');
  
  // Errors
  define('error_no_key','Fehler: Das Feld <strong>Schl&uuml;ssel</strong> darf nicht leer sein. Eintrag wurde <u><strong>nicht</strong></u> gespeichert');
  define('error_key_already_exists','Fehler: Der Schl&uuml;ssel &quot;<strong>&quot;%s&quot;</strong>&quot; existiert bereits. Bitte verwenden Sie einen anderen Schl&uuml;ssel');
  define('error_key_update','Fehler: Konnte <strong>&quot;%s&quot;</strong> nicht aktualisieren.');
  define('error_key_new','Fehler: Konnte <strong>&quot;%s&quot;</strong> nicht erstellen.');
  define('error_cant_delete_constant','Sprachkonstante <strong>&quot;%s&quot;</strong> konnte nicht gel&ouml;scht werden.');
  define('error_page_wrong_id','Fehler: Falscher Aufruf der Datei!');
  define('error_install_new_language','Fehler: Die Sprache <strong>&quot;%s&quot;</strong> konnte nicht installiert werden');
  
  // Success
  define('success_constant_update','Die Sprachkonstante <strong>&quot;%s&quot;</strong> wurde erfolgreich aktualisiert.');
  define('success_constant_add','Die Sprachkonstatnte <strong>&quot;%s&quot;</strong> wurde erfolgreich erstellt.');
  define('success_constant_delete','Sprachkonstante <strong>&quot;%s&quot;</strong> wurde erfolgreich gel&ouml;scht!');
  define('success_install_new_language','Die Sprache <strong>&quot;%s&quot;</strong> wurde erfolgreich installiert.');
  
  /**
   * encode function.
   * 
   * @param string $text
   * @return void
   */
  function encode( $text ) {
    return mysql_real_escape_string( $text );
  }
  
  /**
   * checkPost function.
   * 
   * @param string $field
   * @return bool
   */
  function checkPost( $field ) {
    return ( $field && xtc_not_null( $field ) );
  }
  
  /**
   * checkPost function.
   * 
   * @param string $field
   * @return bool
   */
  function installMissingLanguages( $data ) {
    global $plugin_admin,$pluginId;
    
    $table = $plugin_admin->getPluginDbTables( $pluginId );
    
    if ( !is_object( $lng ) )
      $lng = new language;
    
    if ( is_array( $_POST['install_missing_lang'] ) ) {
      $languages = array_keys( $_POST['install_missing_lang'] );
      $error = ''; $success = '';
      foreach ( $languages as $code ) {
        $insert = array();
        if ( is_array( $data ) ) {
          foreach ( $data as $insert ) {
            $query = xtc_db_query( 'INSERT INTO '.$table['data'].' (
              config_key,
              config_group,
              config_notes,
              language_code,
              language_content
            ) VALUES (
              "'.encode( $insert['config_key'] ).'",
              "'.encode( $insert['config_group'] ).'",
              "'.encode( $insert['config_notes'] ).'",
              "'.encode( $code ).'",
              "'.encode( $insert['language_content'] ).'"
            )');
            
            $insert[] = ( $query );
          }
        }
       
        if ( in_array( false,$insert ) ) {
          $error .= '<p>'.sprintf(error_install_new_language,$lng->catalog_languages[$code]['name']).'</p>';
        } else {
          $success .= '<p>'.sprintf(success_install_new_language,$lng->catalog_languages[$code]['name']).'</p>';
        }
        
      }
      if ( $error != '' )
        $plugin_admin->addMessage('error',$error);
      else if  ( $success != '' )
        $plugin_admin->addMessage('success',$success);
    }
    
    xtc_redirect( xtc_href_link( basename( $_SERVER['PHP_SELF'] ), xtc_get_all_get_params() ) );
    
  }
  
  /**
   * getSnippet function.
   * 
   * @param mixed $id
   * @return mixed
   */
  function getSnippet( $id ) {
    global $plugin_admin,$pluginId;
    
    $table = $plugin_admin->getPluginDbTables( $pluginId );
    $table = $table['data'];
    
    $query = xtc_db_query( " SELECT * FROM ".$table." WHERE config_key='".$id."'" );
		while ($_data = xtc_db_fetch_array($query, true)) {
			$data[$_data['language_code']] = array(
				'config_key'        => $_data['config_key'],
				'config_group'      => $_data['config_group'],
				'config_notes'      => $_data['config_notes'],
				'language_content'  => $_data['language_content'],
			);
		}
    return ( is_array( $data ) ) ? $data : false;
  }
  
  /**
   * updateSnippet function
   * 
   * @return void
   */
  function updateSnippet() {
    global $plugin_admin,$pluginId;
    
    $table = $plugin_admin->getPluginDbTables( $pluginId );
    $table = $table['data'];
    
    $lang = array_keys( $_POST['language_content'] );
    $group = ( !empty( $_POST['config_group'] ) ) ? $_POST['config_group'] : $_POST['config_group_selected'];
    
    if ( is_array( $lang ) && !empty( $lang ) ) {
      foreach ( $lang as $language_code) {
        $query = xtc_db_query( "UPDATE ".$table." SET config_notes = '".encode( $_POST['config_notes'] )."', config_group = '".$group."', language_content = '".encode( $_POST['language_content'][$language_code] )."' WHERE config_key='".$_POST['config_key']."' AND  language_code='".$language_code."'" );
        if ( $query ) {
          $update[] = true;
        } else {
          $update[] = false;
        }
      }
      if ( in_array( false,$update ) ) {
        $plugin_admin->addMessage('error',sprintf(error_key_update,$_POST['config_key']));
      } else {
        $plugin_admin->addMessage('success',sprintf(success_constant_update,(string)$_POST['config_key']));
      }
      xtc_redirect( xtc_href_link( basename( $_SERVER['PHP_SELF'] ), xtc_get_all_get_params( array( 'action','id' ) ) ) );
        
    }
  }
  
  /**
   * addNewSnippet function
   * 
   * @return void
   */
  function newSnippet() {
    global $plugin_admin,$pluginId;
    
    if ( !checkPost( (string)$_POST['config_key'] ) ) {
      // check for empty required config_key
      $plugin_admin->addMessage('error', error_no_key);
      xtc_redirect( xtc_href_link( basename( $_SERVER['PHP_SELF'] ), xtc_get_all_get_params() ) );
    }
    if ( getSnippet( (string)$_POST['config_key'] ) ) {
      // check for double entries in databesa
      $plugin_admin->addMessage('error',error_key_already_exists);
      xtc_redirect( xtc_href_link( basename( $_SERVER['PHP_SELF'] ), xtc_get_all_get_params() ) );
    }
    
    $lang = array_keys( $_POST['language_content'] );
    $group = ( !empty( $_POST['config_group'] ) ) ? $_POST['config_group'] : $_POST['config_group_selected'];
    $table = $plugin_admin->getPluginDbTables( $pluginId );
    $table = $table['data'];
    
    if ( is_array( $lang ) && !empty( $lang ) && $_POST ) {
      foreach ( $lang as $language_code) {
        $query = xtc_db_query( 'INSERT INTO '.$table.' (config_key,config_group,config_notes,language_code,language_content) VALUES ("'.encode( $_POST['config_key'] ).'","'.encode( $group ).'","'.encode( $_POST['config_notes'] ).'","'.encode( $language_code ).'","'.encode( $_POST['language_content'][$language_code] ).'")' );
        if ( $query ) {
          $update[] = true;
        } else {
          $update[] = false;
        }
      }
      if ( in_array( false,$update ) ) {
        $plugin_admin->addMessage('error',sprintf(error_key_new,(string)$_POST['config_key']));
      } else {
        $plugin_admin->addMessage('success',sprintf(success_constant_add,(string)$_POST['config_key']));
      }
      xtc_redirect(xtc_href_link( basename( $_SERVER['PHP_SELF'] ), xtc_get_all_get_params( array( 'action','id' ) ) ) );
    }
    
  }
  
  /**
   * removeSnippet function
   * 
   * @param mixed $id
   * @return bool
   */
  function removeSnippet( $id ) {
    global $plugin_admin,$pluginId;
    
    $table = $plugin_admin->getPluginDbTables( $pluginId );
    $table = $table['data'];
    
    $query = xtc_db_query( "DELETE FROM  ".$table." WHERE config_key='".$id."'" );
    return ( $query ) ? true : false;
  }
  
  /** // functions **/
  
  
  
  if ( !is_object( $lng ) )
    $lng = new language;
  
  $panel_content = new Smarty;
	$panel_content->assign('form_action', xtc_href_link(basename($_SERVER['PHP_SELF']), xtc_get_all_get_params()));
  $panel_content->assign('ws', HTTP_SERVER.DIR_WS_CATALOG.'templates/'.CURRENT_TEMPLATE.'/source/plg/');
  
  /** retrieve plugin data **/
  $dbData   = getTableData( $pluginId );
  $groups = array();
  
  if ( is_array( $dbData ) ) {
    foreach ( $dbData as $plugin => $data ) {
      foreach ( $data as $index => $value ) {
        if ( isset( $lng->catalog_languages[$value['language_code']] ) ) {
          $panelData[$value['language_code']][$value['config_key']] = array (
            'config_key'              => $value['config_key'], 
            'config_group'            => $value['config_group'], 
            'config_notes'            => $value['config_notes'], 
            'language_content'        => $value['language_content'],
            'link_edit'               => xtc_href_link( basename( $_SERVER['PHP_SELF'] ), xtc_get_all_get_params( array( 'id','action' ) ) . 'action=edit_snippet&id='.$value['config_key'] ),
            'link_remove'             => xtc_href_link( basename( $_SERVER['PHP_SELF'] ), xtc_get_all_get_params( array( 'id','action' ) ) . 'action=remove_snippet&id='.$value['config_key'] )
          );
          
          if (!$panelLangData[$value['language_code']]) {
            $panelLangData[$value['language_code']] = array (
              'language_code'         => $value['language_code'],
              'language_icon'         => xtc_image( 'lang/'.$lng->catalog_languages[$value['language_code']]['directory'].'/icon.gif', $lng->catalog_languages[$value['language_code']]['name'] ),
              'language_name'         => $lng->catalog_languages[$value['language_code']]['name']
            );
          }
          
          if ( !in_array( $value['config_group'],$groups ) )
            $groups[] = $value['config_group'];
        }
      }
    }
    $panelLanguage = ( in_array( $lng->catalog_languages[$_SESSION['language_code']]['code'],$panelLangData ) ) ? $lng->catalog_languages[$_SESSION['language_code']]['code'] : 'de';    
    
    if ( !empty( $groups ) )
      $panelData['groups'] = $groups;
  }
  
  
  
  if ( !isset( $_GET['action'] ) || $_GET['action'] == 'panel' ) {
    
    /**
     *
     * _page = panel
     *
     */
    
    // check missing languages
    if ( count( $panelLangData ) < count( $lng->catalog_languages ) ) {
      foreach ( $lng->catalog_languages as $code=>$data ) {
        if ( !isset( $panelLangData[$code] ) ) {
          $missing_languages[] = array(
            'language_code'=>$data['code'],
            'language_icon'=>xtc_image( DIR_WS_CATALOG.'lang/'.$data['directory'].'/'.$data['image'], $data['name'] ),
            'language_name'=>$data['name']
          );
        }
      }
      $panel_content->assign('missing_languages', $missing_languages);
      
      // install missing languages
      if ( is_array( $_POST['install_missing_lang'] ) ) {
        $default_lang = ( isset( $panelData['de'] ) ) ? 'de' : 'en';
        installMissingLanguages( $panelData[$default_lang] );
      }
    }
    
    $panel_content->assign('link_add_new_snippet', xtc_href_link(basename($_SERVER['PHP_SELF']), xtc_get_all_get_params().'action=new_snippet'));
    $panel_content->assign('panel_language_data', $panelLangData);
    $panel_content->assign('panel_data', $panelData);
    $panel_content->assign('panel_language', $panelLanguage);
    
  	$panel = $panel_content->fetch( dirname(__FILE__).'/panel.html' );
    
    
  } else if ( $_GET['action'] == 'edit_snippet' ) {
  	
  	/**
  	 *
  	 * _page = edit snippet
  	 *
  	 */
    
    $id = ( isset( $_GET['id'] ) ) ? $_GET['id'] : false;
    if ( $id ) {
      if ( $_POST ) {
        updateSnippet();
      } else {
        $tplData  = $panelData['de'][$_GET['id']];
        $tplData['groups'] = $panelData['groups'];
        $tplData['lang_data'] = $panelLangData;
        
        foreach ( $panelLangData as $lang=>$vars ) {
          $tplData['lang_data'][$lang]['language_content'] = $panelData[$lang][$_GET['id']]['language_content'];
        }
      }
      
    	$panel_content->assign( 'tplData',$tplData );
    } else {
      $plugin_admin->addMessage( 'error', error_page_wrong_id );
    }
    
    $panel = $panel_content->fetch(dirname(__FILE__).'/edit_snippet.html');
     
     
  } else if ( $_GET['action'] == 'new_snippet' ) {
  	
  	/**
  	 *
  	 * _page = new snippet
  	 *
  	 */
    
    if ( $_POST ) {
      newSnippet();
    } else {
      $tplData  = $panelData['de'][$_GET['id']];
      $tplData['groups'] = $panelData['groups'];
      $tplData['lang_data'] = $panelLangData;
      
      foreach ( $panelLangData as $lang=>$vars ) {
        $tplData['lang_data'][$lang]['language_content'] = $panelData[$lang][$_GET['id']]['language_content'];
      }
    	$panel_content->assign( 'tplData',$tplData );
    }
    
    $panel = $panel_content->fetch(dirname(__FILE__).'/new_snippet.html');
     
     
  } else if ( $_GET['action'] == 'remove_snippet' ) {
  	/**
  	 *
  	 * _page = new language
  	 *
  	 */
    
    $id = ( isset( $_GET['id'] ) ) ? $_GET['id'] : false;
    if ( $id && removeSnippet( $id ) ) {
      $plugin_admin->addMessage( 'success',sprintf(success_constant_delete,(string)$id) );
    } else {
      $plugin_admin->addMessage( 'error', sprintf(error_cant_delete_constant,$id) );
    }
    xtc_redirect( xtc_href_link( basename( $_SERVER['PHP_SELF'] ), xtc_get_all_get_params( array( 'action','id' ) ) ) );
    
    $panel = $panel_content->fetch(dirname(__FILE__).'/panel.html');
     
     
  }
  
	$page_panel->assign( 'panel_content',$panel );
	
?>
