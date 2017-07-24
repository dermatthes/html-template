<?php
    /**
     * Created by PhpStorm.
     * User: matthes
     * Date: 20.07.17
     * Time: 11:20
     */

    namespace Html5\Template\Directive;


    use Html5\Template\Directive\Ex\GoReturnDataException;
    use Html5\Template\GoTemplateDirectiveBag;
    use Html5\Template\Node\GoCommentNode;
    use Html5\Template\Node\GoElementNode;
    use Html5\Template\Node\GoTextNode;
    use Symfony\Component\Yaml\Exception\ParseException;

    class GoNsCallDirective implements GoDirective {

        public function register(GoTemplateDirectiveBag $bag)
        {
            $bag->elemNsToDirective["call"] = $this;
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
            $callName = $node->name;

            $as = null;
            if (isset ($node->attributes["as"]))
                $as = $node->attributes["as"];

            $parse = isset ($node->attributes["parse"]) ? $node->attributes["parse"] : "json";
            $parse = trim (strtoupper($parse));


            $returnData = null;
            $params = [];

            if (isset ($node->childs[0]) && $node->childs[0] instanceof GoElementNode) {

                foreach ($node->childs as $child) {
                    try {
                        $child->run($scope, $execBag);
                    } catch (GoReturnDataException $data) {
                        if ($returnData === null)
                            $returnData = [];
                        if ($data->isArray()) {
                            if ( ! isset ($returnData[$data->getName()]))
                                $returnData[$data->getName()] = [];
                            $returnData[$data->getName()][] = $data->getDataToReturn();
                        } else {
                            $returnData[$data->getName()] = $data->getDataToReturn();
                        }

                    }
                    $params = $returnData;
                }

            } else {
                /*
                try {
                    $code = "";
                    if (count($node->childs) > 0) {
                        $child = $node->childs[0];

                        if ($child instanceof GoTextNode) {
                            $code = $child->text;
                        }
                        if ($child instanceof GoCommentNode) {
                            $code = $child->text;
                        }
                    }
                    if ($parse == "JSON") {
                        $params = json_decode($code, false);
                        if ($params === null)
                            throw new ParseException("Cannot parse json string");
                        $arrVal = [];
                        foreach ($params as $key => $value) {
                            $arrVal[$key] = $value;
                        }
                        $params = $arrVal;
                    } else if ($parse == "YAML") {
                        $params = $execBag->expressionEvaluator->yaml($code, $scope);
                    }

                } catch (ParseException $e) {
                    throw new ParseException("Cannot parse: {$e->getMessage()}\n{$child->text}", -1, null, null, $e);
                }
                */
            }

            $ret = ($this->callback)($callName, $params);

            if ($as !== null) {
                $scope[$as] = $ret;
                return null;
            }

            return $ret;
        }
    }