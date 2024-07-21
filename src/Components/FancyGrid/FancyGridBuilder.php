<?php
/**
 * @see https://github.com/FancyGrid/FancyGrid
 * @see https://fancygrid.com/
 */

declare(strict_types=1);

namespace App\Components\FancyGrid;

class FancyGridBuilder
{
    protected array $assets = ['fancy.min.js', 'fancy.min.css'];
    protected array $assetsDebug = ['fancy.js', 'fancy.css'];

    protected string $assetsBaseUrl = '';
    protected bool $debug = false;

    private bool $includeAssets = false;

    public function __construct(array $options)
    {
        $this->assetsBaseUrl = $options['assetsBaseUrl'] ?? '';
        $this->debug = $options['debug'] ?? false;
    }

    public function fancygrid(array $options = []): FancyGrid
    {
        $this->includeAssets = true;
        return new FancyGrid($options);
    }

    public function setAssetsBaseUrl(string $baseUrl): void
    {
        $this->assetsBaseUrl = $baseUrl;
    }

    public function setDebug(bool $debug): void
    {
        $this->debug = $debug;
    }

    public function htmlAssets(): string
    {
        $assets = $this->debug ? $this->assetsDebug : $this->assets;
        $html = array_reduce(
            $this->debug ? $this->assetsDebug : $this->assets,
            function ($carry, $value) {
                $url = '/' . trim($this->assetsBaseUrl, '/') . '/' . $value;
                if (str_ends_with($value, '.css')) {
                    $carry[] = HtmlHelper::cssUrl($url);
                } elseif (str_ends_with($value, '.js')) {
                    $carry[] = HtmlHelper::scriptUrl($url);
                }
                return $carry;
            },
            [],
        );
        return implode(PHP_EOL, $html);
    }
}
