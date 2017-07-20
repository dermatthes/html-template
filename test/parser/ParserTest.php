<?php

/**
 * Created by PhpStorm.
 * User: matthes
 * Date: 28.07.16
 * Time: 20:37
 */
    namespace Gismo\Test\Component;


    use Html5\Template\HtmlTemplate;
    use PHPUnit\Framework\TestCase;

    class ParserTest extends TestCase
    {
    
    
        public function testGeneratedDocumentMatchesInputDocument()
        {
            $inputContent = file_get_contents(__DIR__ . "/mockfiles/testWhiteSpaceParsing.xml");
    
            $parser = new HtmlTemplate();
            $output = $parser->render($inputContent, []);
    
            $this->assertEquals($inputContent, $output);
        }
    
    
        public function testXmlParserKnowsHtml5()
        {

            /*
            $p = xml_parser_create();
            xml_set_element_handler($p, function(){}, function(){});

            $fp = fopen(__DIR__ . "/mockfiles/testHtml5.html", "r");
            while (($data = fread($fp, 8192))) {
                if (!xml_parse($p, $data, feof($fp))) {
                    throw new \Exception("Parsing error: " . xml_error_string(xml_get_error_code($p)) . " Line: " . xml_get_current_line_number($p) . " Col: " . xml_get_current_column_number($p));
                }
            }
            */
        }
    
    
    }
