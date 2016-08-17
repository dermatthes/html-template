<?php
/**
 * Created by PhpStorm.
 * User: matthes
 * Date: 28.07.16
 * Time: 23:33
 */

    namespace Html5\Template\Directive;


    use Html5\Template\Directive\Ex\GoBreakLoopException;
    use Html5\Template\Directive\Ex\GoContinueLoopException;
    use Html5\Template\Node\GoElementNode;
    use Html5\Template\GoTemplateDirectiveBag;

    class GoForeachDirective implements GoDirective
    {


        public function register(GoTemplateDirectiveBag $bag)
        {
            $bag->attrToDirective["go-foreach"] = $this;
            $bag->directiveClassNameMap[get_class($this)] = $this;
        }
        
        public function getPriority() : int {
            return 50;
        }

        public function exec(GoElementNode $node, array $scope, &$output, GoDirectiveExecBag $execBag)
        {
            $stmt = $node->attributes["go-foreach"];

            $output = "";


            if (preg_match ('/^(.*)\s+as\s+([a-z0-9_]+)$/i', trim ($stmt), $matches)) {
                $data = $execBag->expressionEvaluator->eval($matches[1], $scope);
                foreach ($data as $curElem) {
                    $scope[$matches[2]] = $curElem;
                    $clone = clone $node;
                    $clone->attributes["go-foreach"] = "";
                    try {
                        $output .= $clone->render($scope, $execBag);
                    } catch (GoBreakLoopException $e) {
                        break;
                    } catch (GoContinueLoopException $e) {
                        continue;
                    }
                }
                return $output;
            } else if (preg_match ('/^(.*)\s+as\s+([a-z0-9_]+)\s*=>\s*([a-z0-9_])$/i', trim ($stmt), $matches)) {
                $data = $execBag->expressionEvaluator->eval($matches[1], $scope);
                foreach ($data as $key => $val) {
                    $scope[$matches[2]] = $key;
                    $scope[$matches[3]] = $val;
                    $clone = clone $node;
                    $clone->attributes["go-foreach"] = "";
                    try {
                        $output .= $clone->render($scope, $execBag);
                    } catch (GoBreakLoopException $e) {
                        break;
                    } catch (GoContinueLoopException $e) {
                        continue;
                    }
                }
                return $output;
            } else {
                throw new \InvalidArgumentException("Cannot parse foreach '$stmt'");
            }
        }
    }