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
  
  
  
function getContent($coID=false) {

	if (GROUP_CHECK == 'true') {
		$group_check = "AND group_ids LIKE '%c_".$_SESSION['customers_status']['customers_status_id']."_group%'";
	}
	$dbQuery = xtDBquery("
		SELECT	content_title,
		content_heading,
		content_text 
		FROM 	".TABLE_CONTENT_MANAGER."
		WHERE 	content_group = '".intval($coID)."'
		AND 	languages_id = '".(int) $_SESSION['languages_id']."'
		".$group_check."
	");
	$dbQuery = xtc_db_fetch_array($dbQuery,true);

	if(!empty($dbQuery)){
		if(SEARCH_ENGINE_FRIENDLY_URLS == 'true') {
			$SEF_parameter = '&content='.xtc_cleanName($dbQuery['content_title']);
		}
		return array(
			'link' 		=> 	xtc_href_link(FILENAME_CONTENT,'coID='.$coID.$SEF_parameter),
			'title'		=>	$dbQuery['content_title'],
			'heading'	=>	$dbQuery['content_heading'],
			'text'		=>	$dbQuery['content_text']
		);
	}
	return false;
}



function smarty_function_getContent($Params=array(),&$smarty) {

	$ContentData = getContent( $Params['id'] );

	$to = trim($Params['to']);
	if(!empty($to)) {
		$smarty->clear_assign($to);   
		$smarty->assign($to,$ContentData);
	} else {
		$smarty->assign('ContentData',$ContentData);
	}

}

?>