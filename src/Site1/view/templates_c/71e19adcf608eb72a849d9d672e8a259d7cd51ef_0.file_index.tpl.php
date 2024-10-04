<?php
/* Smarty version 5.4.1, created on 2024-10-04 13:23:29
  from 'file:index.tpl' */

/* @var \Smarty\Template $_smarty_tpl */
if ($_smarty_tpl->getCompiled()->isFresh($_smarty_tpl, array (
  'version' => '5.4.1',
  'unifunc' => 'content_66ffec51b1d821_89769392',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '71e19adcf608eb72a849d9d672e8a259d7cd51ef' => 
    array (
      0 => 'index.tpl',
      1 => 1728048208,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
))) {
function content_66ffec51b1d821_89769392 (\Smarty\Template $_smarty_tpl) {
$_smarty_current_dir = '/var/www/html/Site1/view';
echo $_smarty_tpl->getValue('title');?>
<br/>
<?php echo $_smarty_tpl->getValue('h1_title');?>
<br/>
<?php
$_from = $_smarty_tpl->getSmarty()->getRuntime('Foreach')->init($_smarty_tpl, $_smarty_tpl->getValue('menus'), 'menu', false, 'k');
$foreach0DoElse = true;
foreach ($_from ?? [] as $_smarty_tpl->getVariable('k')->value => $_smarty_tpl->getVariable('menu')->value) {
$foreach0DoElse = false;
?>
    [ <?php echo $_smarty_tpl->getValue('menu')['url'];?>
 | <?php echo $_smarty_tpl->getValue('menu')['name'];?>
 ]<br/>
<?php
}
$_smarty_tpl->getSmarty()->getRuntime('Foreach')->restore($_smarty_tpl, 1);
}
}
