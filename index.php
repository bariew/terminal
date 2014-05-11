<!DOCTYPE html>
<!--[if lt IE 7 ]><html lang="en" class="no-js ie6"><![endif]-->
<!--[if IE 7 ]><html dir="ltr" lang="ru" class="no-js ie7 index"><![endif]-->
<!--[if IE 8 ]><html dir="ltr" lang="ru" class="no-js ie8 index"><![endif]-->
<!--[if IE 9 ]><html dir="ltr" lang="ru" class="no-js ie9 index"><![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--><html dir="ltr" lang="ru" class="no-js index"><!--<![endif]-->
<style>
textarea {
    background-color: #000000;
    border: 2px solid #DDAA00;
    color: #AAAA77;
    float: left;
    font-family: monospace;
    font-size: 15px;
    height: 294px;
    width: 70%;
}
#content {
    height: 300px;
}
#results {
    float: left;
    height: 284px;
    margin: 3px 20px;
    padding: 5px 10px;
    width: 20%;
}
ul {
    margin: 0;
    padding: 0 0 0 50px;
}
form span{
    color: red;
    float: left;
    font-size: 10px;
}
#evalphp {
    float:left;
    width:100%;
}
.submit{
    float: left;
    height: 300px;
    width: 30px;
}
.footer{
    color: #000088;
    margin: 10px;
    width: 70%;
}
span.home {
    bottom: 0;
    margin: 20px;
    position: absolute;
    right: 0;
}
</style>
<?php
    $languages = array('php', 'c++', 'perl');
    $query = @$_POST['newPhp'];
    $lang = @$_POST['lang'];
function execute($lang, $string){
switch($lang){
case 'perl': exPerl($string);
break;
            case 'c++' : evalC($string);
                break;
default: exPhp($string);
}
}
function exPhp($string){
eval($string.';');
}
function exPerl($string){
$string = "perl -e '$string';";
exec($string,$output);
foreach($output as $line){
echo $line;
}
};

    function evalC($string)
    {
        $filePath = dirname(__FILE__).DIRECTORY_SEPARATOR.'eval.c';
        file_put_contents($filePath, $string);
exec("cint -E {$filePath} -c -p", $output);
foreach($output as $line){
            echo $line;
        }
    }

?>
<head>
<title>php-console</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
</head>
<body>
<div id="content">
<form name ="evalphp" id="evalphp" method="post" action="index.php"
onkeydown="if (event.ctrlKey && event.keyCode == 13) this.submit();">
<select name="lang">
<?php foreach($languages as $item): ?>
<option value="<?php echo $item; ?>" <?php echo ($item==$lang) ? 'selected="selected"' : ''; ?>>
<?php echo $item; ?>
</option>
<?php endforeach; ?>
</select>
<br />
<textarea name="newPhp"><?php echo $query; ?></textarea>
<input class="submit" type="submit" value=">" />
<div id="results">
<?php
                    $startTime = microtime();
                    execute($lang, $query);
                    $endTime = microtime();
                    $speed = array_sum(explode(' ', $endTime)) - array_sum(explode(' ', $startTime));
                ?>
</div>
<input type="hidden" name="speed" value="<?php echo $speed; ?>">
</form>
</div>
<div class="footer">
<div class="speed">
Speed<br />
<?php printf("%0.7f", $speed) ; ?> s.<br />
<?php if(isset($_POST['speed'])): ?>
<?php printf("%0.7f", $_POST['speed']); ?> s.(old)
<?php endif; ?>
</div>
</div>

<span class="home"><a href="/">HOME</a></span>
</body>

</html>
<script>
window.onload = document.forms['evalphp'].elements['newPhp'].focus();
</script>
