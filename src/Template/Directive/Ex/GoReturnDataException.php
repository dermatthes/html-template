<?php
    /**
     * Created by PhpStorm.
     * User: matthes
     * Date: 17.08.16
     * Time: 09:38
     */


    namespace Html5\Template\Directive\Ex;


    /**
     * Class GoReturnDataException
     *
     * Thrown within Directives to advise the Parser to stop
     * further parsing and return the Dataset in Parameter 1
     *
     * @package Html5\Template\Directive\Ex
     */
    class GoReturnDataException extends \Exception {

        private $dataToReturn;
        private $name;
        private $isArray;


        public function __construct($dataToReturn, string $name=null) {
            $this->dataToReturn = $dataToReturn;
            $this->isArray = false;
            $this->name = $name;
            if($name !== null && preg_match ("/(.*)\\[\\]$/", $name, $matches)) {
                $this->name = $matches[1];
                $this->isArray = true;
            }
        }

        public function getDataToReturn() {
            return $this->dataToReturn;
        }

        public function getName () {
            return $this->name;
        }

        public function isArray() : bool {
            return $this->isArray;
        }
    }