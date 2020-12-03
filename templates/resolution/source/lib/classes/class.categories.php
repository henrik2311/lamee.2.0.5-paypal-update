<?php
/**
 * template class
 *
 * @copyright   (2012) Christian Riedl. (http://www.squidio.de)
 * @author      Christian Riedl <christian.riedl@squidio.de>
 *
 * @category    $core
 * @version     $ v0.1
 */


if (isset($hook->class_categories_php_html_top))(eval($hook->class_categories_php_html_top));
class Categories {
	
	public $currentUrl;
	public $navPath = false;
	
 /*
	* class constructor 
	*/
	function __construct(){
		$this->currentUrl = xtc_href_link(basename($_SERVER['SCRIPT_NAME']),xtc_get_all_get_params(array('XTCsid')));
		$this->navPath = $this->getCurrentNavigationPath();
	}

 /*
	* fragt den aktuellen navigationspfad ab
	*
	* @return array
	*/
	private function getCurrentNavigationPath(){
		return ($GLOBALS['cPath']) ? explode('_',$GLOBALS['cPath']) : false;
	}
	
 /*
	* gruppen-berechtigung f�r die sql-abfrage
	*
	* @param string
	* @return sql
	*/
	private function sqlGroupCheck($tablePrefix = false){
		if ($tablePrefix) $tablePrefix = $tablePrefix.'.';
		return  ($tablePrefix && GROUP_CHECK == 'true') ? "and ".$tablePrefix."group_permission_".$_SESSION['customers_status']['customers_status_id']."=1 " : false;
	}
	
 /*
	* kategorien-array
	*
	* @params int
	* @return array
	*/
	public function getCategoriesByParentId($parentId = 0){
		$sql = xtc_db_query(" 
			SELECT	
				c.categories_id,
				c.categories_image,
				cd.categories_name, 
				cd.categories_heading_title, 
				c.parent_id 
			FROM 	
				".TABLE_CATEGORIES." c, 
				".TABLE_CATEGORIES_DESCRIPTION . " cd 
			WHERE 	
				c.categories_status = '1' 
				".$this->sqlGroupCheck('c')." 
				AND c.parent_id = ".$parentId." 
				AND c.categories_id = cd.categories_id 
				AND cd.language_id='" . (int)$_SESSION['languages_id'] ."' 
			ORDER BY 
				sort_order, cd.categories_name
		");
		
		while ($arr = xtc_db_fetch_array($sql))  {
			$categories[$arr['categories_id']] = array(	
				'id' 					=> 	$arr['categories_id'],
				'image' 			=> 	$arr['categories_image'],
				'name' 				=> 	$arr['categories_name'],
				'heading' 		=> 	$arr['categories_heading_title'],
				'parent' 			=> 	$arr['parent_id'],
				'level' 			=> 	false,
				'position' 		=> 	false,
				'subcats'			=> 	$this->categoryHasSubCategories($arr['categories_id']),
				'link'				=>	$this->categoryLink($arr['categories_id'],$arr['categories_name'])
			);
		}
		
		return !empty($categories) ? $categories : false;
	}
			
 /*
	* legt die zu verwendenden html-tags je nach listentyp fest
	* m�gliche werte f�r $listType = 'list','def','div'
	*
	* @params string
	* @return array
	*/	
	private function getTypeBasedHtmlTags($type){
		
		$elements['wrap'] = 'ul';
		$elements['head'] = false;
		$elements['item'] = 'li';
		
		switch ($type){
			case 'def':
				$elements['wrap'] = 'dl';
				$elements['head'] = 'dt';
				$elements['item'] = 'dd';
				break;
			case 'div': 
				$elements['wrap'] = 'div';
				$elements['head'] = false;
				$elements['item'] = 'div';
			break;
		}
		
		return $elements;
	}
	
	/*
	 * hat diese kategorie eine unterkatagorie?
	 *
	 * @params int
	 * @return bool
	 */
	private function categoryHasSubCategories($cid){
		$childSql = xtDBquery("SELECT categories_id FROM ".TABLE_CATEGORIES." WHERE parent_id = '".$cid."' ".$this->sqlGroupCheck()." AND categories_status = '1'");
		$hasSub = $cid && xtc_db_num_rows($childSql,true) ? true : false;
		return $hasSub;
	}
		
	/*
	 * kategorielink erstellen
	 *
	 * @params $cid int
	 * @params $cname string
	 * @return string
	 */
	private function categoryLink($cid,$cname){
		return $cid && $cname ? xtc_href_link(FILENAME_DEFAULT,xtc_category_link(intval($cid),$cname)) : false;
	}
	
	/**
	 * ist $cid im aktuellen navigations-pfad?
	 *
	 * @param int
	 * @return bool
	 */
	private function isInNavigationPath($cid = false){
		$navPath = $this->navPath;
		$inArr = $navPath && $cid ? in_array($cid, $navPath) : false;
		return $inArr;
	}
	
	/**
	 * ist $cid die aktuell aufgerufene kategorie?
	 *
	 * @param int
	 * @return bool
	 */
	private function isCurrentCategory($cid){
		$navPath = $this->navPath;
		$current = is_array($navPath) ? array_pop($navPath) : false;
		return $current==$cid;
	}	
	
	/**
	 * die aktuelle category-id ermitteln
	 *
	 * @return int
	 */
	public function getCurrentCategoryId(){
		$navPath = $this->navPath;
		return is_array($navPath) ? array_pop($navPath) : false;
	}	
	
	/**
	 * setzt die position der elemente im array (first oder last)
	 *
	 * @param array
	 * @return array
	 */
	private function setPosition($data = false){
		if (is_array($data)) {
			$i=0; foreach($data as $key => $catData) { $i++;
				if ($i==1) 
					$data[$key]['position'] = ' first';
				elseif ($i==sizeof($data)) 
					$data[$key]['position'] = ' last';
			}
			return $data;
		} else {
			return false;
		}
		
	}	
	
 /*
	* setzt das element auf aktiv oder �bergeordnet-aktiv
	*
	* @params array
	* @return array
	*/	
	private function setActiveState($data = false){
		if ($data) {
			if ($this->isCurrentCategory($data['id']))
				$activeState = ' active';
			elseif ($this->isInNavigationPath($data['id']))
				$activeState = ' active active_parent';
		}
		
		return $activeState ? $activeState : false;
	}
	
 /*
	* pr�ft ob die n�chste Kategorieebene (level) geladen wird in abh�ngigkeit von "$isNested" und "$maxLevel"
	*
	* @params $cid int
	* @params $level int
	* @params $maxLevel int
	* @params $isNested bool
	* @return bool
	*/	
	private function allowSub($cid, $level, $maxLevel, $isNested){
		if ( $isNested ) {
			$allowed = ( $this->isInNavigationPath($cid) && $this->categoryHasSubCategories($cid) && $level+1 <= $maxLevel ) ? true : false;
		} else {
		  if ( $maxLevel ) {
			  $allowed = ( $this->categoryHasSubCategories( $cid ) && $level+1 <= $maxLevel ) ? true : false;
      } else {
			  $allowed = $this->categoryHasSubCategories( $cid ) ? true : false;
      }
		}
		
		return $allowed;
	}
	
	/*
	 * legt die css-klassen und ids f�r die listenelemente fest
	 * $data enth�lt ein array aus den aktuellen kategoriewerten
	 * $arr enth�lt ein array allen kategorien und werten auf diesem level
	 *
	 * @params $data array
	 * @params $level int
	 * @params $arr array
	 * @return array
	 */	
	private function setCss($data = false, $level, $allow, $arr = false){
		if ($data && is_array($data)){
			$activeState		= $this->setActiveState($data);
			$position 			= $arr[$data['id']]['position'];
			$hasSub					= $allow ? ' has_sub' : '';
			
			$params = array(
				'subCntClass'		=> 'menulevel_'.$level.$activeState,
				'itemClass' 		=> 'level_'.$level.' catid_'.$data['id'].$hasSub.$activeState.$position,
				'itemId' 				=> 'catid_'.$data['id'],
				'linkClass' 		=> 'level_'.$level.' catid_'.$data['id'].$hasSub.$activeState,
				'linkId' 				=> 'catid_'.$level,
			);
		}
		
		return $params ? $params : false;
	}
	
	/*
	 * universelle funktion um eine html-navigation auszugeben
	 *
	 * @params $parentId int
	 * @params $maxRunLevel int
	 * @params $htmlType string
	 * @params $wrapId string
	 * @params $textWrap bool
	 * @params $runLevel int
	 *
	 * @return string
	 */	
	public function HtmlNavigation( $config,$parent=false,$runLevel=1 ) {		
		global $cPath_array, $hook;
		
		// config set default
		if ( !$parent )
		  $parent = ($config['root']) ? $config['root'] : 0;
		$config['nested'] = ( isset( $config['nested'] ) ) ? $config['nested'] : false;
		$config['type']   = ( $config['type'] ) ? $config['type'] : false;
		$config['levels'] = ( $config['levels'] ) ? $config['levels'] : false;
		$config['items_per_row'] = ($config['items_per_row']) ? $config['items_per_row'] : false;
		
		// data
		$data = $this->getCategoriesByParentId( $parent );
    
    if (isset($hook->class_categories_php_html_navigation_data_add))(eval($hook->class_categories_php_html_navigation_data_add));
		
		// add startpage link to $data array
		if ( $config['show_startpage_link'] && $runLevel==1 )
		  array_unshift( $data, array( 'id'=>'0','name'=>'<span class="icon-home icon_startpage_link"></span>','heading'=>text_link_startpage,'parent'=>'0','subcats'=>false,'link'=>xtc_href_link(FILENAME_DEFAULT) ) );
		
		$data		= $this->setPosition( $data );
		$tag 		= $this->getTypeBasedHtmlTags( $config['type'] );
		
		if ( is_array( $data ) ) {
			$i=0; $e=0; $a=0; foreach( $data as $set ) { ++$e;
				$allow  = $this->allowSub( $set['id'],$runLevel,$config['levels'],$config['nested'] );
        
        if (isset($hook->class_categories_php_html_navigation_process))(eval($hook->class_categories_php_html_navigation_process));
        
				$css    = $this->setCss( $set,$runLevel,$allow,$data );
    		
				if ( $runLevel==2 && $config['number_of_sunlinks'] ) $a++;
				if ( ++$i==1 ){
					if ( $runLevel==1 )
						$html = '<'.$tag['wrap'].' class="' . $config['class'] . '" id="'.$config['id'].'">';
					elseif ($runLevel==2)
						$html = '<'.$tag['wrap'].' class="submenu '.$css['subCntClass'].' '.$config['dropdown_class'].'">';
					else
						$html = '<'.$tag['wrap'].' class="'.$css['subCntClass'].'">';
				}
				
				$html .= $tag['item'] ? '<'.$tag['item'].' class="'.$css['itemClass'].'">' : '';
			  $html .= '<a href="'.$set['link'].'" class="'.$css['linkClass'].'" title="'.$set['heading'].'">'.$config['link_pre'].$set['name'].$config['link_post'].'</a>';
				
				if ( $allow )
					$html .= $this->HtmlNavigation( $config,$set['id'],$runLevel+1 );
        
				$html .= $tag['item'] ? '</'.$tag['item'].'>' : '';
				
				if ( $e==$config['items_per_row'] && $runLevel > 1 ) {
  				$html .= '<'.$tag['item'].' class="clb"></'.$tag['item'].'>';
  				$e=0;
				}
			}
			
  		$html .= '</'.$tag['wrap'].'>';
  		
  		return $html;
		}
		return false;
	}
  
	/*
	 * universelle funktion um bilder-navigation auszugeben
	 *
	 * @params $parentId int
	 * @params $maxRunLevel int
	 * @params $htmlType string
	 * @params $wrapId string
	 * @params $textWrap bool
	 * @params $runLevel int
	 *
	 * @return string
	 */	
	public function ImageNavigation($parentId = 0, $imageType = 'thumbnail', $imageWidth = 100, $imageHeight = 100){
		$categories		= $this->getCategoriesByParentId($parentId);
		$categories		= $this->setPosition($categories);
	
		if ($imageType=='thumbnail' || !$imageType || empty($imageType))
			$folder = DIR_WS_THUMBNAIL_IMAGES;
		elseif ($imageType=='info')
			$folder = DIR_WS_INFO_IMAGES;
		elseif ($imageType=='popup')
			$folder = DIR_WS_POPUP_IMAGES;
		elseif ($imageType=='original')
			$folder = DIR_WS_ORIGINAL_IMAGES;
		
		if (is_array($categories)){
			foreach($categories as $category) {
				$catImage					= DIR_WS_IMAGES.$folder.$category['image'];
				$defaultImage			= 'templates/'.CURRENT_TEMPLATE.'/squidio/img/global/nopic.gif';
				$imgSize					= @getimagesize($img['src']);
				
				$img['src']				= file_exists($catImage) ? $catImage : $defaultImage;
				$img['width']			= $imageWidth ? $imageWidth : $imgSize[0];
				$img['height']		= $imageWidth ? $imageWidth : $imgSize[1];
				
				if ($category['position']=='first') 
					$html .= '<ul class="list_category_'.$category['id'].'">';
					$html .= '<li>';
						$html .= '<a href="'.$category['link'].'">';
							$html .= '<span class="image">'.xtc_image($img['src'], $category['name'], $img['width'], $img['height'], 'alt="'.$category['name'].'"').'</span>';
							$html .= '<span class="name">'.$category['name'].'</span>';
						$html .= '</a>';
					$html .= '</li>';
				if ($category['position']=='last') $html .= '</ul>';
			}
		}
		
		return $html;
	}

	/*
	 * testen einzelner funktionen oder variablen
	 *
	 * @params mixed
	 * @return string
	 */	
	function debug($obj = false){
		echo ('<pre style="position: absolute; width: 100%; height: 600px; overflow: auto; background: #000; color: #fff; z-index: 10000000; margin: 0 auto;">');
		if(is_array($obj)){
			print_r($obj);
		}elseif (is_object($obj)){
			var_dump($obj);
		}else{
			echo $obj;
		}
		echo ('</pre>');
	}
	
}
if (isset($hook->class_categories_php_html_bottom))(eval($hook->class_categories_php_html_bottom));
?>