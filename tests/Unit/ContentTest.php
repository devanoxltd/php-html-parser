<?php

declare(strict_types=1);

use PHPHtmlParser\Content;
use PHPHtmlParser\Enum\StringToken;
use PHPUnit\Framework\TestCase;

class ContentTest extends TestCase
{
    public function test_char()
    {
        $content = new Content('abcde');
        $this->assertEquals('a', $content->char());
    }

    public function test_char_selection()
    {
        $content = new Content('abcde');
        $this->assertEquals('d', $content->char(3));
    }

    public function test_fast_forward()
    {
        $content = new Content('abcde');
        $content->fastForward(2);
        $this->assertEquals('c', $content->char());
    }

    public function test_rewind()
    {
        $content = new Content('abcde');
        $content->fastForward(2)
            ->rewind(1);
        $this->assertEquals('b', $content->char());
    }

    public function test_rewind_negative()
    {
        $content = new Content('abcde');
        $content->fastForward(2)
            ->rewind(100);
        $this->assertEquals('a', $content->char());
    }

    public function test_copy_until()
    {
        $content = new Content('abcdeedcba');
        $this->assertEquals('abcde', $content->copyUntil('ed'));
    }

    public function test_copy_until_char()
    {
        $content = new Content('abcdeedcba');
        $this->assertEquals('ab', $content->copyUntil('edc', true));
    }

    public function test_copy_until_escape()
    {
        $content = new Content('foo\"bar"bax');
        $this->assertEquals('foo\"bar', $content->copyUntil('"', false, true));
    }

    public function test_copy_until_not_found()
    {
        $content = new Content('foo\"bar"bax');
        $this->assertEquals('foo\"bar"bax', $content->copyUntil('baz'));
    }

    public function test_copy_by_token()
    {
        $content = new Content('<a href="google.com">');
        $content->fastForward(3);
        $this->assertEquals('href="google.com"', $content->copyByToken(StringToken::ATTR(), true));
    }

    public function test_skip()
    {
        $content = new Content('abcdefghijkl');
        $content->skip('abcd');
        $this->assertEquals('e', $content->char());
    }

    public function test_skip_copy()
    {
        $content = new Content('abcdefghijkl');
        $this->assertEquals('abcd', $content->skip('abcd', true));
    }

    public function test_skip_by_token()
    {
        $content = new Content(' b c');
        $content->fastForward(1);
        $content->skipByToken(StringToken::BLANK());
        $this->assertEquals('b', $content->char());
    }
}
