/**
 * 
 * JavaScript Document for Option Images
 * @author: henrik kierbaum
 * k1 mediendesign, www.k1-mediendesign.de
 * 12.03.2012 - 17.03.2013
 * 
 * 
 */


var markedElement	= 'none';
var markedSrc		= 'none';
var markedType		= 'none';



///////////////////////////////////////////////////////////////////////////////
// Permanentes Markieren eines Containers durch Umrandung oder ähnliches
//function k1_markiere_element(container, colorNumber, attrImage, attrImageZoom, productsId) {
function k1_markiere_element(options_id, container) {

	if(document.getElementById(markedElements[options_id])) {
		document.getElementById(markedElements[options_id]).style.border='transparent 1px solid';
		}
	
	if(document.getElementById(container)) {
		document.getElementById(container).style.border='grey 1px solid';
		}

	markedElements[options_id] = container;
	}
	

/**
 * Wähle die entsprechende Select-Menü-Option für das angeklickte Produktoptionenbild aus
 *
 * @param int option
 * @param int option value 
 * 
 */

function k1_select_attribute_by_optionImage(options_id, options_values_id) {
	//console.log(options_id + ', ' + options_values_id);
	//var id = 'id';
	var id = 'selector'; // template resolution
	var element = id + '_' + options_id;
	if(document.getElementById(element)) document.getElementById(element).value = options_values_id;
}




/**
 * Wähle das entsprechende Produktoptionenbild für das angeklickte Select-Menü-Option aus
 *
 * @param int option
 * @param int option value 
 * 
 */

function k1_select_optionImage_by_attribute(options_id, options_values_id) {
	var id = 'id';
	var element = id + '_' + options_id;
	var container = 'col_' + options_id + '_' + options_values_id;
	k1_markiere_element(options_id, container);
}
