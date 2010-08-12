<?php
$r = db_query("SELECT `content`,`mapto` FROM `tmp_pages` WHERE `mapto`!='0'");
while($c = mysqli_fetch_assoc($r)) {
    $query = "UPDATE `pages` SET `content`='".mysqli_real_escape_string($_dbconn,$c['content'])."' WHERE `id`='".$c['mapto']."'";
    db_query($query);
}