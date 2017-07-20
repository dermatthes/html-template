<?php
    /**
     * Created by PhpStorm.
     * User: matthes
     * Date: 29.07.16
     * Time: 23:56
     */

    namespace Html5\Template;
    
    use Html5\Template\Directive\GoDirective;
    use Html5\Template\Directive\GoInlineTextDirective;

    class GoTemplateDirectiveBag {

        /**
         * @var GoDirective[]
         */
        public $elemToDirective = [];

        /**
         * @var GoDirective[]
         */
        public $elemNsToDirective = [];

        /**
         * @var GoDirective[]
         */
        public $attrToDirective = [];



        /**
         * @var GoInlineTextDirective
         */
        public $textDirective = null;

        /**
         * @var GoDirective[]
         */
        public $directiveClassNameMap = [];

    }