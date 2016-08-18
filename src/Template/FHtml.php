<?php
    /**
     * Created by PhpStorm.
     * User: matthes
     * Date: 18.08.16
     * Time: 11:44
     */

    namespace Html5\Template;



    use Html5\Template\Directive\GoDirectiveExecBag;
    use Html5\Template\HtmlTemplate;
    use Html5\Template\Node\GoDocumentNode;
    use Html5\Template\Node\GoElementNode;
    use Html5\Template\Node\GoTextNode;

    class FHtml {

        /**
         * @var HtmlTemplate
         */
        private $template;
        private $directiveBag;
        private $documentNode;

        /**
         * @var GoElementNode
         */
        private $curNode;
        private $jumpMarks = [];

        public function __construct(HtmlTemplate $bindParser=null) {
            if ($bindParser === null)
                $bindParser = new HtmlTemplate();
            $this->template = $bindParser;
            $this->directiveBag = $bindParser->getParser()->getDirectiveBag();
            $this->documentNode = new GoDocumentNode();
            $this->curNode = $this->documentNode;
        }

        /**
         * Define the sub-Element of the current node
         *
         * Example
         *
         * $e->elem("div @class = a b c @name = some Name")
         *
         * @param $def
         */
        public function elem($def) : self {
            $arr = explode("@", $def);
            $tagName = trim (array_shift($arr));

            $attrs = [];
            foreach ($arr as $attdef) {
                list ($key, $val) = explode("=", $attdef, 1);
                $attrs[trim($key)] = trim ($val);
            }

            $newNode = new GoElementNode();
            $newNode->name = $tagName;
            $newNode->attributes = $attrs;
            $this->curNode->childs[] = $newNode;
            $newNode->parent = $this->curNode;

            $this->curNode = $newNode;
            return $this;
        }

        public function end() : self {
            if ( ! isset ($this->curNode->parent))
                throw new \InvalidArgumentException("end(): Node has no parent.");
            $this->curNode = $this->curNode->parent;
            return $this;
        }

        public function as($name) : self {
            $this->jumpMarks[$name] = $this->curNode;
            return $this;
        }

        public function goto($name) : self {
            if ( ! isset($this->jumpMarks[$name]))
                throw new \InvalidArgumentException("goto($name) undefined.");
            $this->curNode = $this->jumpMarks[$name];
            return $this;
        }

        public function text($content) : self {
            $this->curNode->childs[] = new GoTextNode($content);
        }


        public function root() : self {
            $this->curNode = $this->documentNode;
            return $this;
        }

        public function getDocument() : GoDocumentNode {
            return $this->documentNode;
        }

        public function render(array $data) : string {
            return $this->documentNode->run($data, $this->template->getExecBag());
        }

    }