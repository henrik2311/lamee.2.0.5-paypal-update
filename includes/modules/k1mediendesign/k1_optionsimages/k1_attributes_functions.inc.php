<?php 


####################################################
####################################################
##                                                ##
## k1_attributes_functions.inc.php                ##
## 11.10.10                                       ##
## (c) k1 mediendesign henrik kierbaum            ##
## www.k1-mediendesign.de                         ##
##                                                ##
####################################################
####################################################

include('includes/configure.php');
// A. Funktion zum Auslesen aller Produktattribute


// A
function k1_get_available_attributes($products_id) {
	
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


	// Prüfe auf Vorhandensein der Tabelle 'products_attributes.products_attributes_image'
	$sql	= "SHOW TABLES LIKE 'products_attributes_images'";
	$result = xtc_db_query($sql);
	if(!xtc_db_num_rows($result)) {
		$sql	 = "CREATE TABLE  `products_attributes_images` (
					`image_id` INT( 11 ) NOT NULL AUTO_INCREMENT ,
					 `products_id` INT( 11 ) NOT NULL DEFAULT  '0',
					 `products_options_values_id` INT( 11 ) NOT NULL DEFAULT  '0',
					 `image_nr` SMALLINT( 6 ) NOT NULL DEFAULT  '0',
					 `image_name` VARCHAR( 254 ) COLLATE latin1_german2_ci NOT NULL DEFAULT  '',
					PRIMARY KEY (  `image_id` ) ,
					KEY  `products_id` (  `products_id` )
					) ENGINE = MYISAM DEFAULT CHARSET = latin1 COLLATE = latin1_german2_ci AUTO_INCREMENT =1;";
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
			// a) Bilder (nach neuem Modus) zu diesem Attribut aus 'products_attributes_images' auslesen
			// b) und in einem Array aufbereiten
			// c) wenn keine vorhanden, dann das einzelne Bild (nach altem Modus) aus 'products_attributes' laden
			
            $attributes_images_array = array();
			// a
			$sql = "SELECT * FROM products_attributes_images WHERE products_id = '".$products_id."' AND products_options_values_id = '".$nr."' ORDER BY image_nr ASC LIMIT 1";
			$new_res = xtc_db_query($sql);
			if(!$new_res) die(xtc_db_error());
			//echo $sql;

			// b
			if(xtc_db_num_rows($new_res)) {
				while($img = xtc_db_fetch_array($new_res)) {
					$attributes_images_array[] = $img['image_name'];
					//echo $img['image_name'].", ";
					}
					// das Bild nach altem Modus vorn an den Array anhängen
					//array_unshift($attributes_images_array, $row['products_attributes_image']);

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