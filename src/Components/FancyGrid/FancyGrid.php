<?php
/**
 * @see https://github.com/FancyGrid/FancyGrid
 * @see https://fancygrid.com/
 */

declare(strict_types=1);

namespace App\Components\FancyGrid;

class FancyGrid
{
    protected const DEFAULT_RENDER_TO = 'container';
    protected const DEFAULT_WIDTH = 500;
    protected const DEFAULT_HEIGHT = 500;

    protected DataProvider $dataProvider;

    protected string $title = '';
    protected string $renderTo;
    protected int $width;
    protected int $height;
    protected array $defaults = [];
    protected array $columns = [];
    protected array $data = [];


    public function __construct(array $options)
    {
        $this->title = $options['title'] ?? '';
        $this->renderTo = $options['renderTo'] ?? self::DEFAULT_RENDER_TO;
        $this->width = $options['width'] ?? self::DEFAULT_WIDTH;
        $this->height = $options['height'] ?? self::DEFAULT_HEIGHT;
        $this->defaults = $options['defaults'] ?? [];
        $this->columns = $options['columns'] ?? [];
        $this->data = $options['data'] ?? [];
    }

    public function setDataProvider(DataProvider $dataProvider): void
    {
        $this->dataProvider = $dataProvider;
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

    public function jsonSerialize(): mixed
    {
        return [
            'renderTo' => $this->renderTo,
            'width' => $this->width,
            'height' => $this->height,
            'defaults' => $this->defaults,
            'columns' => $this->columns,
            'data' => $this->data,
        ];
    }
}
