<?php

declare(strict_types=1);

namespace PHPHtmlParser;

use PHPHtmlParser\Dom\Node\AbstractNode;
use PHPHtmlParser\Dom\Node\InnerNode;
use PHPHtmlParser\Exceptions\ChildNotFoundException;
use PHPHtmlParser\Exceptions\ParentNotFoundException;

class Finder
{
    /**
     * @var int
     */
    private $id;

    /**
     * Finder constructor.
     */
    public function __construct($id)
    {
        $this->id = $id;
    }

    /**
     * Find node in tree by id.
     *
     *
     * @return bool|AbstractNode
     *
     * @throws ChildNotFoundException
     * @throws ParentNotFoundException
     */
    public function find(AbstractNode $node)
    {
        if (! $node->id() && $node instanceof InnerNode) {
            return $this->find($node->firstChild());
        }

        if ($node->id() == $this->id) {
            return $node;
        }

        if ($node->hasNextSibling()) {
            $nextSibling = $node->nextSibling();
            if ($nextSibling->id() == $this->id) {
                return $nextSibling;
            }
            if ($nextSibling->id() > $this->id && $node instanceof InnerNode) {
                return $this->find($node->firstChild());
            }
            if ($nextSibling->id() < $this->id) {
                return $this->find($nextSibling);
            }
        } elseif (! $node->isTextNode() && $node instanceof InnerNode) {
            return $this->find($node->firstChild());
        }

        return false;
    }
}
