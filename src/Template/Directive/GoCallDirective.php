<?php
    /**
     * Created by PhpStorm.
     * User: matthes
     * Date: 01.08.16
     * Time: 11:52
     */

    namespace Html5\Template\Directive;
   
    use Html5\Template\Node\GoCommentNode;
    use Html5\Template\Node\GoElementNode;
    use Html5\Template\GoTemplateDirectiveBag;
    use Html5\Template\Node\GoTextNode;

    class GoCallDirective implements GoDirective
    {


        public function register(GoTemplateDirectiveBag $bag)
        {
            $bag->elemToDirective["go-call"] = $this;
            $bag->directiveClassNameMap[get_class($this)] = $this;
        }

        public function getPriority() : int
        {
            return 0;
        }


        private $callback;

        public function setCallback (callable  $fn) {
            $this->callback = $fn;
        }


        public function exec(GoElementNode $node, array &$scope, &$output, GoDirectiveExecBag $execBag)
        {
            $name = $node->attributes["name"];

            $as = @$node->attributes["as"];
            $datasource = @$node->attributes["datasource"];

            if ( ! preg_match ("|([a-z0-9_\\.]+)\\s*(\\((.*)\\)|)|i", trim ($name), $matches)) {
                throw new \InvalidArgumentException("Cannot parse call name='$name'");
            }

            $callName = $matches[1];


            $params = [];
            if ($datasource != null) {
                 if ( ! preg_match ("|([a-z0-9_\\.]+)\\s*\\((.*)\\)|i", trim ($datasource), $matchesDataSource)) {
                    throw new \InvalidArgumentException("Cannot parse call name='$name'");
                }
                $datasourceCall = $matchesDataSource[1];
                $params = $matches[2];
                $params = $execBag->expressionEvaluator->yaml($params, $scope);
                $params = ($this->callback)($datasourceCall, $params);
            } else if (isset ($matches[3])) {
                $params = $matches[3];
                $params = $execBag->expressionEvaluator->yaml($params, $scope);
            } else {
                if (count ($node->childs) > 0) {
                    $child = $node->childs[0];
                    if ($child instanceof GoTextNode) {
                        $params = $execBag->expressionEvaluator->yaml($child->text, $scope);
                    }
                    if ($child instanceof GoCommentNode) {
                        $params = $execBag->expressionEvaluator->yaml($child->text, $scope);
                    }
                }
            }

            $ret = ($this->callback)($callName, $params);

            if ($as !== null) {
                $scope[$as] = $ret;
                return null;
            }

            return $ret;
        }
    }