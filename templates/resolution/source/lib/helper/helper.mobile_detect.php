<?php

/**
 * devices function.
 * 
 * @access public
 * @return array
 */
function devices()
{
	return array('desktop', 'mobile', 'tablet');
}

/**
 * helperDevice function.
 * 
 * @return string
 */
function helperDevice()
{
	global $detect;

  // Safety check.
  if (!class_exists('Device')) 
    return 'desktop';
  
  if ( !isset( $detect ) || !is_object( $detect ) )
    $detect = new Device;
  
  $isMobile = $detect->isMobile();
  $isTablet = $detect->isTablet();

  $layoutTypes = devices();

  // Set the layout type.
  if ( isset($_GET['device']) ) {
    $layoutType = $_GET['device'];
  } else {
    if (empty($_SESSION['device'])) {
      $layoutType = ($isMobile ? ($isTablet ? 'tablet' : 'mobile') : 'desktop');
    } else {
      $layoutType =  $_SESSION['device'];
    }
  }

  // Fallback. If everything fails choose classic layout.
  if ( !in_array($layoutType, $layoutTypes) ) 
    $layoutType = 'desktop';

  // Store the layout type for future use.
  $_SESSION['device'] = $layoutType;

  return $layoutType;
}

if ( isset( $_GET['device'] ) ) {
  if ( in_array( $_GET['device'],devices() ) )
    $_SESSION['device'] = $_GET['device'];
  else
    $_SESSION['device'] = 'desktop';
}
