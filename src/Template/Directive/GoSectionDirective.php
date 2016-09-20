<?php
    /**
     * Created by PhpStorm.
     * User: matthes
     * Date: 17.08.16
     * Time: 09:58
     */

    namespace Html5\Template\Directive;

    use Html5\Template\Directive\Ex\GoReturnDataException;
    use Html5\Template\Node\GoElementNode;
    use Html5\Template\GoTemplateDirectiveBag;


    /**
     * Class GoSectionDirective
     *
     * Define a parameter for go-call or go-extends
     *
     * <example>
     *  <go-extends name="tpl.xy">
     *      <go-section as="content">
     *          .. Some Html ..
     *      </go-section>
     *  </go-extends>
     * </example>
     *
     * @package Html5\Template\Directive
     */
    class GoSectionDirective implements GoDirective
    {


        public function register(GoTemplateDirectiveBag $bag)
        {
            $bag->elemToDirective["go-section"] = $this;
            $bag->directiveClassNameMap[get_class($this)] = $this;
        }

        public function getPriority() : int
        {
            return 999;
        }

        public function exec(GoElementNode $node, array &$scope, &$output, GoDirectiveExecBag $execBag)
        {
            $as = $node->attributes["as"];



            $select = null;
            if (isset ($node->attributes["select"]))
                $select = $node->attributes["select"];

            if ( ! preg_match ("|([a-z0-9_]+)|i", $as)) {
                throw new \InvalidArgumentException("Invalid go-section as='$as': Allowed [a-zA-Z0-9_]+");
            }

            $returnData = [];

            if ($select !== null) {
                $returnData = $execBag->expressionEvaluator->eval($select, $scope);
            } else {
                $return = "";
                foreach ($node->childs as $curChild) {
                    try {
                        $return .= $curChild->run($scope, $execBag);
                    } catch (GoReturnDataException $data) {
                        if ($data->isArray()) {
                            if ( ! isset ($returnData[$data->getAs()]))
                                $returnData[$data->getAs()] = [];
                            $returnData[$data->getAs()][] = $data->getDataToReturn();
                        } else {
                            $returnData[$data->getAs()] = $data->getDataToReturn();
                        }
                    }
                }
            }

            if (strlen($as) < 1)
                throw new \InvalidArgumentException("go-section requires as attribute");

            throw new GoReturnDataException($returnData, $as);
        }
    }