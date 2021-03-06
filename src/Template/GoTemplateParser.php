<?php
/**
 * Created by PhpStorm.
 * User: matthes
 * Date: 28.07.16
 * Time: 20:26
 */

    namespace Html5\Template;
    
    use Html5\Template\Directive\GoBindDirective;
    use Html5\Template\Directive\GoCallDirective;
    use Html5\Template\Directive\GoCallMacroDirective;
    use Html5\Template\Directive\GoClassDirective;
    use Html5\Template\Directive\GoDirective;
    use Html5\Template\Directive\GoDumpDirective;
    use Html5\Template\Directive\GoExtendsDirective;
    use Html5\Template\Directive\GoForeachDirective;
    use Html5\Template\Directive\GoHtmlDirective;
    use Html5\Template\Directive\GoIfDirective;
    use Html5\Template\Directive\GoInlineTextDirective;
    use Html5\Template\Directive\GoMacroDirective;
    use Html5\Template\Directive\GoNsCallDirective;
    use Html5\Template\Directive\GoNsParamDirective;
    use Html5\Template\Directive\GoOutputDirective;
    use Html5\Template\Directive\GoRepeatDirective;
    use Html5\Template\Directive\GoParamDirective;
    use Html5\Template\Directive\GoStructDirective;
    use Html5\Template\Exception\TemplateParsingException;
    use Html5\Template\Node\GoCommentNode;
    use Html5\Template\Node\GoDocumentNode;
    use Html5\Template\Node\GoElementNode;
    use Html5\Template\Node\GoNode;
    use Html5\Template\Node\GoTextNode;
    use HTML5\HTMLReader;
    use HTML5\Tokenizer\HtmlCallback;


    class GoTemplateParser
    {


        /**
         * @var GoTemplateDirectiveBag
         */
        private $directiveBag;

        /**
         * @var HTMLReader
         */
        private $htmlReader;
        
        public function __construct()
        {
            $this->directiveBag = new GoTemplateDirectiveBag();

            $this->addDirective(new GoIfDirective());
            $this->addDirective(new GoForeachDirective());
            $this->addDirective(new GoBindDirective());
            $this->addDirective(new GoHtmlDirective());
            $this->addDirective(new GoClassDirective());
            $this->addDirective(new GoRepeatDirective());
            $this->addDirective(new GoMacroDirective());
            $this->addDirective(new GoCallMacroDirective());
            $this->addDirective(new GoDumpDirective());
            $this->addDirective(new GoInlineTextDirective());
            $this->addDirective(new GoParamDirective());
            $this->addDirective(new GoStructDirective());
            $this->addDirective(new GoExtendsDirective());
            $this->addDirective(new GoCallDirective());
            $this->addDirective(new GoOutputDirective());
            $this->addDirective(new GoNsCallDirective());
            $this->addDirective(new GoNsParamDirective());

            $this->htmlReader = new HTMLReader();
        }


        public function getDirective(string $className) : GoDirective
        {
            return $this->directiveBag->directiveClassNameMap[$className];
        }

        public function addDirective(GoDirective $d)
        {
            $d->register($this->directiveBag);
        }


        public function getDirectiveBag() : GoTemplateDirectiveBag {
            return $this->directiveBag;
        }


        /**
         * @var \XMLReader
         */
        private $loadedXmlReader = null;


        public function loadHtml($input)
        {
            $this->htmlReader->loadHtmlString($input);
        }


        public function loadHtmlFile($filename) {
            $this->loadHtml(file_get_contents($filename));
        }
    

        public function parse($templateName="unnamed") : GoDocumentNode
        {
            $rootNode = new GoDocumentNode();
            $rootNode->setTemplateName($templateName);
            $reader = $this->htmlReader;
            
            $reader->setHandler(new class ($rootNode, $this->directiveBag) implements HtmlCallback {

                private $html5EmptyTags = ["img", "meta", "br", "hr", "input", "link"]; // Tags to treat as empty although they're not

                /**
                 * @var GoNode
                 */
                private $curNode;

                /**
                 * @var GoDocumentNode
                 */
                private $rootNode;

                private $curLine = 1;
                /**
                 * @var GoTemplateDirectiveBag
                 */
                private $directiveBag;

                public function __construct(GoNode $rootNode, GoTemplateDirectiveBag $directiveBag) {
                    $this->curNode = $rootNode;
                    $this->rootNode = $rootNode;
                    $this->directiveBag = $directiveBag;
                }

                private $curWhiteSpace = "";

                public function onWhitespace(string $ws) {
                    $this->curLine += substr_count($ws, "\n");
                    $this->curWhiteSpace = $ws;
                }

                public function onTagOpen(string $name, array $attributes, $isEmpty, $ns=null) {
                    $newNode = new GoElementNode();

                    $newNode->ns = $ns;
                    $newNode->name = $name;
                    $newNode->lineNo = $this->curLine;

                    $newNode->isEmptyElement = $isEmpty;

                    if (in_array($name, $this->html5EmptyTags)) {
                        $newNode->isEmptyElement = true;
                    }

                    $newNode->useInlineTextDirective($this->directiveBag->textDirective);

                    if ($newNode->ns !== null && isset ($this->directiveBag->elemNsToDirective[$newNode->ns])) {
                        $newNode->useDirective($this->directiveBag->elemNsToDirective[$newNode->ns]);
                    } else if (isset ($this->directiveBag->elemToDirective[$newNode->name])) {
                        $newNode->useDirective($this->directiveBag->elemToDirective[$newNode->name]);
                    }

                    $newNode->preWhiteSpace = $this->curWhiteSpace;
                    $this->curWhiteSpace = "";
                    $newNode->parent = $this->curNode;


                    foreach ($attributes as $attributeName => $attributeValue) {
                        if (isset ($this->directiveBag->attrToDirective[$attributeName])) {
                            $newNode->useDirective($this->directiveBag->attrToDirective[$attributeName]);
                        }
                        $newNode->attributes[$attributeName] = $attributeValue;
                    }


                    $newNode->postInit();

                    $this->curNode->childs[] = $newNode;
                    if ( ! $newNode->isEmptyElement) {
                        $this->curNode = $newNode;
                    }
                }

                public function onText(string $text) {
                    $this->curLine += substr_count($text, "\n");

                    $text = new GoTextNode($text, $this->directiveBag->textDirective);
                    $text->preWhiteSpace = $this->curWhiteSpace;
                    $this->curWhiteSpace = "";
                    $this->curNode->childs[] = $text;
                }

                public function onTagClose(string $name, $ns=null) {
                    if (in_array($name, $this->html5EmptyTags)) {
                        //Ignore
                        return;
                    }
                    $this->curNode->postWhiteSpace = $this->curWhiteSpace;
                    if ($this->curNode instanceof GoDocumentNode) {
                        throw new TemplateParsingException("Closing Tag mismatch in template '{$this->rootNode->getTemplateName()}'.");
                    }
                    $this->curNode = $this->curNode->parent;
                }

                public function onProcessingInstruction(string $data) {
                    if ($this->curNode instanceof GoDocumentNode) {
                        $this->curNode->processingInstructions = $data;
                    }
                }

                public function onComment(string $data) {
                    $this->curLine += substr_count($data, "\n");
                    $this->curNode->childs[] = $newChild = new GoCommentNode($data);
                    $newChild->preWhiteSpace = $this->curWhiteSpace;
                    $this->curWhiteSpace = "";
                }
            });
            $reader->parse();

            return $rootNode;
        }


    }