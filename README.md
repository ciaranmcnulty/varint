# Varint stream processing library

Varint streams can be used to delimit binary messages without expending the stream size significantly. 
They are especially useful for message formats without inherent delimiting (e.g. protobuf).

## Usage

### Decoding a stream as strings

```
use Cjm\Varint\Stream\Decoder;

$decoder = Decoder::fromPath('php://stdin');

foreach($decoder->strings() as $string) {
   // do something with the message
}
```

