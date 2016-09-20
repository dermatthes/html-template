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
        private $as;
        private $isArray;


        public function __construct($dataToReturn, string $as=null) {
            $this->dataToReturn = $dataToReturn;
            $this->isArray = false;
            $this->as = $as;
            if(preg_match ("/(.*)\\[\\]$/", $as, $matches)) {
                $this->as = $matches[1];
                $this->isArray = true;
            }
        }

        public function getDataToReturn() {
            return $this->dataToReturn;
        }

        public function getAs () {
            return $this->as;
        }

        public function isArray() : bool {
            return $this->isArray;
        }
    }