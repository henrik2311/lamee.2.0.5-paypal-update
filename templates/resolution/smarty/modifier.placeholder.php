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
  
  
  
/**
 * Smarty modifier > placeholder
 *
 * Type:     modifier<br>
 * Name:     placeholder<br>
 * Purpose:  set a placeholder text to inputs
 * @author   Christian Riedl <christian dot riedl at squidio dot de>
 * @param 	 string
 * @return 	 string
 */
function smarty_modifier_placeholder($string, $placeholder, $selector = false)
{
  if ($selector)
    return str_replace($selector, 'placeholder="' . (string)$placeholder . '" '.$selector, $string);
  else
    return str_replace('type', 'placeholder="' . (string)$placeholder . '" type', $string);
}

/* vim: set expandtab: */

?>
