<?php
    /**
     * Created by PhpStorm.
     * User: matthes
     * Date: 29.07.16
     * Time: 15:54
     */


    namespace Html5\Template\Node;


    use Html5\Template\Directive\Ex\GoReturnDataException;
    use Html5\Template\Directive\GoDirectiveExecBag;

    class GoDocumentNode implements GoNode {


        public $processingInstructions = "";
        public $childs = [];


        private $mExecBag = null;

        public function intercept($name) {
            
        }


        public function render(array &$scope, GoDirectiveExecBag $execBag) {
            
        }


        public function setExecBag (GoDirectiveExecBag $execBag) {
            $this->mExecBag = $execBag;
        }



        public function run(array &$scope, GoDirectiveExecBag $execBag=null) {
            if ($execBag === null)
                $execBag = $this->mExecBag;

            try {
                $output = $this->processingInstructions;
                foreach ($this->childs as $child) {
                    $output .= $child->run($scope, $execBag);
                }
                return $output;
            } catch (GoReturnDataException $e) {
                return $e->getDataToReturn();
            }
        }
    }