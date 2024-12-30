<?php 
require "common/conn.php";
session_start();


$arr = array(
    
  array('id'=>100, 'parentid'=>0, 'name'=>'a'),
  array('id'=>101, 'parentid'=>100, 'name'=>'a'),
  array('id'=>102, 'parentid'=>101, 'name'=>'a'),
  array('id'=>103, 'parentid'=>101, 'name'=>'a'),
  array('id'=>104, 'parentid'=>0, 'name'=>'a'),
  array('id'=>105, 'parentid'=>104, 'name'=>'a'),
  
);

$new = array();
foreach ($arr as $a){
    $new[$a['parentid']][] = $a;
}
$tree = createTree($new, array($arr[0]));
//print_r($tree);

function createTree(&$list, $parent){
    $tree = array();
    foreach ($parent as $k=>$l){
        if(isset($list[$l['id']])){
            $l['children'] = createTree($list, $list[$l['id']]);
        }
        $tree[] = $l;
    } 
    return $tree;
}

?>
<style>
    .tree,
    .tree ul,
    .tree li {
        list-style: none;
        margin: 0;
        padding: 0;
        position: relative;
    }
    .tree {
        margin: 0 0 1em;
        text-align: center;
    }
    .tree,
    .tree ul {
        display: table;
    }
    .tree ul {
        width: 100%;
    }
    .tree li {
        display: table-cell;
        padding: .5em 0;
        vertical-align: top;
    }
    .tree li:before {
        outline: solid 1px #666;
        content: "";
        left: 0;
        position: absolute;
        right: 0;
        top: 0;
    }
    .tree li:first-child:before {
        left: 50%;
    }
    .tree li:last-child:before {
        right: 50%;
    }
    .tree code,
    .tree span {
        border: solid .1em #666;
        border-radius: .2em;
        display: inline-block;
        margin: 0 .2em .5em;
        padding: .2em .5em;
        position: relative;
    }
    .tree ul:before,
    .tree code:before,
    .tree span:before {
        outline: solid 1px #666;
        content: "";
        height: .5em;
        left: 50%;
        position: absolute;
    }
    .tree ul:before {
        top: -.5em;
    }
    .tree code:before,
    .tree span:before {
        top: -.55em;
    }
    .tree>li {
        margin-top: 0;
    }
    .tree>li:before,
    .tree>li:after,
    .tree>li>code:before,
    .tree>li>span:before {
        outline: none;
    }
</style>



<ul class="tree">
  <li> <span>Home</span>
    <ul>
      <li> <span>About us</span>
        <ul>
          <li> <span>Our history</span>
            <ul>
              <li><span>Founder</span></li>
                <ul>
                    <li><span>Co-Founder</span></li>
                        <ul>
                            <li><span>Co- Co -Founder</span></li>
                        </ul>
                </ul>
            </ul>
          </li>
          <li> <span>Our board</span>
            <ul>
              <li><span>Brad Whiteman</span></li>
              <li><span>Cynthia Tolken</span></li>
              <li><span>Bobby Founderson</span></li>
            </ul>
          </li>
        </ul>
      </li>
    </ul>
  </li>
</ul>