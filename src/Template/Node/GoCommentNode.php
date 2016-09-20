<?php
    /**
     * Created by PhpStorm.
     * User: matthes
     * Date: 29.07.16
     * Time: 15:47
     */

    namespace Html5\Template\Node;


    use Html5\Template\Directive\GoDirectiveExecBag;

    class GoCommentNode implements GoNode {

        public $text;
        public $preWhiteSpace = "";
        
        public function __construct(string $text) {
            $this->text = $text;
        }


        public function render(array &$scope, GoDirectiveExecBag $execBag) {
            return "{$this->preWhiteSpace}<!--" . $this->text . "-->";
        }


        public function run(array &$scope, GoDirectiveExecBag $execBag, $skipPre=false) {
            return $this->render($scope, $execBag);
        }
    }