<?php
//print_r($_params);

//extract the sections and product info from the url
$secs = array();
$prod = array();
$t = count($_params);
$flag = false;
for($i=0;$i<$t;$i++) {
    if(is_numeric($_params[$i])) {
        $secs = array_slice($_params,0,$i);
        $prod = array_slice($_params,$i);
        $flag = true;
        break;
    }
}
if($flag==false) {
    $secs = $_params;
}

$urlo = implode('/',$secs);
$t = count($secs);

if($t>0) {
    $query = "SELECT `a`.`id`,`a`.`name`,`a`.`content` FROM `pages` AS `a` WHERE `a`.`url`='".$urlo."'";
    $r = db_query($query);
    if(mysqli_num_rows($r)==1) {
        $c = mysqli_fetch_assoc($r);
        $catid = $c['id'];
        $catname = $c['name'];
        $data['content'] = $c['content'];
    }
    else {
        $catid = $page['section'];
        $catname = 'Products';
    }
}
else {
    $catid = $page['section'];
    $catname = 'Products';
}
echo $catname;
echo '<br />';
echo $catid;

$ids = array($catid);
$ids = getParent($catid,$ids);
//print_r($ids);
$data['submenu'] = '';
$level = 1;


function genSubmenu($level) {
    global $data,$ids,$t;
    $query = "SELECT `id`,`url`,`name` FROM `pages` WHERE `parentid`='{$ids[$level]}' ORDER BY `order` ASC";
    $r = db_query($query);
    $level++;
    $data['submenu'] .= '<ul>';
    if(!isset($ids[$level])) { //if the index doesn't exist
        while($c = mysqli_fetch_assoc($r)) {
            $data['submenu'] .= '<li><a href="/'.$c['url'].'">'.$c['name'].'</a></li>';
        }
        $data['submenu'] .= '</ul>';
        return;
    }
    else {
        while($c = mysqli_fetch_assoc($r)) {
            if($c['id']==$ids[$level]) {
                if($level==$t)
                    $data['submenu'] .= '<li class="curi"><a href="/'.$c['url'].'">'.$c['name'].'</a>';
                else
                    $data['submenu'] .= '<li><a href="/'.$c['url'].'" class="bold">'.$c['name'].'</a>';
                genSubmenu($level);
                $data['submenu'] .= '</li>';
            }
            else {
                $data['submenu'] .= '<li><a href="/'.$c['url'].'">'.$c['name'].'</a></li>';
            }
        }
    }
    $data['submenu'] .= '</ul>';
}

genSubmenu($level);
?>