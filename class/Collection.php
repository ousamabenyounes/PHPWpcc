<?php
namespace Wpcc;

use Traversable;

class Collection implements \IteratorAggregate, \ArrayAccess
{
    private $_items;

    public function __construct(array $items) {
        $this->_items = $items;
    }


    /**
     * @param string $key
     * @return bool
     */
    public function has($key) {

        return array_key_exists($key, $this->_items);
    }

    /**
     * @param string $key
     * @param string $value
     */
    public function get($key) {
        $result = false;
        if ($this->has($key)) {
            $result = $this->_items[$key];
        }

        return $result;
    }

    /**
     * @param string $key
     * @param string $value
     */
    public function set($key, $value) {
        $this->_items[$key] = $value;
    }

    /**
      * (PHP 5 &gt;= 5.0.0)<br/>
     * Whether a offset exists
     * @link http://php.net/manual/en/arrayaccess.offsetexists.php
     * @param mixed $offset <p>
     * An offset to check for.
     * </p>
     * @return boolean true on success or false on failure.
     * </p>
     * <p>
     * The return value will be casted to boolean if non-boolean was returned.
     */
    public function offsetExists($offset)
    {
        return $this->has($offset);
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Offset to retrieve
     * @link http://php.net/manual/en/arrayaccess.offsetget.php
     * @param mixed $offset <p>
     * The offset to retrieve.
     * </p>
     * @return mixed Can return all value types.
     */
    public function offsetGet($offset)
    {
        return $this->get($offset);
    }


    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Offset to unset
     * @link http://php.net/manual/en/arrayaccess.offsetunset.php
     * @param mixed $offset <p>
     * The offset to unset.
     * </p>
     * @return void
     */
    public function offsetUnset($offset) {
        if ($this->has($offset)) {
            unset($this->_items[$offset]);
        }
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Offset to set
     * @link http://php.net/manual/en/arrayaccess.offsetset.php
     * @param mixed $offset <p>
     * The offset to assign the value to.
     * </p>
     * @param mixed $value <p>
     * The value to set.
     * </p>
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        return $this->set($offset, $value);
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Retrieve an external iterator
     * @link http://php.net/manual/en/iteratoraggregate.getiterator.php
     * @return Traversable An instance of an object implementing <b>Iterator</b> or
     * <b>Traversable</b>
     */
    public function getIterator()
    {
        return new ArrayLogAdapter($this->_items);
    }

    public function lists($key, $value) {
        $results = [];
        foreach ($this->_items as $item) {
            $results[$item[$key]] = $item[$value];
        }

        return new Collection($results);
    }

    public function extract($key) {
        $results = [];
        foreach ($this->_items as $item) {
            $results[] = $item[$key];
        }

        return new Collection($results);
    }

    /**
     * @param string $glue
     * @return string
     */
    public function join($glue) {

        return implode($glue, $this->_items);
    }

    public function max($key = false) {
        if ($key) {
            return $this->extract($key)->max();
        }
        return max($this->_items);
    }
}

