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
  
  
  
if ($_SESSION['customers_status']['customers_fsk18_display'] == '0')
	$fsk_lock = ' and p.products_fsk18!=1 ';
if (GROUP_CHECK == 'true')
	$group_check=" and p.group_permission_".$_SESSION['customers_status']['customers_status_id']."=1 ";


/**
 * neue produkte
 *
 */
function topproducts($size = false){
	global $product; 
	
	$size = ($size) ? ' limit '.$size : false;
	$query = "SELECT * 
						from ".TABLE_PRODUCTS." p, ".TABLE_PRODUCTS_DESCRIPTION." pd 
						where p.products_id=pd.products_id and p.products_startpage = '1' ".$group_check." ".$fsk_lock." and p.products_status = '1' 
						and pd.language_id = '".$_SESSION['languages_id']."' 
						order by p.products_startpage_sort DESC"
						.$size;	
	$query = xtDBquery($query);
	
	while ($listing = xtc_db_fetch_array($query, true)) {
		$products[] = $product->buildDataArray($listing);
	}

	$return['status'] = (xtc_db_num_rows($query, true) >= 1) ? true : false;
	$return['data'] = $products;
	
	return $return;
}



/**
 * neue produkte
 *
 */
function newproducts($size = false){
	global $product,$fsk_lock,$group_check; 
	
	$size = ($size) ? ' limit '.$size : false;
	$query = "select distinct 
              p.products_id,
              p.products_fsk18,
              pd.products_name,
              pd.products_short_description,
              p.products_image,
              p.products_price,
              p.products_vpe,
              p.products_vpe_status,
              p.products_vpe_value,
              p.products_tax_class_id,
              p.products_shippingtime,
              p.products_date_added
              from ".TABLE_PRODUCTS." p
                left join " . TABLE_PRODUCTS_DESCRIPTION . " pd on p.products_id = pd.products_id
              where pd.language_id = '".(int) $_SESSION['languages_id']."'
              and products_status = '1'
              " . $group_check . "
              " . $fsk_lock . "
              order by p.products_date_added DESC "
              . $size;
	$query = xtDBquery($query);
	
	while ($listing = xtc_db_fetch_array($query, true)) {
		$products[] = $product->buildDataArray($listing);
	}

	$return['status'] = (xtc_db_num_rows($query, true) >= 1) ? true : false;
	$return['data'] = $products;
	
	return $return;
}


/**
 * bestseller produkte
 *
 */
function bestsellers($size){
	require_once (DIR_FS_INC.'xtc_row_number_format.inc.php');
	global $product; 
	
	$size = ($size) ? ' limit '.$size : false;
	
	$query = "select *
				from ".TABLE_PRODUCTS." p, ".TABLE_PRODUCTS_DESCRIPTION." pd
				where p.products_status = '1'
							".$group_check."
							and p.products_ordered > 0
							and p.products_id = pd.products_id ".$fsk_lock."
							and pd.language_id = '".(int) $_SESSION['languages_id']."'
				order by p.products_ordered  desc".
				$size;	
	$query = xtDBquery($query);
	
	if (xtc_db_num_rows($query, true) >= MIN_DISPLAY_BESTSELLERS) {
		$rows = 0;
		
		while ($listing = xtc_db_fetch_array($query, true)) {
			$rows ++; $image = '';
			$listing = array_merge($listing, array('ID'=>xtc_row_number_format($rows)));
			$products[] = $product->buildDataArray($listing);
		}
		
		$return['status'] = (xtc_db_num_rows($query, true) >= 1) ? true : false;
		$return['data'] = $products;
		
		return $return;
	}
}


/**
 * angebots produkte
 *
 */
function specials($size){
	global $product; $size = ($size) ? ' limit '.$size : false;
		
	$query = "select * 
						from ".TABLE_PRODUCTS." p, ".TABLE_PRODUCTS_DESCRIPTION." pd, ".TABLE_SPECIALS." s where p.products_status = '1'
						and p.products_id = s.products_id and pd.products_id = s.products_id and pd.language_id = '".$_SESSION['languages_id']."' 
						and s.status = '1' ".$group_check." ".$fsk_lock." 
						order by s.specials_date_added desc ".
						$size;
	$query = xtDBquery($query);
	
	while ($listing = xtc_db_fetch_array($query, true)) {
		$products[] = $product->buildDataArray($listing);
	}
	
	$return['status'] = (xtc_db_num_rows($query, true) >= 1) ? true : false;
	$return['data'] = $products;
	
	return $return;
}

/**
 * init slider js
 *
 */
function initSliderJs( $type,$interval ) {
  global $plugin, $hook;
  
  if ( $plugin->isInstalled('sq_minify') ) {
    $_hook = $hook->__get('index_html_bottom');
    $hook->__set('index_html_bottom', $_hook . 'echo "<script type=\"text/javascript\"> $(\"#'.$type.'_slider\").carousel({ interval: '.$interval.' }) </script>";');
    return true;
  }
  return '<script type="text/javascript"> $("#'.$type.'_slider").carousel({ interval: false }) </script>'; 
}

/**
 * main function
 *
 */
function smarty_function_productbrowser($params=false, &$smarty) {
  global $plugin, $hook;
	if (!$params) return;
	
	$productbrowser = new smarty;
	
	$tpl_path			= '/module/product_browser/';
	$type 			  = $params['type'];
	$allowed			= function_exists($params['type']);
	$template			= ($params['template']) ? $tpl_path.$params['template'] : $tpl_path.'default.html';
	$iems_per_row	= ($params['items_per_row']) ? $params['items_per_row'] : 4;
	$heading      = ( defined( $params['heading'] ) != '') ? constant( $params['heading'] ) : $params['heading'];
	
	if ($allowed)
		$code = $params['type']($params['show']);
	else
		$code['error'] = '<div class="message">Productbrowser: Der Parameter <strong>&quot;type&quot;</strong> ist unzulässig!<br />Zul&auml;ssige Parameter: <strong>&quot;newproducts&quot;,&quot;bestsellers&quot;,&quot;specials&quot;</strong></div>';
	
	$code['heading'] = $heading;
	$code['type'] = $params['type'];
	
	$productbrowser->assign('content' , $code);
	$productbrowser->assign('items_per_row' , $iems_per_row);
	$productbrowser->assign('tpl_path' , 'templates/'.CURRENT_TEMPLATE.'/');
	
	if ( strpos($template, 'slider') !== false ) {
    $js = initSliderJs( $code['type'],'false' );
    if ( $js !== true )
    	$productbrowser->assign( 'init_js', $js );
	}
	
	return ($code['status']) ? $productbrowser->fetch(CURRENT_TEMPLATE.$template) : false;
}
?>