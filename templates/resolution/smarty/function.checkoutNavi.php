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
  
  
  
 
function getSteps(){
	global $page, $template;
	
	$steps = array(
		array(
			'link' 				=> xtc_href_link(FILENAME_CHECKOUT_SHIPPING),
			'text' 				=> checkout_step_shipping,
			'page' 				=> $template->getPageFromFilename(xtc_href_link(FILENAME_CHECKOUT_SHIPPING)),
			'adresspage' 	=> $template->getPageFromFilename(xtc_href_link(FILENAME_CHECKOUT_SHIPPING_ADDRESS))
		),
		array(
			'link' 				=> xtc_href_link(FILENAME_CHECKOUT_PAYMENT),
			'text' 				=> checkout_step_payment,
			'page' 				=> $template->getPageFromFilename(xtc_href_link(FILENAME_CHECKOUT_PAYMENT)),
			'adresspage' 	=> $template->getPageFromFilename(xtc_href_link(FILENAME_CHECKOUT_PAYMENT_ADDRESS))
		),
		array(
			'link' 				=> xtc_href_link(FILENAME_CHECKOUT_CONFIRMATION),
			'text' 				=> checkout_step_confirm,
			'page' 				=> $template->getPageFromFilename(xtc_href_link(FILENAME_CHECKOUT_CONFIRMATION))
		),
		array(
			'link' 				=> xtc_href_link(FILENAME_CHECKOUT_SUCCESS),
			'text' 				=> checkout_step_success,
			'page' 				=> $template->getPageFromFilename(xtc_href_link(FILENAME_CHECKOUT_SUCCESS))
		)
	);
	
	foreach ($steps as $key=>$step){
		if ($step['page']==$page || $step['adresspage']==$page){
			$steps['activeStep'] = $key;
		}
	}
  
	return $steps;
}


function smarty_function_checkoutNavi($Params,&$smarty) {
	global $page, $section;
	
	if ($section != 'checkout') return;
	
	$steps = getSteps();
	$active = $steps['activeStep'];
	unset($steps['activeStep']);
	
	$data = array();
	foreach ($steps as $key=>$step){
	  $data[$key] = array(
	    'current' => ($key==$active) ? true : false,
	    'solved' => ($key<$active) ? true : false,
	    'last' => ($key+1==sizeof($steps)) ? true : false,
	    'link' => $step['link'],
	    'text' => $step['text']
	  );
	  
	  if ($active+1 == sizeof($steps))
	    $data[$key]['solved'] = false;
	}

	$smarty->assign('steps',$data);
}

?>