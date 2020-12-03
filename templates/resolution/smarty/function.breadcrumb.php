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
 * exclude array keys from breadcrumb
 *
 */
function _exclude( $_keys=false,$arr ) {
	if ( empty( $_keys ) && !isset($_keys ) ) 
		return $arr;
	
	$_excludeKeys	= ( strpos($_keys,',' )===false ) ? $_keys : explode( ',',$_keys );
	
	if ( is_array( $_excludeKeys ) )
		foreach ( $_excludeKeys as $key ){ unset( $arr[$key] ); }
	else
		unset( $arr[$_excludeKeys] );
	
	foreach ( $arr as $_val ) { $return[] = $_val; }
	
	return $return;
}


/**
 * main function
 *
 */
function smarty_function_breadcrumb ( $params,&$smarty ) {
	global $breadcrumb; $output = '';
	
	/* set paramater defaults */
	if ( !$params['current_link'] ) $params['current_link'] = true;
	if ( !$params['itemclass'] ) $params['itemclass'] = 'breadcrumb_link';
	if ( !$params['divider'] ) $params['divider'] = false;
	
	/* remove excluded keys fom breadcrumb array */
	$_crumb = _exclude( $params['exclude'],$breadcrumb->_trail );
	
	/* recount breadcrumb array */
	$_size = count( $_crumb );
	
	/* set list class & id */
	$_listId 		= ( $params['id'] ) ? ' id="' . $params['id'] . '"' : '';
	$_listClass = ( $params['class'] ) ? ' class="' . $params['class'] . '"' : '';
	
	if ( $_size > 1 ){ 
		if  ($params['listmode'] ) $output .= '<ul' . $_listId . $_listClass . '>';
		
		foreach ( $_crumb as $key => $crumb ) { $key = $key+1;
		  /* current item properties */
			$_isFirst = ( $key == 1 );
			$_isLast  = ( $key == $_size );
			$_isHome 	= ( $crumb['link'] == xtc_href_link( FILENAME_DEFAULT ) );
			$_isLink  = ( ( !isset( $params['linkcurrent'] ) ) || $params['linkcurrent']==false && $_isLast ) ? false : true;
			
			/* set first/last class */
			$_class = $item_class;
			if ( $_isFirst ) 	$_class .= ' first';
			if ( $_isLast ) 	$_class .= ' last';
			if ( !$_isLink ) 	$_class .= ' active';
      
      if ($params['divider'])
  			$_divider = ( $key < $_size ) ? '<span class="divider">'.$params['divider'].'</span>' : null;
  		else
  			$_divider = '&nbsp;';
			
			/* start output */
			$output .= ( $params['listmode'] ) ? '<li class="'.$_class.'">' : '';
			$output .= ( $_isLink ) ? '<a title="'.$crumb['title'].'" href="'.$crumb['link'].'">' : null;
			
			$output .= ( $_isHome ) ? '<i class="icon-home"></i>' : $crumb['title'];
			
			$output .= ( $_isLink ) ? '</a>' : null;
			$output .= ( $params['listmode'] ) ? $_divider.'</li>' : $_divider;
		}
		
		if ( $params['listmode'] ) $output .= '</ul>';
	}

	return $output;
}
?>