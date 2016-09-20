<?php
    /**
     * Created by PhpStorm.
     * User: matthes
     * Date: 17.08.16
     * Time: 09:58
     */

    namespace Html5\Template\Directive;

    use Doctrine\Instantiator\Exception\InvalidArgumentException;
    use Html5\Template\Directive\Ex\GoReturnDataException;
    use Html5\Template\Node\GoElementNode;
    use Html5\Template\GoTemplateDirectiveBag;


    /**
     * Class GoParamDirective
     *
     * Define a parameter for go-call or go-extends
     *
     * <example>
     *  <go-extends name="tpl.xy">
     *      <go-param name="content">
     *          .. Some Html ..
     *      </go-param>
     *  </go-extends>
     * </example>
     *
     * @package Html5\Template\Directive
     */
    class GoParamDirective implements GoDirective
    {


        public function register(GoTemplateDirectiveBag $bag)
        {
            $bag->elemToDirective["go-param"] = $this;
            $bag->directiveClassNameMap[get_class($this)] = $this;
        }

        public function getPriority() : int
        {
            return 999;
        }

        public function exec(GoElementNode $node, array &$scope, &$output, GoDirectiveExecBag $execBag)
        {

            if ( ! isset ($node->attributes["name"]))
                throw new InvalidArgumentException("go-section is missing 'name=' - attribute");
            $name = $node->attributes["name"];




            $select = null;
            if (isset ($node->attributes["select"]))
                $select = $node->attributes["select"];

            if ( ! preg_match ("|([a-z0-9_]+)|i", $name)) {
                throw new \InvalidArgumentException("Invalid go-param name='$name': Allowed [a-zA-Z0-9_]+");
            }

            $returnData = null;
            $returnHtml = "";
            if ($select !== null) {
                $returnData = $execBag->expressionEvaluator->eval($select, $scope);
            } else {

                foreach ($node->childs as $curChild) {
                    try {
                        $returnHtml .= $curChild->run($scope, $execBag);
                    } catch (GoReturnDataException $data) {
                        if ($returnData === null)
                            $returnData = [];
                        if ($data->isArray()) {
                            if ( ! isset ($returnData->{$data->getName()}))
                                $returnData[$data->getName()] = [];
                            $returnData[$data->getName()][] = $data->getDataToReturn();
                        } else {
                            $returnData[$data->getName()] = $data->getDataToReturn();
                        }
                        continue;
                    }
                }
            }

            if ($returnData === null)
                throw new GoReturnDataException($returnHtml, $name);
            throw new GoReturnDataException($returnData, $name);
        }
    }