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



/*
 * 
 * Setzt die richtige "formaction" des Express-Kauf Buttons
 * 
 */
function wishlistFormaction()
{
	$query_string = 'wishlist=1&amp;action=add_product';

	return xtc_href_link(FILENAME_PRODUCT_INFO, xtc_get_all_get_params().$query_string);
}

/*
 *
 * Setzt die richtige "formaction" des Express-Kauf Buttons
 *
 */
function expressBuyFormaction()
{
	$query_string = 'express=1&amp;action=add_product';

	return xtc_href_link(FILENAME_PRODUCT_INFO, xtc_get_all_get_params().$query_string);
}

/*
 *
 * gibt den prozentualen durchschnittswert der bewertungen aus
 *
 */
function getReviews($pid)
{
	$_query = xtc_db_query("select avg(`reviews_rating`) as average from reviews where products_id='".$pid."'");
	if (xtc_db_num_rows($_query, true)) {
		$_data = xtc_db_fetch_array($_query);

		return $_data['average'] * 100 / 5;
	}

	return 0;
}

/*
 * 
 * gibt die bewertungsdaten zurueck
 * 
 */
function getReviewsData($pid)
{
	$_query = xtc_db_query("select avg(`reviews_rating`) as average, count(`reviews_rating`) as counter from reviews where products_id='".$pid."'");
	if (xtc_db_num_rows($_query, true)) {
		$_data = xtc_db_fetch_array($_query);
  	$data['count']          = $_data['counter'];
  	$data['average']        = round($_data['average'] * 2) / 2;
	}
	$data['write_link']       = xtc_href_link(FILENAME_PRODUCT_REVIEWS_WRITE, 'products_id='. $pid);
	$data['write_text']       = IMAGE_BUTTON_WRITE_REVIEW;
  
  if ($_SESSION['customers_status']['customers_status_write_reviews'] == 1)
  	$data['status_write']   = true;
  	
	return (isset($data)) ? $data : false;
}

/**
 * prueft ob das produkt ein sonderangebot ist
 */
function isSpecial($pid)
{
	$product_query = xtDBquery("select specials_new_products_price from " . TABLE_SPECIALS . " where products_id = '" . $pid . "' and status=1");
	$product = xtc_db_fetch_array($product_query, true);
	
	return ($product['specials_new_products_price']) ? true : false;
}


/*
 * 
 * gibt die hersteller daten zu dem produkt aus
 * 
 */
function getManufacturersData($pid) {
  
	$query = xtDBquery("select 
		m.manufacturers_id, m.manufacturers_name, m.manufacturers_image, mi.manufacturers_url 
		from ".TABLE_MANUFACTURERS." m left join ".TABLE_MANUFACTURERS_INFO." mi 
		on (m.manufacturers_id = mi.manufacturers_id and mi.languages_id = '" . (int)$_SESSION['languages_id']."'), ".TABLE_PRODUCTS." p  
		where p.products_id = '".$pid."' and p.manufacturers_id = m.manufacturers_id"
	);
	
	if (xtc_db_num_rows($query,true)) {
		$data = xtc_db_fetch_array($query,true); $image = '';
		if (xtc_not_null($data['manufacturers_image'])) $image = DIR_WS_IMAGES.$data['manufacturers_image'];
		
		$return['image'] = $image;
		$return['name'] = $data['manufacturers_name'];
		$return['link'] = xtc_href_link( FILENAME_DEFAULT, xtc_manufacturer_link($data['manufacturers_id'],$data['manufacturers_name']) );
		if ($data['manufacturers_url']!='')
  		$return['website'] = xtc_href_link(FILENAME_REDIRECT,'action=manufacturer&'.xtc_manufacturer_link($data['manufacturers_id'],$data['manufacturers_name']));
		
		return $return;
	}
}

/*
 * 
 * gibt den unformatierten preis zurueck
 * 
 */
function getRawCurrentPrice($pid) {
  global $xtPrice;
  
  $sql = xtc_db_query("select products_price, products_tax_class_id from ".TABLE_PRODUCTS." where products_id = '".(int)$pid."'");
  if(xtc_db_num_rows($sql)) {
    $productsData = xtc_db_fetch_array($sql);
    $products_price = $xtPrice->xtcGetPrice($pid, $format = true, 1, $productsData['products_tax_class_id'], $productsData['products_price'], 1);
    return $products_price['plain'];
  }
  else
    return false;
}

/**
 * gibt den prozentualen durchschnittswert der bewertungen aus
 */
/*
 * 
 * gibt die bewertungsdaten zurueck
 * 
 */
function getProductObject($pid)
{
	global $product;
	
	if (!is_object($product) && !$product->isProduct())
	  return null;
	
  // include needed functions
  require_once (DIR_FS_INC.'xtc_row_number_format.inc.php');
  require_once (DIR_FS_INC.'xtc_date_short.inc.php');
  
	$data = $product->data;
	$data['reviews'] = $product->getReviews();
	$data['image']['thumbnail'] = $product->productImage($data['products_name'], 'thumbnail');
	$data['image']['info'] = $product->productImage($data['products_name'], 'info');
	$data['image']['popup'] = $product->productImage($data['products_name'], 'popup');

  return($data);
}

/*
 * 
 * smarty hauptfunktion
 * 
 */
function smarty_function_productData($params, &$smarty) 
{
	$pid 		= ((int)$params['pid'] > 0) ? (int)$params['pid'] : false;
	$assign = ($params['assign']) ? $params['assign'] : false;
	
	if (! $pid && ! is_callable($params['function']))
		return null;

	if ($assign && ! is_object($smarty))
		$smarty = new Smarty;

	return ($assign) ? $smarty->assign($params['assign'], $params['function']($pid)) : $params['function']($pid);
}
?>