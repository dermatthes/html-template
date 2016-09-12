<?php
    /**
     * Created by PhpStorm.
     * User: matthes
     * Date: 29.07.16
     * Time: 15:41
     */

    namespace Html5\Template\Node;


    use Html5\Template\Directive\GoDirectiveExecBag;

    interface GoNode {

        public function render(array &$scope, GoDirectiveExecBag $execBag);

        public function run(array &$scope, GoDirectiveExecBag $execBag);

    }