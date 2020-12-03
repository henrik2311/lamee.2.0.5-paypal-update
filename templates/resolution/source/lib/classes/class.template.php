<?php
/**
 * Template Class
 *
 * @copyright   (2012) Christian Riedl. (http://www.squidio.de)
 * @author      Christian Riedl <christian.riedl@squidio.de>
 *
 *
 * Datanbank funktionen
 *
 * @category    $core
 * @version     $ v 0.1
 */
class Template
{
	
  /**
   * @access private
   *
   * alias for $_GET
   */
	private $_get;
	
  /**
   * @access private
   *
   * page name
   */
	protected $_page;
	
  /**
   * @access private
   *
   * page section
   */
	protected $_section;
	
  /**
   * @access public
   *
   * class constructor
   */
  public function __construct($_section)
  {
    $this->_get 			= $_GET;
    $this->_page 			= $this->setPage($_SERVER['SCRIPT_NAME']);
		$this->_section 	= $this->setSection($_section);
  }

  /**
   * @access private
   *
   * class constructor
   */
  private function countProductsInCategory($cid=false)
  {
		if (!$cid) return;
		
		$query = "select count(*) as total from " . TABLE_PRODUCTS_TO_CATEGORIES . " where categories_id = '" . $cid . "'";
		$query = xtDBquery($query);
		$query = xtc_db_fetch_array($query, true);
		
		return $query['total'];
  }

  /**
   * @access private
   *
   * set a page-name for each page
   */
	private function setPage($script)
	{
		global $current_category_id;
		
		$script	= basename(preg_replace('/.php/', '', $script));
		$mID = (isset($this->_get['manufacturers_id'])) ? true : false;
		
		$page = $script;
		if ($script == 'index') {
			if ($current_category_id != 0) {
				$page = ($this->countProductsInCategory($current_category_id) > 0) ? 'product_listing' : 'categorie_listing';
			}
		}
		if ($mID) $page = 'manufacturers_product_listing';
		
		return $page;
	}
	
  /**
   * @access public
   *
   * set a page-name for each page
   */
	public function getPage()
	{
		return $this->_page;
	}
	
  /**
   * @access public
   *
   * get the page-name by its filename
   */
	public function getPageFromFilename($file)
	{
		$page	= basename(preg_replace('/.php/', '', $file));
		$mID = (isset($this->_get['manufacturers_id'])) ? true : false;
		
		if ($file == 'index') {
			if ($current_category_id != 0) {
				$page = ($this->countProductsInCategory($current_category_id) > 0) ? 'product_listing' : 'categorie_listing';
			}
		}
		if ($mID) $page = 'manufacturers_product_listing';
		
		return $page;
	}
	
  /**
   * @access private
   *
   * set section name
   */
	private function setSection($_section)
	{
		if (!is_array($_section)) return;
		
		$section = 'default';
		foreach($_section as $key=>$val) {
			if (in_array($this->_page,$val))
				$section = $key;
		}
		
		return $section;
	}

	/**
	 * @access public
	 *
	 * get current section
	 */
	public function getSection()
	{
		return $this->_section;
	}

	/**
	 * @access public
	 *
	 * check if the left-navigation is present
	 */
	public function checkSubcategories()
	{
		global $device, $categories;

		$navpath = (isset($categories->navPath) && ! empty($categories->navPath));
		if ($navpath) {
			$navpath_root = array_shift($categories->navPath);

			if (! is_numeric($navpath_root)) {
				return null;
			}

			$checkmobile = ($device == 'mobile' || $device == 'tablet');
			$checksubcategories_query = xtDBquery("SELECT categories_id FROM ".TABLE_CATEGORIES." WHERE parent_id = '".$navpath_root."' AND categories_status = '1' LIMIT 1");
			$checksubcategories = xtc_db_num_rows($checksubcategories_query, true) == 1;

			return $checksubcategories;
		}

		return null;
	}
}
?>