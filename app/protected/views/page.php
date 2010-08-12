<div id="col1b">
<?php if(isset($submenu)) { ?>
<div class="bbox r2">
<div class="tl"></div><div class="tr"></div>
<ul id="submenu">
    <?php echo $submenu; ?>
</ul>
<div class="bl"></div><div class="br"></div>
</div>
<?php } ?>

</div>

<div id="col2b">

<div class="bbox r2">
<div class="tl"></div><div class="tr"></div>
<h1><?php echo $heading; ?></h1>
<?php echo $content; ?>
<div class="bl"></div><div class="br"></div>
</div>

</div>
<br class="clear" />