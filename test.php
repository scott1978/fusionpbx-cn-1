<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
    <title>无标题文档</title>
</head>
<body>
html复选框如果要以数据组形式发送给php脚本处理就必须以如checkbox[]这形式
<form id="form1" name="form1" method="post" action="">
    <label>
        <input type="checkbox" name="checkbox[]" value="1" />
    </label>
    <label>
        <input type="checkbox" name="checkbox[]" value="2" />
    </label>
    <label>
        <input type="checkbox" name="checkbox[]" value="www.jb51.net" />
    </label>
    <label>
        <input type="checkbox" name="checkbox[]" value="jb51.net" />
    </label>
    <label>
        <input type="submit" name="Submit" value="提交" />
    </label>
</form>
</body>
</html>
<?
//判断是否点击提交
if( $_POST )
{
    $array = $_POST['checkbox'];
    print_r($array);
}

?>