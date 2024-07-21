<?php
/**
 * @var \App\Helpers\View $this
 * @var \App\Components\FancyGrid\FancyGridBuilder $fancygridBuilder
 */
$fancygridBuilder = $this->getContainer()->get('fancygrid');
?>
<h1>FancyGrid demo</h1>

<?=$fancygridBuilder->htmlAssets()?>