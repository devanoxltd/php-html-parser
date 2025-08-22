<?php

declare(strict_types=1);

namespace PHPHtmlParser\DTO\Tag;

use stringEncode\Exception;
use StringEncoder\Encoder;

use function htmlspecialchars_decode;
use function is_null;

final class AttributeDTO
{
    /**
     * @var ?string
     */
    private $value;

    /**
     * @var bool
     */
    private $doubleQuote;

    private function __construct(array $values)
    {
        $this->value = $values['value'];
        $this->doubleQuote = $values['doubleQuote'] ?? true;
    }

    public static function makeFromPrimitives(?string $value, bool $doubleQuote = true): self
    {
        return new self([
            'value' => $value,
            'doubleQuote' => $doubleQuote,
        ]);
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function isDoubleQuote(): bool
    {
        return $this->doubleQuote;
    }

    public function htmlspecialcharsDecode(): void
    {
        if (! is_null($this->value)) {
            $this->value = htmlspecialchars_decode($this->value);
        }
    }

    /**
     * @throws Exception
     */
    public function encodeValue(Encoder $encode)
    {
        if ($this->value !== null) {
            $this->value = $encode->convert()->fromString($this->value)->toString();
        }
    }
}
