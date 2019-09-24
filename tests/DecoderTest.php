<?php
declare(strict_types=1);

final class DecoderTest extends \PHPUnit\Framework\TestCase
{
    function testDecodingReturnsCorrectLengthChunks()
    {
        $decoder = Cjm\Varint\Stream\Decoder::fromPath(__DIR__ . '/samples/stream');

        $strings = $decoder->strings();

        $this->assertEquals(
            [
                0 => 3489,
                1 => 1945,
            ],
            array_map(
                'strlen',
                iterator_to_array($strings)
            )
        );
    }
}
