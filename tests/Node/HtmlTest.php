<?php

declare(strict_types=1);
require_once 'tests/data/MockNode.php';

use PHPHtmlParser\Dom;
use PHPHtmlParser\Dom\Node\HtmlNode;
use PHPHtmlParser\Dom\Node\MockNode;
use PHPHtmlParser\Dom\Node\TextNode;
use PHPHtmlParser\Dom\Tag;
use PHPUnit\Framework\TestCase;

class HtmlTest extends TestCase
{
    public function test_inner_html()
    {
        $div = new Tag('div');
        $div->setAttributes([
            'class' => [
                'value' => 'all',
                'doubleQuote' => true,
            ],
        ]);
        $a = new Tag('a');
        $a->setAttributes([
            'href' => [
                'value' => 'http://google.com',
                'doubleQuote' => false,
            ],
        ]);
        $br = new Tag('br');
        $br->selfClosing();

        $parent = new HtmlNode($div);
        $childa = new HtmlNode($a);
        $childbr = new HtmlNode($br);
        $parent->addChild($childa);
        $parent->addChild($childbr);
        $childa->addChild(new TextNode('link'));

        $this->assertEquals("<a href='http://google.com'>link</a><br />", $parent->innerHtml());
    }

    public function test_inner_html_twice()
    {
        $div = new Tag('div');
        $div->setAttributes([
            'class' => [
                'value' => 'all',
                'doubleQuote' => true,
            ],
        ]);
        $a = new Tag('a');
        $br = new Tag('br');
        $br->selfClosing();

        $parent = new HtmlNode($div);
        $childa = new HtmlNode($a);
        $childa->setAttribute('href', 'http://google.com', false);
        $childbr = new HtmlNode($br);
        $parent->addChild($childa);
        $parent->addChild($childbr);
        $childa->addChild(new TextNode('link'));

        $inner = $parent->innerHtml();
        $this->assertEquals($inner, $parent->innerHtml());
    }

    /**
     * @expectedException \PHPHtmlParser\Exceptions\UnknownChildTypeException
     */
    public function test_inner_html_unkown_child()
    {
        $div = new Tag('div');
        $div->setAttributes([
            'class' => [
                'value' => 'all',
                'doubleQuote' => true,
            ],
        ]);
        $a = new Tag('a');
        $a->setAttributes([
            'href' => [
                'value' => 'http://google.com',
                'doubleQuote' => false,
            ],
        ]);
        $br = new Tag('br');
        $br->selfClosing();

        $parent = new HtmlNode($div);
        $childa = new HtmlNode($a);
        $childbr = new MockNode($br);
        $parent->addChild($childa);
        $parent->addChild($childbr);
        $childa->addChild(new TextNode('link'));

        $inner = $parent->innerHtml();
        $this->assertEquals($inner, $parent->innerHtml());
    }

    public function test_inner_html_magic()
    {
        $parent = new HtmlNode('div');
        $parent->tag->setAttributes([
            'class' => [
                'value' => 'all',
                'doubleQuote' => true,
            ],
        ]);
        $childa = new HtmlNode('a');
        $childa->getTag()->setAttributes([
            'href' => [
                'value' => 'http://google.com',
                'doubleQuote' => false,
            ],
        ]);
        $childbr = new HtmlNode('br');
        $childbr->getTag()->selfClosing();

        $parent->addChild($childa);
        $parent->addChild($childbr);
        $childa->addChild(new TextNode('link'));

        $this->assertEquals("<a href='http://google.com'>link</a><br />", $parent->innerHtml);
    }

    public function test_outer_html()
    {
        $div = new Tag('div');
        $div->setAttributes([
            'class' => [
                'value' => 'all',
                'doubleQuote' => true,
            ],
        ]);
        $a = new Tag('a');
        $a->setAttributes([
            'href' => [
                'value' => 'http://google.com',
                'doubleQuote' => false,
            ],
        ]);
        $br = new Tag('br');
        $br->selfClosing();

        $parent = new HtmlNode($div);
        $childa = new HtmlNode($a);
        $childbr = new HtmlNode($br);
        $parent->addChild($childa);
        $parent->addChild($childbr);
        $childa->addChild(new TextNode('link'));

        $this->assertEquals('<div class="all"><a href=\'http://google.com\'>link</a><br /></div>', $parent->outerHtml());
    }

    public function test_outer_html_twice()
    {
        $div = new Tag('div');
        $div->setAttributes([
            'class' => [
                'value' => 'all',
                'doubleQuote' => true,
            ],
        ]);
        $a = new Tag('a');
        $a->setAttributes([
            'href' => [
                'value' => 'http://google.com',
                'doubleQuote' => false,
            ],
        ]);
        $br = new Tag('br');
        $br->selfClosing();

        $parent = new HtmlNode($div);
        $childa = new HtmlNode($a);
        $childbr = new HtmlNode($br);
        $parent->addChild($childa);
        $parent->addChild($childbr);
        $childa->addChild(new TextNode('link'));

        $outer = $parent->outerHtml();
        $this->assertEquals($outer, $parent->outerHtml());
    }

    public function test_outer_html_empty()
    {
        $a = new Tag('a');
        $a->setAttributes([
            'href' => [
                'value' => 'http://google.com',
                'doubleQuote' => false,
            ],
        ]);
        $node = new HtmlNode($a);

        $this->assertEquals("<a href='http://google.com'></a>", $node->OuterHtml());
    }

    public function test_outer_html_magic()
    {
        $parent = new HtmlNode('div');
        $parent->getTag()->setAttributes([
            'class' => [
                'value' => 'all',
                'doubleQuote' => true,
            ],
        ]);
        $childa = new HtmlNode('a');
        $childa->getTag()->setAttributes([
            'href' => [
                'value' => 'http://google.com',
                'doubleQuote' => false,
            ],
        ]);
        $childbr = new HtmlNode('br');
        $childbr->getTag()->selfClosing();

        $parent->addChild($childa);
        $parent->addChild($childbr);
        $childa->addChild(new TextNode('link'));

        $this->assertEquals('<div class="all"><a href=\'http://google.com\'>link</a><br /></div>', $parent->outerHtml);
    }

    public function test_outer_html_no_value_attribute()
    {
        $parent = new HtmlNode('div');
        $parent->setAttribute('class', 'all');
        $childa = new HtmlNode('a');
        $childa->setAttribute('href', 'http://google.com', false);
        $childa->setAttribute('ui-view', null);
        $childbr = new HtmlNode('br');
        $childbr->getTag()->selfClosing();

        $parent->addChild($childa);
        $parent->addChild($childbr);
        $childa->addChild(new TextNode('link'));

        $this->assertEquals('<div class="all"><a href=\'http://google.com\' ui-view>link</a><br /></div>', $parent->outerHtml);
    }

    public function test_outer_html_with_changes()
    {
        $div = new Tag('div');
        $div->setAttributes([
            'class' => [
                'value' => 'all',
                'doubleQuote' => true,
            ],
        ]);
        $a = new Tag('a');
        $a->setAttributes([
            'href' => [
                'value' => 'http://google.com',
                'doubleQuote' => false,
            ],
        ]);
        $br = new Tag('br');
        $br->selfClosing();

        $parent = new HtmlNode($div);
        $childa = new HtmlNode($a);
        $childbr = new HtmlNode($br);
        $parent->addChild($childa);
        $parent->addChild($childbr);
        $childa->addChild(new TextNode('link'));

        $this->assertEquals('<div class="all"><a href=\'http://google.com\'>link</a><br /></div>', $parent->outerHtml());

        $childa->setAttribute('href', 'https://www.google.com');

        $this->assertEquals('<a href="https://www.google.com">link</a>', $childa->outerHtml());
    }

    public function test_text()
    {
        $a = new Tag('a');
        $node = new HtmlNode($a);
        $node->addChild(new TextNode('link'));

        $this->assertEquals('link', $node->text());
    }

    public function test_text_twice()
    {
        $a = new Tag('a');
        $node = new HtmlNode($a);
        $node->addChild(new TextNode('link'));

        $text = $node->text();
        $this->assertEquals($text, $node->text());
    }

    public function test_text_none()
    {
        $a = new Tag('a');
        $node = new HtmlNode($a);

        $this->assertEmpty($node->text());
    }

    public function test_text_magic()
    {
        $node = new HtmlNode('a');
        $node->addChild(new TextNode('link'));

        $this->assertEquals('link', $node->text);
    }

    public function test_text_look_in_children()
    {
        $p = new HtmlNode('p');
        $a = new HtmlNode('a');
        $a->addChild(new TextNode('click me'));
        $p->addChild(new TextNode('Please '));
        $p->addChild($a);
        $p->addChild(new TextNode('!'));
        $node = new HtmlNode('div');
        $node->addChild($p);

        $this->assertEquals('Please click me!', $node->text(true));
    }

    public function test_inner_text()
    {
        $node = new HtmlNode('div');
        $node->addChild(new TextNode('123 '));
        $anode = new HtmlNode('a');
        $anode->addChild(new TextNode('456789 '));
        $span_node = new HtmlNode('span');
        $span_node->addChild(new TextNode('101112'));

        $node->addChild($anode);
        $node->addChild($span_node);
        $this->assertEquals($node->innerText, '123 456789 101112');
    }

    public function test_text_look_in_children_and_no_children()
    {
        $p = new HtmlNode('p');
        $a = new HtmlNode('a');
        $a->addChild(new TextNode('click me'));
        $p->addChild(new TextNode('Please '));
        $p->addChild($a);
        $p->addChild(new TextNode('!'));

        $p->text;
        $p->text(true);

        $this->assertEquals('Please click me!', $p->text(true));
    }

    public function test_get_attribute()
    {
        $node = new HtmlNode('a');
        $node->getTag()->setAttributes([
            'href' => [
                'value' => 'http://google.com',
                'doubleQuote' => false,
            ],
            'class' => [
                'value' => 'outerlink rounded',
                'doubleQuote' => true,
            ],
        ]);

        $this->assertEquals('outerlink rounded', $node->getAttribute('class'));
    }

    public function test_get_attribute_magic()
    {
        $node = new HtmlNode('a');
        $node->getTag()->setAttributes([
            'href' => [
                'value' => 'http://google.com',
                'doubleQuote' => false,
            ],
            'class' => [
                'value' => 'outerlink rounded',
                'doubleQuote' => true,
            ],
        ]);

        $this->assertEquals('http://google.com', $node->href);
    }

    public function test_get_attributes()
    {
        $node = new HtmlNode('a');
        $node->getTag()->setAttributes([
            'href' => [
                'value' => 'http://google.com',
                'doubleQuote' => false,
            ],
            'class' => [
                'value' => 'outerlink rounded',
                'doubleQuote' => true,
            ],
        ]);

        $this->assertEquals('outerlink rounded', $node->getAttributes()['class']);
    }

    public function test_set_attribute()
    {
        $node = new HtmlNode('a');
        $node->setAttribute('class', 'foo');
        $this->assertEquals('foo', $node->getAttribute('class'));
    }

    public function test_remove_attribute()
    {
        $node = new HtmlNode('a');
        $node->setAttribute('class', 'foo');
        $node->removeAttribute('class');
        $this->assertnull($node->getAttribute('class'));
    }

    public function test_remove_all_attributes()
    {
        $node = new HtmlNode('a');
        $node->setAttribute('class', 'foo');
        $node->setAttribute('href', 'http://google.com');
        $node->removeAllAttributes();
        $this->assertEquals(0, \count($node->getAttributes()));
    }

    public function test_set_tag()
    {
        $node = new HtmlNode('div');
        $this->assertEquals('<div></div>', $node->outerHtml());

        $node->setTag('p');
        $this->assertEquals('<p></p>', $node->outerHtml());

        $node->setTag(new Tag('span'));
        $this->assertEquals('<span></span>', $node->outerHtml());
    }

    public function test_countable()
    {
        $div = new Tag('div');
        $div->setAttributes([
            'class' => [
                'value' => 'all',
                'doubleQuote' => true,
            ],
        ]);
        $a = new Tag('a');
        $a->setAttributes([
            'href' => [
                'value' => 'http://google.com',
                'doubleQuote' => false,
            ],
        ]);
        $br = new Tag('br');
        $br->selfClosing();

        $parent = new HtmlNode($div);
        $childa = new HtmlNode($a);
        $childbr = new HtmlNode($br);
        $parent->addChild($childa);
        $parent->addChild($childbr);
        $childa->addChild(new TextNode('link'));

        $this->assertEquals(\count($parent->getChildren()), \count($parent));
    }

    public function test_iterator()
    {
        $div = new Tag('div');
        $div->setAttributes([
            'class' => [
                'value' => 'all',
                'doubleQuote' => true,
            ],
        ]);
        $a = new Tag('a');
        $a->setAttributes([
            'href' => [
                'value' => 'http://google.com',
                'doubleQuote' => false,
            ],
        ]);
        $br = new Tag('br');
        $br->selfClosing();

        $parent = new HtmlNode($div);
        $childa = new HtmlNode($a);
        $childbr = new HtmlNode($br);
        $parent->addChild($childa);
        $parent->addChild($childbr);
        $childa->addChild(new TextNode('link'));

        $children = 0;
        foreach ($parent as $child) {
            $children++;
        }
        $this->assertEquals(2, $children);
    }

    public function test_ancestor_by_tag_failure()
    {
        $this->expectException(PHPHtmlParser\Exceptions\ParentNotFoundException::class);

        $a = new Tag('a');
        $node = new HtmlNode($a);
        $node->ancestorByTag('div');
    }

    public function test_replace_node()
    {
        $dom = new Dom;
        $dom->loadStr('<div class="all"><p>Hey bro, <a href="google.com">click here</a><br /> :)</p></div>');
        $id = $dom->find('p')[0]->id();
        $newChild = new HtmlNode('h1');
        $dom->find('p')[0]->getParent()->replaceChild($id, $newChild);
        $this->assertEquals('<div class="all"><h1></h1></div>', (string) $dom);
    }

    public function test_text_node_first_child()
    {
        $dom = new Dom;
        $dom->loadStr('<div class="all"><p>Hey bro, <a href="google.com">click here</a><br /> :)</p></div>');
        $p = $dom->find('p');
        foreach ($p as $element) {
            $child = $element->firstChild();
            $this->assertInstanceOf(TextNode::class, $child);
            break;
        }
    }
}
