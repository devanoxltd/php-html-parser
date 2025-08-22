<?php

declare(strict_types=1);
require_once 'tests/data/MockNode.php';

use PHPHtmlParser\Dom\Node\MockNode as Node;
use PHPUnit\Framework\TestCase;

class ParentTest extends TestCase
{
    public function test_has_child()
    {
        $parent = new Node;
        $child = new Node;
        $parent->addChild($child);
        $this->assertTrue($parent->hasChildren());
    }

    public function test_has_child_no_children()
    {
        $node = new Node;
        $this->assertFalse($node->hasChildren());
    }

    public function test_add_child()
    {
        $parent = new Node;
        $child = new Node;
        $this->assertTrue($parent->addChild($child));
    }

    public function test_add_child_two_parent()
    {
        $parent = new Node;
        $parent2 = new Node;
        $child = new Node;
        $parent->addChild($child);
        $parent2->addChild($child);
        $this->assertFalse($parent->hasChildren());
    }

    public function test_get_child()
    {
        $parent = new Node;
        $child = new Node;
        $child2 = new Node;
        $parent->addChild($child);
        $parent->addChild($child2);
        $this->assertTrue($parent->getChild($child2->id()) instanceof Node);
    }

    public function test_remove_child()
    {
        $parent = new Node;
        $child = new Node;
        $parent->addChild($child);
        $parent->removeChild($child->id());
        $this->assertFalse($parent->hasChildren());
    }

    public function test_remove_child_not_exists()
    {
        $parent = new Node;
        $parent->removeChild(1);
        $this->assertFalse($parent->hasChildren());
    }

    public function test_next_child()
    {
        $parent = new Node;
        $child = new Node;
        $child2 = new Node;
        $parent->addChild($child);
        $parent->addChild($child2);

        $this->assertEquals($child2->id(), $parent->nextChild($child->id())->id());
    }

    public function test_has_next_child()
    {
        $parent = new Node;
        $child = new Node;
        $child2 = new Node;
        $parent->addChild($child);
        $parent->addChild($child2);

        $this->assertEquals($child2->id(), $parent->hasNextChild($child->id()));
    }

    public function test_has_next_child_not_exists()
    {
        $parent = new Node;
        $child = new Node;

        $this->expectException(PHPHtmlParser\Exceptions\ChildNotFoundException::class);
        $parent->hasNextChild($child->id());
    }

    public function test_next_child_with_remove()
    {
        $parent = new Node;
        $child = new Node;
        $child2 = new Node;
        $child3 = new Node;
        $parent->addChild($child);
        $parent->addChild($child2);
        $parent->addChild($child3);

        $parent->removeChild($child2->id());
        $this->assertEquals($child3->id(), $parent->nextChild($child->id())->id());
    }

    public function test_previous_child()
    {
        $parent = new Node;
        $child = new Node;
        $child2 = new Node;
        $parent->addChild($child);
        $parent->addChild($child2);

        $this->assertEquals($child->id(), $parent->previousChild($child2->id())->id());
    }

    public function test_previous_child_with_remove()
    {
        $parent = new Node;
        $child = new Node;
        $child2 = new Node;
        $child3 = new Node;
        $parent->addChild($child);
        $parent->addChild($child2);
        $parent->addChild($child3);

        $parent->removeChild($child2->id());
        $this->assertEquals($child->id(), $parent->previousChild($child3->id())->id());
    }

    public function test_first_child()
    {
        $parent = new Node;
        $child = new Node;
        $child2 = new Node;
        $child3 = new Node;
        $parent->addChild($child);
        $parent->addChild($child2);
        $parent->addChild($child3);

        $this->assertEquals($child->id(), $parent->firstChild()->id());
    }

    public function test_last_child()
    {
        $parent = new Node;
        $child = new Node;
        $child2 = new Node;
        $child3 = new Node;
        $parent->addChild($child);
        $parent->addChild($child2);
        $parent->addChild($child3);

        $this->assertEquals($child3->id(), $parent->lastChild()->id());
    }

    public function test_insert_before_first()
    {
        $parent = new Node;
        $child = new Node;
        $child2 = new Node;
        $child3 = new Node;
        $parent->addChild($child2);
        $parent->addChild($child3);

        $parent->insertBefore($child, $child2->id());

        $this->assertTrue($parent->isChild($child->id()));
        $this->assertEquals($parent->firstChild()->id(), $child->id());
        $this->assertEquals($child->nextSibling()->id(), $child2->id());
        $this->assertEquals($child2->nextSibling()->id(), $child3->id());
        $this->assertEquals($parent->lastChild()->id(), $child3->id());
    }

    public function test_insert_before_last()
    {
        $parent = new Node;
        $child = new Node;
        $child2 = new Node;
        $child3 = new Node;
        $parent->addChild($child);
        $parent->addChild($child3);

        $parent->insertBefore($child2, $child3->id());

        $this->assertTrue($parent->isChild($child2->id()));
        $this->assertEquals($parent->firstChild()->id(), $child->id());
        $this->assertEquals($child->nextSibling()->id(), $child2->id());
        $this->assertEquals($child2->nextSibling()->id(), $child3->id());
        $this->assertEquals($parent->lastChild()->id(), $child3->id());
    }

    public function test_insert_after_first()
    {
        $parent = new Node;
        $child = new Node;
        $child2 = new Node;
        $child3 = new Node;
        $parent->addChild($child);
        $parent->addChild($child3);

        $parent->insertAfter($child2, $child->id());

        $this->assertTrue($parent->isChild($child2->id()));
        $this->assertEquals($parent->firstChild()->id(), $child->id());
        $this->assertEquals($child->nextSibling()->id(), $child2->id());
        $this->assertEquals($child2->nextSibling()->id(), $child3->id());
        $this->assertEquals($parent->lastChild()->id(), $child3->id());
    }

    public function test_insert_after_last()
    {
        $parent = new Node;
        $child = new Node;
        $child2 = new Node;
        $child3 = new Node;
        $parent->addChild($child);
        $parent->addChild($child2);

        $parent->insertAfter($child3, $child2->id());

        $this->assertTrue($parent->isChild($child2->id()));
        $this->assertEquals($parent->firstChild()->id(), $child->id());
        $this->assertEquals($child->nextSibling()->id(), $child2->id());
        $this->assertEquals($child2->nextSibling()->id(), $child3->id());
        $this->assertEquals($parent->lastChild()->id(), $child3->id());
    }

    public function test_replace_child()
    {
        $parent = new Node;
        $child = new Node;
        $child2 = new Node;
        $child3 = new Node;
        $parent->addChild($child);
        $parent->addChild($child2);
        $parent->replaceChild($child->id(), $child3);

        $this->assertFalse($parent->isChild($child->id()));
    }

    public function test_set_parent_descendant_exception()
    {
        $this->expectException(PHPHtmlParser\Exceptions\CircularException::class);

        $parent = new Node;
        $child = new Node;
        $parent->addChild($child);
        $parent->setParent($child);
    }

    public function test_add_child_ancestor_exception()
    {
        $this->expectException(PHPHtmlParser\Exceptions\CircularException::class);

        $parent = new Node;
        $child = new Node;
        $parent->addChild($child);
        $child->addChild($parent);
    }

    public function test_add_itself_as_child()
    {
        $this->expectException(PHPHtmlParser\Exceptions\CircularException::class);

        $parent = new Node;
        $parent->addChild($parent);
    }

    public function test_is_ancestor_parent()
    {
        $parent = new Node;
        $child = new Node;
        $parent->addChild($child);
        $this->assertTrue($child->isAncestor($parent->id()));
    }

    public function test_get_ancestor()
    {
        $parent = new Node;
        $child = new Node;
        $parent->addChild($child);
        $ancestor = $child->getAncestor($parent->id());
        $this->assertEquals($parent->id(), $ancestor->id());
    }

    public function test_get_great_ancestor()
    {
        $parent = new Node;
        $child = new Node;
        $child2 = new Node;
        $parent->addChild($child);
        $child->addChild($child2);
        $ancestor = $child2->getAncestor($parent->id());
        $this->assertNotNull($ancestor);
        $this->assertEquals($parent->id(), $ancestor->id());
    }

    public function test_get_ancestor_not_found()
    {
        $parent = new Node;
        $ancestor = $parent->getAncestor(1);
        $this->assertNull($ancestor);
    }
}
