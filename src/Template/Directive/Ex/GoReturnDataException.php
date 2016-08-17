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

        public function __construct($dataToReturn) {
            $this->dataToReturn = $dataToReturn;
        }

        public function getDataToReturn() {
            return $this->dataToReturn;
        }

    }