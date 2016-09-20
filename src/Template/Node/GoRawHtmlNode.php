<?php
    /**
     * Created by PhpStorm.
     * User: matthes
     * Date: 14.09.16
     * Time: 18:06
     */

    namespace Html5\Template\Node;


    use Html5\Template\Directive\GoDirectiveExecBag;

    class GoRawHtmlNode extends GoElementNode
    {

        private $rawData;

        public function __construct($rawData)
        {
            $this->rawData = $rawData;
        }


        public function run(array &$scope, GoDirectiveExecBag $execBag, $skipPre=false)
        {
            return $this->rawData;
        }

    }