<?php
    /**
     * Created by PhpStorm.
     * User: matthes
     * Date: 17.08.16
     * Time: 10:15
     */


    namespace Html5\Template\Directive;

    use Html5\Template\Directive\Ex\GoReturnDataException;
    use Html5\Template\Node\GoElementNode;
    use Html5\Template\GoTemplateDirectiveBag;

    class GoStructDirective implements GoDirective
    {


        public function register(GoTemplateDirectiveBag $bag)
        {
            $bag->elemToDirective["go-struct"] = $this;
            $bag->directiveClassNameMap[get_class($this)] = $this;
        }

        public function getPriority() : int
        {
            return 999;
        }

        public function exec(GoElementNode $node, array &$scope, &$output, GoDirectiveExecBag $execBag)
        {

            $params = [];
            foreach ($node->childs as $child) {
                try {
                    $child->run($scope, $execBag);
                } catch (GoReturnDataException $data) {
                    if ($data->isArray()) {
                        if ( ! isset ($params[$data->getName()]))
                            $params[$data->getName()] = [];
                        $params[$data->getName()][] = $data->getDataToReturn();
                    } else {
                        $params[$data->getName()] = $data->getDataToReturn();
                    }
                }
            }

            if (isset ($node->attributes["as"])) {
                $scope[$node->attributes["as"]] = $params;
                return null;
            }

            // Return the colleced Data.
            throw new GoReturnDataException($params);
        }
    }