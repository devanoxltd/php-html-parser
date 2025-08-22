<?php

declare(strict_types=1);

use PHPHtmlParser\Dom;
use PHPUnit\Framework\TestCase;

class LoadTest extends TestCase
{
    /**
     * @var Dom
     */
    private $dom;

    protected function setUp(): void
    {
        $dom = new Dom;
        $dom->loadStr('<div class="all"><br><p>Hey bro, <a href="google.com" id="78" data-quote="\"">click here</a></br></div><br class="both" />');
        $this->dom = $dom;
    }

    protected function tearDown(): void
    {
        Mockery::close();
    }

    public function test_load_escape_quotes()
    {
        $a = $this->dom->find('a', 0);
        $this->assertEquals('<a href="google.com" id="78" data-quote="\"">click here</a>', $a->outerHtml);
    }

    public function test_load_no_closing_tag()
    {
        $p = $this->dom->find('p', 0);
        $this->assertEquals('Hey bro, <a href="google.com" id="78" data-quote="\"">click here</a>', $p->innerHtml);
    }

    public function test_load_closing_tag_on_self_closing()
    {
        $this->assertCount(2, $this->dom->find('br'));
    }

    public function test_incorrect_access()
    {
        $div = $this->dom->find('div', 0);
        $this->assertEquals(null, $div->foo);
    }

    public function test_load_attribute_on_self_closing()
    {
        $br = $this->dom->find('br', 1);
        $this->assertEquals('both', $br->getAttribute('class'));
    }

    public function test_to_string_magic()
    {
        $this->assertEquals('<div class="all"><br /><p>Hey bro, <a href="google.com" id="78" data-quote="\"">click here</a></p></div><br class="both" />', (string) $this->dom);
    }

    public function test_get_magic()
    {
        $this->assertEquals('<div class="all"><br /><p>Hey bro, <a href="google.com" id="78" data-quote="\"">click here</a></p></div><br class="both" />', $this->dom->innerHtml);
    }

    public function test_first_child()
    {
        $this->assertEquals('<div class="all"><br /><p>Hey bro, <a href="google.com" id="78" data-quote="\"">click here</a></p></div>', $this->dom->firstChild()->outerHtml);
    }

    public function test_last_child()
    {
        $this->assertEquals('<br class="both" />', $this->dom->lastChild()->outerHtml);
    }

    public function test_get_element_by_id()
    {
        $this->assertEquals('<a href="google.com" id="78" data-quote="\"">click here</a>', $this->dom->getElementById('78')->outerHtml);
    }

    public function test_get_elements_by_tag()
    {
        $this->assertEquals('<p>Hey bro, <a href="google.com" id="78" data-quote="\"">click here</a></p>', $this->dom->getElementsByTag('p')[0]->outerHtml);
    }

    public function test_get_elements_by_class()
    {
        $this->assertEquals('<br /><p>Hey bro, <a href="google.com" id="78" data-quote="\"">click here</a></p>', $this->dom->getElementsByClass('all')[0]->innerHtml);
    }

    public function test_delete_node()
    {
        $a = $this->dom->find('a')[0];
        $a->delete();
        unset($a);
        $this->assertEquals('<div class="all"><br /><p>Hey bro, </p></div><br class="both" />', (string) $this->dom);
    }
}
