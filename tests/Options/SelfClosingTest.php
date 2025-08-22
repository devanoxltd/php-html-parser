<?php

declare(strict_types=1);

use PHPHtmlParser\Dom;
use PHPHtmlParser\Options;
use PHPUnit\Framework\TestCase;

class SelfClosingTest extends TestCase
{
    public function test_load_closing_tag_add_self_closing_tag()
    {
        $dom = new Dom;
        $dom->setOptions((new Options)->addSelfClosingTag('mytag'));
        $dom->loadStr('<div class="all"><mytag><p>Hey bro, <a href="google.com" data-quote="\"">click here</a></mytag></div>');
        $this->assertEquals('<mytag /><p>Hey bro, <a href="google.com" data-quote="\"">click here</a></p>', $dom->find('div', 0)->innerHtml);
    }

    public function test_load_closing_tag_add_self_closing_tag_array()
    {
        $dom = new Dom;
        $dom->setOptions((new Options)->addSelfClosingTags([
            'mytag',
            'othertag',
        ]));
        $dom->loadStr('<div class="all"><mytag><p>Hey bro, <a href="google.com" data-quote="\"">click here</a><othertag></div>');
        $this->assertEquals('<mytag /><p>Hey bro, <a href="google.com" data-quote="\"">click here</a><othertag /></p>', $dom->find('div', 0)->innerHtml);
    }

    public function test_load_closing_tag_remove_self_closing_tag()
    {
        $dom = new Dom;
        $dom->setOptions((new Options)->removeSelfClosingTag('br'));
        $dom->loadStr('<div class="all"><br><p>Hey bro, <a href="google.com" data-quote="\"">click here</a></br></div>');
        $this->assertEquals('<br><p>Hey bro, <a href="google.com" data-quote="\"">click here</a></p></br>', $dom->find('div', 0)->innerHtml);
    }

    public function test_load_closing_tag_clear_self_closing_tag()
    {
        $dom = new Dom;
        $dom->setOptions((new Options)->clearSelfClosingTags());
        $dom->loadStr('<div class="all"><br><p>Hey bro, <a href="google.com" data-quote="\"">click here</a></br></div>');
        $this->assertEquals('<br><p>Hey bro, <a href="google.com" data-quote="\"">click here</a></p></br>', $dom->find('div', 0)->innerHtml);
    }
}
