<?php
    /**
     * Created by PhpStorm.
     * User: matthes
     * Date: 29.07.16
     * Time: 15:50
     */


    namespace Html5\Template\Node;


    use Html5\Template\Directive\GoDirectiveExecBag;

    class GoWhiteSpaceNode implements GoNode {

        private $whitespace;

        public function __construct($data) {
            $this->whitespace = $data;
        }


        public function render(array &$scope, GoDirectiveExecBag $execBag) {
            return $this->whitespace;
        }

        public function run(array &$scope, GoDirectiveExecBag $execBag, $skipPre=false) {
            return $this->render($scope, $execBag);
        }
    }