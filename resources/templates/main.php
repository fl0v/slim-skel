<?php
/**
 * @var \App\Helpers\View $this
 * @var string $content
 */
?>
<!DOCTYPE html>
<html lang="<?=$this->getConfig()->get('app.lang', 'en')?>">
<head>
    <meta charset="<?=$this->getConfig()->get('app.charset', 'UTF-8')?>">
    <title><?=$this->getConfig()->get('app.name')?></title>
</head>
<body>
<?=$content?>
</body>
</html>