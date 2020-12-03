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
  
  
  
  
/**
 * hauptfunktione outputfilter
 *
 * @params $content string
 * @params $smarty object
 * @return mixed 
 */
function smarty_outputfilter_sq_widget( $content,&$smarty ) {
  global $registry, $plugin;
  
  if ( !file_exists( $registry->plg . 'sq_widget/classes/class.sq_widget.php' ) && !file_exists( $registry->plg . 'sq_widget/sq_widgets.php' ) )
    return;
  
  if ( !isset( $sq_widgets_obj ) ) {
    require_once( $registry->plg . 'sq_widget/classes/class.sq_widget.php' );
    $sq_widgets_obj = new Sq_Wisgets;     
  }
  
  require_once( $registry->plg . 'sq_widget/sq_widgets.php' );
	return $sq_widgets_obj->run_sq_widget( $content );
}
?>