<?php

declare(strict_types=1);
require_once 'tests/data/MockNode.php';

use PHPHtmlParser\Dom\Node\MockNode as Node;
use PHPUnit\Framework\TestCase;

class ChildrenTest extends TestCase
{
    public function test_get_parent()
    {
        $parent = new Node;
        $child = new Node;
        $child->setParent($parent);
        $this->assertEquals($parent->id(), $child->getParent()->id());
    }

    public function test_set_parent_twice()
    {
        $parent = new Node;
        $parent2 = new Node;
        $child = new Node;
        $child->setParent($parent);
        $child->setParent($parent2);
        $this->assertEquals($parent2->id(), $child->getParent()->id());
    }

    public function test_next_sibling()
    {
        $parent = new Node;
        $child = new Node;
        $child2 = new Node;
        $child->setParent($parent);
        $child2->setParent($parent);
        $this->assertEquals($child2->id(), $child->nextSibling()->id());
    }

    public function test_next_sibling_not_found()
    {
        $this->expectException(PHPHtmlParser\Exceptions\ChildNotFoundException::class);

        $parent = new Node;
        $child = new Node;
        $child->setParent($parent);
        $child->nextSibling();
    }

    public function test_next_sibling_no_parent()
    {
        $this->expectException(PHPHtmlParser\Exceptions\ParentNotFoundException::class);

        $child = new Node;
        $child->nextSibling();
    }

    public function test_previous_sibling()
    {
        $parent = new Node;
        $child = new Node;
        $child2 = new Node;
        $child->setParent($parent);
        $child2->setParent($parent);
        $this->assertEquals($child->id(), $child2->previousSibling()->id());
    }

    public function test_previous_sibling_not_found()
    {
        $this->expectException(PHPHtmlParser\Exceptions\ChildNotFoundException::class);

        $parent = new Node;
        $node = new Node;
        $node->setParent($parent);
        $node->previousSibling();
    }

    public function test_previous_sibling_no_parent()
    {
        $this->expectException(PHPHtmlParser\Exceptions\ParentNotFoundException::class);

        $child = new Node;
        $child->previousSibling();
    }

    public function test_get_children()
    {
        $parent = new Node;
        $child = new Node;
        $child2 = new Node;
        $child->setParent($parent);
        $child2->setParent($parent);
        $this->assertEquals($child->id(), $parent->getChildren()[0]->id());
    }

    public function test_count_children()
    {
        $parent = new Node;
        $child = new Node;
        $child2 = new Node;
        $child->setParent($parent);
        $child2->setParent($parent);
        $this->assertEquals(2, $parent->countChildren());
    }

    public function test_is_child()
    {
        $parent = new Node;
        $child1 = new Node;
        $child2 = new Node;

        $child1->setParent($parent);
        $child2->setParent($child1);

        $this->assertTrue($parent->isChild($child1->id()));
        $this->assertTrue($parent->isDescendant($child2->id()));
        $this->assertFalse($parent->isChild($child2->id()));
    }
}
