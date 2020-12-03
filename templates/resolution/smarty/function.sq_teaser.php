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
  
  

function convertLink( $link,$type ) {
  switch( $type ) {
    case '1': $result = ( strpos( $link,'http://' ) === false ) ? 'http://'.$link : $link; break;
    case '2': $result = xtc_href_link( FILENAME_PRODUCT_INFO,xtc_product_link( $link ) ); break;
    case '3': $result = xtc_href_link( FILENAME_DEFAULT,xtc_category_link( $link ) ); break;
    case '4': $result = xtc_href_link( FILENAME_CONTENT,'coID='.$link ); break;
  }
  return ( $result ) ? $result : $link; 
}

function getImageUrl( $filename ) {
  global $plugin;
  $path = DIR_WS_IMAGES.'banner/';
  
  return ( $filename ) ? $path.$filename : false; 
}


function initTeaserJs( $set_key,$interval ) {
  global $plugin, $hook;
  
  if ( $plugin->isInstalled('sq_minify') ) {
    $_hook = $hook->__get('index_html_bottom');
    $hook->__set('index_html_bottom', $_hook . 'echo "<script type=\"text/javascript\"> $(\"#teaser_set_'.$set_key.'\").carousel({ interval: '.$interval.' }) </script>";');
    return true;
  }
  return '<script type="text/javascript"> $("#teaser_set_'.$set_key.'").carousel({ interval: 5000 }) </script>'; 
}

function smarty_function_sq_teaser($params=array(), &$smarty) {
  global $registry, $plugin, $device;
  
  if ( $plugin->isInstalled('sq_teaser') == true && ( $device == 'mobile' && $params['mobile'] == '0' ) )
    return;
  
  $sq_smarty = new Smarty;
  $sq_teaser = $plugin->getPluginDbData( 'sq_teaser' );
  
  $settings['indicators'] = ( $params['indicators'] || $params['indicators'] != '0' || $params['indicators'] != 0 );
  $settings['caption']    = ( $params['caption'] || $params['caption'] != '0' || $params['caption'] != 0  );
  
  $curr_lang_id = $_SESSION['languages_id'];
  
  $set_data   = $sq_teaser['plugin_sq_teaser_data'];
  $media_data = $sq_teaser['plugin_sq_teaser_media'];
  
  if ( is_array( $sq_teaser ) && count( $sq_teaser ) > 0 ) {
    foreach($set_data as $set) {
      if ( $set['teaser_set_key'] == $params['key']) {
        $teaser_set_data  = array(
          'teaser_set_key'=>$set['teaser_set_key'],
          'teaser_set_id'=>$set['teaser_set_id'],
          'teaser_set_title'=>$set['teaser_set_title']
        );
        
        if ( is_array( $media_data ) && isset( $teaser_set_data ) ) {
          foreach($media_data as $media) {
            if ( $media['teaser_set_id'] == $set['teaser_set_id'] && $curr_lang_id == $media['teaser_languages_id'] && $media['teaser_status'] == '1' ) {
              
              $sorting[] = $media['sorting'];
              
              $teaser_set_data['teaser_set_media'][] = array(
                'teaser_title'=> $media['teaser_title'],
                'is_external'=> ( $media['teaser_url_typ'] == '1' ),
                'teaser_url'=> convertLink( $media['teaser_url'],$media['teaser_url_typ'] ),
                'teaser_description'=> $media['teaser_description'],
                'teaser_image'=> getImageUrl( $media['teaser_image'] ),
              );
            }
          }
        }
        
        if ( !empty( $teaser_set_data['teaser_set_media'] ) )
          array_multisort($teaser_set_data['teaser_set_media'],SORT_STRING,$sorting,SORT_ASC);
      }
    }
  }
	$sq_smarty->assign( 'settings',  $settings );
	$sq_smarty->assign( 'set_key',   $teaser_set_data['teaser_set_key'] );
	$sq_smarty->assign( 'set_id',    $teaser_set_data['teaser_set_id'] );
	$sq_smarty->assign( 'set_title', $teaser_set_data['teaser_set_title'] );
	$sq_smarty->assign( 'set_media', $teaser_set_data['teaser_set_media'] );
  
  $js = initTeaserJs( $teaser_set_data['teaser_set_key'],5000 );
  if ( $js !== true ) {
  	$sq_smarty->assign( 'init_js', $js );
  }
  
	return ( $teaser_set_data ) ? $sq_smarty->fetch($registry->ws_plg . 'sq_teaser/templates/' . $params['template']) : false;
}

?>