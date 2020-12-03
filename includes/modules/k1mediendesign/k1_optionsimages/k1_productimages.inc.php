<?php 


####################################################
####################################################
##                                                ##
## k1_productimages.inc.php                       ##
## 04.10.10                                       ##
## (c) k1 mediendesign henrik kierbaum            ##
## www.k1-mediendesign.de                         ##
##                                                ##
####################################################
####################################################

include('includes/configure.php');
define('DIR_FS_CATALOG_THUMBNAIL_IMAGES', 'images/product_images/thumbnail_images/');
define('DIR_FS_CATALOG_ORIGINAL_IMAGES', 'images/product_images/popup_images/');

// Array indexes are 0-based, jCarousel positions are 1-based.
$first = max(0, intval($_GET['first']) - 1);
$last  = max($first + 1, intval($_GET['last']) - 1);

$length = $last - $first + 1;


// Listet alle zum Produkt vorhandenen Bilder in einem XML-Array für eine jCarousel-Leiste mit Navigationspfeilen, die jeweils nur ca. vier Bilder beinhaltet

$images_array = array();
//echo "file: ".$_SERVER['PHP_SELF']."<br>";
//echo $_GET['startnr'];



/*// A. Hauptbild auslesen
$sql = "SELECT products_image FROM products";
$sql.= " WHERE products_id = '" . $products_id."'";
//echo $sql."<br>";

$images_res = xtc_db_query($sql);
if(!$images_res) echo xtc_db_error();
$row = xtc_db_fetch_array($images_res);
// Bild zur Liste hinzufügen
$images_array[] = '{url: "' . DIR_FS_CATALOG_THUMBNAIL_IMAGES . $row['products_image'] . '", zoom: "' . DIR_FS_CATALOG_ORIGINAL_IMAGES . $row['products_image'] . '"}';
//	echo "bild: ".$row['image_name']."<br>";
*/

// B. Weitere Bilder auslesen
$sql = "SELECT image_name FROM products_images";
$sql.= " WHERE products_id = '" . $products_id."'";
$sql.= " ORDER BY image_nr";
//echo $sql."<br>";

$images_res = xtc_db_query($sql);
if(!$images_res) echo xtc_db_error();
// Alle Bilder zur liste hinzufügen
while($row = xtc_db_fetch_array($images_res)) {
	$images_array[] = '{url: "' . DIR_FS_CATALOG_THUMBNAIL_IMAGES . $row['image_name']. '", zoom: "' . DIR_FS_CATALOG_ORIGINAL_IMAGES . $row['image_name'] . '"}';
	//echo "bild: ".$row['image_name']."<br>";
	}

//$total    = count($images);
//$selected = array_slice($images, $first, $length);


// Daten in einen String schreiben, der mittels Javascript in Daten umgewandelt wird
$imagestring = join(", \n", $images_array);
//$_SESSION['slider_images'] = $imagestring;
$slider_images_items = count($images_array);


// Daten in ein Textfile schreiben
/*$imagestring = join("|", $images_array);
$fh = fopen('includes/k1mediendesign/k1_imageslider_data.txt', 'w');
fwrite($fh, $imagestring);
fclose($fh);*/

?>