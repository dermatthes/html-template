<?php
/**
 * Created by PhpStorm.
 * User: matthes
 * Date: 11.08.16
 * Time: 22:54
 */

    namespace Html5\Template\Expression;

    class Scope implements \ArrayAccess {

        private $data;

        public function __construct(array $data)
        {
            $this->data = $data;
        }



        public function offsetExists($offset)
        {
            return true;
        }

        /**
         * Offset to retrieve
         * @link http://php.net/manual/en/arrayaccess.offsetget.php
         * @param mixed $offset <p>
         * The offset to retrieve.
         * </p>
         * @return mixed Can return all value types.
         * @since 5.0.0
         */
        public function offsetGet($offset)
        {
            if ( ! isset ($this->data[$offset]))
                return null;
            if (is_array($this->data[$offset]))
                return new self($this->data[$offset]);
            return $this->data[$offset];
        }

        /**
         * Offset to set
         * @link http://php.net/manual/en/arrayaccess.offsetset.php
         * @param mixed $offset <p>
         * The offset to assign the value to.
         * </p>
         * @param mixed $value <p>
         * The value to set.
         * </p>
         * @return void
         * @since 5.0.0
         */
        public function offsetSet($offset, $value)
        {
            $this->data = $value;
        }

        /**
         * Offset to unset
         * @link http://php.net/manual/en/arrayaccess.offsetunset.php
         * @param mixed $offset <p>
         * The offset to unset.
         * </p>
         * @return void
         * @since 5.0.0
         */
        public function offsetUnset($offset)
        {
            unset ($this->data);
        }
    }