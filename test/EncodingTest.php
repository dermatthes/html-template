<?php
/**
 * Created by PhpStorm.
 * User: matthes
 * Date: 18.08.16
 * Time: 18:26
 */

namespace Html5\Template\Test;


use Html5\Template\GoTemplateParser;
use Html5\Template\HtmlTemplate;

class EncodingTest extends \PHPUnit_Framework_TestCase
{


    public function testPropertiesEncodeCorrectly () {
        $t = new HtmlTemplate();
        $ret = $t->render('<div type="&quot;\'{}@">" &quot; &amp;</div>', []);

        self::assertEquals('<div type="&quot;\'{}@">&quot; &quot; &amp;</div>', $ret);

    }


}
