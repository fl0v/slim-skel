<?php
/**
 * @var \App\Helpers\View $this
 * @var \App\Components\FancyGrid\FancyGrid $fancyGrid
 */
$gridHtml = $fancyGrid->gridHtml();

?>
<h1>FancyGrid demo</h1>

<div class="container">
    <?=$gridHtml?>
</div>
