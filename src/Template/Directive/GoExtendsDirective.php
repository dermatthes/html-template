<?php
    /**
     * Created by PhpStorm.
     * User: matthes
     * Date: 17.08.16
     * Time: 10:33
     */

    namespace Html5\Template\Directive;


    use Html5\Template\Directive\Ex\GoReturnDataException;
    use Html5\Template\GoTemplateDirectiveBag;
    use Html5\Template\Node\GoElementNode;

    class GoExtendsDirective implements GoDirective{


        private $mExtendsCallback = null;

        public function register(GoTemplateDirectiveBag $bag)
        {
            $bag->elemToDirective["go-extends"] = $this;
            $bag->directiveClassNameMap[get_class($this)] = $this;
        }


        /**
         * Callback to run after a go-extends directive is parsed
         *
         * Parameter:
         *  function($name, array $sectionData)
         *
         * @param callable $fn
         */
        public function setExtendsCallback(callable $fn) {
            $this->mExtendsCallback = $fn;
        }

        public function getPriority() : int
        {
            return 999;
        }

        public function exec(GoElementNode $node, array &$scope, &$output, GoDirectiveExecBag $execBag)
        {
            $return = "";

           // $execBag->dataToReturnScope = [];

            if ($this->mExtendsCallback === null)
                throw new \InvalidArgumentException("go-extends must be configured with valid callback. See documentation or unit-tests.");

            $name = $node->attributes["name"];
            if ( ! preg_match("|^[a-z0-9/\\._]+$|i", $name))
                throw new \InvalidArgumentException("Invalid go-extends name='$name'");

            $params = [];
            foreach ($node->childs as $curChild) {
                try {
                    $curChild->run($scope, $execBag);
                    throw new \InvalidArgumentException("go-extends accepts only go-param as child!");
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

            // Return the colleced Data.
            throw new GoReturnDataException(($this->mExtendsCallback)($name, $params));
        }
    }