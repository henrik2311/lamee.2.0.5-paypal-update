<?php
/**
 *
 * Template-Class Plugin_Admin
 *
 * @copyright   (2013) Christian Riedl. (http://www.squidio.de)
 * @author      Christian Riedl <christian.riedl@squidio.de>
 *
 */

class Plugin_Admin extends Plugin
{

  /**
   *
   * @access private
   */
  private $pluginId;

  /**
   *
   * @access public
   */
  public $messageStack;

  /**
   * @access public
   * @return null
   *
   */
  public function __construct()
  {
    parent::__construct();
  }

  /**
   * @access public
   * @param string $pluginId
   *
   */
  public function _set($pluginId)
  {
    if (!$pluginId) return;

    $this->pluginId = $pluginId;
  }

  /**
   * @access public
   *
   */
  public function getPluginData()
  {
    if (!$this->pluginId) return;

    if ($this->isInstalled()) {
      $data = $this->getRegistryData();
    } else {
      $data = $this->xml2array($this->data[$this->pluginId]);
    }

    return $data;
  }

  /**
   * @access private
   * @param string $path
   * @param string $path
   *
   * set the plugin foder
   */
  private function getInstallData()
  {
    if ( !$this->pluginId ) return;

    $data = $this->data[$this->pluginId]['installer'];
    $tables = $this->getPluginDbTables($this->pluginId);

    if ( isset( $data['tables'] ) ) {
      foreach ( $data['tables'] as $key=>$val ) {
        $data['tables'][$key]['table_name'] = $tables[$key];
      }
    }

    return ( $data ) ? $data : false;
  }

  /**
   * @access private
   * @param string $path
   * @param string $path
   *
   * set the plugin foder
   */
  private function getRegistryData()
  {
    if (!$this->pluginId) return;

    $sql = xtc_db_query('SELECT * from '.$this->tableRegistry.' where id="'.$this->pluginId.'"');
    $config = xtc_db_fetch_array($sql);

    return $config;
  }

  /**
   * @access private
   * @param string $path
   * @param string $path
   *
   * set the plugin foder
   */
  public function install()
  {
    if ($this->isInstalled()){
      $this->addMessage('error','Das Plugin: &quot;'.$this->pluginId.'&quot; ist bereits installiert');
      return false;
    }
    $this->runInstallQuery();

    return ($this->isInstalled());
  }

  /**
   * @access private
   * @param string $path
   * @param string $path
   *
   * set the plugin foder
   */
  public function reinstall()
  {
    if (!$this->isInstalled()){
      $this->addMessage('error','Das Plugin: &quot;'.$this->pluginId.'&quot; ist noch nicht installiert');
      return false;
    }

    $this->runUninstallQuery();
    $this->runInstallQuery();

    return ($this->isInstalled());
  }

  /**
   * @access private
   * @param string $path
   * @param string $path
   *
   * set the plugin foder
   */
  public function uninstall()
  {
    if (!$this->isInstalled()){
      $this->addMessage('error','Das Plugin: &quot;'.$this->pluginId.'&quot; ist nicht installiert');
      return false;
    }

    $this->runUninstallQuery();

    return (!$this->isInstalled());
  }

  /**
   * @access private
   * @param string $path
   * @param string $path
   *
   * set the plugin foder
   */
  public function addMessage($type,$message)
  {
    $_SESSION['plugin_messages'][$type] = $message;
    return (is_array($_SESSION['plugin_messages'])) ? true : false;
  }

  /**
   * @access private
   * @param string $path
   * @param string $path
   *
   * set the plugin foder
   */
  public function removeMessages()
  {
    unset($_SESSION['plugin_messages']);
    return (!isset($_SESSION['plugin_messages'])) ? true : false;
  }

  /**
   * @access private
   * @param string $path
   * @param string $path
   *
   * set the plugin foder
   */
  public function getPluginName()
  {
    return $this->data[$this->pluginId]['title'];
  }

  /**
   * @access private
   * @param string $path
   * @param string $path
   *
   * set the plugin foder
   */
  public function getPluginStatus()
  {
    return parent::getPluginStatus($this->pluginId);
  }

  /**
   * @access private
   * @param string $path
   * @param string $path
   *
   * set the plugin foder
   */
  public function updatePluginStatus($status)
  {
    $sql = xtc_db_query('UPDATE '.$this->tableRegistry.' SET status='.(int)$status.' WHERE id="'.$this->pluginId.'"');
    return ($sql);
  }

  /**
   * @access private
   * @param string $path
   * @param string $path
   *
   * set the plugin foder
   */
  public function getPanel()
  {
    global $plugin;

    $panelFile = $plugin->pluginPath.$this->pluginId.'/'.$plugin->pluginPanelFile;

    if ( file_exists($panelFile) )
      $file = $panelFile;
    else
      $this->addMessage('info','Für dieses Plugin ist kein Konfigurationspanel vorhanden!');

    return ($file) ? $file : false;
  }

  /**
   * @access private
   * @param string $path
   * @param string $path
   *
   * set the plugin foder
   */
  public function isInstalled()
  {
    return (parent::isInstalled($this->pluginId));
  }

  /**
   * registerPlugin function.
   *
   * @access public
   * @param mixed $registry
   * @return void
   */
  private function registerPlugin($registry) {
    if (xtc_db_num_rows(xtc_db_query("SHOW TABLES LIKE '".$this->tableRegistry."'")) == 0) {
      xtc_db_query("CREATE TABLE ".$this->tableRegistry." (
        unique_key INT NOT NULL AUTO_INCREMENT,
        status INT NULL,
        title VARCHAR(64) NOT NULL,
        description VARCHAR(255) NOT NULL,
        version VARCHAR(8) NOT NULL,
        id VARCHAR(64),
        required INT NULL,
        copyright VARCHAR(255) NOT NULL,
        PRIMARY KEY(unique_key)
      )");
    }
    if (!$this->isInstalled());
    xtc_db_query("INSERT INTO ".$this->tableRegistry." (".implode(array_keys($registry),', ').") VALUES ('".implode(array_values($registry),"', '")."')");

    return ($this->isInstalled());
  }


  /**
   * @access private
   * @param string $path
   * @param string $path
   *
   * set the plugin foder
   */
  private function runInstallQuery()
  {
    $data = $this->getInstallData();
    $insertFormat = 'INSERT INTO %s (%s) VALUES ("%s")';

    $this->registerPlugin( $data['registry'] );

    if ( isset( $data['tables'] ) ) {
      $i=0; foreach( $data['tables'] as $tableType=>$tableData ){ $i++;
        if ( is_array( $tableData ) ) {
          /** _buildQuery: create table **/
          $primary = $tableData['table_config']['primary'];
          unset($tableData['table_config']['primary']);

          $query[$tableType]['create_table'] = "CREATE TABLE ".$tableData['table_name']." (";
          foreach( $tableData['table_config'] as $index=>$value ) {
            $query[$tableType]['create_table'] .= $index." ".$value.", ";
          }
          $query[$tableType]['create_table'] .= "PRIMARY KEY (".$primary.")";
          $query[$tableType]['create_table'] .= ")";

          /** _buildQuery: insert into table **/
          if ( $tableData['insert'] ) {
            $a=0; foreach( $tableData['insert'] as $key=>$val ) { $a++;
              if ( is_array( $val ) ) {
                $query[$tableType]['insert_into'][$a] = sprintf( $insertFormat, $tableData['table_name'], implode( array_keys( $val ),', ' ), implode( array_values( $val ),'", "' ) );
              } else {
                $query[$tableType]['insert_into'][$i] = sprintf( $insertFormat, $tableData['table_name'], implode( array_keys( $tableData['insert'] ),', ' ), implode( array_values( $tableData['insert'] ),'", "' ) );
              }
            }
          }
        }
      }
    }

    // run query
    if ( is_array( $query ) ) {
      foreach( $query as $val ) {
        foreach( $val as $val1 ){
          if ( is_array( $val1 ) ) {
            foreach( $val1 as $val2 )
              xtc_db_query( $val2 );
          } else {
            xtc_db_query( $val1 );
          }
        }
      }
    }

    return ( $this->isInstalled() );
  }

  /**
   * @access private
   * @param string $path
   * @param string $path
   *
   * set the plugin foder
   */
  public function runUninstallQuery()
  {
    global $hook;
    if (isset($hook->hook_class_plugin_admin_runUninstallQuery_top))(eval($hook->hook_class_plugin_admin_runUninstallQuery_top));

    $data = $this->getInstallData();

    /** _delete: fron registry **/
    xtc_db_query( 'DELETE FROM '.$this->tableRegistry.' where id="'.$this->pluginId.'"' );

    /** _delete: data tables **/
    if ( isset( $data['tables'] ) ) {
      foreach( $data['tables'] as $tableType=>$tableData ) {
        xtc_db_query( 'DROP TABLE IF EXISTS '.$data['tables'][$tableType]['table_name'] );
      }
    }

    return ( !$this->isInstalled() );
  }

}
?>