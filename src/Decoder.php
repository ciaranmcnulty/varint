<?php
declare(strict_types=1);

namespace Cjm\Varint\Stream;

final class Decoder
{
    private $path;

    private function __construct(){}

    /**
     * Create a decoder from a path, e.g. a file on disk
     */
    public static function fromPath(string $path) : self
    {
        $decoder = new self();
        $decoder->path = $path;

        return $decoder;
    }

    /**
     * The raw strings contained in the stream
     */
    public function strings() : iterable
    {
        $fh = fopen($this->path, 'r');

        while (!feof($fh)) {

            $length = $index = 0;

            do {
                $byte = ord(fread($fh, 1));

                // If MSB is 0 this is the last byte of varint
                $more = (0b10000000 & $byte) > 0;

                // prepend the 7 LSB to what we have some far, in chunks of 7 digits
                $length |= ((0b01111111 & $byte) << 7 * $index++);

            } while (!feof($fh) && $more);

            // streams can terminate with 0b00000000
            if (!$length) {
                return;
            }

            yield (fread($fh, $length));
        }
    }
}
