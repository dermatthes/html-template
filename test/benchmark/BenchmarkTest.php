<?php

    namespace Gismo\Test\Component;
    use Html5\Template\HtmlTemplate;
    use Html5\Template\GoTemplateParser;
    use PHPUnit\Framework\TestCase;

    /**
     * Created by PhpStorm.
     * User: matthes
     * Date: 28.07.16
     * Time: 20:37
     */
    class BenchmarkTest extends TestCase
    {
    
    
        public function testGeneratedDocumentMatchesInputDocument() 
        {
            $time = microtime(true);
            $parser = new HtmlTemplate();
    
            $inputContent = file_get_contents(__DIR__ . "/benchmark.xml");
    
    
    
            $output = $parser->render($inputContent, []);
            echo $output;
    
            echo "\nTime: " . (microtime(true) - $time);
        }
    
    }
