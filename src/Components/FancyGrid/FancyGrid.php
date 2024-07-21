<?php
/**
 * @see https://github.com/FancyGrid/FancyGrid
 * @see https://fancygrid.com/
 */

declare(strict_types=1);

namespace App\Components\FancyGrid;

class FancyGrid implements \JsonSerializable
{
    protected const DEFAULT_RENDER_TO = 'container';
    protected const DEFAULT_WIDTH = 500;
    protected const DEFAULT_HEIGHT = 500;

    protected string $title = '';
    protected string $renderTo;
    protected int $width;
    protected int $height;
    protected array $defaults = [];

    public function __construct(
        protected DataProvider $dataProvider,
        /** @var Column[] */
        protected array $columns,
        array $options = [],
    ) {
        $this->title = $options['title'] ?? static::class;
        $this->renderTo = $options['renderTo'] ?? self::DEFAULT_RENDER_TO;
        $this->width = $options['width'] ?? self::DEFAULT_WIDTH;
        $this->height = $options['height'] ?? self::DEFAULT_HEIGHT;
        $this->defaults = $options['defaults'] ?? Column::DEFAULT_PARAMS;
    }

    public function setDataProvider(DataProvider $dataProvider): void
    {
        $this->dataProvider = $dataProvider;
    }

    public function getDataProvider(): DataProvider
    {
        return $this->dataProvider;
    }

    public function setColumns(array $columns): void
    {
        $this->columns = $columns;
    }

    public function getColumns(): array
    {
        return $this->columns;
    }

    public function gridHtml(): string
    {
        return '<table>...</table>';
    }

    public function gridJavascript(): string
    {
        $json = json_encode($this);
        return <<<JS
            <script>
            new FancyGrid({$json});
            </script>
        JS;
    }

    public function jsonSerialize(): array
    {
        return [
            'renderTo' => $this->renderTo,
            'width' => $this->width,
            'height' => $this->height,
            'defaults' => $this->defaults,
            'columns' => array_map(
                fn(Column $column) => $column->jsonSerialize(),
                $this->columns,
            ),
            'data' => $this->dataProvider->getData(),
        ];
    }
}
