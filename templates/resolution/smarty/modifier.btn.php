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
  
  
  
function smarty_modifier_btn($Input, $class=false, $icon=false, $myText=false, $params=false) {
	
	require_once(dirname(dirname(__FILE__)) . '/source/lib/classes/class.simple_html_dom.php');
	$e = new simple_html_dom();
	$e->load($Input);

	$link = $e->find('a',0);
	$img = $e->find('img',0);
	
	if ($link->attr['onclick'])
	  $onclick = $link->attr['onclick'];
  	elseif ($img->attr['onclick'])
	  $onclick = $img->attr['onclick'];
	
	if (!empty($link->attr['href']) || $onclick) {
		$_params['params'] 		= ' '.$params;
		$_params['text'] 		= ($myText) ? defined($myText) ? constant($myText) : $myText : $img->attr['alt'];
		$_params['class'] 		= ($class) ? ' class="'.$class.'"': false;
		$_params['icon'] 		= ($icon) ? '<span class="'.$icon.'"></span>' : false;
		
		$_linkTarget = (isset($onclick)) ? 'onclick="'.$onclick.'"' : 'href="'. $link->attr['href'] .'"';
		
		$Input = '<a '.$_linkTarget.$_params['class'].$_params['params'].'>'.$_params['icon'].$_params['text'].'</a>';
	}

	return $Input;
}

?>