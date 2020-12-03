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
  

function smarty_function_sq_lagerampel($params=array(), &$smarty) {
  global $registry, $plugin;
  
  if ( !$params['pid'] )
    return;
  
  $tmeplate = new Smarty;
  $product = new product($params['pid']);
  $product_qty = $product->data['products_quantity'];
  
  $sq_lagerampel = $plugin->getPluginDbData( 'sq_lagerampel' );
  if ( is_array( $sq_lagerampel ) ) {
    foreach( $sq_lagerampel as $key=>$val ) {
      foreach( $val as $index=>$value ) {
        $data[$value['config_key']] = $value['config_value'];
      }
    }
  }
  
  if ( $product_qty > $data['stock_min_yellow'] )
    $status = 'green';
  elseif ( $product_qty <= $data['stock_min_yellow'] && $product_qty > $data['stock_min_orange'] )
    $status = 'yellow';
  elseif ( $product_qty <= $data['stock_min_orange'] && $product_qty > $data['stock_min_red'] )
    $status = 'orange';
  elseif ( $product_qty <= $data['stock_min_red'] )
    $status = 'red';

  if ( $params['showtext'] == 1 ) {
    if ( defined( 'sq_lagerampel_status_'.$status ) )
      $text = '<span class="sq_lagerampel_text">' . constant( 'sq_lagerampel_status_'.$status ) . '</span>';
    else
      $text = '<span class="sq_lagerampel_text">' . $data['frontend_text_'.$status] . '</span>';
  }
  
  $textposition = ( !$params['textposition'] || $params['textposition'] == 'after' ) ? 'after' : 'before';
  
  if ( $params['customclass'] )
    $customclass = ' ' . $params['customclass'];
  
  $tpl_output = '<span class="sq_lagerampel '. 'textposition_' . $textposition . $customclass . '" title="' . $data['frontend_text_'.$status] . '">';
  
  if ( $params['textposition'] == 'before' )
    $tpl_output .= $text;
  if ( $status == 'green' ) {
    $tpl_output .= '<span class="icon-stop" style="color: '. $data['color_'.$status] .'"></span>';
    $tpl_output .= '<span class="icon-stop" style="color: '. $data['color_'.$status] .'"></span>';
    $tpl_output .= '<span class="icon-stop" style="color: '. $data['color_'.$status] .'"></span>';
  } elseif ( $status == 'yellow' ) {
    $tpl_output .= '<span class="icon-stop" style="color: '. $data['color_'.$status] .'"></span>';
    $tpl_output .= '<span class="icon-stop" style="color: '. $data['color_'.$status] .'"></span>';
    $tpl_output .= '<span class="icon-stop"></span>';
  } elseif ( $status == 'orange' ) {
    $tpl_output .= '<span class="icon-stop" style="color: '. $data['color_'.$status] .'"></span>';
    $tpl_output .= '<span class="icon-stop"></span>';
    $tpl_output .= '<span class="icon-stop"></span>';
  } elseif ( $status == 'red' ) {
    $tpl_output .= '<span class="icon-stop" style="color: '. $data['color_'.$status] .'"></span>';
    $tpl_output .= '<span class="icon-stop" style="color: '. $data['color_'.$status] .'"></span>';
    $tpl_output .= '<span class="icon-stop" style="color: '. $data['color_'.$status] .'"></span>';
  }
  if ( !$params['textposition'] || $params['textposition'] == 'after' )
    $tpl_output .= $text;
  $tpl_output .= '</span>';
  
	return $tpl_output;
}

?>