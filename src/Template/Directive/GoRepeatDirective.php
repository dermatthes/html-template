<?php
    /**
     * Created by PhpStorm.
     * User: matthes
     * Date: 29.07.16
     * Time: 19:51
     */
    
    
    namespace Html5\Template\Directive;


   

    use Html5\Template\Directive\Ex\GoBreakLoopException;
    use Html5\Template\Directive\Ex\GoContinueLoopException;
    use Html5\Template\Node\GoElementNode;
    use Html5\Template\GoTemplateDirectiveBag;

    class GoRepeatDirective implements GoPreDirective
    {
        
        public function register(GoTemplateDirectiveBag $bag)
        {
            $bag->attrToDirective["go-repeat"] = $this;
            $bag->directiveClassNameMap[get_class($this)] = $this;
        }

        public function getPriority() : int {
            return 998;
        }

        public function exec(GoElementNode $node, array &$scope, &$output, GoDirectiveExecBag $execBag)
        {
            $stmt = $node->attributes["go-repeat"];

            $output = "";

            if (preg_match ('/^(.*)\s+index\s+([a-z0-9_]+)$/i', trim ($stmt), $matches)) {
                $data = $execBag->expressionEvaluator->eval($matches[1], $scope);
                for ($i = 0; $i < $data; $i++) {

                    $scope[$matches[2]] = $i;
                    $clone = clone $node;
                    $clone->attributes["go-repeat"] = "";
                    try {
                        $output .= $clone->run($scope, $execBag, true);
                    } catch (GoBreakLoopException $e) {
                        break;
                    } catch (GoContinueLoopException $e) {
                        continue;
                    }
                }
                return $output;

            } else {
                throw new \InvalidArgumentException("Cannot parse repeat '$stmt'");
            }
        }
    }