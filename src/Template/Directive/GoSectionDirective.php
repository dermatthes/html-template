<?php
    /**
     * Created by PhpStorm.
     * User: matthes
     * Date: 17.08.16
     * Time: 09:58
     */

    namespace Html5\Template\Directive;

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

            if ( ! preg_match ("|([a-z0-9_]+)|i", $as)) {
                throw new \InvalidArgumentException("Invalid go-section as='$as': Allowed [a-zA-Z0-9_]+");
            }

            $return = "";
            foreach ($node->childs as $curChild) {
                $return .= $curChild->run($scope, $execBag);
            }

            $asArray = false;
            if (preg_match ("/(.+)\\[\\]/", $as, $matches)) {
                $asArray = true;
                $as = $matches[1];
            }

            if ($asArray) {
                if ( ! isset ($execBag->dataToReturnScope[$as])) {
                    $execBag->dataToReturnScope[$as] = [];
                } else if ( ! is_array($execBag->dataToReturnScope[$as])) {
                    $execBag->dataToReturnScope[$as] = [ $execBag->dataToReturnScope[$as] ];
                }
                $execBag->dataToReturnScope[$as][] = $return;
                return false;
            }
            $execBag->dataToReturnScope[$as] = $return;
            return false;
        }
    }