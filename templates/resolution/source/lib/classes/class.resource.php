<?php
/**
 * template class
 *
 * @copyright   (2012) Christian Riedl. (http://www.squidio.de)
 * @author      Christian Riedl <christian.riedl@squidio.de>
 *
 */

require_once(DIR_FS_EXTERNAL.'compactor/compactor.php');

class Resource
{
	/**
	 *
	 * @access private
	 */
	private $resources = [];

	/**
	 * class constructor
	 * 
   * @access private
   * @return null
   *
   */
  public function __construct(){}
	
  /**
	 * getResources function.
	 * 
   * @access private
   * @return bool
   *
   * get all added resources
   */
	public function getResources() 
	{
		return ($this->isResource()) ? $this->resources : false; 
	}
	
  /**
	 * isResource function.
	 * 
   * @access private
   * @return bool
   *
   * is there a resource added
   */
	private function isResource() 
	{
		return (! empty($this->resources));
	}
	
	/**
	 * sortResources function.
	 * 
	 * @access private
	 * @return array
	 */
	private function sortResources() 
	{
		if (!$this->resources) return;
		
		foreach ($this->resources as $type => $value ) {  
			foreach ($value as $id => $resource ) 
				$sortingCol[] = $resource['sort_order'];  
			
			return array_multisort($sortingCol, SORT_ASC, $this->resources[$type]); 
		}  
	}
	
  /**
   * getFileType function.
   * 
   * @access private
   * @param string $file
   * @return string
   */
  private function getFileType($file) 
	{
    $type = 'js';
    if (substr($file,-4) == '.css') 
      $type = 'css';
    
    return $type;
  }

  /**
   * add function.
   * 
   * @access public
   * @param string $file
   * @param string $sort_order
   * @return void
   */
  public function add($file, $sort_order, $force_type=false)
  {
    if ( $force_type )
      $type = $force_type;
    else 
      $type = $this->getFileType($file);

    $this->resources[$type][] = array('sort_order'=>$sort_order, 'source'=>$file, 'type'=>$type);
		$this->sortResources();
  }
	
  /**
   * remove function.
   * 
   * @access public
   * @param string $file
   * @param string $sort_order
   * @return void
   */
  public function remove()
  {
    unset($this->resources);
  }
	
  /**
   * isExtUrl function.
   * 
   * @access private
   * @param string $url
   * @return bool
   */
  private function isExtUrl($url)
  {
    return ( strpos( $url,HTTP_SERVER ) === false && ( strpos( $url,'http://' ) !== false || strpos( $url,'https://' ) !== false || strpos( $url,'//' ) !== false ) );
  }
  
  /**
   * showResources function.
   * 
   * @access public
   * @return string resources html
   * 
   * output resources (sorted by priority)
   */
	public function showResources($type)
	{		
		if ($this->isResource()) {
			$resources = '';
			
			foreach ($this->resources[$type] as $key => $val) {
				if ( file_exists($val['source']) || $this->isExtUrl($val['source'])) {
					/* MOD2 > DIR_WS_BASE added */
					if ($type == 'css')
						$resources .= '<link href="'. DIR_WS_BASE . $val['source'].'" id="resources_id_'.$key.'" rel="stylesheet" type="text/css" />'."\n";
					if ($type == 'js')
						$resources .= '<script src="'. DIR_WS_BASE . $val['source'].'" id="resources_id'.$key.'" type="text/javascript"></script>'."\n";
				}
			}
			echo $resources;
		}
		return;
	}
  
}
?>