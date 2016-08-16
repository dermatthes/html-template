<?php

    /**
     * Created by PhpStorm.
     * User: matthes
     * Date: 29.07.16
     * Time: 16:33
     */    
    
    
    namespace Gismo\Test\Component;

    use Html5\Template\GoTemplate;

    class DirectiveTest extends \PHPUnit_Framework_TestCase {

        
        public function testAllDirectives () {
            $parser = new GoTemplate();
            foreach (scandir(__DIR__ . "/data") as $file) {
                if (preg_match ("/(.*)\\.php/", $file, $matches)) {
                    $inFile = __DIR__ . "/data/$file";
                    $compFile = __DIR__ . "/data/{$matches[1]}.out.txt";
                    
                    echo "\nRunning $inFile";
                    $IN = null;
                    ob_start();
                    require ($inFile);
                    $content = ob_get_contents();
                    ob_end_clean();
                    
                    $ret = $parser->render($content, $IN);
                    
                    $this->assertEquals (file_get_contents($compFile), $ret, "$file");
                    
                    
                }
            }
        }
        
    }
