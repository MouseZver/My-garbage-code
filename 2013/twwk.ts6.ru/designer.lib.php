<?php
require ("header.php");
print "<input type=\"button\" onclick='location.href=\"./\"' value=\"На стартовую страницу\"><br>";
if($_SESSION['login'] && $_SESSION['password'] && $_SESSION['login'].'::'.$_SESSION['password'] != $admin['login'].'::'.$admin['password']){
print "<b>Добавление материалов в Онлайн конструктор модов</b><br>
<span style=\"color:red;\">Доступ в панель управления запрещен!</span><br>
Авторизуйтесь как администратор сервера!";
} elseif($_SESSION['login'].'::'.$_SESSION['password'] != $admin['login'].'::'.$admin['password']){
print 'Авторизуйтесь как администратор сервера:<br>
<input type="button" value="Представиться серверу" onclick="location.href=\'login.php\'">';
} else {
if (empty($_GET['step']))
{
error_reporting(0);
if($_SESSION['login'].'::'.$_SESSION['password'] == $admin['login'].'::'.$admin['password']){
$open_test_text = sqlite_fetch_single(sqlite_query($db, "SELECT F FROM db_twwk_configs WHERE id='1'"));
print "<b>Добавление материалов в Онлайн конструктор модов</b>
<form  style = \"margin-bottom:5px;\" action = \"designer.lib.php?\">
<input style = \"margin-top:5px;\" type = \"submit\" value = \"Дальше\"><br>
<input type = \"hidden\" name = \"step\" value = \"2\">
1.<input type = \"radio\" name = \"case\" value = \"1\" checked = \"checked\">Добавить материалы через архив JAR<br>
2.<input type = \"radio\" name = \"case\" value = \"2\">Выбор конструктора локации замок<br>
3.<input type = \"radio\" name = \"case\" value = \"3\">Выбор цвета скролинга<br>
4.<input type = \"radio\" name = \"case\" value = \"4\">Выбор цвета текста<br>
5.<input type = \"radio\" name = \"case\" value = \"5\">Добавить мод<br>
</form>
<table style=\"color:#00ffff;clear: both;border-bottom: 2px dashed lime;border-top: 2px dashed lime;border-left: 2px dashed lime;border-right: 2px dashed lime;padding-bottom: 3px;padding-top: 3px;padding-left: 3px;padding-right: 3px;\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\"><tr>
<td style=\"clear: both;border-bottom: 2px dashed lime;border-top: 2px dashed lime;border-left: 2px dashed lime;border-right: 2px dashed lime;padding-bottom: 3px;padding-top: 3px;padding-left: 3px;padding-right: 3px;\" width=\"30%\"><span style=\"color:red\">".(($open_server == 0 || $closed_server)?"Включить":"Выключить")."</span> сервер:<br>
<form  style = \"margin-bottom:5px;\" action = \"designer.lib.php?\">
<input style = \"margin-top:5px;\" type = \"submit\" value = \"Дальше\"><br>
<input type = \"hidden\" name = \"step\" value = \"2\">
".(($open_server == 0 || $closed_server)?"<input type = \"radio\" name = \"case\" value = \"on\" checked = \"checked\">Включить<br>":"<input type = \"radio\" name = \"case\" value = \"off\" checked = \"checked\">Выключить<br>")."
<input type=\"text\" name=\"open_test_text\" value = \"$open_test_text\"><br>
</form></td></tr>
<tr>
<td style=\"clear: both;border-bottom: 2px dashed lime;border-top: 2px dashed lime;border-left: 2px dashed lime;border-right: 2px dashed lime;padding-bottom: 3px;padding-top: 3px;padding-left: 3px;padding-right: 3px;\" width=\"30%\">
<input type=\"button\" onclick='location.href=\"designer.lib.php?step=user_list\"' value=\"Лист зарегестрировавшихся пользователей\">
</td></tr></table>";
}
}
if($_GET["open_test_text"]){
sqlite_query($db, "UPDATE db_twwk_configs SET F='".$_GET["open_test_text"]."' WHERE id='1'");
}
if (!empty($_GET['step'])){
switch ($_GET['step']){

case 'user_list' :
print "<hr style=\"clear: both;border: 2px dashed lime;\" width=\"100%\">\n";
$db_twwk_user = sqlite_query($db, "SELECT * FROM db_twwk_user");
while ($array = sqlite_fetch_array($db_twwk_user)) {
print 'ID'.$array['id'].': <input type="text" value="'.$array['nik'].'"><input type="text" value="'.$array['login'].'"><input type="text" value="'.$array['password'].'">';
print "<hr style=\"clear: both;border: 2px dashed lime;\" width=\"100%\">\n";
}
break;

case "2" :
if (isset($_GET['case'])){
switch ($_GET['case']){

case 'on' :
sqlite_query($db, "UPDATE db_twwk_configs SET G=1, H='' WHERE id='1'");
print "Конструктор включен!
<script language=\"JavaScript\">
function getgoing()
{
top.location=\"/designer.lib.php\";
}
setTimeout(\"getgoing()\",1);
</script>";
break;

case 'off' :
$time=time()+120;
sqlite_query($db, "UPDATE db_twwk_configs SET H='$time' WHERE id='1'");
print "Конструктор выключен!
<script language=\"JavaScript\">
function getgoing()
{
top.location=\"/designer.lib.php\";
}
setTimeout(\"getgoing()\",1);
</script>";
break;

case '1' :
print "<form enctype=\"multipart/form-data\" style = \"margin-bottom:5px;\" action = \"designer.lib.php?step=uploadsJar\" method=\"post\">
Добавление Архива с материалами Jar:<br>
<input name=\"jar\" type=\"file\"><br>
<input type=\"submit\" value=\"UPLOADIN\">
</form>";
break;

case '2' :
print "<form enctype=\"multipart/form-data\" Map = \"margin-bottom:5px;\" action = \"designer.lib.php?step=imagesMapMap\" method=\"post\">
Добавление конструктора локации замок:<br>
Скриншот стены (JPEG):<br>
<input name=\"scrin\" type=\"file\"><br>
Castle.map3:<br>
<input name=\"map3\" type=\"file\"><br>
b62:<br>
<input name=\"b62\" type=\"file\"><br>
b63:<br>
<input name=\"b63\" type=\"file\"><br>
b64:<br>
<input name=\"b64\" type=\"file\"><br>
b65:<br>
<input name=\"b65\" type=\"file\"><br>
b66:<br>
<input name=\"b66\" type=\"file\"><br>
b67:<br>
<input name=\"b67\" type=\"file\"><br>
b68:<br>
<input name=\"b68\" type=\"file\"><br>
b69:<br>
<input name=\"b69\" type=\"file\"><br>
b76:<br>
<input name=\"b76\" type=\"file\"><br>
b77:<br>
<input name=\"b77\" type=\"file\"><br>
b78:<br>
<input name=\"b78\" type=\"file\"><br>
b79:<br>
<input name=\"b79\" type=\"file\"><br>
b80:<br>
<input name=\"b80\" type=\"file\"><br>
b81:<br>
<input name=\"b81\" type=\"file\"><br>
b82:<br>
<input name=\"b82\" type=\"file\"><br>
b83:<br>
<input name=\"b83\" type=\"file\"><br>
<input type=\"submit\" value=\"UPLOADIN\">
</form>";
break;

case '3' :
print "<form enctype=\"multipart/form-data\" style = \"margin-bottom:5px;\" action = \"designer.lib.php?step=classci\" method=\"post\">
<img src=\"http://manifest-xacker.ucoz.com/site/images/skroling.png\"><br><br>
Добавление цвета скролинга (без решетки: #):<br>
Обводка скролинга (стандарт #220700):<br>
<input type=\"text\" name=\"name220700\"><br>
Сердцевина (стандарт #330000):<br>
<input type=\"text\" name=\"name330000\"><br>
Скролиг 3 (стандарт #380500):<br>
<input type=\"text\" name=\"name380500\"><br>
Скролиг 2 (стандарт #511E00):<br>
<input type=\"text\" name=\"name511E00\"><br>
Скролиг 1 (стандарт #663300):<br>
<input type=\"text\" name=\"name663300\"><br>
<input type=\"submit\" value=\"UPLOADIN\">
</form>";
break;

case '4' :
print "<form enctype=\"multipart/form-data\" style = \"margin-bottom:5px;\" action = \"designer.lib.php?step=txtcolor\" method=\"post\">
Название материала:<br>
<input type=\"text\" name=\"name\"><br>
Добавление цвета текста (без решетки: #):<br>
Цвет текста (стандарт #FFFFFF):<br>
<input type=\"text\" name=\"nameFFFFFF\"><br>
<input type=\"submit\" value=\"UPLOADIN\">
</form>";
break;

case '5' :
print "<form enctype=\"multipart/form-data\" style = \"margin-bottom:5px;\" action=\"./designer.lib.php?step=mods_list\" method=\"post\">
Название мода:<br>
<input name=\"name\" type=\"text\" maxlength=\"15\" style=\"width:350px;\" value=\"\"><br>
URL step=90:<br>
<input name=\"url_jar\" type=\"text\" style=\"width:350px;\" value=\"\"><br>
Android мод:<br>
<input name=\"url_android\" type=\"file\" style=\"width:350px;\"><br>
Изображение:<br>
<input name=\"img\" type=\"file\" style=\"width:350px;\"><br>
<input style=\"margin-top:5px;\" type=\"submit\" value=\"Добавить\"><br>
</form>";
break;

case '6' :
print "<form enctype=\"multipart/form-data\" style = \"margin-bottom:5px;\" action=\"./designer.lib.php?step=android_up\" method=\"post\">
<input type = \"hidden\" name = \"name\" value = \"".$_GET['name']."\">
<input type = \"hidden\" name = \"id\" value = \"".$_GET['id']."\">
Android мод:<br>
<input name=\"mod_apk\" type=\"file\" style=\"width:350px;\"><br>
<input style=\"margin-top:5px;\" type=\"submit\" value=\"Добавить\"><br>
</form>";
break;

}}
break;



case "uploadsJar" :
$directory = 'unjar_mods/2/';
//----------------------------------------------------
foreach(array_merge(glob("unjar_mods/2/*.png")) as $materials){
@unlink($materials);}
foreach(array_merge(glob("unjar_mods/2/Map/*.png")) as $materials){
@unlink($materials);}
foreach(array_merge(glob("unjar_mods/2/Style/*.png")) as $materials){
@unlink($materials);}
foreach(array_merge(glob("unjar_mods/2/Style/*/*.png")) as $materials){
@unlink($materials);}
//----------------------------------------------------
if(preg_match("/.jar/i", $_FILES['jar']['name'])){
move_uploaded_file($_FILES['jar']['tmp_name'], "temp_new/".$_FILES['jar']['name']);
require_once('pclzip.lib.php');
$name_jar = $_FILES['jar']['name'];
$jar = new PclZip("temp_new/".$name_jar);
$a = $jar->extract($directory);
@unlink ("temp_new/".$name_jar);
if(fopen("unjar_mods/2/ico.png","r") != ''){
sqlite_query($db, "UPDATE db_twwk_case SET A='".($cases[5]+1)."' WHERE id=5");
copy("unjar_mods/2/ico.png","ico/".$cases[5].".png");}
if(fopen("unjar_mods/2/Map/b1.png","r") != ''){
sqlite_query($db, "UPDATE db_twwk_case SET A='".($cases[18]+1)."' WHERE id=18");
copy("unjar_mods/2/Map/b1.png","Map/b1/".$cases[18].".png");}
if(fopen("unjar_mods/2/Map/b2.png","r") != ''){
sqlite_query($db, "UPDATE db_twwk_case SET A='".($cases[19]+1)."' WHERE id=19");
copy("unjar_mods/2/Map/b2.png","Map/b2/".$cases[19].".png");}
if(fopen("unjar_mods/2/Map/b3.png","r") != ''){
sqlite_query($db, "UPDATE db_twwk_case SET A='".($cases[20]+1)."' WHERE id=20");
copy("unjar_mods/2/Map/b3.png","Map/b3/".$cases[20].".png");}
if(fopen("unjar_mods/2/Map/b4.png","r") != ''){
sqlite_query($db, "UPDATE db_twwk_case SET A='".($cases[21]+1)."' WHERE id=21");
copy("unjar_mods/2/Map/b4.png","Map/b4/".$cases[21].".png");}
if(fopen("unjar_mods/2/Map/b5.png","r") != ''){
sqlite_query($db, "UPDATE db_twwk_case SET A='".($cases[22]+1)."' WHERE id=22");
copy("unjar_mods/2/Map/b5.png","Map/b5/".$cases[22].".png");}
if(fopen("unjar_mods/2/Map/b6.png","r") != ''){
sqlite_query($db, "UPDATE db_twwk_case SET A='".($cases[23]+1)."' WHERE id=23");
copy("unjar_mods/2/Map/b6.png","Map/b6/".$cases[23].".png");}
if(fopen("unjar_mods/2/Map/b7.png","r") != ''){
sqlite_query($db, "UPDATE db_twwk_case SET A='".($cases[24]+1)."' WHERE id=24");
copy("unjar_mods/2/Map/b7.png","Map/b7/".$cases[24].".png");}
if(fopen("unjar_mods/2/Map/b8.png","r") != ''){
sqlite_query($db, "UPDATE db_twwk_case SET A='".($cases[25]+1)."' WHERE id=25");
copy("unjar_mods/2/Map/b8.png","Map/b8/".$cases[25].".png");}
if(fopen("unjar_mods/2/Map/b9.png","r") != ''){
sqlite_query($db, "UPDATE db_twwk_case SET A='".($cases[26]+1)."' WHERE id=26");
copy("unjar_mods/2/Map/b9.png","Map/b9/".$cases[26].".png");}
if(fopen("unjar_mods/2/Map/b10.png","r") != ''){
sqlite_query($db, "UPDATE db_twwk_case SET A='".($cases[27]+1)."' WHERE id=27");
copy("unjar_mods/2/Map/b10.png","Map/b10/".$cases[27].".png");}
if(fopen("unjar_mods/2/Map/b11.png","r") != ''){
sqlite_query($db, "UPDATE db_twwk_case SET A='".($cases[28]+1)."' WHERE id=28");
copy("unjar_mods/2/Map/b11.png","Map/b11/".$cases[28].".png");}
if(fopen("unjar_mods/2/Map/b12.png","r") != ''){
sqlite_query($db, "UPDATE db_twwk_case SET A='".($cases[29]+1)."' WHERE id=29");
copy("unjar_mods/2/Map/b12.png","Map/b12/".$cases[29].".png");}
if(fopen("unjar_mods/2/Map/b13.png","r") != ''){
sqlite_query($db, "UPDATE db_twwk_case SET A='".($cases[30]+1)."' WHERE id=30");
copy("unjar_mods/2/Map/b13.png","Map/b13/".$cases[30].".png");}
if(fopen("unjar_mods/2/Map/b14.png","r") != ''){
sqlite_query($db, "UPDATE db_twwk_case SET A='".($cases[31]+1)."' WHERE id=31");
copy("unjar_mods/2/Map/b14.png","Map/b14/".$cases[31].".png");}
if(fopen("unjar_mods/2/Map/b15.png","r") != ''){
sqlite_query($db, "UPDATE db_twwk_case SET A='".($cases[32]+1)."' WHERE id=32");
copy("unjar_mods/2/Map/b15.png","Map/b15/".$cases[32].".png");}
if(fopen("unjar_mods/2/Map/b16.png","r") != ''){
sqlite_query($db, "UPDATE db_twwk_case SET A='".($cases[33]+1)."' WHERE id=33");
copy("unjar_mods/2/Map/b16.png","Map/b16/".$cases[33].".png");}
if(fopen("unjar_mods/2/Map/b17.png","r") != ''){
sqlite_query($db, "UPDATE db_twwk_case SET A='".($cases[34]+1)."' WHERE id=34");
copy("unjar_mods/2/Map/b17.png","Map/b17/".$cases[34].".png");}
if(fopen("unjar_mods/2/Map/b18.png","r") != ''){
sqlite_query($db, "UPDATE db_twwk_case SET A='".($cases[35]+1)."' WHERE id=35");
copy("unjar_mods/2/Map/b18.png","Map/b18/".$cases[35].".png");}
if(fopen("unjar_mods/2/Map/b19.png","r") != ''){
sqlite_query($db, "UPDATE db_twwk_case SET A='".($cases[36]+1)."' WHERE id=36");
copy("unjar_mods/2/Map/b19.png","Map/b19/".$cases[36].".png");}
if(fopen("unjar_mods/2/Map/b20.png","r") != ''){
sqlite_query($db, "UPDATE db_twwk_case SET A='".($cases[37]+1)."' WHERE id=37");
copy("unjar_mods/2/Map/b20.png","Map/b20/".$cases[37].".png");}
if(fopen("unjar_mods/2/Map/b21.png","r") != ''){
sqlite_query($db, "UPDATE db_twwk_case SET A='".($cases[38]+1)."' WHERE id=38");
copy("unjar_mods/2/Map/b21.png","Map/b21/".$cases[38].".png");}
if(fopen("unjar_mods/2/Map/b22.png","r") != ''){
sqlite_query($db, "UPDATE db_twwk_case SET A='".($cases[39]+1)."' WHERE id=39");
copy("unjar_mods/2/Map/b22.png","Map/b22/".$cases[39].".png");}
if(fopen("unjar_mods/2/Map/b23.png","r") != ''){
sqlite_query($db, "UPDATE db_twwk_case SET A='".($cases[40]+1)."' WHERE id=40");
copy("unjar_mods/2/Map/b23.png","Map/b23/".$cases[40].".png");}
if(fopen("unjar_mods/2/Map/b24.png","r") != ''){
sqlite_query($db, "UPDATE db_twwk_case SET A='".($cases[41]+1)."' WHERE id=41");
copy("unjar_mods/2/Map/b24.png","Map/b24/".$cases[41].".png");}
if(fopen("unjar_mods/2/Map/b25.png","r") != ''){
sqlite_query($db, "UPDATE db_twwk_case SET A='".($cases[42]+1)."' WHERE id=42");
copy("unjar_mods/2/Map/b25.png","Map/b25/".$cases[42].".png");}
if(fopen("unjar_mods/2/Map/b26.png","r") != ''){
sqlite_query($db, "UPDATE db_twwk_case SET A='".($cases[43]+1)."' WHERE id=43");
copy("unjar_mods/2/Map/b26.png","Map/b26/".$cases[43].".png");}
if(fopen("unjar_mods/2/Map/b27.png","r") != ''){
sqlite_query($db, "UPDATE db_twwk_case SET A='".($cases[44]+1)."' WHERE id=44");
copy("unjar_mods/2/Map/b27.png","Map/b27/".$cases[44].".png");}
if(fopen("unjar_mods/2/Map/b28.png","r") != ''){
sqlite_query($db, "UPDATE db_twwk_case SET A='".($cases[45]+1)."' WHERE id=45");
copy("unjar_mods/2/Map/b28.png","Map/b28/".$cases[45].".png");}
if(fopen("unjar_mods/2/Map/b29.png","r") != ''){
sqlite_query($db, "UPDATE db_twwk_case SET A='".($cases[46]+1)."' WHERE id=46");
copy("unjar_mods/2/Map/b29.png","Map/b29/".$cases[46].".png");}
if(fopen("unjar_mods/2/Map/b30.png","r") != ''){
sqlite_query($db, "UPDATE db_twwk_case SET A='".($cases[47]+1)."' WHERE id=47");
copy("unjar_mods/2/Map/b30.png","Map/b30/".$cases[47].".png");}
if(fopen("unjar_mods/2/Map/b31.png","r") != ''){
sqlite_query($db, "UPDATE db_twwk_case SET A='".($cases[48]+1)."' WHERE id=48");
copy("unjar_mods/2/Map/b31.png","Map/b31/".$cases[48].".png");}
if(fopen("unjar_mods/2/Map/b32.png","r") != ''){
sqlite_query($db, "UPDATE db_twwk_case SET A='".($cases[49]+1)."' WHERE id=49");
copy("unjar_mods/2/Map/b32.png","Map/b32/".$cases[49].".png");}
if(fopen("unjar_mods/2/Map/b33.png","r") != ''){
sqlite_query($db, "UPDATE db_twwk_case SET A='".($cases[50]+1)."' WHERE id=50");
copy("unjar_mods/2/Map/b33.png","Map/b33/".$cases[50].".png");}
if(fopen("unjar_mods/2/Map/b34.png","r") != ''){
sqlite_query($db, "UPDATE db_twwk_case SET A='".($cases[51]+1)."' WHERE id=51");
copy("unjar_mods/2/Map/b34.png","Map/b34/".$cases[51].".png");}
if(fopen("unjar_mods/2/Map/b35.png","r") != ''){
sqlite_query($db, "UPDATE db_twwk_case SET A='".($cases[52]+1)."' WHERE id=52");
copy("unjar_mods/2/Map/b35.png","Map/b35/".$cases[52].".png");}
if(fopen("unjar_mods/2/Map/b36.png","r") != ''){
sqlite_query($db, "UPDATE db_twwk_case SET A='".($cases[53]+1)."' WHERE id=53");
copy("unjar_mods/2/Map/b36.png","Map/b36/".$cases[53].".png");}
if(fopen("unjar_mods/2/Map/b37.png","r") != ''){
sqlite_query($db, "UPDATE db_twwk_case SET A='".($cases[54]+1)."' WHERE id=54");
copy("unjar_mods/2/Map/b37.png","Map/b37/".$cases[54].".png");}
if(fopen("unjar_mods/2/Map/b38.png","r") != ''){
sqlite_query($db, "UPDATE db_twwk_case SET A='".($cases[55]+1)."' WHERE id=55");
copy("unjar_mods/2/Map/b38.png","Map/b38/".$cases[55].".png");}
if(fopen("unjar_mods/2/Map/b39.png","r") != ''){
sqlite_query($db, "UPDATE db_twwk_case SET A='".($cases[56]+1)."' WHERE id=56");
copy("unjar_mods/2/Map/b39.png","Map/b39/".$cases[56].".png");}
if(fopen("unjar_mods/2/Map/b40.png","r") != ''){
sqlite_query($db, "UPDATE db_twwk_case SET A='".($cases[57]+1)."' WHERE id=57");
copy("unjar_mods/2/Map/b40.png","Map/b40/".$cases[57].".png");}
if(fopen("unjar_mods/2/Map/b41.png","r") != ''){
sqlite_query($db, "UPDATE db_twwk_case SET A='".($cases[58]+1)."' WHERE id=58");
copy("unjar_mods/2/Map/b41.png","Map/b41/".$cases[58].".png");}
if(fopen("unjar_mods/2/Map/b42.png","r") != ''){
sqlite_query($db, "UPDATE db_twwk_case SET A='".($cases[59]+1)."' WHERE id=59");
copy("unjar_mods/2/Map/b42.png","Map/b42/".$cases[59].".png");}
if(fopen("unjar_mods/2/Map/b43.png","r") != ''){
sqlite_query($db, "UPDATE db_twwk_case SET A='".($cases[60]+1)."' WHERE id=60");
copy("unjar_mods/2/Map/b43.png","Map/b43/".$cases[60].".png");}
if(fopen("unjar_mods/2/Map/b44.png","r") != ''){
sqlite_query($db, "UPDATE db_twwk_case SET A='".($cases[61]+1)."' WHERE id=61");
copy("unjar_mods/2/Map/b44.png","Map/b44/".$cases[61].".png");}
if(fopen("unjar_mods/2/Map/b45.png","r") != ''){
sqlite_query($db, "UPDATE db_twwk_case SET A='".($cases[62]+1)."' WHERE id=62");
copy("unjar_mods/2/Map/b45.png","Map/b45/".$cases[62].".png");}
if(fopen("unjar_mods/2/Map/b46.png","r") != ''){
sqlite_query($db, "UPDATE db_twwk_case SET A='".($cases[63]+1)."' WHERE id=63");
copy("unjar_mods/2/Map/b46.png","Map/b46/".$cases[63].".png");}
if(fopen("unjar_mods/2/Map/b47.png","r") && fopen("unjar_mods/2/Map/b48.png","r")){
sqlite_query($db, "UPDATE db_twwk_case SET A='".($cases[64]+1)."' WHERE id=64");
copy("unjar_mods/2/Map/b47.png","Map/b47/".$cases[64].".png");
copy("unjar_mods/2/Map/b48.png","Map/b48/".$cases[64].".png");}
if(fopen("unjar_mods/2/Map/b50.png","r") && fopen("unjar_mods/2/Map/b51.png","r")){
sqlite_query($db, "UPDATE db_twwk_case SET A='".($cases[65]+1)."' WHERE id=65");
copy("unjar_mods/2/Map/b50.png","Map/b50/".$cases[65].".png");
copy("unjar_mods/2/Map/b51.png","Map/b51/".$cases[65].".png");}
if(fopen("unjar_mods/2/Map/b52.png","r") && fopen("unjar_mods/2/Map/b54.png","r") && fopen("unjar_mods/2/Map/b55.png","r")){
sqlite_query($db, "UPDATE db_twwk_case SET A='".($cases[66]+1)."' WHERE id=66");
copy("unjar_mods/2/Map/b52.png","Map/b52/".$cases[66].".png");
copy("unjar_mods/2/Map/b54.png","Map/b54/".$cases[66].".png");
copy("unjar_mods/2/Map/b55.png","Map/b55/".$cases[66].".png");}
if(fopen("unjar_mods/2/Map/b53.png","r") != ''){
sqlite_query($db, "UPDATE db_twwk_case SET A='".($cases[67]+1)."' WHERE id=67");
copy("unjar_mods/2/Map/b53.png","Map/b53/".$cases[67].".png");}
if(fopen("unjar_mods/2/Map/b56.png","r") != ''){
sqlite_query($db, "UPDATE db_twwk_case SET A='".($cases[68]+1)."' WHERE id=68");
copy("unjar_mods/2/Map/b56.png","Map/b56/".$cases[68].".png");}
if(fopen("unjar_mods/2/Map/b57.png","r") != ''){
sqlite_query($db, "UPDATE db_twwk_case SET A='".($cases[69]+1)."' WHERE id=69");
copy("unjar_mods/2/Map/b57.png","Map/b57/".$cases[69].".png");}
if(fopen("unjar_mods/2/Map/b58.png","r") != ''){
sqlite_query($db, "UPDATE db_twwk_case SET A='".($cases[70]+1)."' WHERE id=70");
copy("unjar_mods/2/Map/b58.png","Map/b58/".$cases[70].".png");}
if(fopen("unjar_mods/2/Map/b59.png","r") != ''){
sqlite_query($db, "UPDATE db_twwk_case SET A='".($cases[71]+1)."' WHERE id=71");
copy("unjar_mods/2/Map/b59.png","Map/b59/".$cases[71].".png");}
if(fopen("unjar_mods/2/Map/b70.png","r") && fopen("unjar_mods/2/Map/b71.png","r") && fopen("unjar_mods/2/Map/b72.png","r") && fopen("unjar_mods/2/Map/b73.png","r")){
sqlite_query($db, "UPDATE db_twwk_case SET A='".($cases[72]+1)."' WHERE id=72");
copy("unjar_mods/2/Map/b70.png","Map/b70/".$cases[72].".png");
copy("unjar_mods/2/Map/b71.png","Map/b71/".$cases[72].".png");
copy("unjar_mods/2/Map/b72.png","Map/b72/".$cases[72].".png");
copy("unjar_mods/2/Map/b73.png","Map/b73/".$cases[72].".png");}
if(fopen("unjar_mods/2/Map/b74.png","r") != ''){
sqlite_query($db, "UPDATE db_twwk_case SET A='".($cases[73]+1)."' WHERE id=73");
copy("unjar_mods/2/Map/b74.png","Map/b74/".$cases[73].".png");}
if(fopen("unjar_mods/2/Map/b75.png","r") != ''){
sqlite_query($db, "UPDATE db_twwk_case SET A='".($cases[74]+1)."' WHERE id=74");
copy("unjar_mods/2/Map/b75.png","Map/b75/".$cases[74].".png");}
if(fopen("unjar_mods/2/Map/bX.png","r") && fopen("unjar_mods/2/Map/bX2.png","r")){
sqlite_query($db, "UPDATE db_twwk_case SET A='".($cases[76]+1)."' WHERE id=76");
copy("unjar_mods/2/Map/bX.png","Map/bX/".$cases[76].".png");
copy("unjar_mods/2/Map/bX2.png","Map/bX2/".$cases[76].".png");}
if(fopen("unjar_mods/2/Map/bXX.png","r") && fopen("unjar_mods/2/Map/bXXX.png","r")){
sqlite_query($db, "UPDATE db_twwk_case SET A='".($cases[77]+1)."' WHERE id=77");
copy("unjar_mods/2/Map/bXX.png","Map/bXX/".$cases[77].".png");
copy("unjar_mods/2/Map/bXXX.png","Map/bXXX/".$cases[77].".png");}
if(fopen("unjar_mods/2/Map/bXXXX.png","r") != ''){
sqlite_query($db, "UPDATE db_twwk_case SET A='".($cases[78]+1)."' WHERE id=78");
copy("unjar_mods/2/Map/bXXXX.png","Map/bXXXX/".$cases[78].".png");}
if(fopen("unjar_mods/2/Map/mb0.png","r") != ''){
sqlite_query($db, "UPDATE db_twwk_case SET A='".($cases[79]+1)."' WHERE id=79");
copy("unjar_mods/2/Map/mb0.png","Map/mb0/".$cases[79].".png");}
if(fopen("unjar_mods/2/Map/mb1.png","r") != ''){
sqlite_query($db, "UPDATE db_twwk_case SET A='".($cases[80]+1)."' WHERE id=80");
copy("unjar_mods/2/Map/mb1.png","Map/mb1/".$cases[80].".png");}
if(fopen("unjar_mods/2/Map/mb2.png","r") != ''){
sqlite_query($db, "UPDATE db_twwk_case SET A='".($cases[81]+1)."' WHERE id=81");
copy("unjar_mods/2/Map/mb2.png","Map/mb2/".$cases[81].".png");}
if(fopen("unjar_mods/2/Map/mb3.png","r") != ''){
sqlite_query($db, "UPDATE db_twwk_case SET A='".($cases[82]+1)."' WHERE id=82");
copy("unjar_mods/2/Map/mb3.png","Map/mb3/".$cases[82].".png");}
if(fopen("unjar_mods/2/Map/Progress0.png","r") && fopen("unjar_mods/2/Map/Progress1.png","r") && fopen("unjar_mods/2/Map/Progress2.png","r")){
sqlite_query($db, "UPDATE db_twwk_case SET A='".($cases[83]+1)."' WHERE id=83");
copy("unjar_mods/2/Map/Progress0.png","Map/Progress0/".$cases[83].".png");
copy("unjar_mods/2/Map/Progress1.png","Map/Progress1/".$cases[83].".png");
copy("unjar_mods/2/Map/Progress2.png","Map/Progress2/".$cases[83].".png");}
if(fopen("unjar_mods/2/Style/Button/a1.png","r") && fopen("unjar_mods/2/Style/Button/a2.png","r") && fopen("unjar_mods/2/Style/Button/a3.png","r")){
sqlite_query($db, "UPDATE db_twwk_case SET A='".($cases[6]+1)."' WHERE id=6");
copy("unjar_mods/2/Style/Button/a1.png","Style/Button/a1/".$cases[6].".png");
copy("unjar_mods/2/Style/Button/a2.png","Style/Button/a2/".$cases[6].".png");
copy("unjar_mods/2/Style/Button/a3.png","Style/Button/a3/".$cases[6].".png");}
if(fopen("unjar_mods/2/Style/CheckBox/a1.png","r") && fopen("unjar_mods/2/Style/CheckBox/a2.png","r") && fopen("unjar_mods/2/Style/CheckBox/a3.png","r")){
sqlite_query($db, "UPDATE db_twwk_case SET A='".($cases[7]+1)."' WHERE id=7");
copy("unjar_mods/2/Style/CheckBox/a1.png","Style/CheckBox/a1/".$cases[7].".png");
copy("unjar_mods/2/Style/CheckBox/a2.png","Style/CheckBox/a2/".$cases[7].".png");
copy("unjar_mods/2/Style/CheckBox/a3.png","Style/CheckBox/a3/".$cases[7].".png");}
if(fopen("unjar_mods/2/Style/CheckBox/a4.png","r")){
sqlite_query($db, "UPDATE db_twwk_case SET A='".($cases[8]+1)."' WHERE id=8");
copy("unjar_mods/2/Style/CheckBox/a4.png","Style/CheckBox/a4/".$cases[8].".png");}
if(fopen("unjar_mods/2/Style/CheckBox/a5.png","r")){
sqlite_query($db, "UPDATE db_twwk_case SET A='".($cases[9]+1)."' WHERE id=9");
copy("unjar_mods/2/Style/CheckBox/a5.png","Style/CheckBox/a5/".$cases[9].".png");}
if(fopen("unjar_mods/2/Style/ComboBox/a1.png","r") && fopen("unjar_mods/2/Style/ComboBox/a2.png","r")){
sqlite_query($db, "UPDATE db_twwk_case SET A='".($cases[10]+1)."' WHERE id=10");
copy("unjar_mods/2/Style/ComboBox/a1.png","Style/ComboBox/a1/".$cases[10].".png");
copy("unjar_mods/2/Style/ComboBox/a2.png","Style/ComboBox/a2/".$cases[10].".png");}
if(fopen("unjar_mods/2/Style/Gears/a1.png","r") && fopen("unjar_mods/2/Style/Gears/a2.png","r")){
sqlite_query($db, "UPDATE db_twwk_case SET A='".($cases[11]+1)."' WHERE id=11");
copy("unjar_mods/2/Style/Gears/a1.png","Style/Gears/a1/".$cases[11].".png");
copy("unjar_mods/2/Style/Gears/a2.png","Style/Gears/a2/".$cases[11].".png");}
if(fopen("unjar_mods/2/Style/ListBox/a1.png","r") && fopen("unjar_mods/2/Style/ListBox/a2.png","r") && fopen("unjar_mods/2/Style/ListBox/a3.png","r")){
sqlite_query($db, "UPDATE db_twwk_case SET A='".($cases[12]+1)."' WHERE id=12");
copy("unjar_mods/2/Style/ListBox/a1.png","Style/ListBox/a1/".$cases[12].".png");
copy("unjar_mods/2/Style/ListBox/a2.png","Style/ListBox/a2/".$cases[12].".png");
copy("unjar_mods/2/Style/ListBox/a3.png","Style/ListBox/a3/".$cases[12].".png");}
if(fopen("unjar_mods/2/Style/Menu/Bottom.png","r") && fopen("unjar_mods/2/Style/Menu/BottomX.png","r") && fopen("unjar_mods/2/Style/Menu/Top.png","r") && fopen("unjar_mods/2/Style/Menu/TopX.png","r")){
sqlite_query($db, "UPDATE db_twwk_case SET A='".($cases[13]+1)."' WHERE id=13");
copy("unjar_mods/2/Style/Menu/Bottom.png","Style/Menu/Bottom/".$cases[13].".png");
copy("unjar_mods/2/Style/Menu/BottomX.png","Style/Menu/BottomX/".$cases[13].".png");
copy("unjar_mods/2/Style/Menu/Top.png","Style/Menu/Top/".$cases[13].".png");
copy("unjar_mods/2/Style/Menu/TopX.png","Style/Menu/TopX/".$cases[13].".png");}
if(fopen("unjar_mods/2/Style/ProgressBar/a0.png","r")){
sqlite_query($db, "UPDATE db_twwk_case SET A='".($cases[14]+1)."' WHERE id=14");
copy("unjar_mods/2/Style/ProgressBar/a0.png","Style/ProgressBar/a0/".$cases[14].".png");}
if(fopen("unjar_mods/2/Style/ProgressBar/a1.png","r")){
sqlite_query($db, "UPDATE db_twwk_case SET A='".($cases[15]+1)."' WHERE id=15");
copy("unjar_mods/2/Style/ProgressBar/a1.png","Style/ProgressBar/a1/".$cases[15].".png");}
if(fopen("unjar_mods/2/Style/RunningLine/a0.png","r") && fopen("unjar_mods/2/Style/RunningLine/a1.png","r") && fopen("unjar_mods/2/Style/RunningLine/a2.png","r")){
sqlite_query($db, "UPDATE db_twwk_case SET A='".($cases[16]+1)."' WHERE id=16");
copy("unjar_mods/2/Style/RunningLine/a0.png","Style/RunningLine/a0/".$cases[16].".png");
copy("unjar_mods/2/Style/RunningLine/a1.png","Style/RunningLine/a1/".$cases[16].".png");
copy("unjar_mods/2/Style/RunningLine/a2.png","Style/RunningLine/a2/".$cases[16].".png");}
if(fopen("unjar_mods/2/Style/TextBox/a1.png","r") && fopen("unjar_mods/2/Style/TextBox/a2.png","r")){
sqlite_query($db, "UPDATE db_twwk_case SET A='".($cases[17]+1)."' WHERE id=17");
copy("unjar_mods/2/Style/TextBox/a1.png","Style/TextBox/a1/".$cases[17].".png");
copy("unjar_mods/2/Style/TextBox/a2.png","Style/TextBox/a2/".$cases[17].".png");}
if(fopen("unjar_mods/2/Style/delim.png","r")){
sqlite_query($db, "UPDATE db_twwk_case SET A='".($cases[2]+1)."' WHERE id=2");
copy("unjar_mods/2/Style/delim.png","Style/delim/".$cases[AAAAA].".png");}
if(fopen("unjar_mods/2/Style/Menu.png","r")){
sqlite_query($db, "UPDATE db_twwk_case SET A='".($cases[4]+1)."' WHERE id=4");
copy("unjar_mods/2/Style/Menu.png","Style/Menu/".$cases[4].".png");}
if(fopen("unjar_mods/2/Style/Window.png","r")){
sqlite_query($db, "UPDATE db_twwk_case SET A='".($cases[84]+1)."' WHERE id=84");
copy("unjar_mods/2/Style/Window.png","Style/Window/".$cases[84].".png");}
if(fopen("unjar_mods/2/Style/WindowLogo.png","r")){
sqlite_query($db, "UPDATE db_twwk_case SET A='".($cases[3]+1)."' WHERE id=3");
copy("unjar_mods/2/Style/WindowLogo.png","Style/WindowLogo/".$cases[3].".png");}
if(fopen("unjar_mods/2/Style/logo.png","r")){
sqlite_query($db, "UPDATE db_twwk_case SET A='".($cases[1]+1)."' WHERE id=1");
$img=imageCreateFromPNG("unjar_mods/2/Style/logo.png");
$w=ImageSX($img);
$h=ImageSY($img);
$maxW=100;
$maxH=100;
$ratioWidth = $w/$maxW;
$ratioHeight = $h/$maxH;
if ($ratioWidth < $ratioHeight){
$destW = $w/$ratioHeight;
$destH = $maxH;
} else {
$destW = $maxW;
$destH = $h/$ratioWidth;
}
$destImage = imagecreate($destW, $destH);
ImageCopyResized($destImage, $img, 0, 0, 0, 0, $destW, $destH, $w, $h);
imagepng($destImage, "images/Style/logo/".$cases[1].".png", 9, PNG_ALL_FILTERS);
copy("unjar_mods/2/Style/logo.png","Style/logo/".$cases[1].".png");}
print "Файлы успешно загружены!<br>";
foreach(array_merge(glob("unjar_mods/2/*.png")) as $materials){
print "<img src=\"$materials\">";}
foreach(array_merge(glob("unjar_mods/2/Map/*.png")) as $materials){
print "<img src=\"$materials\">";}
foreach(array_merge(glob("unjar_mods/2/Style/*.png")) as $materials){
print "<img src=\"$materials\">";}
foreach(array_merge(glob("unjar_mods/2/Style/*/*.png")) as $materials){
print "<img src=\"$materials\">";}
}
break;

case "imagesMapMap" :
sqlite_query($db, "UPDATE db_twwk_case SET A='".($cases[75]+1)."' WHERE id=75");
move_uploaded_file($_FILES['scrin']['tmp_name'], 'temp_new/1/'.$_FILES['scrin']['name']);
move_uploaded_file($_FILES['map3']['tmp_name'], 'temp_new/2/'.$_FILES['map3']['name']);
move_uploaded_file($_FILES['b62']['tmp_name'], 'temp_new/3/'.$_FILES['b62']['name']);
move_uploaded_file($_FILES['b63']['tmp_name'], 'temp_new/4/'.$_FILES['b63']['name']);
move_uploaded_file($_FILES['b64']['tmp_name'], 'temp_new/5/'.$_FILES['b64']['name']);
move_uploaded_file($_FILES['b65']['tmp_name'], 'temp_new/6/'.$_FILES['b65']['name']);
move_uploaded_file($_FILES['b66']['tmp_name'], 'temp_new/7/'.$_FILES['b66']['name']);
move_uploaded_file($_FILES['b67']['tmp_name'], 'temp_new/8/'.$_FILES['b67']['name']);
move_uploaded_file($_FILES['b68']['tmp_name'], 'temp_new/9/'.$_FILES['b68']['name']);
move_uploaded_file($_FILES['b69']['tmp_name'], 'temp_new/10/'.$_FILES['b69']['name']);
move_uploaded_file($_FILES['b76']['tmp_name'], 'temp_new/11/'.$_FILES['b76']['name']);
move_uploaded_file($_FILES['b77']['tmp_name'], 'temp_new/12/'.$_FILES['b77']['name']);
move_uploaded_file($_FILES['b78']['tmp_name'], 'temp_new/13/'.$_FILES['b78']['name']);
move_uploaded_file($_FILES['b79']['tmp_name'], 'temp_new/14/'.$_FILES['b79']['name']);
move_uploaded_file($_FILES['b80']['tmp_name'], 'temp_new/15/'.$_FILES['b80']['name']);
move_uploaded_file($_FILES['b81']['tmp_name'], 'temp_new/16/'.$_FILES['b81']['name']);
move_uploaded_file($_FILES['b82']['tmp_name'], 'temp_new/17/'.$_FILES['b82']['name']);
move_uploaded_file($_FILES['b83']['tmp_name'], 'temp_new/18/'.$_FILES['b83']['name']);
copy('temp_new/1/'.$_FILES['scrin']['name'],'images/Map/Map/'.$case77.'.jpg');
copy('temp_new/2/'.$_FILES['map3']['name'],'Map/Map/Castle/'.$case77.'.map3');
copy('temp_new/3/'.$_FILES['b62']['name'],'Map/b62/'.$case77.'.png');
copy('temp_new/4/'.$_FILES['b63']['name'],'Map/b63/'.$case77.'.png');
copy('temp_new/5/'.$_FILES['b64']['name'],'Map/b64/'.$case77.'.png');
copy('temp_new/6/'.$_FILES['b65']['name'],'Map/b65/'.$case77.'.png');
copy('temp_new/7/'.$_FILES['b66']['name'],'Map/b66/'.$case77.'.png');
copy('temp_new/8/'.$_FILES['b67']['name'],'Map/b67/'.$case77.'.png');
copy('temp_new/9/'.$_FILES['b68']['name'],'Map/b68/'.$case77.'.png');
copy('temp_new/10/'.$_FILES['b69']['name'],'Map/b69/'.$case77.'.png');
copy('temp_new/11/'.$_FILES['b76']['name'],'Map/b76/'.$case77.'.png');
copy('temp_new/12/'.$_FILES['b77']['name'],'Map/b77/'.$case77.'.png');
copy('temp_new/13/'.$_FILES['b78']['name'],'Map/b78/'.$case77.'.png');
copy('temp_new/14/'.$_FILES['b79']['name'],'Map/b79/'.$case77.'.png');
copy('temp_new/15/'.$_FILES['b80']['name'],'Map/b80/'.$case77.'.png');
copy('temp_new/16/'.$_FILES['b81']['name'],'Map/b81/'.$case77.'.png');
copy('temp_new/17/'.$_FILES['b82']['name'],'Map/b82/'.$case77.'.png');
copy('temp_new/18/'.$_FILES['b83']['name'],'Map/b83/'.$case77.'.png');
unlink ('temp_new/1/'.$_FILES['scrin']['name']);
unlink ('temp_new/2/'.$_FILES['map3']['name']);
unlink ('temp_new/3/'.$_FILES['b62']['name']);
unlink ('temp_new/4/'.$_FILES['b63']['name']);
unlink ('temp_new/5/'.$_FILES['b64']['name']);
unlink ('temp_new/6/'.$_FILES['b65']['name']);
unlink ('temp_new/7/'.$_FILES['b66']['name']);
unlink ('temp_new/8/'.$_FILES['b67']['name']);
unlink ('temp_new/9/'.$_FILES['b68']['name']);
unlink ('temp_new/10/'.$_FILES['b69']['name']);
unlink ('temp_new/11/'.$_FILES['b76']['name']);
unlink ('temp_new/12/'.$_FILES['b77']['name']);
unlink ('temp_new/13/'.$_FILES['b78']['name']);
unlink ('temp_new/14/'.$_FILES['b79']['name']);
unlink ('temp_new/15/'.$_FILES['b80']['name']);
unlink ('temp_new/16/'.$_FILES['b81']['name']);
unlink ('temp_new/17/'.$_FILES['b82']['name']);
unlink ('temp_new/18/'.$_FILES['b83']['name']);
print "Файл успешно загружен!<br>\n
Просмотреть: <a target=\"_blank\" href=\"http://".$_SERVER["SERVER_NAME"]."/?step=77\">step=77</a>";
break;

case "classci" :
sqlite_query($db, "UPDATE db_twwk_case SET A='".($cases[85]+1)."' WHERE id=85");
$hexrename = file_get_contents('case/lib/hexcolor.php');
$uphex = fopen('case/lib/hexcolor.php','w');
$uphex2 = str_replace ("break;}}", "break;", $hexrename);
fwrite($uphex,''.$uphex2.'
case \''.$case88.'\' :
$hexcolor1 = "'.$_POST['name220700'].'";
$hexcolor2 = "'.$_POST['name330000'].'";
$hexcolor3 = "'.$_POST['name380500'].'";
$hexcolor4 = "'.$_POST['name511E00'].'";
$hexcolor5 = "'.$_POST['name663300'].'";
break;}}');
fclose($uphex);
print "Файл успешно загружен!<br>
Просмотреть: <a target=\"_blank\" href=\"http://".$_SERVER["SERVER_NAME"]."/?step=88\">step=88</a>";
break;

case "txtcolor" :
sqlite_query($db, "UPDATE db_twwk_case SET A='".($cases[86]+1)."' WHERE id=86");
$hexrename = file_get_contents('case/lib/txt_color.php');
$uphex = fopen('case/lib/txt_color.php','w');
$uphex2 = str_replace ("break;}}", "break;", $hexrename);
fwrite($uphex,''.$uphex2.'
case \''.$txt_color.'\' :
$txt_color = "'.$_POST['nameFFFFFF'].'";
break;}}');
fclose($uphex);
print "Файл успешно загружен!<br>
Просмотреть: <a target=\"_blank\" href=\"http://".$_SERVER["SERVER_NAME"]."/?step=89\">step=89</a>";
break;

case "mods_list" :
sqlite_query($db, "INSERT INTO db_twwk_elect_mods('id', 'name', 'url_jar', 'url_android') VALUES ((SELECT COUNT(*)+1 FROM db_twwk_elect_mods), '".$_POST['name']."', '".$_POST['url_jar']."', '".$_FILES['url_android']['name']."')");
move_uploaded_file($_FILES['img']['tmp_name'], $_SERVER['DOCUMENT_ROOT']."/images/all/".sqlite_fetch_single(sqlite_query($db, "SELECT COUNT(*)+1 FROM db_twwk_elect_mods")).".jpg");
move_uploaded_file($_FILES['url_android']['tmp_name'], $_SERVER['DOCUMENT_ROOT']."/Androids_elect/".$_FILES['url_android']['name']);
print "Мод сохранен.
<script language=\"JavaScript\">
function getgoing()
{
top.location=\"./designer.lib.php?step=2&case=5\";
}
setTimeout(\"getgoing()\",5);
</script>";
break;

case "android_up" :
move_uploaded_file($_FILES['mod_apk']['tmp_name'], $_SERVER['DOCUMENT_ROOT']."/androids/".$_POST['name'].".apk");
sqlite_query($db, "UPDATE db_twwk_android SET value='2', url='".$_POST['name'].".apk' WHERE id='".$_POST['id']."' AND name='".$_POST['name']."'");
unlink("pack/android/".$_POST['name'].".zip");
print "Apk мод добавлен.
<script language=\"JavaScript\">
function getgoing()
{
top.location=\"./profile.php?step=androids\";
}
setTimeout(\"getgoing()\",3000);
</script>";
break;

default : print 'Конструктор неработает... обратитесь к создателю сервера [ON]Mods или сообщите игроку ALADUSHEK о ошибке ';
}}}
require ("footer.php");
?>