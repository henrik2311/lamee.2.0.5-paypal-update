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



function smarty_function_link(array $params, &$smarty)
{
	$parameters = [];
	$link = null;

	$defaults = [
		'page' => '',
		'parameters' => '',
		'connection' => 'NONSSL',
		'add_session_id' => true,
		'search_engine_safe' => true,
		'urlencode' => false,
		'admin' => false
	];

	if (empty($params)) {
		return false;
	}

	if (isset($params['page'])) {
		// if theres no dot in page parameter search for a defined constant
		if (strpos($params['page'], '.') === false) {
			$params['page'] = (defined($params['page'])) ? constant($params['page']) : $params['page'];
		}
	}

	if (! isset($params['page'])) {
		// is theres no page parameter set the page to the current url-filename
		$params['page'] = basename($_SERVER['SCRIPT_FILENAME']);

		// ...also set the $_GET parameters of the current url
		if (! empty(xtc_get_all_get_params())) {
			$params['parameters'] = xtc_get_all_get_params().$params['parameters'];
		}
	}

	// merge default xtc_href_link parameters
	$parameters = array_merge($defaults, $params);
	$link = xtc_href_link($parameters['page'], $parameters['parameters'], $parameters['connection'], $parameters['add_session_id'], $parameters['search_engine_safe'], $parameters['urlencode'], $parameters['admin']);

	// only check if the link is the same as the current url
	if (isset($params['check_current'])) {
		$current = xtc_href_link(basename($_SERVER['SCRIPT_FILENAME']), xtc_get_all_get_params());

		return $current == $link;
	}

	// assign link if needed
	if (isset($params['assign'])) {
		return $smarty->assign($params['assign'], $link);
	}

	return $link;
}

?>