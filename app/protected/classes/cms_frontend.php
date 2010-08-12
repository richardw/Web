<?php
$query = "SELECT `id`,`name`,`title`,`desc`,`keywords`,`content` FROM `pages` WHERE `id`='".$page['section']."'";
$r = db_query($query);
$c = mysqli_fetch_assoc($r);
if(mysqli_num_rows($r)==1) {
    echo $c['id'];
    $page['title'] = $c['title'];
    $page['description'] = $c['desc'];
    $page['keywords'] = $c['keywords'];
    $data['heading'] = $c['name'];
    $data['content'] = $c['content'];
}
else {
    echo 'db entry not found!';
}
?>