<?php
/* -----------------------------------------------------------------------------------------
   $Id: currencies.php 1262 2005-09-30 10:00:32Z mz $   

   XT-Commerce - community made shopping
   http://www.xt-commerce.com

   Copyright (c) 2003 XT-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(currencies.php,v 1.16 2003/02/12); www.oscommerce.com 
   (c) 2003	 nextcommerce (currencies.php,v 1.11 2003/08/17); www.nextcommerce.org

   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/
  $currencies_array = array();

  if (isset($xtPrice) && is_object($xtPrice)) {
    reset($xtPrice->currencies);

    while (list($key, $value) = each($xtPrice->currencies)) {
      $currencies_array[] = array('id' => $key, 'text' => $value['title']);
    }
  }
  
  // dont show box if there's only 1 currency
  if (count($currencies_array) > 1 ) {
    // reset var
    $box_smarty = new smarty;
    $box_smarty->assign('tpl_path', DIR_WS_BASE.'templates/'.CURRENT_TEMPLATE.'/');

    $box_content='';
    while (list($key, $value) = each($currencies_array)) {
      if (! empty($xtPrice->currencies[$value['id']]['symbol_left'])) {
        $symbol = $xtPrice->currencies[$value['id']]['symbol_left'];
      } elseif (! empty($xtPrice->currencies[$value['id']]['symbol_right'])) {
        $symbol = $xtPrice->currencies[$value['id']]['symbol_right'];
      } else {
        $symbol = false;
      }

      $box_content .= '<a title="'.$value['text'].'" href="' . xtc_href_link(basename($PHP_SELF), xtc_get_all_get_params(array('currency','language')). 'currency=' . $value['id'], $request_type, false) . '">' . $symbol . ' / ' . $value['text'] . '</a>';
    }
    $box_smarty->assign('BOX_CONTENT', $box_content);
    $box_smarty->assign('curr_currency', $xtPrice->actualCurr);
    $box_smarty->assign('language', $_SESSION['language']);

    if (! CacheCheck()) {
      $box_smarty->caching = 0;
      $box_currencies= $box_smarty->fetch(CURRENT_TEMPLATE.'/boxes/box_currencies.html');
    } else {
      $box_smarty->caching = 1;
      $box_smarty->cache_lifetime = CACHE_LIFETIME;
      $box_smarty->cache_modified_check = CACHE_CHECK;
      $cache_id = $_SESSION['language'] . $_SESSION['currency'];
      $box_currencies= $box_smarty->fetch(CURRENT_TEMPLATE.'/boxes/box_currencies.html',$cache_id);
    }

    $smarty->assign('box_CURRENCIES', $box_currencies);
  }
?>