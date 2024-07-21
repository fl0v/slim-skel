<?php

declare(strict_types=1);

namespace App\Components\FancyGrid;

class Column implements \JsonSerializable
{
    protected string $name;
    protected ?string $label = null;

    public function __construct(
        string $name,
        array $options = [],
    ) {
        $this->setLabel($options['label'] ?? $name);
    }

    public function setLabel(string $label): void
    {
        $this->label = $label;
    }

    public function jsonSerialize(): mixed
    {
        return [
            'name' => $this->name,
            'label' => $this->label,
        ];
    }
}