<?php

declare(strict_types=1);

use PHPHtmlParser\Dom;
use PHPHtmlParser\Options;
use PHPUnit\Framework\TestCase;

class CleanupTest extends TestCase
{
    public function test_cleanup_input_true()
    {
        $dom = new Dom;
        $dom->setOptions((new Options)->setCleanupInput(true));
        $dom->loadFromFile('tests/data/files/big.html');
        $this->assertEquals(0, \count($dom->find('style')));
        $this->assertEquals(0, \count($dom->find('script')));
    }

    public function test_cleanup_input_false()
    {
        $dom = new Dom;
        $dom->setOptions((new Options)->setCleanupInput(false));
        $dom->loadFromFile('tests/data/files/big.html');
        $this->assertEquals(1, \count($dom->find('style')));
        $this->assertEquals(22, \count($dom->find('script')));
    }

    public function test_remove_styles_true()
    {
        $dom = new Dom;
        $dom->setOptions((new Options)->setRemoveStyles(true));
        $dom->loadFromFile('tests/data/files/big.html');
        $this->assertEquals(0, \count($dom->find('style')));
    }

    public function test_remove_styles_false()
    {
        $dom = new Dom;
        $dom->setOptions((new Options)->setRemoveStyles(false));
        $dom->loadFromFile('tests/data/files/big.html');
        $this->assertEquals(1, \count($dom->find('style')));
        $this->assertEquals('text/css',
            $dom->find('style')->getAttribute('type'));
    }

    public function test_remove_scripts_true()
    {
        $dom = new Dom;
        $dom->setOptions((new Options)->setRemoveScripts(true));
        $dom->loadFromFile('tests/data/files/big.html');
        $this->assertEquals(0, \count($dom->find('script')));
    }

    public function test_remove_scripts_false()
    {
        $dom = new Dom;
        $dom->setOptions((new Options)->setRemoveScripts(false));
        $dom->loadFromFile('tests/data/files/big.html');
        $this->assertEquals(22, \count($dom->find('script')));
        $this->assertEquals('text/javascript',
            $dom->find('script')->getAttribute('type'));
    }

    public function test_smarty_scripts()
    {
        $dom = new Dom;
        $dom->loadStr('
        aa={123}
        ');
        $this->assertEquals(' aa= ', $dom->innerHtml);
    }

    public function test_smarty_scripts_disabled()
    {
        $dom = new Dom;
        $dom->setOptions((new Options)->setRemoveSmartyScripts(false));
        $dom->loadStr('
        aa={123}
        ');
        $this->assertEquals(' aa={123} ', $dom->innerHtml);
    }
}
