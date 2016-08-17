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


        public function render(array $scope, GoDirectiveExecBag $execBag) {
            
        }




        public function run(array $scope, GoDirectiveExecBag $execBag) {
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