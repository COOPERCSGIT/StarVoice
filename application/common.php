<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------
use OSS\OssClient;
use OSS\Core\OssException;
require_once './vendor/aliyun-oss/autoload.php'; //引入这个阿里云文件

function rand_string($len = 6, $type = '', $addChars = '') {
	$str = '';
	switch($type) {
		case 0 :
			$chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz' . $addChars;
			break;
		case 1 :
			$chars = str_repeat('0123456789', 3);
			break;
		case 2 :
			$chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ' . $addChars;
			break;
		case 3 :
			$chars = 'abcdefghijklmnopqrstuvwxyz' . $addChars;
			break;
		case 4 :
			$chars = "们以我到他会作时要动国产的一是工就年阶义发成部民可出能方进在了不和有大这主中人上为来分生对于学下级地个用同行面说种过命度革而多子后自社加小机也经力线本电高量长党得实家定深法表着水理化争现所二起政三好十战无农使性前等反体合斗路图把结第里正新开论之物从当两些还天资事队批点育重其思与间内去因件日利相由压员气业代全组数果期导平各基或月毛然如应形想制心样干都向变关问比展那它最及外没看治提五解系林者米群头意只明四道马认次文通但条较克又公孔领军流入接席位情运器并飞原油放立题质指建区验活众很教决特此常石强极土少已根共直团统式转别造切九你取西持总料连任志观调七么山程百报更见必真保热委手改管处己将修支识病象几先老光专什六型具示复安带每东增则完风回南广劳轮科北打积车计给节做务被整联步类集号列温装即毫知轴研单色坚据速防史拉世设达尔场织历花受求传口断况采精金界品判参层止边清至万确究书术状厂须离再目海交权且儿青才证低越际八试规斯近注办布门铁需走议县兵固除般引齿千胜细影济白格效置推空配刀叶率述今选养德话查差半敌始片施响收华觉备名红续均药标记难存测士身紧液派准斤角降维板许破述技消底床田势端感往神便贺村构照容非搞亚磨族火段算适讲按值美态黄易彪服早班麦削信排台声该击素张密害侯草何树肥继右属市严径螺检左页抗苏显苦英快称坏移约巴材省黑武培著河帝仅针怎植京助升王眼她抓含苗副杂普谈围食射源例致酸旧却充足短划剂宣环落首尺波承粉践府鱼随考刻靠够满夫失包住促枝局菌杆周护岩师举曲春元超负砂封换太模贫减阳扬江析亩木言球朝医校古呢稻宋听唯输滑站另卫字鼓刚写刘微略范供阿块某功套友限项余倒卷创律雨让骨远帮初皮播优占死毒圈伟季训控激找叫云互跟裂粮粒母练塞钢顶策双留误础吸阻故寸盾晚丝女散焊功株亲院冷彻弹错散商视艺灭版烈零室轻血倍缺厘泵察绝富城冲喷壤简否柱李望盘磁雄似困巩益洲脱投送奴侧润盖挥距触星松送获兴独官混纪依未突架宽冬章湿偏纹吃执阀矿寨责熟稳夺硬价努翻奇甲预职评读背协损棉侵灰虽矛厚罗泥辟告卵箱掌氧恩爱停曾溶营终纲孟钱待尽俄缩沙退陈讨奋械载胞幼哪剥迫旋征槽倒握担仍呀鲜吧卡粗介钻逐弱脚怕盐末阴丰雾冠丙街莱贝辐肠付吉渗瑞惊顿挤秒悬姆烂森糖圣凹陶词迟蚕亿矩康遵牧遭幅园腔订香肉弟屋敏恢忘编印蜂急拿扩伤飞露核缘游振操央伍域甚迅辉异序免纸夜乡久隶缸夹念兰映沟乙吗儒杀汽磷艰晶插埃燃欢铁补咱芽永瓦倾阵碳演威附牙芽永瓦斜灌欧献顺猪洋腐请透司危括脉宜笑若尾束壮暴企菜穗楚汉愈绿拖牛份染既秋遍锻玉夏疗尖殖井费州访吹荣铜沿替滚客召旱悟刺脑措贯藏敢令隙炉壳硫煤迎铸粘探临薄旬善福纵择礼愿伏残雷延烟句纯渐耕跑泽慢栽鲁赤繁境潮横掉锥希池败船假亮谓托伙哲怀割摆贡呈劲财仪沉炼麻罪祖息车穿货销齐鼠抽画饲龙库守筑房歌寒喜哥洗蚀废纳腹乎录镜妇恶脂庄擦险赞钟摇典柄辩竹谷卖乱虚桥奥伯赶垂途额壁网截野遗静谋弄挂课镇妄盛耐援扎虑键归符庆聚绕摩忙舞遇索顾胶羊湖钉仁音迹碎伸灯避泛亡答勇频皇柳哈揭甘诺概宪浓岛袭谁洪谢炮浇斑讯懂灵蛋闭孩释乳巨徒私银伊景坦累匀霉杜乐勒隔弯绩招绍胡呼痛峰零柴簧午跳居尚丁秦稍追梁折耗碱殊岗挖氏刃剧堆赫荷胸衡勤膜篇登驻案刊秧缓凸役剪川雪链渔啦脸户洛孢勃盟买杨宗焦赛旗滤硅炭股坐蒸凝竟陷枪黎救冒暗洞犯筒您宋弧爆谬涂味津臂障褐陆啊健尊豆拔莫抵桑坡缝警挑污冰柬嘴啥饭塑寄赵喊垫丹渡耳刨虎笔稀昆浪萨茶滴浅拥穴覆伦娘吨浸袖珠雌妈紫戏塔锤震岁貌洁剖牢锋疑霸闪埔猛诉刷狠忽灾闹乔唐漏闻沈熔氯荒茎男凡抢像浆旁玻亦忠唱蒙予纷捕锁尤乘乌智淡允叛畜俘摸锈扫毕璃宝芯爷鉴秘净蒋钙肩腾枯抛轨堂拌爸循诱祝励肯酒绳穷塘燥泡袋朗喂铝软渠颗惯贸粪综墙趋彼届墨碍启逆卸航衣孙龄岭骗休借" . $addChars;
			break;
		default :
			// 默认去掉了容易混淆的字符oOLl和数字01，要添加请使用addChars参数
			$chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789' . $addChars;
			break;
	}
	if ($len > 10) {//位数过长重复字符串一定次数
		$chars = $type == 1 ? str_repeat($chars, $len) : str_repeat($chars, 5);
	}
	if ($type != 4) {
		$chars = str_shuffle($chars);
		$str = substr($chars, 0, $len);
	} else {
		// 中文随机字
		for ($i = 0; $i < $len; $i++) {
			$str .= msubstr($chars, floor(mt_rand(0, mb_strlen($chars, 'utf-8') - 1)), 1);
		}
	}
	return $str;
}

/**
 * 字符串截取，支持中文和其他编码
 * @static
 * @access public
 * @param string $str 需要转换的字符串
 * @param string $start 开始位置
 * @param string $length 截取长度
 * @param string $charset 编码格式
 * @param string $suffix 截断显示字符
 * @return string
 */
function msubstr($str, $start=0, $length, $charset="utf-8", $suffix=true) {
    if(function_exists("mb_substr"))
        $slice = mb_substr($str, $start, $length, $charset);
    elseif(function_exists('iconv_substr')) {
        $slice = iconv_substr($str,$start,$length,$charset);
        if(false === $slice) {
            $slice = '';
        }
    }else{
        $re['utf-8']   = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
        $re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
        $re['gbk']    = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
        $re['big5']   = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
        preg_match_all($re[$charset], $str, $match);
        $slice = join("",array_slice($match[0], $start, $length));
    }
    return $suffix ? $slice.'...' : $slice;
}

function getMillisecond() {
	list($t1, $t2) = explode(' ', microtime());
	return (float)sprintf('%.0f',(floatval($t1)+floatval($t2))*1000);
}

//从数组中获取唯一的数据
function codeStr($num=1, $arr = array(),$type=0, $len=15 ,$fixed = false){
	if($num>1){
		$arrs = array();
		for($i=0;$i<$num;$i++){
			$leng = $fixed ? $len : rand(10,$len);
			$code = rand_string($leng,$type);
			if(in_array($code, $arr)===true || in_array($code,$arrs)===true) {
				$i--;
			}else {
				$arrs[] = $code;
			}
		}
		return array_unique($arrs);
	}else{
		$leng = $fixed ? $len : rand(10,$len);
		$code = rand_string($leng, $type ,'0123456789');
		if(in_array($code, $arr)===true) {
			$code = $this->codeStr(1,$arr,$type,$len,$fixed);
		}
		return $code;
	}
}

/**
 * 根据用户UID 判断用户是否存在
 */
 
function is_safe($uid){
	return model("user")->get($uid) ? TRUE : FALSE;
}

/**
 * 获取转化率  包括 day(今天), yesterday(昨天), week(周), month(月) 四种
 */
function income(){
	$wh = array('day','yesterday','week','month');
	$rate = array();
	foreach($wh as $row){
		switch ($row) {
			case 'day':
				$day = date("Y-m-d");
				$mindate = strtotime($day);
				$maxdate = $mindate+86400;
				break;
			case 'yesterday':
				$day = date("Y-m-d");
				$mindate = strtotime($day)-86400;
				$maxdate = $mindate+86400;
				
				break;
			case 'week':
				$dateInfo = GetCurrentDateInfo();
				$mindate = strtotime($dateInfo['W1']);
				$maxdate = strtotime($dateInfo['W7']);
				break;
			case 'month':
				$dateInfo = GetCurrentDateInfo();
				$mindate = strtotime($dateInfo['M1']);
				$maxdate = strtotime($dateInfo['M2']);
				break;		
		}		
		$daylog = daylog($mindate,$maxdate);
		$rate[] = '';//$daylog['rate'];
	}
	return $rate;
}

/**
 * 根据当前日期，获取当前周，或当前月
 */
function GetCurrentDateInfo(){  
   $dayTimes = 24*60*60;  
   $dateArr = array();
   $temp = '';  
  
       /* 0:周末 1-6:周一 至 周六 */  
   $weekIndex = (int)date('w');  
   switch($weekIndex){  
        case 0:  
            $dateArr['W1'] = date('Y-m-d 00:00:00',strtotime('-6 day'));  
            $dateArr['W7'] = date('Y-m-d 23:59:59');  
            break;  
        case 1:  
            $dateArr['W1'] = date('Y-m-d 00:00:00');  
            $dateArr['W7'] = date('Y-m-d 23:59:59',strtotime('+6 day'));  
            break;  
        case 2:  
            $dateArr['W1'] = date('Y-m-d 00:00:00',strtotime('-1 day'));  
            $dateArr['W7'] = date('Y-m-d 23:59:59',strtotime('+5 day'));  
            break;  
        case 3:  
            $dateArr['W1'] = date('Y-m-d 00:00:00',strtotime('-2 day'));  
            $dateArr['W7'] = date('Y-m-d 23:59:59',strtotime('+4 day'));  
            break;  
        case 4:  
            $dateArr['W1'] = date('Y-m-d 00:00:00',strtotime('-3 day'));  
            $dateArr['W7'] = date('Y-m-d 23:59:59',strtotime('+3 day'));  
            break;  
        case 5:  
            $dateArr['W1'] = date('Y-m-d 00:00:00',strtotime('-4 day'));  
            $dateArr['W7'] = date('Y-m-d 23:59:59',strtotime('+2 day'));  
            break;  
        case 6:  
            $dateArr['W1'] = date('Y-m-d 00:00:00',strtotime('-5 day'));  
            $dateArr['W7'] = date('Y-m-d 23:59:59',strtotime('+1 day'));  
                break;  
       }  
  
  
       //1-12：一月 至 十二月  
   $monthIndex = (int)date('m');  
   switch($monthIndex){  
       case 1:  
           $temp = date('Y-02-01 00:00:00');  
           $dateArr['M1'] = date('Y-m-01 00:00:00');  
           $dateArr['M2'] = date('Y-m-d 23:59:59',strtotime($temp)-$dayTimes);  
           break;  
       case 2:  
           $temp = date('Y-03-01 00:00:00');  
           $dateArr['M1'] = date('Y-m-01 00:00:00');  
           $dateArr['M2'] = date('Y-m-d 23:59:59',strtotime($temp)-$dayTimes);  
           break;  
       case 3:  
           $temp = date('Y-04-01 00:00:00');  
           $dateArr['M1'] = date('Y-m-01 00:00:00');  
           $dateArr['M2'] = date('Y-m-d 23:59:59',strtotime($temp)-$dayTimes);  
           break;  
       case 4:  
           $temp = date('Y-05-01 00:00:00');  
           $dateArr['M1'] = date('Y-m-01 00:00:00');  
           $dateArr['M2'] = date('Y-m-d 23:59:59',strtotime($temp)-$dayTimes);  
           break;  
       case 5:  
           $temp = date('Y-06-01 00:00:00');  
           $dateArr['M1'] = date('Y-m-01 00:00:00');  
           $dateArr['M2'] = date('Y-m-d 23:59:59',strtotime($temp)-$dayTimes);  
           break;  
       case 6:  
           $temp = date('Y-07-01 00:00:00');  
           $dateArr['M1'] = date('Y-m-01 00:00:00');  
           $dateArr['M2'] = date('Y-m-d 23:59:59',strtotime($temp)-$dayTimes);  
           break;  
       case 7:  
           $temp = date('Y-08-01 00:00:00');  
           $dateArr['M1'] = date('Y-m-01 00:00:00');  
           $dateArr['M2'] = date('Y-m-d 23:59:59',strtotime($temp)-$dayTimes);  
           break;  
       case 8:  
           $temp = date('Y-09-01 00:00:00');  
           $dateArr['M1'] = date('Y-m-01 00:00:00');  
           $dateArr['M2'] = date('Y-m-d 23:59:59',strtotime($temp)-$dayTimes);  
           break;  
       case 9:  
           $temp = date('Y-10-01 00:00:00');  
           $dateArr['M1'] = date('Y-m-01 00:00:00');  
           $dateArr['M2'] = date('Y-m-d 23:59:59',strtotime($temp)-$dayTimes);  
           break;  
       case 10:  
           $temp = date('Y-11-01 00:00:00');  
           $dateArr['M1'] = date('Y-m-01 00:00:00');  
           $dateArr['M2'] = date('Y-m-d 23:59:59',strtotime($temp)-$dayTimes);  
           break;  
       case 11:  
           $temp = date('Y-12-01 00:00:00');  
           $dateArr['M1'] = date('Y-m-01 00:00:00');  
           $dateArr['M2'] = date('Y-m-d 23:59:59',strtotime($temp)-$dayTimes);  
           break;  
       case 12:  
           $temp = date((date('Y')+1)."-01-01 00:00:00");  
           $dateArr['M1'] = date('Y-m-01 00:00:00');  
           $dateArr['M2'] = date('Y-m-d 23:59:59',strtotime($temp)-$dayTimes);  
               break;  
       }  
  
       return $dateArr;  
    }

//获取指定日期到今日的报表
function daylogs($firstdate='2016-11-11'){
	$firstdate = strtotime($firstdate);
	$lastdate = strtotime(date("Y-m-d"));
	$logs = array();
	while ($lastdate >= $firstdate) {
		$mindate = $lastdate;
		$maxdate = $lastdate+86400;
		$logs[] = daylog($mindate, $maxdate);
		
		$lastdate-=86400;
	}
	return $logs;
}
	
/**
 * 获取每日报表信息
 */
function daylog($mindate = "", $maxdate = ""){
	$day = date('Y-m-d');
	$mindate = $mindate=="" ? strtotime($day) : $mindate;
	$maxdate = $maxdate=="" ? $mindate+86400 : $maxdate;
	
	$uids = model("user")->whereTime('addtime', 'between', [$mindate, $maxdate])->column("uid");
	if($uids){
		$register = count($uids);
		
		$voice = model("voice")->whereTime('addtime', 'between', [$mindate, $maxdate])->field("uid")->select();
		
		$voice_num = 0;
		foreach($uids as $row){
			if(in_array($row, $voice)) $voice_num++;
		}
		$log = array("day"=>date('Y-m-d', $mindate),"register"=>$register,"voice_num"=>$voice_num);
	}else{
		$log = array("day"=>date('Y-m-d', $mindate),"register"=>0,"voice_num"=>0);
	}
	return $log;
}

/**
 * 获取文件信息
 * @return pathinfo 
 */
function get_extension($file){
	return pathinfo($file, PATHINFO_EXTENSION);
}

function getFileSize($url)  
{  
    $url = parse_url($url);  
    if($fp = @fsockopen($url['host'],empty($url['port'])?80:$url['port'],$error))  
    {  
        fputs($fp,"GET ".(empty($url['path'])?'/':$url['path'])." HTTP/1.1\r\n");  
        fputs($fp,"Host:$url[host]\r\n\r\n");  
        while(!feof($fp))  
        {  
            $tmp = fgets($fp);  
            if(trim($tmp) == '')  
            {  
                break;  
            }  
            elseif(preg_match('/Content-Length:(.*)/si',$tmp,$arr))  
            {  
                return trim($arr[1]);  
            }  
        }  
        return null;  
    }  
    else  
    {  
        return null;  
    }  
}

/**
 * 实例化阿里云OSS
 * @return object 实例化得到的对象
 * @return 此步作为共用对象，可提供给多个模块统一调用
 */
 function new_oss(){
    //获取配置项，并赋值给对象$config
    $config=config('aliyun_oss');
    //实例化OSS
    $oss= new \OSS\OssClient($config['KeyId'],$config['KeySecret'],$config['Endpoint']);
    return $oss;
 }

/**
 * 上传指定的本地文件内容
 *
 * @param OssClient $ossClient OSSClient实例
 * @param string $object 上传的文件名称
 * @param string $Path 本地文件路径
 * @param string $bucket 存储空间名称
 * @return null
 */
function uploadFile($object, $Path, $bucket='yuliao'){
    //try 要执行的代码,如果代码执行过程中某一条语句发生异常,则程序直接跳转到CATCH块中,由$e收集错误信息和显示
    try{
        //没忘吧，new_oss()是我们上一步所写的自定义函数
        $ossClient = new_oss();
        
        //uploadFile的上传方法
        $ossClient->uploadFile($bucket, $object, $Path);
    } catch(OssException $e) {
        //如果出错这里返回报错信息
        return $e->getMessage();
    }
    //否则，完成上传操作
    return true;
}

/**
 * 判断object是否存在
 *
 * @param OssClient $ossClient OSSClient实例
 * @param string $bucket bucket名字
 * @return null
 */
function doesObjectExist($object, $bucket='yuliao')
{
    try{
    	$ossClient = new_oss();
        $exist = $ossClient->doesObjectExist($bucket, $object);
    } catch(OssException $e) {
        printf(__FUNCTION__ . ": FAILED\n");
        printf($e->getMessage() . "\n");
        return;
    }
    return $exist;
}

function myUpload($uid = ""){
	$file = request()->file();
	$aliyun_oss = config('aliyun_oss');
	$file_urls = array();
	if($file){
		foreach($file as $key=>$row){
			$fileinfo = $row->getInfo();
			$filetypes = explode("/",$fileinfo['type']);
			$type = $filetypes[0]=='image' ? 'image' : 'file';
			$filename = $fileinfo['name'];
			$ext = get_extension($filename);
			$object = $type.'/'.date('Ymd').'/'.rand_string(20,3,'1234567890').'.'.$ext;
			$object = $uid!='' ? 'user/'.$uid.'/'.$object : $object;
			if(doesObjectExist($object)){
				$object = $type.'/'.date('Ymd').'/'.rand_string(6).rand_string(20,3,'1234567890').'.'.$ext;
				$object = $uid!='' ? 'user/'.$uid.'/'.$object : $object;
			}
			$path = $fileinfo['tmp_name'];
			$flag = uploadFile($object,$path);
			if($flag===true){
				$file_urls[$key] = 'http://app.starsound.xyz/'.$object;
			}else{
				return $flag;
			}
		}
		if(is_array($file_urls)) return $file_urls;
	}else{
		return '上传文件不存在';
	}
}

/**
 * 发起一个get请求到指定接口
 * 
 * @param string $url 请求的接口
 * @param int $timeout 超时时间
 * @return string 请求结果
 */
function getRequest( $url, $timeout = 60 ) {
	$ch = curl_init();
	curl_setopt( $ch, CURLOPT_URL, cn_urlencode($url));
	// 以返回的形式接收信息
	curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
	
	// 不验证https证书
	curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, 0 );
	curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, 0 );
	curl_setopt( $ch, CURLOPT_TIMEOUT, $timeout );
	curl_setopt( $ch, CURLOPT_HTTPHEADER, array(
		'Content-Type: application/x-www-form-urlencoded;charset=UTF-8'
	) ); 
	// 发送数据
	$response = curl_exec( $ch );
	// 不要忘记释放资源
	curl_close( $ch );
	$response = mb_convert_encoding($response, 'utf-8', 'GBK,UTF-8,ASCII');
	return $response;
}


function cn_urlencode($url){ 
	$pregstr = "/[\x{4e00}-\x{9fa5}]+/u";//UTF-8中文正则 
	if(preg_match_all($pregstr,$url,$matchArray)){//匹配中文，返回数组 
		foreach($matchArray[0] as $key=>$val){ 
			$url=str_replace($val, urlencode($val), $url);//将转译替换中文 
		} 
		if(strpos($url,' ')){//若存在空格 
			$url=str_replace(' ','%20',$url); 
		} 
	} 
	return $url; 
}

function is_zan($uid, $cid, $arr=array()){
	if($arr){
		$cids = model("zan")->where("uid",$uid)->column("cid");
		foreach($arr as $key=>$row){
			$is_zan = in_array($row['cid'], $cids) ? 1 : 0;
			$arr[$key]['is_zan'] = $is_zan;
		}
		return $arr;
	}else{
		$cids = model("zan")->where("uid",$uid)->column("cid");
		return in_array($cid, $cids) ? 1 : 0;
	}
}


