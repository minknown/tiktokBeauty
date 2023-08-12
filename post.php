<?php
//接口说明->
//put:创建待处理任务（from-android）
//get:获取待处理任务
//post:写入处理结果
//err:登记错误，超过一定次数会自动sta=-1
//show:取得随机展示
//将客户端index.html布置在其他网络主机的方法如下:
//-拷贝index.html\post.php到该网络主机，配置本文的otherc为主机名即可
//-注意otherc尽在视频条目oss不存在时生效，其值不包括dirname(online_video),尾部必须加/。
$otherc="";
//参数设置与初始化
//error_reporting(0);
$way=$_GET['way'];
$id=$_GET['id'];
//超过多少次错误的任务不再显示
$errcs=3;
$bucket='videobeauty';
//数据库配置
$config = array(
    'host' => 'localhost',//服务器
    'user' => 'root',//用户名
    'pass' => '123456',//密码
    'dbname' => 'sql',//数据库名
);
//数据库链接
$con = mysql_connect($config['host'], $config['user'], $config['pass']);
if (!$con) {die( '服务器数据库繁忙');}
mysql_select_db($config['dbname'], $con);
mysql_query("SET NAMES UTF8MB4");
//put
if($way=="put"){
	$qq="update tiktok set=-1 where sta!=1 and errcs>".$errcs;
	mysql_query($qq, $con);
	
	$url=$_GET['url'];
	$qq="select * from tiktok where url='".$url."' limit 1";
	$result = mysql_query($qq, $con);
	$res = mysql_fetch_array($result); 
	if($res['id']>0){
		if($res['sta']==-1){
			echo "DEL";
		}else{
			echo "AL";
		}
	}else{
		$qq="INSERT INTO tiktok (id,url,errcs,sta) VALUES (NULL,'".$url."',0,0)";
		mysql_query($qq, $con);
		echo mysql_insert_id();
	}
	
	mysql_close();exit();
}
//get
if($way=="get"){
	$qq="update tiktok set=-1 where sta!=1 and errcs>".$errcs;
	mysql_query($qq, $con);
	$qq="select * from tiktok where sta=0 order by errcs,RAND() limit 1";
	$result = mysql_query($qq, $con);
	$res = mysql_fetch_array($result); 
	if($res['id']>0){
		echo $res['id']."|".$res['url'];
	}else{
		echo "No-Need-To-Deal";
	}
	mysql_close();exit();
}
//post
if($way=="post"){
	
	$qq="update tiktok set=-1 where sta!=1 and errcs>".$errcs;
	mysql_query($qq, $con);
	
	$title=$_GET['title'];
	$author=$_GET['author'];
	$lcount=$_GET['lcount'];
	$local=$_GET['local'];
	$oss=$_GET['oss'];
	$qq="update tiktok set sta=1,title='".$title."',author='".$author."',lcount='".$lcount."',local='".$local."',oss='".$oss."' where id=".$id;
	//echo $qq;
	mysql_query($qq, $con);
	
	$qq="select * from tiktok where id=".$id;
	$result = mysql_query($qq, $con);
	$res = mysql_fetch_array($result);
	if($res ['sta']==1){
		echo "OK";
	}else{
		echo "ERR";
	}
	mysql_close();
	exit();
}
//del
if($way=="err"){
	//$qq="delete from tiktok where id=".$id;
	$qq="update tiktok set errcs=errcs+1 where id=".$id;
	$result = mysql_query($qq, $con);
	echo "OK";
	mysql_close();exit();
}
//show
//[返回结果的有无判断交给视频展示页处理和判断]
if($way=="show"){
	$fromway=1;//1:mysql 非1:本地目录原始模式
	$dirname="online_video";
	$sc=4;
	$tou=$otherc.$dirname."/";
	$tou_oss="https://".$bucket.".oss-cn-shanghai.aliyuncs.com/";
	//查询详情
	if($id!=""){
		$qq="select * from tiktok where url='".$id."' limit 1";	
		$result = mysql_query($qq, $con);
		$res = mysql_fetch_array($result); 
		header("Content-type: text/html; charset=utf-8");
		
		foreach($res as $key => $value){
				if(is_numeric($key)==false){
					echo "<b>".$key."</b>=".$value."<br>";
				}
				
			}
		echo "<button onclick='history.go(-1)'>返回</button>";
		mysql_close();
		exit();
	}else{
		$res=array();
	}
	//fromway!=1模式
	if($fromway!=1){

		$re=scandir("./".$dirname."/");
		array_splice($re,array_search(".",$re),1);
		array_splice($re,array_search("..",$re),1);
		if(count($re)<=0 or !is_dir($dirname)){
			die("noone");
		}
		shuffle($re);
		shuffle($re);
		shuffle($re);
		
		$num=min($sc,count($re));
		
		
		for ($i=1; $i<=$num; $i++)
		{
			$temp['url']=$re[$i];
			$temp['title']=$re[$i];
			$temp['lcount']=0;
			$temp['vurl']=$tou.$re[$i];
			
			
		    array_push($res,$temp);
		}
		echo json_encode($res);
		
	}
	//fromway==1模式
	if($fromway==1){
		//查询总量
		$qq="select count(*) as ab from tiktok where sta=1";
		$result = mysql_query($qq, $con);
		$can = mysql_fetch_array($result);
		$qq="select count(*) as ab from tiktok where sta=-1";
		$result = mysql_query($qq, $con);
		$un = mysql_fetch_array($result);
		$qq="select count(*) as ab from tiktok where sta=0";
		$result = mysql_query($qq, $con);
		$wait = mysql_fetch_array($result);
		//查询本条
		
		if($_GET['s']==""){
			$qq="select * from tiktok where sta=1 order by rand() limit ".$sc;
			}else{
			$qq="select * from tiktok where sta=1 and author LIKE '%".$_GET['s']."%' order by rand() limit ".$sc;
		}
		
		$result = mysql_query($qq, $con);

		while($temp=mysql_fetch_assoc($result)){
			$temptwo['url']=$temp['url'];
			$temptwo['title']=$temp['title'];
			$temptwo['lcount']=$temp['lcount'];
			if(strlen($temp['oss'])>=4){
				$temptwo['vurl']=$tou_oss.$temp['oss'].".mp4";
			}else{
				$temptwo['vurl']=$tou.$temp['local'].".mp4";
			}
			array_push($res,$temptwo);
		}
		if(count($res)<=0){
			die("noone");
		}
		unset($temptwo);
		$temptwo['title']="tj";
		$temptwo['can']=$can['ab'];
		$temptwo['un']=$un['ab'];
		$temptwo['wait']=$wait['ab'];
		array_push($res,$temptwo);
		echo json_encode($res);
	}
	mysql_close();
	exit();

}
?>