<?php

declare(strict_types=1);

use PHPHtmlParser\StaticDom;
use PHPUnit\Framework\TestCase;

class StaticDomTest extends TestCase
{
    protected function setUp(): void
    {
        StaticDom::mount();
    }

    protected function tearDown(): void
    {
        StaticDom::unload();
    }

    public function test_mount_with_dom()
    {
        $dom = new PHPHtmlParser\Dom;
        StaticDom::unload();
        $status = StaticDom::mount('newDom', $dom);
        $this->assertTrue($status);
    }

    public function testload_str()
    {
        $dom = Dom::loadStr('<div class="all"><p>Hey bro, <a href="google.com">click here</a><br /> :)</p></div>');
        $div = $dom->find('div', 0);
        $this->assertEquals('<div class="all"><p>Hey bro, <a href="google.com">click here</a><br /> :)</p></div>', $div->outerHtml);
    }

    public function test_load_with_file()
    {
        $dom = Dom::loadFromFile('tests/data/files/small.html');
        $this->assertEquals('VonBurgermeister', $dom->find('.post-user font', 0)->text);
    }

    public function test_load_from_file()
    {
        $dom = Dom::loadFromFile('tests/data/files/small.html');
        $this->assertEquals('VonBurgermeister', $dom->find('.post-user font', 0)->text);
    }

    public function test_find_noload_str()
    {
        $this->expectException(PHPHtmlParser\Exceptions\NotLoadedException::class);

        Dom::find('.post-user font', 0);
    }

    public function test_find_i()
    {
        Dom::loadFromFile('tests/data/files/big.html');
        $this->assertEquals('В кустах блестит металл<br /> И искрится ток<br /> Человечеству конец', Dom::find('i')[1]->innerHtml);
    }

    public function test_load_from_url()
    {
        $streamMock = Mockery::mock(Psr\Http\Message\StreamInterface::class);
        $streamMock->shouldReceive('getContents')
            ->once()
            ->andReturn(\file_get_contents('tests/data/files/small.html'));
        $responseMock = Mockery::mock(Psr\Http\Message\ResponseInterface::class);
        $responseMock->shouldReceive('getBody')
            ->once()
            ->andReturn($streamMock);
        $clientMock = Mockery::mock(Psr\Http\Client\ClientInterface::class);
        $clientMock->shouldReceive('sendRequest')
            ->once()
            ->andReturn($responseMock);

        Dom::loadFromUrl('http://google.com', null, $clientMock);
        $this->assertEquals('VonBurgermeister', Dom::find('.post-row div .post-user font', 0)->text);
    }
}
