<?php
/**
 * SimpleDB - PHP database class.
 * V. 1.0.4
 *
 * @package SimpleDB
 * @link https://github.com/kylon/simpleDB The GitHub project page
 * @author kylon (founder)
 * @copyright 2015 kylon
 * @license http://www.gnu.org/licenses/lgpl-3.0.html GNU Lesser General Public License
 * @note This program is distributed in the hope that it will be useful - WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or
 * FITNESS FOR A PARTICULAR PURPOSE.
 *
 *
 * @note Recommed Database structure:
 * Database name: xxxxxxx_name
 * Tables names: xxxxxxx_Aname_tableName
 */
class simpleDB {
    /**
     * An instance of the PDO class.
     * @var PDO
     */
    protected $db;

    /**
     * Tables prefixes
     * @var array
     * @note xxxxxxx_
     * Use an empty string '' for empty values
     */
    protected $prefx = array('');

    /**
     * Tables prefixes No. 2
     * @var array
     * @note Aname_
     * Use an empty string '' for empty values
     */
    protected $db_tab = array('');

    /**
     * Databases names
     * @var array
     * @note name
     * Use an empty string '' for empty values
     */
    protected $db_name = array(__DIR__.'/res/cce_configs.db');

    /**
     * Databases hosts
     * @var array
     * @note Use an empty string '' for empty values
     */
    protected $host = array('');

    /**
     * Databases username and password
     * @var array
     * @note Structure: $login('username_1', 'password_1', 'username_2', 'password_2'...)
     * Use an empty string '' for empty values
     */
    protected $login = array('');

    /**
     * Full selected table name
     * @var string
     * @note xxxxxxx_Aname_tableName
     */
    protected $selected_table;

    /**
     * Current PDO driver
     * @var string
     */
    protected $cur_driver;

    /**
     * Show PDO/SQL errors
     * @var boolean
     * @note WIP
     */
    protected $display_error = false;

    /**
     * PDO Options
     * @var array
     */
    protected $options = array(
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    );

    /**
     * Open a database connection.
     * Suppoted drivers: mysql, sqlite3
     *
     * @param string $driver. The PDO Driver
     * @param integer $offset_db_name. Index number of $db_name
     * @param integer $offset_login_name. Index number of $login
     * @param integer $offset_login_pwd. Index number of $login
     * @param integer $offset_prefx. Index number of $prefx
     * @param integer $offset_db_tab. Index number of $db_tab
     * @param integer $offset_host. Index number of $host
     * @throws PDOException
     * @return array, Connection data
     * @access public
     */
    public function simpleDB($driver='mysql', $offset_db_name=0, $offset_login_name=0, $offset_login_pwd=1,
                             $offset_prefx=0, $offset_db_tab=0, $offset_host=null) {
        try {
            if ($this->db == null) {
                $pdo_string = $driver.':';

                if ($driver == 'mysql')
                    $pdo_string .= 'host='.$this->host[$offset_host].';charset=utf8;dbname=';

                $pdo_string .= $this->db_name[$offset_db_name];

                $this->db = new PDO($pdo_string, $this->login[$offset_login_name], $this->login[$offset_login_pwd],
                    $this->options);

                $this->selected_table = $this->prefx[$offset_prefx].$this->db_tab[$offset_db_tab];
                $this->cur_driver = $driver;
            }
        } catch (PDOException $e) {
            $this->db = $this->debug($e);
        }

        return $this->db;
    }

    /**
     * Execute a simple query.
     *
     * @param string $table. tableName
     * @param string $cmd2. Second part of a query
     * @param string $cmd. First part of a query
     * @throws PDOException
     * @return boolean true success, string error
     * @access public
     */
    public function write($table, $cmd2, $cmd = 'SELECT * FROM') {
        try {
            $test = $this->db->prepare("$cmd `".$this->selected_table.$table."`$cmd2");
            $test->execute();
            $success = true;
        } catch (PDOException $e) {
            $success = $this->debug($e);
        }

        return $success;
    }

    /**
     * Execute a query to write values.
     *
     * @param string $table. tableName
     * @param string $cmd2. Second part of a query
     * @param array $arr. PHP variables to bind
     * @param string $cmd. First part of a query
     *
     * @throws PDOException
     * @return boolean true success, string error
     * @access public
     */
    public function write_av($table, $cmd2, $arr, $cmd='SELECT * FROM') {
        try {
            $sql = $this->db->prepare("$cmd `".$this->selected_table.$table."`$cmd2");

            for ($i=0, $len=count($arr); $i<$len; ++$i) {
                switch (true) {
                    case $this->is_sqlite_blob($arr[$i]):
                        $type = PDO::PARAM_LOB;
                        break;
                    case is_int($arr[$i]):
                        $type = PDO::PARAM_INT;
                        break;
                    case is_bool($arr[$i]):
                        $type = PDO::PARAM_BOOL;
                        break;
                    case is_null($arr[$i]):
                        $type = PDO::PARAM_NULL;
                        break;
                    default:
                        $type = PDO::PARAM_STR;
                        break;
                }

                $sql->bindParam('val'.$i, $arr[$i], $type);
            }

            $sql->execute();
            $success = true;
        } catch (PDOException $e) {
            $success = $this->debug($e);
        }

        return $success;
    }

    /**
     * Execute a query to count the rows.
     *
     * @param string $table. tableName
     * @param string $cmd2. Second part of a query
     * @param string $cmd. First part of a query
     *
     * @throws PDOException
     * @return integer N>=0 success, string error
     * @access public
     */
    public function rows($table, $cmd2=null, $cmd='SELECT * FROM') {
        try {
            if ($this->cur_driver == 'mysql') {
                $numb = $this->db->query("$cmd `".$this->selected_table.$table."`$cmd2")->rowCount();
            } else {
                $sql = $this->fetch($table, $cmd2, 'fetch', $cmd);
                $numb = intval($sql[0]);
            }
        } catch (PDOException $e) {
            $numb = $this->debug($e);
        }

        return $numb;
    }

    /**
     * Execute a PDO fetch.
     *
     * @param string $table. tableName
     * @param string $cmd2. Second part of a query
     * @param array $arr. PHP variables to bind
     * @param string $cmd. First part of a query
     * @param string $type. Fetch Type
     *
     * @throws PDOException
     * @return array success, string error
     * @access public
     */
    public function fetch_av($table, $cmd2, $arr, $cmd='SELECT * FROM', $type='fetch') {
        try {
            $sql = $this->db->prepare("$cmd `".$this->selected_table.$table."`$cmd2");

            for ($i=0, $len=count($arr); $i<$len; ++$i) {
                switch (true) {
                    case $this->is_sqlite_blob($arr[$i]):
                        $type = PDO::PARAM_LOB;
                        break;
                    case is_int($arr[$i]):
                        $type = PDO::PARAM_INT;
                        break;
                    case is_bool($arr[$i]):
                        $type = PDO::PARAM_BOOL;
                        break;
                    case is_null($arr[$i]):
                        $type = PDO::PARAM_NULL;
                        break;
                    default:
                        $type = PDO::PARAM_STR;
                        break;
                }
                $sql->bindParam('val'.$i, $arr[$i], $type);
            }

            $sql->execute();

            switch ($type) {
                default :
                case 'fetch':
                    $var = $sql->fetch();
                    break;
                case 'all':
                    $var = $sql->fetchAll();
                    break;
            }
        } catch (PDOException $e) {
            $var = $this->debug($e);
        }

        return $var;
    }

    /**
     * Execute a simple PDO fetch.
     *
     * @param string $table. tableName
     * @param string $cmd2. Second part of a query
     * @param string $type. Fetch Type
     * @param string $cmd. First part of a query
     *
     * @throws PDOException
     * @return array success, string error
     * @access public
     */
    public function fetch($table, $cmd2, $type='fetch', $cmd='SELECT * FROM') {
        try {
            $sql = $this->db->prepare("$cmd `".$this->selected_table.$table."` $cmd2");
            $sql->execute();

            switch ($type) {
                case 'all':
                    $var = $sql->fetchAll();
                    break;
                default :
                case 'fetch':
                    $var = $sql->fetch();
                    break;
            }
        } catch (PDOException $e) {
            $var = $this->debug($e->getMessage());
        }

        return $var;
    }

    /**
     * Return all the columns names of a table.
     *
     * @param string $table. tableName
     *
     * @throws PDOException
     * @return array success, string error
     * @access public
     * @note ONLY WORKS WITH MYSQL
     */
    public function get_column($table) {
        try {
            $re = $this->db->prepare("SHOW COLUMNS FROM ".$this->selected_table.$table);
            $re->execute();
            $output = array();

            while ($row = $re->fetch(PDO::FETCH_ASSOC))
                $output[] = $row['Field'];

        } catch (PDOException $e) {
            $output = $this->debug($e);
        }

        return $output;
    }

    /**
     * Execute Join query.
     *
     * @param string $table. tableName
     * @param string $cmd2. Second part of a query
     * @param array $arr. PHP variables to bind
     * @param array $tabToJ. Tables list to join
     * @param array $colToCmp. Colums list to compare
     * @param string $fetchType. Select the fetch type (fetch - all)
     * @param string $joinType. Type of the Join
     * @param string $cmd. First part of a query
     *
     * @throws PDOException
     * @return array success, string error
     * @access public
     */
    public function join_av($table, $cmd2, $arr, $tabToJ, $colToCmp=array('id','id'), $fetchType='fetch', $joinType = 'LEFT OUTER JOIN', $cmd = 'SELECT * FROM') {
        try {
            for ($i=0, $c=0, $len=count($tabToJ); $i<$len; $c+=2, ++$i)
                $sql_loop[] = $joinType.' `'.$this->selected_table.$tabToJ[$i]."` ON `".$this->selected_table.$table."`.`".$colToCmp[$c]."` = `".$this->selected_table.$tabToJ[$i]."`.`".$colToCmp[$c+1]."` ";

            $sql_loopf = implode("", $sql_loop);
            $sql = $this->db->prepare("$cmd `".$this->selected_table.$table."` $sql_loopf $cmd2");

            for ($i=0, $len=count($arr); $i<$len; ++$i) {
                switch (true) {
                    case $this->is_sqlite_blob($arr[$i]):
                        $type = PDO::PARAM_LOB;
                        break;
                    case is_int($arr[$i]):
                        $type = PDO::PARAM_INT;
                        break;
                    case is_bool($arr[$i]):
                        $type = PDO::PARAM_BOOL;
                        break;
                    case is_null($arr[$i]):
                        $type = PDO::PARAM_NULL;
                        break;
                    default:
                        $type = PDO::PARAM_STR;
                        break;
                }
                $sql->bindParam('val'.$i, $arr[$i], $type);
            }
            $sql->execute();

            if ($fetchType != 'all')
                $var = $sql->fetch();
            else
                $var = $sql->fetchAll();

        } catch (PDOException $e) {
            $var = $this->debug($e);
        }

        return $var;
    }

    /**
     * Debug - WIP.
     *
     * @param string $err. PDOException
     *
     * @return string if $display_error true, false if $display_error false
     * @access protected
     */
    protected function debug($err) {
        $error = NULL;

        if ($this->display_error) {
            $error = $err;
        }

        return $error;
    }

    protected function is_sqlite_blob($el) {
        if (substr($el, 0, 2) == "b_")
            return true;

        return false;
    }

    /**
     * Close a database connection
     */
    public function kill() {
        unset($this->db);
    }
}
