<?php 


####################################################
####################################################
##                                                ##
## k1_options_images.inc.php                      ##
## 2017-10-17                                     ##
## (c) k1 mediendesign henrik kierbaum            ##
## www.k1-mediendesign.de                         ##
##                                                ##
####################################################
####################################################


	/**
	 * hk 2017-10-17: Produkt-Optionsbild
	 */
	require_once(K1MEDIENDESIGN_MODULE_OPTIONS_IMAGES_PATH.'k1_options_images_functions.inc.php');
	k1_option_image_exists_in_db(); // Wenn die Produkt-Optionen-Bilder-Spalte in der db nicht vorhanden ist, lege sie an
	$product_colors = k1_get_available_options_images($product->data['products_id']);
	$info_smarty->assign('PRODUCTS_OPTION_IMAGES', $product_colors);
	// erstes Element speichern, falls in der Produktliste keine Farbe ausgewählt wurde, und diese erste Farbe dann automatisch anwählen
	if(isset($_GET['color'])) {
		$info_smarty->assign('color', $_GET['color']);
		} else {
			$firstcolor_array = array_shift($product_colors);
			$firstcolor = $firstcolor_array['id'];
			$info_smarty->assign('color', $firstcolor);
			}


?>