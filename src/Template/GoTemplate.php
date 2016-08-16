<?php
    /**
     * Created by PhpStorm.
     * User: matthes
     * Date: 01.08.16
     * Time: 23:49
     */

    namespace Html5\Template;

   
    use Html5\Template\Datastore\GoTemplateStore;
    use Html5\Template\Directive\GoDirective;
    use Html5\Template\Directive\GoDirectiveExecBag;
    use Html5\Template\Expression\GoExpressionEvaluator;
    use Html5\Template\Expression\Scope;

    class GoTemplate
    {

        /**
         * @var GoTemplateParser
         */
        private $mParser;


        /**
         * @var GoDirectiveExecBag
         */
        private $mExecBag;


        
        public function __construct()
        {
            $this->mParser = new GoTemplateParser();
            $this->mExecBag = new GoDirectiveExecBag(new GoExpressionEvaluator());
        }



        public function addDirective(GoDirective $directive)
        {
            $this->mParser->addDirective($directive);
        }

        public function getDirective(string $className) : GoDirective
        {
            return $this->mParser->getDirective($className);
        }

        public function getExpressionEvaluator () : GoExpressionEvaluator
        {
            return $this->mExecBag->expressionEvaluator;
        }

        public function setExpressionEvaluator(GoExpressionEvaluator $evaluator)
        {
            $this->mExecBag->expressionEvaluator = $evaluator;
        }


        public function setScopePrototype(array $scope)
        {
            $this->mExecBag->scopePrototype = $scope;
        }



        public function render(string $inputTemplateData, array $scopeData, &$structOutputData = []) : string
        {
            $scope = $this->mExecBag->scopePrototype;
            foreach ($scopeData as $key => $val) {
                $scope[$key] = $val;
            }


            $this->mParser->loadHtml($inputTemplateData);

            $template = $this->mParser->parse();

            return $template->run($scope, $this->mExecBag);
        }


        public function renderHtmlFile($filename, array $scopeData = []) : string
        {
            return $this->render(file_get_contents($filename), $scopeData, $data);
        }
        
        

    }