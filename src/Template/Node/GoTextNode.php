<?php
/**
 * Created by PhpStorm.
 * User: matthes
 * Date: 28.07.16
 * Time: 22:06
 */

    namespace Html5\Template\Node;


    use Html5\Template\Directive\GoDirectiveExecBag;
    use Html5\Template\Directive\GoInlineTextDirective;

    class GoTextNode implements GoNode {

        public $text;
        public $preWhiteSpace = "";

        /**
         * @var GoInlineTextDirective
         */
        private $mInlineTextDirective;
        
        public function __construct($text, GoInlineTextDirective $inlineTextDirective=null)
        {
            $this->text = $text;
            $this->mInlineTextDirective = $inlineTextDirective;
        }

        public function render (array &$scope, GoDirectiveExecBag $execBag) {
            $text = htmlspecialchars(html_entity_decode($this->text));
            if ($this->mInlineTextDirective !== null) {
                $text = htmlspecialchars(html_entity_decode($this->mInlineTextDirective->execText($this->text, null, $scope, $output, $execBag)));
            }

            return $this->preWhiteSpace . $text;
        }

        public function run (array &$scope, GoDirectiveExecBag $execBag, $skipPre=false) {
            return $this->render($scope, $execBag);
        }

    }