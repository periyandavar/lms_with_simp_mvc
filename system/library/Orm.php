<?php
/**
 * Orm
 * php version 7.3.5
 *
 * @category Orm
 * @package  Core
 * @author   Periyandavar <periyandavar@gmail.com>
 * @license  http://license.com license
 * @link     http://url.com
 */

namespace System\Library;

use System\Database\DatabaseFactory;
use System\Core\Log;
use ReflectionClass;
use ReflectionObject;

defined('VALID_REQ') or exit('Invalid request');
/**
 * Orm Class handles routing
 *
 * @category Orm
 * @package  Core
 * @author   Periyandavar <periyandavar@gmail.com>
 * @license  http://license.com license
 * @link     http://url.com
 */
class Orm
{
    /**
     * Database connection variable
     *
     * @var Database $_db
     */
    private static $_db;

    /**
     * Omr instance variable
     *
     * @var Omr $_instance
     */
    private $_instance;
    
    private $_isNew;

    /**
     * Instantiate the new BaseModel instance
     */
    public function __construct()
    {
        $this->_isNew = true;
        self::$_db = DatabaseFactory::create();
        Log::getInstance()->info(
            "The " . static::class . " ORM is initalized successfully"
        );
    }

    /**
     * Saves the object to database
     * 
     * @param string|null $key Primary key
     *
     * @return bool
     */
    public function save(?string $key = null): bool
    {
        $fields = [];
        $reflection = new ReflectionClass(new static());
        $class = $reflection->getShortName();
        $properties = $reflection->getProperties();
        foreach ($properties as $property) {
            $fieldName = $property->getName();
            $fields[$fieldName] = $this->{$fieldName};
        }
        if ($key == null) {
            self::$_db->insert($class, $fields);
        } else {
            self::$_db->update($class, $fields);
            $key = isset($fields[$key]) ? $key : 'id';
            $where = $fields[$key];
            self::$_db->where($key, '=', $where);
        }
        if (self::$_db->execute()) {
            $this->_isNew=false;
            return true;
        } 
        return false;
    }

    /**
     * Returns array of objects matching given where
     * 
     * @param string $where      Where conditions as string
     * @param array  $bindValues BindValues
     * @param array  $order      Order, direction as array
     * @param array  $limit      Limit, Offset as array
     * 
     * @return array
     */
    public static function get(
        string $where = "1",
        array $bindValues =[],
        array $order = [1,"ASC"],
        array $limit = []
    ): array {
        $result = [];
        $mapper = get_called_class();
        $class = (new ReflectionClass(new static()))->getShortName();
        self::$_db = self::$_db ?? DatabaseFactory::create();
        self::$_db->selectAs("*")
            ->from($class)
            ->where($where)
            ->orderby(...$order);
        if ((sizeof($limit) == 1) || (sizeof($limit) == 2)) {
            self::$_db->limit(...$limit);
        }
        self::$_db->appendBindValues($bindValues)
            ->execute();
        while ($row = self::$_db->fetch()) {
            $result[] = self::map($mapper, $row);
        }
        return $result;
    }

    /**
     * Class mapp
     *
     * @param string|object $mapperObj Destination
     * @param object        $stdObj    Source
     *
     * @return object
     */
    public static function map($mapperObj, $stdObj)
    {
        if (is_string($mapperObj)) {
            $mapperObj = new $mapperObj();
        }
        $sourceReflection = new ReflectionObject($stdObj);
        $mapperObjReflection = new ReflectionObject($mapperObj);
        $sourceProperties = $sourceReflection->getProperties();
        foreach ($sourceProperties as $sourceProperty) {
            $sourceProperty->setAccessible(true);
            $name = $sourceProperty->getName();
            $value = $sourceProperty->getValue($stdObj);
            if ($mapperObjReflection->hasProperty($name)) {
                $propDest = $mapperObjReflection->getProperty($name);
                $propDest->setAccessible(true);
                $propDest->setValue($mapperObj, $value);
            } else {
                $mapperObj->$name = $value;
            }
        }
        $mapperObj->_isNew=false;
        return $mapperObj;
    }
}
