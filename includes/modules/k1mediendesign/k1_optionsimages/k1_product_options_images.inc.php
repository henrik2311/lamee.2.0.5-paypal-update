<?php 


####################################################
####################################################
##                                                ##
## k1_product_options_images.inc.php              ##
## 07.01.13                                       ##
## (c) k1 mediendesign henrik kierbaum            ##
## www.k1-mediendesign.de                         ##
##                                                ##
####################################################
####################################################

// Listet alle zur Produkt-Option vorhandenen Options-Bilder
$images_array = array();

// A. Optionsbild auslesen
$sql = "SELECT products_options_image FROM ".TABLE_PRODUCTS_OPTIONS;
$sql.= " WHERE products_options_values_id = '" . $products_options_values_id."'";
//echo "<br>".$sql."<br>";


$images_res = xtc_db_query($sql);
if(!$images_res) echo xtc_db_error();
// Alle Bilder zur liste hinzufügen
while($row = xtc_db_fetch_array($images_res)) {
	$images_array[] = '{url: "' . DIR_WS_THUMBNAIL_IMAGES . $row['image_name']. '", zoom: "' . DIR_WS_ORIGINAL_IMAGES . $row['image_name'] . '"}';
	$src  = DIR_WS_THUMBNAIL_IMAGES . $row['image_name'];
	$info = DIR_WS_INFO_IMAGES . $row['image_name'];
	$zoom = DIR_WS_POPUP_IMAGES . $row['image_name'];
	$id   = $row['image_name'];
	$options_values_id = $row['options_values_id'];
	$image_output_array[] = array('THUMB' => $src, 'POPUP' => $zoom, 'INFO' => $info, 'ID' => $id, 'OVID' => $options_values_id);
	//echo "bild: ".$row['image_name']."<br>";
	}

// Daten in einen String schreiben, der mittels Javascript in Daten umgewandelt wird
$imagestring = join(", \n", $images_array);
//$_SESSION['slider_images'] = $imagestring;
$slider_images_items = count($images_array);
$info_smarty->assign('ADDITIONAL_PRODUCTS_IMAGE_COUNT', count($image_output_array));

$info_smarty->assign('ADDITIONAL_PRODUCTS_IMAGE', $image_output_array);
$additional_products_image_script = '                          	<script language="JavaScript" charset="UTF-8">
                          	/* <![CDATA[ */

									
                          		function k1_show_options_image(bild, popup, bild_id, ovid) {
									
									// Bild markieren
									k1_markiere_element(bild_id);
									
									// Groessenliste abfragen, wenn noch keine Ausführungsliste vorhanden
									if(document.getElementById(\'select_groesse\')) {
										var groesse = document.getElementById(\'select_groesse\').value;
										//alert(groesse);
										show_voption(groesse);
										}
								
                          			document.getElementById(\'product_main_image\').style.display = \'none\';
                          			document.getElementById(\'product_main_image\').src=bild;
                          			document.getElementById(\'product_main_link\').href=popup;
                           			// Scriptaculous für xt-commerce
                           			//new Effect.Appear(\'product_main_image\');
                           			// JQuery für modified
                           			$(\'#product_main_image\').show(\'slow\');
									
									// Ausfuehrung anpassen, wenn die durch das Bild gewählte in der Liste enthalten ist
									if(document.getElementById(\'select_groesse\')) {
										var gefunden = false;
										for (i = 0; i < document.getElementById(\'select_ausfuehrung_\'+groesse).length; ++i) {
		    								if (document.getElementById(\'select_ausfuehrung_\'+groesse).options[i].value == ovid) {
			      								//alert(document.getElementById(\'select_ausfuehrung_\'+groesse).options[i].value);
			      								document.getElementById(\'select_ausfuehrung_\'+groesse).value = ovid;
												show_amount(ovid); // Matrix-Funktion reguliert den Rest
												gefunden = true;
												}
											}
										if(gefunden == false) alert(\'Diese Ausführung ist in der gewählten Größe leider nicht vorhanden!\');
									}
                          		}
                          	/* ]]> */
                          	</script>';

$info_smarty->assign('ADDITIONAL_PRODUCTS_IMAGE_SCRIPT', $additional_products_image_script);
?>