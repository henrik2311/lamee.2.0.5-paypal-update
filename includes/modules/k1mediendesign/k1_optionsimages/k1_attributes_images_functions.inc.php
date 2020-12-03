<?php 


####################################################
####################################################
##                                                ##
## k1_attributes_images_functions.inc.php         ##
## 05.01.11                                       ##
## (c) k1 mediendesign henrik kierbaum            ##
## www.k1-mediendesign.de                         ##
##                                                ##
####################################################
####################################################

include('includes/configure.php');
// A. Funktion zum Auslesen aller Produktattribute


// A
function k1_get_available_attributes_images($products_id) {
	
	// Prüfe auf Vorhandensein der Spalte 'products_attributes.products_attributes_image'
	$sql	= "SHOW COLUMNS FROM ".TABLE_PRODUCTS_ATTRIBUTES;
	$result = xtc_db_query($sql);
	$vorhanden = 0;
	while($row = xtc_db_fetch_array($result)) {
		//echo $row['Field']."<br>";
		if($row['Field'] == 'products_attributes_image') $vorhanden = 1;
		}
	if(!$vorhanden) {
		$sql	 = "ALTER TABLE " . TABLE_PRODUCTS_ATTRIBUTES;
		$sql	.= " ADD `products_attributes_image` VARCHAR( 254 ) NOT NULL DEFAULT ''";
		//echo $sql."<br>";
		$result = xtc_db_query($sql);
		}
	
	$sql = "SELECT * FROM products_options_values WHERE language_id = '" . $_SESSION['languages_id'] . "' ORDER BY products_options_values_id";
	$res = xtc_db_query($sql);
	if(!$res) die(xtc_db_error()." ".$sql);
	$general_colors_array = array();
	while($row = xtc_db_fetch_array($res)) {
		$general_colors_array[$row['products_options_values_id']] = $row['color'];
		$general_colors_names_array[$row['products_options_values_id']] = $row['products_options_values_name'];
		}
	//print_r($general_colors_array);

	$sql = "SELECT DISTINCT(pa.attributes_model), pa.options_values_id, pa.products_attributes_image, pov.products_options_values_name FROM products_attributes pa";
	$sql.= " LEFT JOIN products_options_values pov ON (pa.options_values_id = pov.products_options_values_id)";
	$sql.= " WHERE pa.products_id = '" . $products_id . "' AND pov.language_id='" . $_SESSION['languages_id'] . "'";
	$sql.= " ORDER BY pov.products_options_values_id";
	$res = xtc_db_query($sql);
	if(!$res) die(xtc_db_error()." ".$sql);
	// Liste der Farbattribute aus Artikelnummer des Attributes erstellen
	// Farbliste durchlaufen und einen Array bilden, der alle zum Produkt verfügbaren Farben mit ihrem Farbwert aus der generellen Farbliste zurückgibt.
	$products_colors_array = array();
	$products_colors_nrs_array = array();
	//echo "<br>".$products_id.":: ";
	while($row = xtc_db_fetch_array($res)) {
		//$array = explode(".", $row['attributes_model']);
		//$nr = substr($array[1], 0, 2);
		$nr = $row['options_values_id'];
		
			// hk 23.12.2010: 
			// a) erstes Bild (nach neuem Modus) zu diesem Attribut aus 'products_attributes_images' auslesen: deshalb "LIMIT 1"
			// b) und in einem Array aufbereiten
			// c) wenn keine vorhanden, dann das einzelne Bild (nach altem Modus) aus 'products_attributes' laden
			// d) dann die übrigen Attributbilder in einer Liste bereitstellen, die bei Klick auf eine Attributfarbe unterhalb des neu einzusetzenden Attributbildes eine Sammlung von versteckten Bildern speisen kann
			
            $attributes_images_array = array();
			$more_attributes_images_array = array();
			// a
			$sql = "SELECT * FROM products_attributes_images WHERE products_id = '".$products_id."' AND products_options_values_id = '".$nr."' ORDER BY image_nr ASC";
			$new_res = xtc_db_query($sql);
			if(!new_res) die(xtc_db_error());
			//echo $sql;

			// b
			if(xtc_db_num_rows($new_res)) {
				$img = xtc_db_fetch_array($new_res);
				$attributes_images_array[] = $img['image_name'];

				// c
				} else {
					$attributes_images_array[] = $row['products_attributes_image'];
					}
				


		// !!!!!!!!!
		// Vorerst alles mit dem Kram vom alten Modus überschreiben
		//$attributes_images_array = array($row['products_attributes_image']);
		//if($row['products_attributes_image']) echo $row['products_attributes_image'];

		foreach($attributes_images_array as $aimg) {
			if($aimg) {
				$attr_image = "";
				$attr_image = DIR_WS_INFO_IMAGES.$aimg;
				$attr_image_zoom = "";
				
				$attr_image_zoom = DIR_WS_POPUP_IMAGES.$aimg;
				$products_colors_nrs_array[] = array('id' => $nr, 'color' => $general_colors_array[$nr], 'name' => $row['products_options_values_name'], 'attr_image' => $attr_image, 'attr_image_zoom' => $attr_image_zoom);
				//echo $nr.": ".$general_colors_array[$nr].", ";
				}
			}
		}
	
	//print_r($products_colors_nrs_array);			
	return $products_colors_nrs_array;
	}

?>