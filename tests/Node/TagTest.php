<?php

declare(strict_types=1);

use PHPHtmlParser\Dom\Tag;
use PHPUnit\Framework\TestCase;

class TagTest extends TestCase
{
    public function test_self_closing()
    {
        $tag = new Tag('a');
        $tag->selfClosing();
        $this->assertTrue($tag->isSelfClosing());
    }

    public function test_set_attributes()
    {
        $attr = [
            'href' => [
                'value' => 'http://google.com',
                'doubleQuote' => false,
            ],
        ];

        $tag = new Tag('a');
        $tag->setAttributes($attr);
        $this->assertEquals('http://google.com', $tag->getAttribute('href')->getValue());
    }

    public function test_remove_attribute()
    {
        $this->expectException(PHPHtmlParser\Exceptions\Tag\AttributeNotFoundException::class);

        $tag = new Tag('a');
        $tag->setAttribute('href', 'http://google.com');
        $tag->removeAttribute('href');
        $tag->getAttribute('href');
    }

    public function test_remove_all_attributes()
    {
        $tag = new Tag('a');
        $tag->setAttribute('href', 'http://google.com');
        $tag->setAttribute('class', 'clear-fix', true);
        $tag->removeAllAttributes();
        $this->assertEquals(0, \count($tag->getAttributes()));
    }

    public function test_set_attribute_no_array()
    {
        $tag = new Tag('a');
        $tag->setAttribute('href', 'http://google.com');
        $this->assertEquals('http://google.com', $tag->getAttribute('href')->getValue());
    }

    public function test_set_attributes_no_double_array()
    {
        $attr = [
            'href' => 'http://google.com',
            'class' => 'funtimes',
        ];

        $tag = new Tag('a');
        $tag->setAttributes($attr);
        $this->assertEquals('funtimes', $tag->getAttribute('class')->getValue());
    }

    public function test_update_attributes()
    {
        $tag = new Tag('a');
        $tag->setAttributes([
            'href' => [
                'value' => 'http://google.com',
                'doubleQuote' => false,
            ],
            'class' => [
                'value' => null,
                'doubleQuote' => true,
            ],
        ]);

        $this->assertEquals(null, $tag->getAttribute('class')->getValue());
        $this->assertEquals('http://google.com', $tag->getAttribute('href')->getValue());

        $attr = [
            'href' => 'https://www.google.com',
            'class' => 'funtimes',
        ];

        $tag->setAttributes($attr);
        $this->assertEquals('funtimes', $tag->getAttribute('class')->getValue());
        $this->assertEquals('https://www.google.com', $tag->getAttribute('href')->getValue());
    }

    public function test_noise()
    {
        $tag = new Tag('a');
        $this->assertTrue($tag->noise('noise') instanceof Tag);
    }

    public function test_get_attribute_magic()
    {
        $attr = [
            'href' => [
                'value' => 'http://google.com',
                'doubleQuote' => false,
            ],
        ];

        $tag = new Tag('a');
        $tag->setAttributes($attr);
        $this->assertEquals('http://google.com', $tag->getAttribute('href')->getValue());
    }

    public function test_set_attribute_magic()
    {
        $tag = new Tag('a');
        $tag->setAttribute('href', 'http://google.com');
        $this->assertEquals('http://google.com', $tag->getAttribute('href')->getValue());
    }

    public function test_make_opening_tag()
    {
        $attr = [
            'href' => [
                'value' => 'http://google.com',
                'doubleQuote' => true,
            ],
        ];

        $tag = new Tag('a');
        $tag->setAttributes($attr);
        $this->assertEquals('<a href="http://google.com">', $tag->makeOpeningTag());
    }

    public function test_make_opening_tag_empty_attr()
    {
        $attr = [
            'href' => [
                'value' => 'http://google.com',
                'doubleQuote' => true,
            ],
        ];

        $tag = new Tag('a');
        $tag->setAttributes($attr);
        $tag->setAttribute('selected', null);
        $this->assertEquals('<a href="http://google.com" selected>', $tag->makeOpeningTag());
    }

    public function test_make_opening_tag_self_closing()
    {
        $attr = [
            'class' => [
                'value' => 'clear-fix',
                'doubleQuote' => true,
            ],
        ];

        $tag = (new Tag('div'))
            ->selfClosing()
            ->setAttributes($attr);
        $this->assertEquals('<div class="clear-fix" />', $tag->makeOpeningTag());
    }

    public function test_make_closing_tag()
    {
        $tag = new Tag('a');
        $this->assertEquals('</a>', $tag->makeClosingTag());
    }

    public function test_make_closing_tag_self_closing()
    {
        $tag = new Tag('div');
        $tag->selfClosing();
        $this->assertEmpty($tag->makeClosingTag());
    }

    public function test_set_tag_attribute()
    {
        $tag = new Tag('div');
        $tag->setStyleAttributeValue('display', 'none');
        $this->assertEquals('display:none;', $tag->getAttribute('style')->getValue());
    }

    public function test_get_style_attributes_array()
    {
        $tag = new Tag('div');
        $tag->setStyleAttributeValue('display', 'none');
        $this->assertIsArray($tag->getStyleAttributeArray());
    }
}
