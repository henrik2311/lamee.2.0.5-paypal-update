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
  
  
  
  function smarty_function_btn($params, &$smarty) {
		if (!is_array($params)) return false;
		
		$default_btn_class = (!empty($params['defaultclass'])) ? $params['defaultclass'].' ' : 'btn ';
		$btn_params = array('type','id','name','width','height','disabled','accesskey','tabindex','onclick','onchange','onfocus','onblur','onkeypress','onkeydown','autocomplete');
		$icon_only_btn_class = (!$params['value'] && $params['icontype']) ? 'icon' : '';
		
		if (!$params['type'] || empty($params['type']))
			$params['type'] = 'submit';
		
		if ($params['class'])
			$params['class'] = ' '.$params['class'];
		
		// button tag
		$btn = '<button ';
		
		// add button-type and additional css-classes if defined
		$btn .= 'class="'.$default_btn_class.$icon_only_btn_class.$params['btntype'].$params['class'].'"';
		
		// add alternativ e text (check if defined constant or text)
		if ($params['title'] && !empty($params['title']))
			$btn .= (defined($params['title'])) ? ' title="'.constant($params['title']).'"' : ' title="'.$params['title'].'"';
		
		// add $btn_params if defined
		while (list ($key, $value) = each($params)) {
			if (in_array(strtolower($key),$btn_params)) {
				$btn .= ' '.$key.'="'.$value.'"';
			}
		}
		
		// add custom params
		if ($params['params'] && !empty($params['params'])) 
			$btn .= ' '.$params['params'];
		
		$btn .= '>';
		
		// find example button classes at: http://twitter.github.com/bootstrap/base-css.html#icons
		if ($params['icontype'] && !empty($params['icontype'])){
			$btn .= '<span class="'.$params['icontype'].'"></span>';
		}
		
		// button text
		$btn .= (defined($params['value']) != '') ? constant($params['value']) : $params['value'];
		// button close-tag
		$btn .= '</button>';

		return $btn;
  }
?>