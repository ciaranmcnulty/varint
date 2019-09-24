<?php
declare(strict_types=1);

namespace Cjm\Varint\Stream;

final class Decoder
{
    private $stream;

    private function __construct($stream)
    {
        $this->stream = $stream;
    }

    /**
     * Create a decoder from a path, e.g. a file on disk
     */
    public static function fromPath(string $path) : self
    {
        return new self(fopen($path, 'r'));
    }

    /**
     * Create a decoder from an already opened stream
     *
     * @param resource $stream
     */
    public static function fromStream($stream) : self
    {
        return new self($stream);
    }

    /**
     * The raw strings contained in the stream
     */
    public function strings() : iterable
    {
        while (!feof($this->stream)) {

            $length = $index = 0;

            do {
                $byte = ord(fread($this->stream, 1));

                // If MSB is 0 this is the last byte of varint
                $more = (0b10000000 & $byte) > 0;

                // prepend the 7 LSB to what we have some far, in chunks of 7 digits
                $length |= ((0b01111111 & $byte) << 7 * $index++);

            } while (!feof($this->stream) && $more);

            // streams can terminate with 0b00000000
            if (!$length) {
                return;
            }

            yield (fread($this->stream, $length));
        }
    }
}
