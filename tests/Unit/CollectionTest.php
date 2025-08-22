<?php

declare(strict_types=1);

use PHPHtmlParser\Dom\Node\Collection;
use PHPHtmlParser\Dom\Node\HtmlNode;
use PHPHtmlParser\Dom\Tag;
use PHPHtmlParser\Selector\Parser;
use PHPHtmlParser\Selector\Selector;
use PHPUnit\Framework\TestCase;

class CollectionTest extends TestCase
{
    public function test_each()
    {
        $root = new HtmlNode(new Tag('root'));
        $parent = new HtmlNode(new Tag('div'));
        $child1 = new HtmlNode(new Tag('a'));
        $child2 = new HtmlNode(new Tag('p'));
        $child3 = new HtmlNode(new Tag('a'));
        $root->addChild($parent);
        $parent->addChild($child1);
        $parent->addChild($child2);
        $child2->addChild($child3);

        $selector = new Selector('a', new Parser);
        $collection = $selector->find($root);
        $count = 0;
        $collection->each(function ($node) use (&$count) {
            $count++;
        });
        $this->assertEquals(2, $count);
    }

    public function test_call_no_nodes()
    {
        $this->expectException(PHPHtmlParser\Exceptions\EmptyCollectionException::class);

        $collection = new Collection;
        $collection->innerHtml();
    }

    public function test_no_node_string()
    {
        $collection = new Collection;
        $string = (string) $collection;
        $this->assertEmpty($string);
    }

    public function test_call_magic()
    {
        $root = new HtmlNode(new Tag('root'));
        $parent = new HtmlNode(new Tag('div'));
        $child1 = new HtmlNode(new Tag('a'));
        $child2 = new HtmlNode(new Tag('p'));
        $child3 = new HtmlNode(new Tag('a'));
        $root->addChild($parent);
        $parent->addChild($child1);
        $parent->addChild($child2);
        $child2->addChild($child3);

        $selector = new Selector('div * a', new Parser);
        $this->assertEquals($child3->id(), $selector->find($root)->id());
    }

    public function test_get_magic()
    {
        $root = new HtmlNode(new Tag('root'));
        $parent = new HtmlNode(new Tag('div'));
        $child1 = new HtmlNode(new Tag('a'));
        $child2 = new HtmlNode(new Tag('p'));
        $child3 = new HtmlNode(new Tag('a'));
        $root->addChild($parent);
        $parent->addChild($child1);
        $parent->addChild($child2);
        $child2->addChild($child3);

        $selector = new Selector('div * a', new Parser);
        $this->assertEquals($child3->innerHtml, $selector->find($root)->innerHtml);
    }

    public function test_get_no_nodes()
    {
        $this->expectException(PHPHtmlParser\Exceptions\EmptyCollectionException::class);

        $collection = new Collection;
        $collection->innerHtml;
    }

    public function test_to_string_magic()
    {
        $root = new HtmlNode(new Tag('root'));
        $parent = new HtmlNode(new Tag('div'));
        $child1 = new HtmlNode(new Tag('a'));
        $child2 = new HtmlNode(new Tag('p'));
        $child3 = new HtmlNode(new Tag('a'));
        $root->addChild($parent);
        $parent->addChild($child1);
        $parent->addChild($child2);
        $child2->addChild($child3);

        $selector = new Selector('div * a', new Parser);
        $this->assertEquals((string) $child3, (string) $selector->find($root));
    }

    public function test_to_array()
    {
        $root = new HtmlNode(new Tag('root'));
        $parent = new HtmlNode(new Tag('div'));
        $child1 = new HtmlNode(new Tag('a'));
        $child2 = new HtmlNode(new Tag('p'));
        $child3 = new HtmlNode(new Tag('a'));
        $root->addChild($parent);
        $parent->addChild($child1);
        $parent->addChild($child2);
        $child2->addChild($child3);

        $selector = new Selector('a', new Parser);
        $collection = $selector->find($root);
        $array = $collection->toArray();
        $lastA = \end($array);
        $this->assertEquals($child3->id(), $lastA->id());
    }

    public function test_get_iterator()
    {
        $collection = new Collection;
        $iterator = $collection->getIterator();
        $this->assertTrue($iterator instanceof ArrayIterator);
    }

    public function test_offset_set()
    {
        $collection = new Collection;
        $collection->offsetSet(7, true);
        $this->assertTrue($collection->offsetGet(7));
    }

    public function test_offset_unset()
    {
        $collection = new Collection;
        $collection->offsetSet(7, true);
        $collection->offsetUnset(7);
        $this->assertTrue(\is_null($collection->offsetGet(7)));
    }
}
