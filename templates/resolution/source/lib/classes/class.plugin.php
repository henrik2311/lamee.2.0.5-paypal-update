<?php
/**
 * template class
 *
 * @copyright   (2012) Christian Riedl. (http://www.squidio.de)
 * @author      Christian Riedl <christian.riedl@squidio.de>
 *
 *
 * Plugin Funktionen
 *
 * @category    $core
 * @version     $ v0.2
 */

class Plugin
{

	/**
	 *
	 * @access public
	 */
	public $data;

	/**
	 *
	 * @access protected
	 */
	public $pluginPath;

	/**
	 *
	 * @access protected
	 */
	protected $tableRegistry  = 'plugin';

	/**
	 *
	 * @access protected
	 */
	protected $tableFormat  = 'plugin_%s_%s';

	/**
	 *
	 * @access protected
	 */
	protected $pluginXmlFile    = 'plugin.xml';

	/**
	 *
	 * @access protected
	 */
	protected $pluginPhpFile   = 'plugin.php';

	/**
	 *
	 * @access protected
	 */
	protected $pluginPanelFile   = 'admin/panel.php';

	/**
	 * __construct function.
	 *
	 * @access public
	 * @return void
	 */
	public function __construct(){
		global $registry;
		$this->pluginPath = $registry->plg;
		$this->setData();
	}

	/**
	 * __construct function.
	 *
	 * @access public
	 * @return void
	 */
	public function initPlugins(){
		$this->registerHooks();
		$this->includePluginFile();
	}

	/**
	 * setData function.
	 *
	 * @access private
	 * @return void
	 */
	private function setData()
	{
		$this->data = $this->getPluginStaticData();

		foreach($this->data as $id=>$config){
			if (count($config['installer']) > 0) {
				$this->data[$id]['installed'] = $this->isInstalled($id);
			}
		}
	}

	/**
	 * getStaticData function.
	 *
	 * @access public
	 * @return array
	 */
	public function getPluginStaticData()
	{
		foreach($this->getPluginDirs() as $dir){

			$file = $this->pluginPath . '/' . $dir . '/' . $this->pluginXmlFile;
			if (file_exists($file)) {
				// UTF8 ISSUE:
				// use utf8_encode() only if the shop
				// is installed in utf8 mode.
				$xml = simplexml_load_string(DB_SERVER_CHARSET == 'utf8' ? utf8_encode(file_get_contents($file)) : file_get_contents($file));
			}

			if ($xml->status == 'true') {
				$data[$dir] = $this->xml2array($xml);
			}
		}

		/* manually set plugin-order for specific plugins */
		if ( is_array( $data ) )
		{
			$sq_minify_key = 'sq_minify';
			$sq_minify_plg = ( isset( $data[$sq_minify_key] ) );
			if ( $sq_minify_plg )
			{
				$sq_minify = $data[$sq_minify_key];
				unset( $data[$sq_minify_key] );
				$data[$sq_minify_key] = $sq_minify;
			}

			$sq_language_snippets_key = 'language_snippets';
			$sq_minify_plg = ( isset( $data[$sq_language_snippets_key] ) );
			if ( $sq_minify_plg )
			{
				$sq_language_snippets = $data[$sq_language_snippets_key];
				unset( $data[$sq_language_snippets_key] );
				$data = array_reverse($data);
				$data[$sq_language_snippets_key] = $sq_language_snippets;
				$data = array_reverse($data);
			}

			$sq_template_key = 'template';
			$sq_template_plg = ( isset( $data[$sq_template_key] ) );
			if ( $sq_template_plg )
			{
				$sq_template = $data[$sq_template_key];
				unset( $data[$sq_template_key] );
				$data = array_reverse($data);
				$data[$sq_template_key] = $sq_template;
				$data = array_reverse($data);
			}

		}

		return $data;
	}

	/**
	 * getStaticData function.
	 *
	 * @access public
	 * @return array
	 */
	public function getPluginDbData($pluginId)
	{
		if (!$pluginId) return;

		if ($this->getPluginStatus($pluginId)) {
			$dataTables = $this->getPluginDbTables($pluginId);
			if (is_array($dataTables)) {
				foreach ($dataTables as $table) {
					$query = xtc_db_query("SELECT * FROM ".$table);

					if (xtc_db_num_rows($query) > 0) {
						while ($result = xtc_db_fetch_array($query,false))
							$data[$table][] = $result;
					}
				}
			}
		}

		return $data;
	}

	/**
	 * @access private
	 * @param null
	 * @param null
	 *
	 * register all hooks in global object $hook
	 */
	private function registerHooks()
	{
		global $hook;

		foreach($this->data as $key=>$plugin){
			if ($this->isInstalled($key) && $this->getPluginStatus($key)) {
				foreach($plugin['plugin_code'] as $index=>$_hook){

					if (isset($_hook['hook'])) {
						$_hookName = (string)$_hook['hook'];
						$_hookCode = (string)$_hook['phpcode'];

						if (isset($hook->$_hookName)) {
							$hook->$_hookName .= $_hookCode;
						} else {
							$hook->$_hookName = $_hookCode;
						}
					} else {
						foreach($_hook as $__hook) {
							$_hookName = (string)$__hook['hook'];
							$_hookCode = (string)$__hook['phpcode'];

							if (isset($hook->$_hookName)) {
								$hook->$_hookName .= $_hookCode;
							} else {
								$hook->$_hookName = $_hookCode;
							}
						}
					}
				}
			}
		}
	}

	/**
	 * @access public
	 * @param null
	 *
	 * register a hook
	 */
	public function addHook($hookName, $hookCode)
	{
		global $hook;
		$hook->$hookName = $hookCode;

		return (isset($hook->$hookName));
	}

	/**
	 * @access public
	 * @param null
	 *
	 * register a hook
	 */
	public function removeHook($hookName)
	{
		global $hook;
		unset($hook->$hookName);

		return (!isset($hook->$hookName));
	}

	/**
	 * @access public
	 * @param null
	 * @param null
	 *
	 * get plugin object
	 */
	public function includePluginFile()
	{
		global $registry;

		$i=0; foreach($this->getPluginDirs() as $dir){ $i++;
		$pluginFile = $registry->plg . '/' . $dir . '/' . $this->pluginPhpFile;

		if (file_exists($pluginFile) && $this->isInstalled($dir) && $this->getPluginStatus($dir))
			require_once($pluginFile);
	}
	}

	/**
	 * getPluginDirs function.
	 *
	 * @access protected
	 * @return array
	 */
	protected function getPluginDirs()
	{
		$dir = opendir($this->pluginPath);
		while ($file = readdir($dir)) {
			if ($file != "." && $file != "..") {
				if(is_dir($this->pluginPath . '/' . $file)) {
					$pluginData[] = $file;
				}
			}
		}
		closedir($dir);

		return (is_array($pluginData)) ? $pluginData : null;
	}

	/**
	 * getPluginDbTables function.
	 *
	 * @access public
	 * @return array
	 */
	public function getPluginDbTables($pluginId)
	{
		if (!$pluginId) return;

		if ($data = $this->data[$pluginId]['installer']['tables']) {
			foreach ($data as $type=>$table) {
				$tables[$type] = sprintf($this->tableFormat,$pluginId,$type);
			}
		}

		return $tables;
	}

	/**
	 * @access public
	 * @param string $pluginId
	 *
	 * check olugin installation status
	 */
	public function isInstalled($pluginId)
	{
		if (!$pluginId) return;

		$plgTable = (xtc_db_num_rows(xtc_db_query("SHOW TABLES LIKE '".$this->tableRegistry."'")) > 0);
		if ($plgTable) {
			return (xtc_db_num_rows(xtc_db_query("SELECT * FROM ".$this->tableRegistry." WHERE id='".$pluginId."'"), false) > 0);
		};

		return;
	}

	/**
	 * @access private
	 * @param string $path
	 * @param string $path
	 *
	 * set the plugin foder
	 */
	public function getPluginStatus($pluginId)
	{
		if (!$this->isInstalled($pluginId)) return;

		$sql = xtc_db_query('SELECT status from '.$this->tableRegistry.' where id="'.$pluginId.'"');
		$status = xtc_db_fetch_array($sql, false);

		return ($status['status']=='1') ? true : false;
	}

	/**
	 * xml2array function.
	 *
	 * @access protected
	 * @param mixed $xmlObject
	 * @return array
	 */
	function xml2array($obj) {
		$_arr = is_object($obj) ? get_object_vars($obj) : $obj;
		foreach ($_arr as $key => $val) {
			$val = (is_array($val) || is_object($val)) ? $this->xml2array($val) : utf8_decode((string)$val);
			$arr[$key] = $val;
		}

		return $arr;
	}

}
?>