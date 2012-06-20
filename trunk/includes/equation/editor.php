<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="copyright" content="Copyright CodeCogs 2007-2008"/>
<title>LaTeX Equation Editor for the Internet</title>
<meta name="robots" content="index, nofollow"/>
<meta name="description" content="A web-based LaTeX equation editor that generates graphical equations (gif, png, swf, pdf). It also provides the HTML code for directly embedding the equations into any website, forum or blog. Images may also be used directly within your offline documentation. Open source and XHTML compliant."/>
<meta name="keywords" content="LaTeX, equation editor, open source, equations, mimetex, pdf, gif, png, swf, instantly rendered, preview, free"/>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

<link rel="stylesheet" href="css/equation.css" type="text/css"/>
<script src="js/eq_fck.js" type="text/javascript"></script>
<script src="js/editor.js" type="text/javascript"></script>
<script src="js/clipboard.js" type="text/javascript"></script>
<script type="text/javascript">AC_FL_RunContent = 0;</script>
<script src="js/eq_flash.js" type="text/javascript"></script>
<script type="text/javascript">
// Send the equation to the opening window.
var cctarget = 'message';
var cctype = 'phpBB';
</script>
</head>
<body onload="LoadSelected();">
<div id="hover"></div>
<div class="center">
<div class="top">

<?
$lang=array();

$lang['help']='Help';

$lang['size_title']='Equation Font Size';
$lang['tiny']='Tiny';  // this refers to a font size of tiny
$lang['small']='Small';
$lang['normal']='Normal';
$lang['large']='Large';
$lang['huge']='Huge';

$lang['color']='Colors';   // Displayed like "Colors...", allowing users to display equations in new colors
$lang['color_title']='Equation Color';
$lang['red']='red';
$lang['green']='green';
$lang['blue']='blue';

$lang['history']='History';  // Displayed like 'History...', shows recent equations a user has typed
$lang['history_title']='A history of recently entered equations';

$lang['favorites']='Favorites';  // Displayed like 'Favorites...', shows a used saved (favorite) equations
$lang['favorites_title']='A dynamic list for you to create of your favorite equations';
$lang['favorites_add']='add current equation to favorites';  // adds an equation to the favorite list
$lang['favorites_delete']='delete currently selected equation from favorites';  // removes an equation from the favorite list


$lang['inline']='Inline';          // place equations inline within text in the parent document
$lang['inline_title']='Place equations inline with other text (by default equations are placed on a new line)';

$lang['compressed']='Compressed';  // create equations with restricted vertical height
$lang['compressed_title']='Create equations that are vertically compressed, suitable for being inline with other text';

$lang['clear']='Clear';
$lang['clear_title']='Clear the editor window';

$lang['render']='Render Equation';
$lang['render_title']='Click to see your equation rendered below';

$lang['copy']='Copy to Document';
$lang['copy_clipboard']='Copy to Clipboard';
$lang['click_here']='click here';

$lang['intro']='Type your LaTeX equation in the box above and click Render Expression to see it displayed here.';

$lang['updated']='Last updated on';  // .. 27/6/2008
$lang['refresh']='(Refresh your browser if you\'ve used an older edition)';
$lang['dev']='Written by Will Bateman with assistance from Steve Mayer.';

// This message appears at the bottom of the screen when the editor is first loaded.
$lang['install']='To Install this editor on your website,';


?>
<select title="Spaces" onchange="insertText(this.options[this.selectedIndex].value); this.selectedIndex=0;">
  <option selected="selected" value="" style="color:#8080ff">Spaces...</option>
  <option value="\,">thin</option>
  <option value="\:">medium</option>
  <option value="\;">thick</option>
  <option value="\!">negative</option>
</select>
<select title="Styles" onchange="insertText(this.options[this.selectedIndex].value, 1000); this.selectedIndex=0;">
  <option selected="selected" value="" style="color:#8080ff">Style...</option>
  <optgroup label="Equation Only">
  <option value="\mathbb{}" title="\mathbb{}">Blackboard</option>
  <option value="\mathbf{}" title="\mathbf{}">Bold</option>
  <option value="\boldsymbol{}" title="\boldsymbol{}">Bold Greek</option>
  <option value="\mathit{}" title="\mathit{}">Italic</option>

  <option value="\mathrm{}" title="\mathrm{}">Roman</option>
  <option value="\mathfrak{}" title="\mathfrak{}">Fraktur</option>
  </optgroup>
  <optgroup label="Text Only">
  <option value="\texttt{}" title="\texttt{}">Typewriter</option>
  <option value="\textup{}" title="\textup{}">Upright</option>
  <option value="\textbf{}" title="\textbf{}">Bold</option>
  <option value="\textit{}" title="\textit{}">Italic</option>
  <option value="\textrm{}" title="\textrm{}">Roman</option>
  <option value="\textsl{}" title="\textsl{}">Slanted</option>
  <option value="\textsc{}" title="\textsc{}">SMALL CAPS</option>
  <option value="\emph{}"   title="\emph{}">Emphasis</option>
  </optgroup>
</select>
<select title="Functions" onchange="insertText(this.options[this.selectedIndex].value); this.selectedIndex=0;">
  <option selected="selected" value="" style="color:#8080ff">Functions&hellip;</option>
  <option value="\arg">arg</option>
  <option value="\deg">degree</option>
  <option value="\det">det</option>
  <option value="\dim">dim</option>
  <option value="\gcd">gcd</option>
  <option value="\hom">hom</option>
  <option value="\ker">ker</option>
  <option value="\partial">partial</option>
  <option value="\Pr">Pr</option>
  <option value="\sup">sup</option>
  <optgroup label="Logs">
  <option value="\exp">exp</option>
  <option value="\lg">lg</option>
  <option value="\ln">ln</option>
  <option value="\log">log</option>
  <option value="\log_{e}">log e</option>
  <option value="\log_{10}">log 10</option>
  </optgroup>
  <optgroup label="Limits">
  <option value="\lim">limit</option>
  <option value="\liminf">liminf</option>
  <option value="\limsup">limsup</option>
  <option value="\max">maximum</option>
  <option value="\min">minimum</option>
  <option value="\infty">infinite</option>
  </optgroup>
  <optgroup label="Trig">
  <option value="\sin">sin</option>
  <option value="\cos">cos</option>
  <option value="\tan">tan</option>
  <option value="\sin^{-1}">sin-1</option>
  <option value="\cos^{-1}">cos-1</option>
  <option value="\tan^{-1}">tan-1</option>
  <option value="\csc">csc</option>
  <option value="\sec">sec</option>
  <option value="\cot">cot</option>
  <option value="\sinh">sinh</option>
  <option value="\cosh">cosh</option>
  <option value="\tanh">tanh</option>
  <option value="\coth">coth</option>
  <option value="\sinh^{-1}">sinh-1</option>
  <option value="\cosh^{-1}">cosh-1</option>
  <option value="\tanh^{-1}">tanh-1</option>
  </optgroup>
  <optgroup label="Complex">
  <option value="\Re">Real</option>
  <option value="\Im">Imaginary</option>
  <option value="\imath">i</option>
  <option value="\jmath">j</option>
  </optgroup>
</select>
<select title="Foreign Characters" onchange="insertText(this.options[this.selectedIndex].value); this.selectedIndex=0;">
  <option selected="selected" value="" style="color:#8080ff">Foreign...</option>
  <option value="\oe">&oelig;</option>
  <option value="\OE">&OElig;</option>
  <option value="\ae">&aelig;</option>
  <option value="\AE">&AElig;</option>
  <option value="\aa">&aring;</option>
  <option value="\AA">&Aring;</option>
  <option value="\ss">&szlig;</option>
  <option value="\S">(&sect;) section</option>
  <option value="\P">(&para;) paragraph</option>
  <option value="\o">o</option>
  <option value="\O">O</option>
  <option value="\l">l</option>
  <option value="\L">L</option>
  <option value="\dag">dagger</option>
  <option value="\ddag">double dagger</option>
  <option value="\copyright">&copy;</option>
  <option value="\pounds">&pound;</option>
</select>
<select title="Symbols" onchange="insertText(this.options[this.selectedIndex].value); this.selectedIndex=0;">
  <option selected="selected" value="" style="color:#8080ff">Symbols...</option>
  <option value="\pm" title="\pm">(&plusmn;) plus or minus</option>
  <option value="\mp" title="\mp">minus or plus</option>
  <option value="\times">(&times;) times</option>
  <option value="\div" title="\div">(&divide;) divided by</option>
  <option value="\ast" title="\ast">(&lowast;) asterisk</option>
  <option value="\star">(*) star</option>
  <option value="\circ" title="\circ">circle</option>
  <option value="^{\circ}" title="^{\circ}">degree symbol</option>
  <option value="\bullet">(&bull;) bullet</option>
  <option value="\cdot">(&middot;) center dot</option>
  <option value="\uplus">u plus</option>
  <option value="\sqcap">square cap</option>
  <option value="\sqcup">square cup</option>
  <option value="\vee">(&or;) vee</option>
  <option value="\wedge">(&and;) wedge</option>
  <option value="\diamond">(&loz;) diamond</option>
  <option value="\bigtriangleup">big triangle up</option>
  <option value="\bigtriangledown">big triangle down</option>
  <option value="\triangleleft">triangle left</option>
  <option value="\triangleright">triangle right</option>
  <option value="\lhd">large triangle left</option>
  <option value="\rhd">large triangle right</option>
  <option value="\oplus">(&oplus;) circle plus</option>
  <option value="\ominus">circle minus</option>
  <option value="\otimes">(&otimes;) circle times</option>
  <option value="\oslash">circle slash</option>
  <option value="\odot">circle dot</option>
  <option value="\bigcirc">big circle</option>
  <option value="\dagger">(&dagger;) dagger</option>
  <option value="\ddagger">(&Dagger;) double dagger</option>
  <option value="\amalg">coproduct</option>
</select>
&nbsp;&nbsp;&nbsp;&nbsp;<a href="http://en.wikipedia.org/wiki/Help:Formula" target="_blank"><img src="http://www.codecogs.com/images/icons/i.gif" alt="help" align="bottom" width="13" height="13" border="0"/></a>
</div>
<div class="top">
<img id="undobutton" src="http://www.codecogs.com/images/buttons/undo-x.gif" width="20" height="13" alt="undo" title="undo" style="vertical-align:bottom" onclick="undo('latex_formula');"/>
<img id="redobutton" src="http://www.codecogs.com/images/buttons/redo-x.gif" width="20" height="13" alt="redo" title="redo" style="vertical-align:bottom; margin-right:20px" onclick="redo('latex_formula');" />
<select name="fontsize" id="fontsize" title="<?php echo $lang['size_title']; ?>" onchange="textchanged();">
  <option value="\tiny">(8pt) <?php echo $lang['tiny']; ?></option>
  <option value="\small">(10pt) <?php echo $lang['small']; ?></option>
  <option value="" selected="selected">(12pt) <?php echo $lang['normal']; ?></option>
  <option value="\large">(14pt) <?php echo $lang['large']; ?></option>
  <option value="\huge">(20pt) <?php echo $lang['huge']; ?></option>
</select>

<select title="<?php echo $lang['color_title']; ?>" onchange="insertText(this.options[this.selectedIndex].value, this.options[this.selectedIndex].value.length-1); this.selectedIndex=0">
  <option selected="selected" value="" style="color:#8080ff"><?php echo $lang['color']; ?>...</option>
  <option value="{\color{red} }" style="color:red"><?php echo $lang['red']; ?></option>
  <option value="{\color{green} }" style="color:green"><?php echo $lang['green']; ?></option>
  <option value="{\color{blue} }" style="color:blue"><?php echo $lang['blue']; ?></option>
</select>
<select name="history" id="history" title="<?php echo $lang['history_title']; ?>" onchange="insertText(this.options[this.selectedIndex].value); this.selectedIndex=0;">
  <option selected="selected" value="" style="color:#8080ff"><?php echo $lang['history']; ?>...</option>
</select>
<select name="favorites" id="favorites" title="<?php echo $lang['favorites_title']; ?>" onchange="insertText(this.options[this.selectedIndex].value);">
  <option selected="selected" value="" style="color:#8080ff"><?php echo $lang['favorites']; ?>...</option>
<?php 
  foreach($_COOKIE as $a=>$b)
        {
          if(substr($a,0,3)=='eq_')
                {
      $a = str_replace(array('&plus;','&space;'),array('+',' '),urldecode($a));
      $b = str_replace('&plus;','+',urldecode($b));
                  echo('<option value="'.htmlentities($b).'">'.htmlentities(substr($a,3)).'</option>');
                }
        }
?>
</select>&nbsp;<img src="images/add.gif" width="11" height="12" onclick="addfavorite('favorites');" alt="plus" title="<?php echo $lang['favorites_add']; ?>"/>&nbsp;<img src="images/sub.gif" width="11" height="12" onclick="deletefavorite('favorites')" alt="minus" title="<?php echo $lang['favorites_delete']; ?>"/>
</div>

<div id="toolbar_wrapper">
<div id="toolbar">
<div class="panel" id="panel1" onmouseover="this.style.overflow='visible'" onmouseout="this.style.overflow='hidden'">
<?php require 'panels/operators_panel.tpl'; ?>
</div>

<div class="panel" id="panel2" onmouseover="this.style.overflow='visible'" onmouseout="this.style.overflow='hidden'">
<?php require 'panels/brackets_panel.tpl'; ?>
</div>

<div class="panel2" id="panel3" onmouseover="this.style.overflow='visible'" onmouseout="this.style.overflow='hidden'">
<?php require 'panels/greekletters_panel.tpl'; ?>
</div>

<div class="panel2" id="panel4" onmouseover="this.style.overflow='visible'" onmouseout="this.style.overflow='hidden'">
<?php require 'panels/relations_panel.tpl'; ?>
</div>

<div class="panel2" id="panel5" onmouseover="this.style.overflow='visible'" onmouseout="this.style.overflow='hidden'">
<?php require 'panels/arrows_panel.tpl'; ?>
</div>

<div class="panel2" id="panel6" onmouseover="this.style.overflow='visible'" onmouseout="this.style.overflow='hidden'">
<?php require 'panels/accents_panel.tpl'; ?>
</div>

<div class="panel" id="panel7" onmouseover="this.style.overflow='visible'" onmouseout="this.style.overflow='hidden'">
<?php require 'panels/matrix_panel.tpl'; ?>
</div>

</div>   
</div>


<div id="toolbar_space" class="bottom"></div>
<textarea name="latex_formula" id="latex_formula" cols="80" rows="5" onkeyup="textchanged(); autorenderEqn(10)" onkeydown="countclik(this);"></textarea>
<div style="padding:3px;">
<select id="format" name="format" onchange="formatchanged();" title="Select the output format for the rendered equation">
  <option value="gif">gif</option>
  <option value="png">png</option>
  <option value="pdf">pdf</option>
  <option value="swf">swf</option>
</select>
<select id="dpi" name="dpi" onchange="textchanged();" title="Select the output resolution">
  <option value="100">100 dpi</option>
  <option value="200">200 dpi</option>
  <option value="300">300 dpi</option>
</select>
<select id="bg" name="bg" onchange="textchanged();" title="Background color">
  <option value="transparent">transparent</option>
  <option value="white">white</option>
  <option value="black">black</option>
  <option value="red">red</option>  
  <option value="green">green</option>  
  <option value="blue">blue</option>  
</select>
<input type="checkbox" id="inline" name="inline" title="<?php echo $lang['inline_title']; ?>" onchange="textchanged(); document.getElementById('compressed').checked=this.checked;" /> <label for="eqstyle"><?php echo $lang['inline']; ?></label>
<input type="checkbox" id="compressed" name="compressed" title="<?php echo $lang['compressed_title']; ?>" onchange="textchanged();"/> <label for="eqstyle2"><?php echo $lang['compressed']; ?></label>
</div>
<div>
<input type="button" class="lightbluebutton" onclick="cleartext()" value="<?php echo $lang['clear']; ?>" title="<?php echo $lang['clear_title']; ?>"/>
<input id="renderbutton" type="button" class="greybutton" onclick="renderEqn(null)" value="<?php echo $lang['render']; ?>" title="<?php echo $lang['render_title']; ?>" />
<input id="copybutton" type="button" class="greybutton" onclick="updateOpener(cctarget,cctype);document.getElementById('latex_formula').select();" value="<?php echo $lang['copy']; ?>" />
<input id="clipboardbutton" type="hidden" class="greybutton" onclick="copy(document.getElementById('latex_formula').value);" value="<?php echo $lang['copy_clipboard']; ?>" />
</div>

<div id="equationcomment"><strong><?php echo $lang['intro']; ?></strong></div>

<img id="equationview" name="equationview" />
<script type="text/javascript">
var el = document.getElementById('equationview');
el.onload = processEquationChange;
el.src = "images/spacer.gif";
</script>
</div>
<div align='center'><font color='red'>Nếu Click vào nút <b>Copy vào Bài viết</b> không được, bạn hãy đánh dấu chọn hết phần text trên và dán vào bài viết như Ví dụ: <b>[np]</b>Đoạn mã công thức toán<b>[/np]</b></div>
