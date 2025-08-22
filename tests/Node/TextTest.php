<?php

declare(strict_types=1);

use PHPHtmlParser\Dom;
use PHPHtmlParser\Dom\Node\TextNode;
use PHPUnit\Framework\TestCase;
use StringEncoder\Encoder;

class TextTest extends TestCase
{
    public function test_text()
    {
        $node = new TextNode('foo bar');
        $this->assertEquals('foo bar', $node->text());
    }

    public function test_get_tag()
    {
        $node = new TextNode('foo bar');
        $this->assertEquals('text', $node->getTag()->name());
    }

    public function test_ancestor_by_tag()
    {
        $node = new TextNode('foo bar');
        $text = $node->ancestorByTag('text');
        $this->assertEquals($node, $text);
    }

    public function test_preserve_entity()
    {
        $node = new TextNode('&#x69;');
        $text = $node->outerhtml;
        $this->assertEquals('&#x69;', $text);
    }

    public function test_is_text_node()
    {
        $node = new TextNode('text');
        $this->assertEquals(true, $node->isTextNode());
    }

    public function test_text_in_text_node()
    {
        $node = new TextNode('foo bar');
        $this->assertEquals('foo bar', $node->outerHtml());
    }

    public function test_set_text_to_text_node()
    {
        $node = new TextNode('');
        $node->setText('foo bar');
        $this->assertEquals('foo bar', $node->innerHtml());
    }

    public function test_set_text()
    {
        $dom = new Dom;
        $dom->loadStr('<div class="all"><p>Hey bro, <a href="google.com">click here</a><br /> :)</p></div>');
        $a = $dom->find('a')[0];
        $a->firstChild()->setText('biz baz');
        $this->assertEquals('<div class="all"><p>Hey bro, <a href="google.com">biz baz</a><br /> :)</p></div>', (string) $dom);
    }

    public function test_set_text_encoded()
    {
        $encode = new Encoder;
        $encode->setSourceEncoding('UTF-8');
        $encode->setTargetEncoding('UTF-8');

        $node = new TextNode('foo bar');
        $node->propagateEncoding($encode);
        $node->setText('biz baz');
        $this->assertEquals('biz baz', $node->text());
    }
}
