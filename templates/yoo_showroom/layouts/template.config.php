<?php
/**
* @package   yoo_showroom
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
*/

// generate css for layout
$css[] = sprintf('.wrapper { max-width: %dpx; }', $this['config']->get('template_width'));

// generate css for 3-column-layout
$sidebar_a       = '';
$sidebar_b       = '';
$block_side_width = intval($this['config']->get('sidebar-a_width'));
$block_main_width = 100 - $block_side_width;
$sidebar_b_width = intval($this['config']->get('sidebar-b_width'));
$maininner_width = 100;
$sidebar_classes = "";
$rtl             = $this['config']->get('direction') == 'rtl';
$body_config	 = array();

// set widths
$sidebar_a = $this['config']->get('sidebar-a'); 
$css[] = sprintf('#block-side { width: %d%%; }', $block_side_width);
$css[] = sprintf('#block-main { width: %d%%; }', $block_main_width);
$css[] = sprintf('#side-container { width: %dpx; }', intval($this['config']->get('template_width') / 100 * $block_side_width) - 20);
$css[] = sprintf('.sidebar-a-bottom-fixed { width: %dpx; }', intval($this['config']->get('template_width') / 100 * $block_side_width) - 40);

$body_config["template_width"]   = $this['config']->get('template_width');
$body_config["block_side_width"] = $block_side_width;

if ($this['modules']->count('sidebar-b')) {
	$sidebar_b = $this['config']->get('sidebar-b'); 
	$maininner_width -= $sidebar_b_width;
	$css[] = sprintf('#sidebar-b { width: %d%%; }', $sidebar_b_width);
}

$css[] = sprintf('#maininner { width: %d%%; }', $maininner_width);

if ($sidebar_a == 'left') {
	$sidebar_classes .= 'sidebar-a-left ';
} elseif ($sidebar_a == 'right') {
	$sidebar_classes .= 'sidebar-a-right ';
	$css[] = sprintf('#block-side { float: %s; }', $rtl ? 'left' : 'right');
}

if ($sidebar_b == 'right') {
	$sidebar_classes .= 'sidebar-b-right ';
} elseif ($sidebar_b == 'left') {
	$sidebar_classes .= 'sidebar-b-left ';
	$css[] = sprintf('#maininner { float: %s; }', $rtl ? 'left' : 'right');
}

// number of sidebars
if ($sidebar_a && $sidebar_b) {
	$sidebar_classes .= 'sidebars-2 ';
} elseif ($sidebar_a || $sidebar_b) {
	$sidebar_classes .= 'sidebars-1 ';
}

// generate css for dropdown menu
foreach (array(1 => '.dropdown', 2 => '.columns2', 3 => '.columns3', 4 => '.columns4') as $i => $class) {
	$css[] = sprintf('#menu %s { width: %dpx; }', $class, $i * intval($this['config']->get('menu_width')));
}

// load css
$this['asset']->addFile('css', 'css:base.css');
$this['asset']->addFile('css', 'css:layout.css');
$this['asset']->addFile('css', 'css:menus.css');
$this['asset']->addString('css', implode("\n", $css));
$this['asset']->addFile('css', 'css:modules.css');
$this['asset']->addFile('css', 'css:tools.css');
$this['asset']->addFile('css', 'css:system.css');
$this['asset']->addFile('css', 'css:extensions.css');
$this['asset']->addFile('css', 'css:custom.css');
if (($background = $this['config']->get('background')) && $this['path']->path("css:/background/$background.css")) { $this['asset']->addFile('css', "css:/background/$background.css"); }
if ($this['config']->get('animations')) $this['asset']->addFile('css', 'css:/animations.css');

if (($color = $this['config']->get('block_side')) && $this['path']->path("css:/bg_side/$color.css")) { $this['asset']->addFile('css', "css:/bg_side/$color.css"); }
if (($color = $this['config']->get('block_main')) && $this['path']->path("css:/bg_main/$color.css")) { $this['asset']->addFile('css', "css:/bg_main/$color.css"); }

if (($color = $this['config']->get('color_hover')) && $this['path']->path("css:/color_hover/$color.css")) { $this['asset']->addFile('css', "css:/color_hover/$color.css"); }
if (($font = $this['config']->get('font1')) && $this['path']->path("css:/font1/$font.css")) { $this['asset']->addFile('css', "css:/font1/$font.css"); }
if (($font = $this['config']->get('font2')) && $this['path']->path("css:/font2/$font.css")) { $this['asset']->addFile('css', "css:/font2/$font.css"); }
if (($font = $this['config']->get('font3')) && $this['path']->path("css:/font3/$font.css")) { $this['asset']->addFile('css', "css:/font3/$font.css"); }
$this['asset']->addFile('css', 'css:style.css');
if ($this['config']->get('direction') == 'rtl') $this['asset']->addFile('css', 'css:rtl.css');
$this['asset']->addFile('css', 'css:responsive.css');
$this['asset']->addFile('css', 'css:print.css');

// load fonts
$http  = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$fonts = array(
	'droidsans' => 'template:fonts/droidsans.css',
	'opensans' => 'template:fonts/opensans.css',
	'opensans_uppercase' => 'template:fonts/opensans.css',
	'yanonekaffeesatz' => 'template:fonts/yanonekaffeesatz.css',
	'yanonekaffeesatz_uppercase' => 'template:fonts/yanonekaffeesatz.css',
	'mavenpro' => 'template:fonts/mavenpro.css',
	'hattori' => 'template:fonts/hattori.css',
	'oswald' => 'template:fonts/oswald.css',
	'oswald_uppercase' => 'template:fonts/oswald.css',
	'droidserifitalic' => 'template:fonts/droidserifitalic.css',
	'metrophobic' => 'template:fonts/metrophobic.css');

foreach (array_unique(array_intersect_key($fonts, array_flip(array_unique(array($this['config']->get('font1'), $this['config']->get('font2'), $this['config']->get('font3')))))) as $font) {
	$this['asset']->addFile('css', $font);
}

// set body css classes
$body_classes  = $sidebar_classes.' ';
$body_classes .= $this['config']->get('page_alignment') ? 'page-left ' : '';
$body_classes .= $this['config']->get('sidebar-a-fixed') ? 'sidebar-a-fixed ' : '';
$body_classes .= 'sidebar-' . $this['config']->get('block_side') . ' ';
$body_classes .= ($this['config']->get('block_main') == $this['config']->get('block_side')) ? 'separator-side ' : '';
$body_classes .= ($this['config']->get('block_main') == $this['config']->get('background')) ? 'separator-main ' : '';
$body_classes .= $this['system']->isBlog() ? 'isblog ' : 'noblog ';
$body_classes .= $this['config']->get('page_class');

$this['config']->set('body_classes', $body_classes);

// add social buttons
$body_config['menu-follower'] = (int) $this['config']->get('menu_follower', 1);
$body_config['twitter'] = (int) $this['config']->get('twitter', 0);
$body_config['plusone'] = (int) $this['config']->get('plusone', 0);
$body_config['facebook'] = (int) $this['config']->get('facebook', 0);

$this['config']->set('body_config', json_encode($body_config));

// add javascripts
$this['asset']->addFile('js', 'js:warp.js');
$this['asset']->addFile('js', 'js:responsive.js');
$this['asset']->addFile('js', 'js:accordionmenu.js');
$this['asset']->addFile('js', 'js:dropdownmenu.js');
$this['asset']->addFile('js', 'js:template.js');

// internet explorer
if ($this['useragent']->browser() == 'msie') {

	// add conditional comments
	$head[] = sprintf('<!--[if lte IE 8]><script src="%s"></script><![endif]-->', $this['path']->url('js:html5.js'));
	$head[] = sprintf('<!--[if IE 8]><link rel="stylesheet" href="%s" /><![endif]-->', $this['path']->url('css:ie8.css'));

}

// add $head
if (isset($head)) {
	$this['template']->set('head', implode("\n", $head));
}