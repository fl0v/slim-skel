<?php

declare(strict_types=1);

namespace App\Components\FancyGrid;

class Column implements \JsonSerializable
{
    public const TYPE_STRING = 'string';

    public const DEFAULT_TYPE = self::TYPE_STRING;
    public const DEFAULT_WIDTH = 100;

    public const DEFAULT_PARAMS = [
        'type' => Column::DEFAULT_TYPE,
        'width' => Column::DEFAULT_WIDTH,
        'editable' => false,
        'sortable' => false,
    ];

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

    public function jsonSerialize(): array
    {
        return [
            'name' => $this->name,
            'label' => $this->label,
        ];
    }
}