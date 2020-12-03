<?php 


####################################################
####################################################
##                                                ##
## k1_options_images_functions.inc.php            ##
## 10.02.13                                       ##
## (c) k1 mediendesign henrik kierbaum            ##
## www.k1-mediendesign.de                         ##
##                                                ##
####################################################
####################################################

//include('includes/configure.php');
// A. Funktion zum Auslesen aller Produktattribute


function k1_get_options_image($products_options_values_id) {
	$sql = "SELECT products_options_image FROM ".TABLE_PRODUCTS_OPTIONS_VALUES." WHERE language_id = '" . $_SESSION['languages_id'] . "' AND products_options_values_id = '".$products_options_values_id."'";
	//echo $sql."<br>";
	$res = xtc_db_query($sql);
	$row = xtc_db_fetch_array($res);
	return $row['products_options_image'];
}

// A
function k1_get_available_options_images($products_id) {
	
	// a)
	// Welche Optionen spielen bei diesem Produkt eine Rolle und wie heißen sie in der aktuellen Sprache?
	$sql = "SELECT DISTINCT pa.options_id, po.products_options_name FROM ".TABLE_PRODUCTS_ATTRIBUTES." pa";
	$sql.= " LEFT JOIN ".TABLE_PRODUCTS_OPTIONS." po ON (po.products_options_id = pa.options_id)";
	$sql.= " WHERE po.language_id = '" . $_SESSION['languages_id'] . "' AND products_id  = '" . $products_id . "'";
	$sql.= " ORDER BY po.products_options_sortorder ASC";
	//echo $sql."<br>";
	$options_res = xtc_db_query($sql);


	// Alle Optionen durchgehen und für diese die Optionenbilder anzeigen, die für die Produktoptionen gelten
	$products_options_images_array = array();
	while($option = xtc_db_fetch_array($options_res)) {
	
		// b)
		// Welche Optionsbilder gibt es zu dieser Option bei diesem Produkt?
		$options_id		= $option['options_id'];
		$options_name	= $option['products_options_name'];
		// Grunddaten wie Name der Option übermittelten, wenn mehr als eine Option vorhanden ist und die Optionen unterschieden werden müssen
		if(xtc_db_num_rows($options_res) > 1) {
			$products_options_images_array[$options_id]['NAME'] = $options_name;
			}
		// ID aber in jedem Fall übermitteln, weil sie für die Optionselemente-IDs notwendig ist
		$products_options_images_array[$options_id]['ID']	= $options_id;

		$sql = "SELECT DISTINCT pa.options_id, pa.options_values_id, pov.products_options_values_name, pov.products_options_image";
		$sql.= " FROM ".TABLE_PRODUCTS_ATTRIBUTES." pa";
		$sql.= " LEFT JOIN ".TABLE_PRODUCTS_OPTIONS_VALUES." pov ON (pa.options_values_id = pov.products_options_values_id)";
		$sql.= " WHERE pa.options_id = '" . $options_id . "' AND pa.products_id  = '" . $products_id . "'";
		$sql.= " AND pov.language_id = '" . $_SESSION['languages_id'] . "' ";
		$sql.= " ORDER BY pov.products_options_values_name ASC";
		//echo $sql."<br>";
		$attributes_res = xtc_db_query($sql);


		// Liste der Optionsbilder der Produkt-Attribute erstellen
		while($row = xtc_db_fetch_array($attributes_res)) {
			$options_values_id = $row['options_values_id'];

			$attr_image = "";
			$attr_image = DIR_WS_INFO_IMAGES.$aimg;
			$attr_image_zoom = "";
			
			$attr_image_zoom = DIR_WS_POPUP_IMAGES.$aimg;
			$products_options_images_array[$options_id]['DATA'][] = array(
				'options_id' => $options_id, 
				'options_name' => $options_name, 
				'options_values_id' => $options_values_id, 
				'name' => $row['products_options_values_name'], 
				'image' => DIR_WS_THUMBNAIL_IMAGES . $row['products_options_image'], 
				'image_zoom' => DIR_WS_POPUP_IMAGES . $row['products_options_image']
				);

			}
		}

	//var_dump($products_options_images_array);			
	return $products_options_images_array;
	}





function k1_option_image_exists_in_db() {

	// Prüfe auf Vorhandensein der Spalte 'products_options_values.products_options_image'
	$sql	= "SHOW COLUMNS FROM ".TABLE_PRODUCTS_OPTIONS_VALUES;
	$result = xtc_db_query($sql);
	$vorhanden = 0;
	while($row = xtc_db_fetch_array($result)) {
		//echo $row['Field']."<br>";
		if($row['Field'] == 'products_options_image') $vorhanden = 1;
		}
	if(!$vorhanden) {
		$sql	 = "ALTER TABLE " . TABLE_PRODUCTS_OPTIONS_VALUES;
		$sql	.= " ADD `products_options_image` VARCHAR( 254 ) NOT NULL DEFAULT ''";
		//echo $sql."<br>";
		$result = xtc_db_query($sql);
		}

}
?>