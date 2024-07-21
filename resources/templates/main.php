<?php
/**
 * @var \App\Helpers\View $this
 * @var string $content
 */
$gridAssets = $this->getAction()->getFancyGridBuilder()->htmlAssets();

?>
<!DOCTYPE html>
<html lang="<?=$this->getConfig()->get('app.lang', 'en')?>">
<head>
    <meta charset="<?=$this->getConfig()->get('app.charset', 'UTF-8')?>">
    <title><?=$this->getConfig()->get('app.name')?></title>
    <?=$gridAssets?>
</head>
<body>
<?=$content?>
</body>
</html>