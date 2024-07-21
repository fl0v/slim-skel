<?php

declare(strict_types=1);

namespace App\Components\FancyGrid;

abstract class DataProvider
{
    protected int $pageIndex = 0;
    protected int $pageSize = 20;

    /**
     * @return Column[]
     */
    abstract public function getColumns(): array;
    abstract public function getData(): \Iterator;


    


}