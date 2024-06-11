<?php
/**
 * Database
 * php version 7.3.5
 *
 * @category Database
 * @package  Database
 * @author   Periyandavar <periyandavar@gmail.com>
 * @license  http://license.com license
 * @link     http://url.com
 */

namespace System\Database;

defined('VALID_REQ') or exit('Invalid request');
use System\Core\Utility;

/**
 * Super class for all Database. All drivers should extend this Database
 * Database class consists of basic level functions for various purposes and
 * query building functionality
 *
 * @category Database
 * @package  Database
 * @author   Periyandavar <periyandavar@gmail.com>
 * @license  http://license.com license
 * @link     http://url.com
 */
abstract class Database
{
    /**
     * Database connection object
     */
    protected $con;
    /**
     * This will have the result set of the select query
     */
    protected $result;
    /**
     * Abstract function which should implemented in the handler class
     * to close db connection
     *
     * @return void
     */
    abstract public function close();

    /**
     * This abstract function should implemented on the handlers to
     * directly run the Query
     *
     * @param string $sql        sql
     * @param array  $bindValues bind values
     *
     * @return bool
     */
    abstract public function runQuery(string $sql, array $bindValues=[]): bool;

    /**
     * This abstract function should implemented on the handlers to run the Query
     * called by execute() function
     * It should return true on success and false on failure
     * if the executed query has the result set the set should be
     * stored in the $this->result
     *
     * @return bool
     */
    abstract protected function executeQuery(): bool;

    /**
     * This abstract function should implemented on the handlers to fetch the
     * result set called directly from the object
     * It should return a single row result as object on success and null on failure
     *
     * @return :object|bool|null
     */
    abstract public function fetch(); //:object|bool|null;

    /**
     * Disabling cloning the object from outside the class
     * 
     * @return void
     */
    private function __clone()
    {
        
    }

    /**
     * This abstract function should implemented on the handlers to get the
     * instance of the class in
     * singleton approch
     *
     * @param string $host   host name
     * @param string $user   User name
     * @param string $pass   Password
     * @param string $db     database
     * @param string $driver Driver
     *
     * @return Database
     */
    abstract public static function getInstance(
        string $host,
        string $user,
        string $pass,
        string $db,
        string $driver
    );

    /**
     * Current instance of the class
     */
    protected static $instance = null;

    /**
     * This will contains the executed full query after the execute() get executed
     *
     * @var string $query
     */
    protected $query;

    /**
     * This will contains the incomplete query generally without where
     *
     * @var string $sql
     */
    private $_sql;

    /**
     * This will contains the values to be bind
     *
     * @var array $bindValues
     */
    protected $bindValues;

    /**
     * This will has the table name if its select query
     *
     * @var string $table
     */
    private $_table;

    /**
     * This will has columns
     *
     * @var string $columns
     */
    private $_columns;

    /**
     * This will has the limit value
     *
     * @var string $limit
     */
    private $_limit;

    /**
     * This will has order value
     *
     * @var string $orderBy
     */
    private $_orderby;

    /**
     * This will has the where condition
     *
     * @var string $where
     */
    private $_where;

    /**
     * This has join condition
     *
     * @var string $join
     */
    private $_join;

    /**
     * This will have groupby value
     *
     * @var string $groupby
     */
    private $_groupby;

    /**
     * This will have the groupby condition
     *
     * @var string $having
     */
    private $_having;

    /**
     * Returns executed query in execute function
     *
     * @return string
     */
    public function getSQL()
    {
        return $this->_sql;
    }

    /**
     * Query function to run directly raw query with or without bind values
     *
     * @param string $query sql
     * @param array  $args  bind values
     *
     * @return bool
     */
    public function query(string $query, array $args = []): bool
    {
        $this->_resetQuery();
        $query = trim($query);
        $this->query = $query;
        $this->bindValues = $args;
        $result = $this->runQuery($this->query, $this->bindValues);
        return $result;
    }

    /**
     * Returns the last insert id
     *
     * @return int
     */
    abstract public function insertId(): int;

    /**
     * Execute the function that will execute the earlier build query
     *
     * @return bool
     */
    final public function execute(): bool
    {
        $result = true;
        if ($this->_sql == '') {
            $this->query = "SELECT "
                . $this->_columns
                . " FROM "
                . $this->_table
                . $this->_join
                . $this->_where
                . $this->_groupby
                . $this->_having
                . $this->_orderby
                . $this->_limit;
        } else {
            $this->query  = $this->_sql . $this->_where;
        }
        // echo $this->query, "<br>";
        // print_r($this->bindValues);
        try {
            $result = $this->executeQuery();
        } catch (Exception $e) {
            return false;
        }
        $this->_resetQuery();
        return $result;
    }

    /**
     * Resets all the query build values
     *
     * @access private
     *
     * @return void
     */
    private function _resetQuery()
    {
        $this->_table = null;
        $this->_columns = null;
        $this->_sql = null;
        $this->bindValues = null;
        $this->_limit = null;
        $this->_orderby = null;
        $this->_where = null;
        $this->_join = null;
        $this->_groupby = null;
        $this->_having = null;
    }

    /**
     * Delete function used to build delete query
     * we can call this in any one of the following ways
     * delete('table', 'id = 1') or delete('table')->where('id = 1');
     *
     * @param string      $table Table Name
     * @param string|null $where Where condition
     *
     * @return Database
     */
    public function delete(string $table, ?string $where = null): Database
    {
        $this->_resetQuery();
        $this->_sql = "DELETE FROM `$table`";
        if (isset($where)) {
            $this->_where = " WHERE $where";
        }
        return $this;
    }

    /**
     * Set the values in update query
     *
     * @return Database
     */
    public function setTo(): Database
    {
        $args = func_get_args();
        $change = implode(",", $args);
        $this->_sql .= Utility::endsWith($this->_sql, 'SET ') ? '' : ',';
        $this->_sql .= $change;
        return $this;
    }

    /**
     * Updates function used to build update query
     * we can call this in any one of the following ways
     * update('table', ["name"=>"Raja"] ,'id = 1') or
     * update('table',  ["name"=>"Raja"] )->where('id = 1');
     *
     * @param string      $table  Table Name
     * @param array       $fields Fields
     * @param string|null $where  Where condition
     * @param string|null $join   Join condition
     *
     * @return Database
     */
    public function update(
        string $table,
        array $fields = [],
        ?string $where = null,
        ?string $join = null
    ): Database {
        $this->_resetQuery();
        $set = '';
        $index = 1;
        foreach ($fields as $column => $field) {
            $column = trim($column);
            if (strpos($column, ".")) {
                $column = explode(".", $column);
                $column = $column[0] . "`.`" . $column[1];
            }
            $set .= "`$column` = ?";
            $this->bindValues[] = $field;
            if ($index < count($fields)) {
                $set .= ", ";
            }
            $index++;
        }
        $this->_sql = "UPDATE $table " . $join . " SET " . $set;
        if (isset($where)) {
            $this->_where = " WHERE $where";
        }
        return $this;
    }

    /**
     * This function used to build insert query
     * we can call this by the following way
     * insert(table, ['field' => 'value', 'fild1' => 'value1',
     *  'field2' => 'value2'], ['field' => CURDATE()])
     *
     * @param string $table      Table
     * @param array  $fields     Fields
     * @param array  $funcfields Fields with function values
     *
     * @return Database
     */
    public function insert(
        string $table,
        array $fields = [],
        array $funcfields = []
    ): Database {
        $this->_resetQuery();
        $keys = '';
        if (count($fields) > 0) {
            $keys = implode('`, `', array_keys($fields));
        }
        $values = '';
        $index = 1;
        foreach ($fields as $column => $value) {
            $values .= '?';
            $this->bindValues[] = $value;
            if ($index < count($fields)) {
                $values .= ',';
            }
            $index++;
        }
        $values = ($values != '' && count($funcfields) > 0)
            ? $values . ', '
            : $values;
        $index = 1;
        foreach ($funcfields as $column => $value) {
            $values .= "($value)";
            $keys = $keys != ''
                ? $keys . '`, `' . $column
                : $column;
            if ($index < count($funcfields)) {
                $values .= ',';
            }
            $index++;
        }
        $this->_sql = "INSERT INTO $table (`$keys`) VALUES ({$values})";
        return $this;
    }

    /**
     * This function used to build select query
     * we can call this in following way
     * select('field1', 'field2', 'field3');
     *
     * @return Database
     */
    public function select(): Database
    {
        $this->_resetQuery();
        $columns = func_get_args();
        for ($i = 0; $i < count($columns); $i++) {
            $columns[$i] = trim($columns[$i]);
            if (strpos($columns[$i], " ") && strpos($columns[$i], ".")) {
                $columns[$i] = explode(" ", $columns[$i]);
                $columns[$i][0] = explode(".", $columns[$i][0]);
                $columns[$i] = "`"
                    . $columns[$i][0][0]
                    . "` .`"
                    . $columns[$i][0][1]
                    .'` '
                    . $columns[$i][1];
            } elseif (strpos($columns[$i], " ")) {
                $columns[$i] = explode(" ", $columns[$i]);
                $columns[$i] = "`" . $columns[$i][0] . "` " . $columns[$i][1];
            } elseif (strpos($columns[$i], ".")) {
                $columns[$i] = explode(".", $columns[$i]);
                $columns[$i] = "`" . $columns[$i][0] . "`.`" . $columns[$i][1] .'`';
            } else {
                $columns[$i] = '`' . $columns[$i] . '`';
            }
        }
        $columns = implode(', ', $columns);
        $this->_columns .= "$columns";
        return $this;
    }
    /**
     * SelectAs used to add select fields with as value
     * call this function by
     * selectAs(['field1' => 'as1', 'field2' => 'as2'])
     *
     * @return Database
     */
    public function selectAs(): Database
    {
        $selectData = func_get_args();
        $selectData = implode(",", $selectData);
        $this->_columns = ($this->_columns != null)
            ? $this->_columns . ", " . $selectData
            : $selectData;
        return $this;
    }

    /**
     * This function used to selectAll fields
     *
     * @return Database
     */
    public function selectAll(): Database
    {
        $this->_resetQuery();
        $this->_columns = "*";
        return $this;
    }

    /**
     * This function is used to select table in select query
     * use : select('field')->from('table');
     *
     * @param string $tableName Table Name
     *
     * @return Database
     */
    public function from(string $tableName): Database
    {
        if (strpos($tableName, " ")) {
            $tableName = explode(" ", $tableName);
            $tableName = '`' . $tableName[0] . '` ' . $tableName[1];
        } else {
            $tableName = '`' . $tableName . '`';
        }
        $this->_table = $tableName;
        return $this;
    }

    /**
     * Appends the string to the where condition
     *
     * @param string $where Where condition string
     *
     * @return Database
     */
    public function appendWhere(string $where): Database
    {
        $this->_where = $this->_where==null ? '' : $this->_where;
        $this->_where .= $where;
        return $this;
    }

    /**
     * Returns where condition
     *
     * @return string
     */
    public function getWhere(): string
    {
        return $this->where();
    }

    /**
     * This function to add where condition with AND
     * we can use this in there ways
     * where(str), where(str,bind), where(str,oper,bind)
     * ex:
     * where('id != 1')
     * where('id != ?', 1)
     * where ('id', '!=', 1)
     * $where = ['id != 1']
     * where($where)
     * $where = ['id != ?', 1]
     * where($where)
     * $where = ['id', '!=', 1]
     * where($where)
     *
     * @return Database
     */
    public function where(): Database
    {
        if ($this->_where == null) {
            $this->_where .= " WHERE ";
        } else {
            $this->_where .= " AND ";
        }
        $args = func_get_args();
        $count = func_num_args();

        if ($count == 1) {
            $arg = $args[0];

            if (is_array($arg)) {
                $index = 1;

                foreach ($arg as $param) {
                    if ($x != 1) {
                        $this->_where .= " AND ";
                    }
                    $parmCount = count($param);
                    if ($countParam == 1) {
                        $this->_where .= $param;
                    } elseif ($countParam == 2) {
                        $this->_where .= $param[0];
                        $this->bindValues[] = $param[1];
                    } elseif ($countParam == 3) {
                        $this->_where .= "`"
                            . trim($param[0])
                            . "`"
                            . $param[1]
                            . " ?";
                        $this->bindValues[] = $param[2];
                    }
                }
            } else {
                $this->_where .= $arg;
            }
        } elseif ($count == 2) {
            $this->_where .= $args[0];
            $this->bindValues[] = $args[1];
        } elseif ($count == 3) {
            $field =  trim($args[0]);
            if (strpos($field, ".")) {
                $field = explode(".", $field);
                $field =  $field[0] . "`.`" . $field[1];
            }
            $this->_where .= "`" . $field . "`" . $args[1] . " ?";
            $this->bindValues[] = $args[2];
        }
        return $this;
    }
    /**
     * This function to add where condition with OR
     * we can use this in there ways
     * orWhere(str), orWhere(str,bind), orWhere(str,oper,bind)
     * ex:
     * orWhere('id != 1')
     * orWhere('id != ?', 1)
     * orWhere ('id', '!=', 1)
     * $orWhere = ['id != 1']
     * orWhere($orWhere)
     * $orWhere = ['id != ?', 1]
     * orWhere($orWhere)
     * $orWhere = ['id', '!=', 1]
     * orWhere($orWhere)
     *
     * @return Database
     */
    public function orWhere(): Database
    {
        if ($this->_where == null) {
            $this->_where .= " WHERE ";
        } else {
            $this->_where .= " OR ";
        }
        $args = func_get_args();
        $count = func_num_args();

        if ($count == 1) {
            $arg = $args[0];

            if (is_array($arg)) {
                $index = 1;

                foreach ($arg as $param) {
                    if ($x != 1) {
                        $this->_where .= " OR ";
                    }
                    $parmCount = count($param);
                    if ($countParam == 1) {
                        $this->_where .= $param;
                    } elseif ($countParam == 2) {
                        $this->_where .= $param[0];
                        $this->bindValues[] = $param[1];
                    } elseif ($countParam == 3) {
                        $this->_where .= "`" . trim($param[0]) . "`"
                             . $param[1]
                             . " ?";
                        $this->bindValues[] = $param[2];
                    }
                }
            } else {
                $this->_where .= $arg;
            }
        } elseif ($count == 2) {
            $this->_where .= $args[0];
            $this->bindValues[] = $args[1];
        } elseif ($count == 3) {
            $field =  trim($args[0]);
            if (strpos($field, ".")) {
                $field = explode(".", $field);
                $field =  $field[0] . "`.`" . $field[1];
            }
            $this->_where .= "`" . $field . "`" . $args[1] . " ?";
            $this->bindValues[] = $args[2];
        }
        return $this;
    }

    /**
     * This will sets limit and offset values in select query
     *
     * @param int      $limit  limit
     * @param int|null $offset Offset value
     *
     * @return Database
     */
    public function limit(int $limit, ?int $offset=null): Database
    {
        if ($offset ==null) {
            $this->_limit = " LIMIT $limit";
        } else {
            $this->_limit = " LIMIT $offset,$limit";
        }

        return $this;
    }

    /**
     * Sets order by
     *
     * @param string $fieldName Field name
     * @param string $order     order direction
     *
     * @return Database
     */
    public function orderBy(string $fieldName, string $order = 'ASC'): Database
    {
        $fieldName = trim($fieldName);

        $order =  trim(strtoupper($order));

        // validate it's not empty and have a proper valuse
        if ($fieldName !== null && ($order == 'ASC' || $order == 'DESC')) {
            if ($this->_orderby ==null) {
                $this->_orderby = " ORDER BY $fieldName $order";
            } else {
                $this->_orderby .= ", $fieldName $order";
            }
        }

        return $this;
    }
    /**
     * Returns the query value
     *
     * @return string
     */
    public function getExectedQuery(): string
    {
        return $this->query;
    }

    /**
     * Returns build query
     *
     * @return string
     */
    public function getQuery(): string
    {
        $query = ($this->_sql == '')
            ? "SELECT "
                . $this->_columns
                . " FROM "
                . $this->_table
                . $this->_join
                . $this->_where
                . $this->_groupby
                . $this->_having
                . $this->_limit
                . $this->_orderby
            : $this->_sql
                . $this->_where;
        return $query;
    }

    /**
     * Returns bindValues
     *
     * @return array
     */
    public function getBindValues(): array
    {
        return $this->bindValues;
    }

    /**
     * Appends new value to bind values array
     *
     * @param array $values values
     *
     * @return Database
     */
    public function appendBindValues(array $values): Database
    {
        foreach ($values as $value) {
            $this->bindValues[] = $value;
        }
        return $this;
    }

    /**
     * This function used to build inner join
     *
     * @param string $tableName Table Name
     *
     * @return Database
     */
    public function innerJoin(string $tableName): Database
    {
        if (strpos($tableName, " ")) {
            $tableName = explode(" ", $tableName);
            $tableName = '`' . $tableName[0] . '` ' . $tableName[1];
        } else {
            $tableName = '`' . $tableName . '`';
        }
        $this->_join .= " INNER JOIN " . $tableName;
        return $this;
    }

    /**
     * This function used to build left join
     *
     * @param string $tableName Table Name
     *
     * @return Database
     */
    public function leftJoin(string $tableName): Database
    {
        if (strpos($tableName, " ")) {
            $tableName = explode(" ", $tableName);
            $tableName = '`' . $tableName[0] . '` ' . $tableName[1];
        } else {
            $tableName = '`' . $tableName . '`';
        }
        $this->_join .= " LEFT JOIN " . $tableName;
        return $this;
    }

    /**
     * This function used to build right join
     *
     * @param string $tableName TableName
     *
     * @return Database
     */
    public function rightJoin(string $tableName): Database
    {
        if (strpos($tableName, " ")) {
            $tableName = explode(" ", $tableName);
            $tableName = '`' . $tableName[0] . '` ' . $tableName[1];
        } else {
            $tableName = '`' . $tableName . '`';
        }
        $this->_join .= " Right JOIN " . $tableName;
        return $this;
    }

    /**
     * This function used to build cross join
     *
     * @param string $tableName Table Name
     *
     * @return Database
     */
    public function crossJoin(string $tableName): Database
    {
        if (strpos($tableName, " ")) {
            $tableName = explode(" ", $tableName);
            $tableName = '`' . $tableName[0] . '` ' . $tableName[1];
        } else {
            $tableName = '`' . $tableName . '`';
        }
        $this->_join .= " CROS JOIN " . $tableName;
        return $this;
    }

    /**
     * This function used to set join condition with on
     *
     * @param string $condition On condition
     *
     * @return Database
     */
    public function on(string $condition): Database
    {
        $this->_join .= ' ON ' . $condition;
        return $this;
    }

    /**
     * This function used to set join condition with using
     *
     * @param string $field Field Name
     *
     * @return Database
     */
    public function using(string $field): Database
    {
        $this->_join .= ' USING(' . $field . ')';
        return $this;
    }

    /**
     * This function is used to perform group by
     *
     * @return Database
     */
    public function groupBy(): Database
    {
        $fields = func_get_args();
        $fields = implode(", ", $fields);
        $this->_groupby = " GROUP BY " . $fields;
        return $this;
    }

    /**
     * This is used to set the variable in database
     *
     * @param string $name  Variable Name
     * @param string $value Variable Value
     *
     * @return bool
     */
    public function set(string $name, string $value): bool
    {
        $this->query = "SET " . $name . " = " . $value;
        return $this->executeQuery();
    }

    /**
     * Starts the transactions
     *
     * @return bool
     */
    public function begin(): bool
    {
        $this->query = "START TRANSACTION";
        return $this->executeQuery();
    }

    /**
     * Commits the transaction
     *
     * @return bool
     */
    public function commit(): bool
    {
        return $this->runQuery("COMMIT");
    }

    /**
     * Rollbacks the transaction
     *
     * @return bool
     */
    public function rollback(): bool
    {
        return $this->runQuery("ROLLBACK");
    }
}
