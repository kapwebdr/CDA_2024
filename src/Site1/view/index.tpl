{$title}<br/>
{$h1_title}<br/>
{foreach key=k item=menu from=$menus}
    [ {$menu.url} | {$menu.name} ]<br/>
{/foreach}