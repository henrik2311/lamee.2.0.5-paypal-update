<?php

/**
 |--------------------------------------------------------------------------
 | SMARTY FUNCTION SYSTEM
 |--------------------------------------------------------------------------
 |
 | Stellt Systemvariablen etc. zur verfuegung
 |
 */

function smarty_function_smarty($params=array(), &$ref_smarty) {
  global $smarty;

  $result = null;
  $smarty_version = (isset($ref_smarty->properties)) ? 3 : 2;

  $tpl_vars = (TEMPLATE_ENGINE == 'smarty_3') ? $smarty->tpl_vars : $smarty->_tpl_vars;
  $ref_tpl_vars = (TEMPLATE_ENGINE == 'smarty_3') ? $ref_smarty->tpl_vars : $ref_smarty->_tpl_vars;

  /* get smarty tpl_var */
  if (isset($params['tpl_var'])) {
    $isset = isset($tpl_vars[$params['tpl_var']]) || isset($ref_tpl_vars[$params['tpl_var']]);
    $not_empty = ((isset($tpl_vars[$params['tpl_var']]) || isset($ref_tpl_vars[$params['tpl_var']])) && ! empty($tpl_vars[$params['tpl_var']] || $ref_tpl_vars[$params['tpl_var']]));

    if ($params['return'] == 'isset') {
      $result = $isset;
    }

    if ($params['return'] == 'not_empty') {
      $result = $not_empty;
    }

    if ($params['return'] == 'show') {
      $result = isset($tpl_vars[$params['tpl_var']]) ? $tpl_vars[$params['tpl_var']] : $ref_tpl_vars[$params['tpl_var']];
    }
  }

  if (isset($params['assign']) && null !== $result) {
    return $ref_smarty->assign($params['assign'], $result);
  }

  return null;
}
