<?php
/**
 * Created by PhpStorm.
 * User: matthes
 * Date: 28.07.16
 * Time: 21:12
 */


    namespace Html5\Template\Node;

  

    use Html5\Template\Directive\GoDirective;
    use Html5\Template\Directive\GoDirectiveExecBag;
    use Html5\Template\Directive\GoInlineTextDirective;
    use Html5\Template\Directive\GoPreDirective;

    class GoElementNode implements GoNode {
        public $parent = null;

        public $ns = null;
        public $name;
        public $lineNo;
        public $attributes = [];
        public $preWhiteSpace = "";
        public $postWhiteSpace = "";

        public $isEmptyElement = false;

        /**
         * @var GoDirective[]
         */
        public $useDirectives = [];

        /**
         * @var GoNode[]
         */
        public $childs = [];
        /**
         * @var GoInlineTextDirective
         */
        private $mInlineTextDirective;

        public function useDirective(GoDirective $directive) {
            $this->useDirectives[$directive->getPriority()] = $directive;
        }

        public function useInlineTextDirective (GoInlineTextDirective $directive) {
            $this->mInlineTextDirective = $directive;
        }

        public function postInit () {
            krsort($this->useDirectives, SORT_NUMERIC);
        }


        public function render(array &$scope, GoDirectiveExecBag $execBag) {
            $ns = "";
            if ($this->ns !== null)
                $ns = "{$this->ns}:";
            
            $ret = "{$this->preWhiteSpace}<{$ns}{$this->name}";
            $attrs = [];
            foreach ($this->attributes as $name=>$val) {

                if ($this->mInlineTextDirective !== null) {
                    $val = $this->mInlineTextDirective->execText($val, NULL, $scope, $output, $execBag);
                } else {

                }
                $val = htmlspecialchars(html_entity_decode($val));
                $attrs[] = "{$name}=\"{$val}\"";
            }
            if (count ($attrs) > 0)
                $ret .= " " . implode(" ", $attrs);

            if ($this->isEmptyElement) {
                $ret .= "/>";
                return $ret;
            }

            $ret .= ">";

            foreach ($this->childs as $child) {
                $curData = $child->run($scope, $execBag);
                if (is_array($curData)) {
                    print_r ($curData);
                    throw new \Exception();
                }
                if ($this->name == "script" || $this->name == "style")
                    $curData = html_entity_decode($curData);
                $ret .= $curData;
            }
            $ret .= "{$this->postWhiteSpace}</{$ns}{$this->name}>";
            return $ret;
        }

        public function run(array &$scope, GoDirectiveExecBag $execBag, $skipPre=false) {
            $output = null;

            $curNodeOrOutput = clone $this;
            foreach ($this->useDirectives as $curDirective) {
                if ($curDirective instanceof GoPreDirective && $skipPre)
                    continue;
                $curNodeOrOutput = $curDirective->exec($curNodeOrOutput, $scope, $output, $execBag);
                if ($curNodeOrOutput === false) {
                    return null;
                }
                if (is_string($curNodeOrOutput)) {
                    return $curNodeOrOutput;
                }
            }
            if ($curNodeOrOutput instanceof GoElementNode)
                $output = $curNodeOrOutput->render($scope, $execBag);

            return $output;
        }

    }