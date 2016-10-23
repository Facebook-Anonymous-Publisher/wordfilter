<?php

use FacebookAnonymousPublisher\Wordfilter\Wordfilter;

class WordfilterTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Wordfilter
     */
    protected $wordfilter;

    public function setUp()
    {
        parent::setUp();

        $this->wordfilter = new Wordfilter();
    }

    public function test_match()
    {
        $text = 'I have a pen, i have an apple.';

        $this->assertTrue($this->wordfilter->match($text, ['apple']));
        $this->assertTrue($this->wordfilter->match($text, ['bbb', 'ccc', 'an']));
        $this->assertFalse($this->wordfilter->match($text, ['bbb', 'ccc']));

        $text = '我們這一家，今天天氣真好';

        $this->assertFalse($this->wordfilter->match($text, ['你好嗎', '我很好', '哈囉']));
        $this->assertTrue($this->wordfilter->match($text, ['天氣', '我很好', '我們']));
        $this->assertTrue($this->wordfilter->match($text, ['天起', '我很好', '我們']));
    }

    public function test_match_exact()
    {
        $text = '我們這一家，今天天氣真好';

        $this->assertFalse($this->wordfilter->matchExact($text, ['你好嗎', '我很好', '哈囉']));
        $this->assertTrue($this->wordfilter->matchExact($text, ['天氣', '我很好', '我們']));
    }

    public function test_replace()
    {
        $text = 'I have a pen, i have an apple.';

        $this->assertSame('I have a pen, i have an apple.', $this->wordfilter->replace([], '', $text));
        $this->assertSame('I have a pen, i have an apple.', $this->wordfilter->replace(['天氣', '我很好', '我們'], '', $text));
        $this->assertSame('I have a pen, i have an .', $this->wordfilter->replace(['apple', 'banana'], '', $text));

        $text = '22我們這一家apple，今天天氣真好!';

        $this->assertSame('22這一家apple，今天真好!', $this->wordfilter->replace(['天氣', '我很好', '我們'], '', $text));
        $this->assertSame('22這一家apple，今天真好!', $this->wordfilter->replace(['添氣', '我很好', '我們'], '', $text));
    }

    public function test_replace_exact()
    {
        $text = '我們這一家，今天天氣真好';

        $this->assertSame('我們這一家，今天天氣真好', $this->wordfilter->replaceExact(['你好嗎', '我很好', '哈囉'], '', $text));
        $this->assertSame('嗨嗨嗨這一家，今天嗨嗨嗨真好', $this->wordfilter->replaceExact(['天氣', '我很好', '我們'], '嗨嗨嗨', $text));
        $this->assertSame('ww這一家，今天ww真好', $this->wordfilter->replaceExact(['天氣', '我很好', '我們'], 'w', $text));
        $this->assertSame('這一家，今天真好', $this->wordfilter->replaceExact(['天氣', '我很好', '我們'], '', $text));
    }
}
