<?php
/**
 * EVOLUTION
 *
 * @copyright   (2012) Christian Riedl. (http://www.squidio.de)
 * @author      Christian Riedl <christian.riedl@squidio.de>
 *
 *
 * Datanbank funktionen
 *
 * @category    $core
 * @version     $ v0.1
 */
class Registry
{
  /**
   * @var array $data
   * @access protected
   *
   * Hier werden die Daten abgelegt.
   * Kann nur über __get, __set und __unset manipuliert werden
	 *
	 * @usage 
	 * 	$registry = Registry::getInstance();
	 * 	$registry->variable = $value;
   */
  protected $___data;

  /**
   * @var registry $instance
   * @access private static
   *
   * Speichert die einzige Instanz dieser Klasse (Singleton)
   */
  private static $instance = null;

  /**
   * @access public static
   * @return registry
   *
   * Eine Art Ersatzkonstruktor (Singleton)
   */
  public static function getInstance()
  {
    if(self::$instance === null)
    {
      self::$instance = new self();
    }
    return self::$instance;
  }

  /**
   * @access private
   * Konstruktor privat denn Objekt ist Singleton
   */
  private function __construct() {}

  /**
   * @access private
   * Kopierkonstrutkor ist auch privat, weil nur 1 Objekt erlaubt ist
   */
  private function __clone() {}

  /**
   * @access public
   * @param string $key
   * @param mixed  $value
   *
   * Einen neuen Wert der Registry hinzufügen
   */
  public function __set($key, $value)
  {
    $this->___data[$key] = $value;
  }

  /**
   * @access public
   * @param string $key
   * @return mixed|null
   *
   * Einen Wert aus der Registry anhand von $key lesen
   * Liefert null, wenn der Wert nicht existiert
   */
  public function __get($key)
  {
		return isset($this->___data[$key]) ? $this->___data[$key] : null;
  }

  /**
   * @access public
   * @param string key
   * @return bool
   *
   * Prüft, ob ein $key vorhanden ist
   */
  public function __isset($key)
  {
    return isset($this->___data[$key]);
  }

  /**
   * @access public
   * @param string key
   *
   * Einen Wert aus der Registry löschen
   */
  public function __unset($key)
  {
    unset($this->___data[$key]);
  }
}
?>