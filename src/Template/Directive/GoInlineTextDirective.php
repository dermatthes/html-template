<?php
    /**
     * Created by PhpStorm.
     * User: matthes
     * Date: 01.08.16
     * Time: 17:44
     */

    namespace Html5\Template\Directive;


   
    use Html5\Template\Node\GoElementNode;
    use Html5\Template\Node\GoNode;
    use Html5\Template\GoTemplateDirectiveBag;

    class GoInlineTextDirective implements GoDirective {


        public function register(GoTemplateDirectiveBag $bag)
        {
            $bag->textDirective = $this;
            $bag->directiveClassNameMap[get_class($this)] = $this;
        }

        public function getPriority() : int {
            return 0;
        }

        private $mStartDelimiter = '\{\{';
        private $mEndDelimiter = '\}\}';

        private function setStartEndDelimiter ($start='{{', $end="}}") {

        }

        public function exec(GoElementNode $node, array $scope, &$output, GoDirectiveExecBag $execBag)
        {

            throw new \InvalidArgumentException("Cannot exec on textNode");
        }

        public function execText($inputText, GoNode $owner=null, array $scope, &$output, GoDirectiveExecBag $execBag) {
            $ret = preg_replace_callback("/{$this->mStartDelimiter}(.*?){$this->mEndDelimiter}/im",
                    function ($matches) use (&$scope, $execBag) {
                        return htmlspecialchars($execBag->expressionEvaluator->eval($matches[1], $scope));
                    },
                    $inputText
            );
            return $ret;

        }
    }