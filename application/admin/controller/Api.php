<?php
//  ========== 
//  API 访问格式 域名 + admin.php/api/api名称?channel=APP&条件=值&条件=值... 
//  ========== 
namespace app\admin\controller;
class Api
{
	/**
	 * login  登陆
	 * $channel 渠道(必须)  默认值:APP
	 * $openid 微信OPENID(必须)
	 * $nickname 用户昵称
	 * $sex 用户性别
	 * $headpic 用户头像
	 * 
	 * @return json(status,info,user[uid:用户编号,wxopenid:微信openid,nickname:用户昵称,headpic:头像,sex:性别(1=>男,0=>女)])
	 * @author zwy
	 */
	public function login(){
		$channel = input("param.channel/s");
		if($channel=='APP'){
			$wxopenid = input("param.openid/s");
			if(empty($wxopenid)){
				return json("呵呵");
				exit; 
			}
			$user = model("user")->where("wxopenid",$wxopenid)->field("uid,wxopenid,nickname,headpic,sex")->find();			
			if($user){
				
				$user['headpic'] = stristr($user['headpic'],'http') ? $user['headpic'] : config("__URL__").config("__UPLOAD__").$user['headpic'];
				
				$list = array("status"=>1,"info"=>"成功","user"=>$user);
			}else{
				$data['wxopenid'] = $wxopenid;
				$data['nickname'] = input("param.nickname/s");
				$data['sex'] = input("param.sex/d");
				$data['headpic'] = input("param.headpic/s");
				
				$uids = model("user")->column("uid");			
				$data['uid'] = codeStr(1,$uids);				
				$data['pwd'] = md5('123456');
				$data['status'] = 1;
				$data['addtime'] = time();
				$res = db('user')->insert($data);				
				if($res) {
					$user = model("user")->field("uid,nickname,wxopenid,headpic,sex")->find($data['uid']);
					$user['headpic'] = stristr($user['headpic'],'http') ? $user['headpic'] : config("__URL__").config("__UPLOAD__").$user['headpic'];
					$list = array("status"=>1,"info"=>"成功","user"=>$user);
				}else{
					$list = array("status"=>1,"info"=>"失败");
				}
			}
			return json($list);
		}else{
			return json('呵呵');
		}
	}
	
	/**
	 * editUser  修改用户信息
	 * $channel 渠道(必须)  默认值:APP
	 * $uid 用户UID(必须)
	 * $nickname 昵称
	 * $sex 性别
	 * $headpic 头像
	 * $mobile 电话
	 * 
	 * @return json(status,info,user[uid:用户编号,wxopenid:微信openid,nickname:用户昵称,headpic:头像,sex:性别(1=>男,0=>女)])
	 * @author zwy
	 */
	public function editUser(){
		$channel = input("param.channel/s");
		$uid = input("param.uid/s");
		if($channel=='APP' && $uid){
			$user = model("user")->where("uid",$uid)->field("uid")->find();			
			if($user){
				if(input("param.nickname/s")) $data['nickname'] = input("param.nickname/s");				
				if(input("param.sex/d")) $data['sex'] = input("param.sex/d");
				$file = request()->file('headpic');
				if($file){
				    // 移动到框架应用根目录/public/uploads/ 目录下
				    $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
				    if($info){
				        $data['headpic'] = str_replace('\\','/',$info->getSaveName());
				    }else{
						$this->error($file->getError());
				    }
				}else{
					unset($data['headpic']);
				}
				if(input("param.pwd/s")) $data['pwd'] = md5(input("param.pwd/s"));
				db("user")->where("uid",$uid)->update($data);
				
				$user = model("user")->where("uid",$uid)->field("uid,nickname,wxopenid,headpic,sex")->find();
				$user['headpic'] = stristr($user['headpic'],'http') ? $user['headpic'] : config("__URL__").config("__UPLOAD__").$user['headpic'];
				
				$list = array("status"=>1,"info"=>"成功","user"=>$user);
			}else{
				$list = array("status"=>1,"info"=>"失败");
			}
			return json($list);
		}else{
			return json('呵呵');
		}
	}

	/**
	 * addVoice 发布语音
	 * $channel 渠道(必须)  默认值:APP
	 * $uid 用户ID(必须)
	 * $title 语音标题(必须)
	 * $reurl 语音地址(必须)
	 * $length 语音时长(必须)
	 * $backgrund 语音背景裁剪图片(必须)
	 * $allbackgrund 语音背景原图片(必须)
	 * 
	 * @return json(sid:状态[1:成功,0:失败],info:提示)
	 * @author zwy
	 */
	public function addVoice()
    {    	
    	$channel = input("param.channel/s");		
		$uid = input("param.uid/s") ? input("param.uid/s") : "";
		$title = input("param.title/s") ? input("param.title/s") : "";
		$reurl = input("param.reurl/s") ? input("param.reurl/s") : "";
		$length = input("param.length/s") ? input("param.length/s") : "";
		$backgrund = input("param.backgrund/s") ? input("param.backgrund/s") : "";
		$allbackgrund = input("param.allbackgrund/s") ? input("param.allbackgrund/s") : "";
		
		$tid = input("param.tid/s") ? input("param.tid/s") : "7182896105";
		
		if($channel=='APP' && $uid && $title && $reurl && $length && $backgrund){
			$vids = model('voice')->column("vid");
        	$vid = codeStr(1,$vids);
			$res = db("voice")->insert(['vid'=>$vid, 'uid'=>$uid,'tid'=>$tid, 'title'=>$title,'reurl'=>$reurl,'length'=>$length,'allbackgrund'=>$allbackgrund,'backgrund'=>$backgrund,'addtime'=>time()]);
			if($res){
				$file_path = config('__URL__').'/yuliao/ciku.txt';
				$comments = getRequest($file_path);
				$comments = array_merge(array_filter(explode(PHP_EOL, $comments)));
				if($comments){
					$ws['length(wxopenid)'] = 20;
					$uids = model("user")->where($ws)->column("uid");
					$num = rand(5,10);
					$cids = model('comments')->column("cid");
	    			for($i=0; $i<$num ; $i++){
						$cdata[$i]['cid'] = codeStr(1,$cids);
						$cids = array_merge($cids,$cdata);	
						$ui = array_rand($uids);	
						$cdata[$i]['uid'] = $uids[$ui];
						$cms = array_rand($comments);
						$con = @$comments[$cms] ? @$comments[$cms] : "";
						$cdata[$i]['content'] = $con;
						$cdata[$i]['vid'] = $vid;
						$cdata[$i]['addtime'] = time()+rand(60,360);
						unset($comments[$cms]);
						unset($uids[$ui]);	
					}
					model('comments')->insertAll($cdata,'',true);
				}
				return json(array("status"=>1,"info"=>"当前语音发布成功"));
			}else{
				return json(array("status"=>0,"info"=>"当前语音发布失败"));
			}			
		}else{
			return json('呵呵');
		}	
    }
	
	/**
	 * delVoice 删除自己发布的语音
	 * $channel 渠道(必须)  默认值:APP
	 * $uid 用户ID(必须)
	 * $vid 语音ID(必须)
	 * 
	 * @return json(sid:状态[1:成功,0:失败,-1:权限不足],info:提示)
	 * @author zwy
	 */
	public function delVoice()
    {    	
    	$channel = input("param.channel/s");		
		$uid = input("param.uid/s") ? input("param.uid/s") : "";
		$vid = input("param.vid/s") ? input("param.vid/s") : "";
		
		if($channel=='APP' && $uid && $vid){
			$uid = model("voice")->where(array("uid"=>$uid,"vid"=>$vid))->value("uid");
        	if($uid){
				$res = model("voice")->where(array("uid"=>$uid,"vid"=>$vid))->delete();
				if($res){
					model("comments")->where(array("vid"=>$vid))->delete();
					model("favorite")->where(array("vid"=>$vid))->delete();
					return json(array("status"=>1,"info"=>"当前语音删除成功"));
				}else{
					return json(array("status"=>0,"info"=>"当前语音删除失败"));
				}
			}else{
				return json(array("status"=>-1,"info"=>"当前语音您没有权限删除"));
			}			
		}else{
			return json('呵呵');
		}	
    }
	
	/**
	 * pbVoice 	屏蔽语音
	 * $channel 渠道(必须)  默认值:APP
	 * $uid 用户ID(必须)
	 * $vid 语音ID(必须)
	 * 
	 * @return json(sid:状态[1:成功,0:失败,-1:权限不足],info:提示)
	 * @author zwy
	 */
	public function pbVoice()
    {    	
    	$channel = input("param.channel/s");		
		$uid = input("param.uid/s") ? input("param.uid/s") : "";
		$vid = input("param.vid/s") ? input("param.vid/s") : "";
		
		if($channel=='APP' && $uid && $vid){
			$user = model("user")->get($uid);
        	if($user){
				$res = model("pingbi")->save(array("uid"=>$uid,"vid"=>$vid,"addtime"=>time()));
				if($res){
					return json(array("status"=>1,"info"=>"当前语音屏蔽成功"));
				}else{
					return json(array("status"=>0,"info"=>"当前语音屏蔽失败"));
				}
			}else{
				return json(array("status"=>-1,"info"=>"当前语音您没有权限屏蔽"));
			}			
		}else{
			return json('呵呵');
		}	
    }

	/**
	 * allVoice 获取分类语音数据
	 * $channel 渠道(必须)  默认值:APP
	 * $uid 用户ID(必须)
	 * $is_u 受否是用户发布的语音 默认值：0
	 * $cid 浏览用户ID 在 $is_u 不等于0 时 起作用
	 * $page 页数  默认值:1
	 * $num 数量  默认值:10
	 * 
	 * @return json(vid:语音ID,title:语音标题,user[uid:发布者ID,nickname:发布者昵称,headpic:发布者头像],type[tid:类型ID,title:类型标题],comment_t[user:用户名,content:文字评论内容],comment_c[user:用户名,content:语音评论内容],reurl:语音链接地址,play_num:播放次数,fav_num:收藏次数,comment:评论次数,is_focus:是否关注)
	 * @author zwy
	 */
	public function allVoice(){
		$channel = input("param.channel/s");
		$uid = input("param.uid/s");
		$page = input("param.page/d") && input("param.page/d")> 0 ? input("param.page/d") : 1;
		$num = input("param.num/d") && input("param.num/d")> 0 ? input("param.num/d") : 10;
		$is_u = input("param.is_u/d") && input("param.is_u/d")> 0 ? input("param.is_u/d") : 0;
		$cid = input("param.cid/s") && !empty(input("param.cid/s")) ? input("param.cid/s") : "";
		if($channel=='APP' && $uid){
			if(is_safe($uid)){
				$where['is_hot'] = 1;
				if($is_u) {
					$where = array();
					$where['uid'] = $uid;
				}
				$auid = isset($cid) && $cid!="" && $cid!=="null" && $cid!=="(null)" ? $cid : $uid;
				$vids = model("pingbi")->where("uid='{$auid}'")->column("vid");
				if($vids) $where['vid'] = ['not in',$vids];
				
				$list = model('voice')->where($where)->order("addtime DESC")->limit(($page-1)*$num, $num)->select();
				foreach($list as $key=>$value){
					$list[$key]['fav_num'] = $value['fav_num'] ? $value['fav_num'] : 0;
					$list[$key]['play_num'] = $value['play_num'];
					$list[$key]['comment_c'] = is_zan($uid, "",$value->voicecomment);
					$list[$key]['comment_t'] = is_zan($uid, "",$value->txtcomment);
					$list[$key]['backgrund'] = $value['backgrund'];
					$list[$key]['allbackgrund'] = $value['allbackgrund'];
					$type['tid'] = $value->getData("tid");
					$type['title'] = $value['tid'];
					$list[$key]['type'] = $type;
					$list[$key]['is_focus'] = model("focus")->where(["uid"=>$uid,"cid"=>$value->getData("uid")])->value("cid") ? 1 : 0;
					if($is_u) $list[$key]['is_focus'] = model("focus")->where(["uid"=>$cid,"cid"=>$uid])->value("cid") ? 1 : 0;
					$user['uid'] = $value->getData("uid");
					$user['nickname'] = $value['uid'];
					
					$headpic = $value->face;
					if($headpic){
						$headpic = stristr($headpic,'http') ? $headpic : config("__URL__").config("__UPLOAD__").$headpic;
					}
					$user['headpic'] = $headpic;
					$list[$key]['user'] = $user;
					
					unset($list[$key]['tid']);
					unset($list[$key]['uid']);
				}
				return json($list);
			}else{
				return json("呵呵");
			}
		}else{
			return json('呵呵');
		}
	}
	
	/**
	 * allType  分类
	 * $channel 渠道(必须)  默认值:APP
	 * $uid 用户ID(必须)
	 * 
	 * @return json(tid:分类ID,title:分类标题)
	 * @author zwy
	 */
	public function allType(){
		$channel = input("param.channel/s");
		$uid = input("param.uid/s");
		if($channel=='APP' && $uid){
			if(is_safe($uid)){
				$class = model("type")->where("pid=0")->field("tid,title")->order("sort ASC")->select();
				return json($class);
			}else{
				return json("呵呵");
			}
		}else{
			return json('呵呵');
		}
	}
	
	/**
	 * voiceToComments 获取当前语音所有评论
	 * $channel 渠道(必须)  默认值:APP
	 * $uid 用户ID(必须)
	 * $vid 当前评论语音ID(必须)
	 * $type 评论类型(必须) 默认值：0
	 * $page 页数  默认值:1
	 * $num 数量  默认值:10
	 * 
	 * @return json(vid:语音ID,title:语音标题,user[uid:发布者ID,nickname:发布者昵称],type[tid:类型ID,title:类型标题],reurl:语音链接地址,play_num:播放次数,fav_num:收藏次数,comment:评论次数)
	 * @author zwy
	 */
	public function voiceToComments(){
		$channel = input("param.channel/s");
		$uid = input("param.uid/s");
		$vid = input("param.vid/s");
		$page = input("param.page/d") && input("param.page/d")> 0 ? input("param.page/d") : 1;
		$num = input("param.num/d") && input("param.num/d")> 0 ? input("param.num/d") : 10;
		$type = input("param.type/d") && input("param.type/d")> 0 ? input("param.type/d") : 0;
		if($channel=='APP' && $uid){
			if(is_safe($uid)){
				$where['vid'] = $vid;
				$where['type'] = $type;
				$list = model('comments')->where($where)->field("cid,uid,type,content,zan,clength")->order("zan DESC")->limit(($page-1)*$num, $num)->select();
				$comments = array();
				foreach($list as $key=>$row){
					$comments[$key]['user'] = $row['uid'];
					$headpic = $row->userface;
					if($headpic){
						$headpic = stristr($row->userface,'http') ? $row->userface : config("__URL__").config("__UPLOAD__").$row->userface;
					}
					$comments[$key]['headpic'] = $headpic;
					$comments[$key]['zan'] = $row['zan'];
					$comments[$key]['cid'] = $row['cid'];
					$comments[$key]['is_zan'] = is_zan($uid, $row['cid']);
					$comments[$key]['clength'] = $row['clength'];
					$comments[$key]['content'] = $row->getData("content");
				}
				return json($comments);
			}else{
				return json("呵呵");
			}
		}else{
			return json('呵呵');
		}
	}
    
    /**
	 * addComments 发布评论
	 * $channel 渠道(必须)  默认值:APP
	 * $uid 用户ID(必须)
	 * $vid 语音ID(必须)
	 * $type 评论类型(必须)
	 * $content 评论内容(必须)
	 * $clength 语音评论长度
	 * 
	 * @return json(sid:状态[1:成功,0:失败],info:提示)
	 * @author zwy
	 */
	public function addComments()
    {    	
    	$channel = input("param.channel/s");		
		$uid = input("param.uid/s") ? input("param.uid/s") : "";
		$vid = input("param.vid/s") ? input("param.vid/s") : "";
		$content = input("param.content/s") ? input("param.content/s") : "";
		$type = input("param.type/d") ? input("param.type/d") : 0;
		$clength = input("param.clength/d") ? input("param.clength/d") : 0;
		$cids = model('comments')->column("cid");
        $cid = codeStr(1,$cids);
		
		if($channel=='APP' && $uid && $vid){
			$res = db("comments")->insert(['cid'=>$cid,'uid'=>$uid,'vid'=>$vid,'type'=>$type,'content'=>$content,'clength'=>$clength,'addtime'=>time()]);
			if($res){
				return json(array("status"=>1,"info"=>"当前语音评论成功"));
			}else{
				return json(array("status"=>0,"info"=>"当前语音评论失败"));
			}			
		}else{
			return json('呵呵');
		}	
    }
	
	/**
	 * myFocus 我的关注
	 * $channel 渠道(必须)  默认值:APP
	 * $uid 用户ID(必须)
	 * $page 页数  默认值:1
	 * $num 数量  默认值:10
	 * 
	 * @return json(vid:语音ID,title:语音标题,user[uid:发布者ID,nickname:发布者昵称,headpic:发布者头像],type[tid:类型ID,title:类型标题],comment_t[user:用户名,content:文字评论内容],comment_c[user:用户名,content:语音评论内容],reurl:语音链接地址,play_num:播放次数,fav_num:收藏次数,comment:评论次数,is_focus:是否关注)
	 * @author zwy
	 */
    public function myFocus()
    {    	
    	$channel = input("param.channel/s");		
		$uid = input("param.uid/s") ? input("param.uid/s") : "";
		$page = input("param.page/d") && input("param.page/d")> 0 ? input("param.page/d") : 1;
		$num = input("param.num/d") && input("param.num/d")> 0 ? input("param.num/d") : 10;
		
		if($channel=='APP' && $uid){			
			$where['uid'] = $uid;
			$cids = model('focus')->where($where)->column("cid");
			$cids = array_merge(array($uid),$cids);
			if($cids){
				$vids = model("pingbi")->where("uid='{$uid}'")->column("vid");
				$wh['uid'] = ['in',$cids];
				if($vids) $wh['vid'] = ['not in',$vids]; 
				$list = model("voice")->where($wh)->order("addtime DESC")->limit(($page-1)*$num, $num)->select();
				foreach($list as $key=>$value){
					$list[$key]['fav_num'] = $value['fav_num'] ? $value['fav_num'] : 0;
					$list[$key]['play_num'] = $value['play_num'];
					$list[$key]['comment_c'] = is_zan($uid, "",$value->voicecomment);
					$list[$key]['comment_t'] = is_zan($uid, "",$value->txtcomment);
					$list[$key]['backgrund'] = $value['backgrund'];
					$list[$key]['allbackgrund'] = $value['allbackgrund'];
					$type['tid'] = $value->getData("tid");
					$type['title'] = $value['tid'];
					$list[$key]['type'] = $type;
					$list[$key]['is_focus'] = 1;
					
					$user['uid'] = $value->getData("uid");
					$user['nickname'] = $value['uid'];
					
					$headpic = $value->face;
					if($headpic){
						$headpic = stristr($headpic,'http') ? $headpic : config("__URL__").config("__UPLOAD__").$headpic;
					}
					$user['headpic'] = $headpic;
					$list[$key]['user'] = $user;
					
					unset($list[$key]['tid']);
					unset($list[$key]['uid']);
				}
				return json($list);	
			}else{
				return json(array());	
			}
		}else{
			return json('呵呵');
		}	
    }
    
	/**
	 * myFavorites 我的收藏
	 * $channel 渠道(必须)  默认值:APP
	 * $uid 用户ID(必须)
	 * $page 页数  默认值:1
	 * $num 数量  默认值:10
	 * 
	 * @return json(vid:语音ID,title:语音标题,user[uid:发布者ID,nickname:发布者昵称,headpic:发布者头像],type[tid:类型ID,title:类型标题],comment_t[user:用户名,content:文字评论内容],comment_c[user:用户名,content:语音评论内容],reurl:语音链接地址,play_num:播放次数,fav_num:收藏次数,comment:评论次数,is_focus:是否关注)
	 * @author zwy
	 */
    public function myFavorites()
    {    	
    	$channel = input("param.channel/s");		
		$uid = input("param.uid/s") ? input("param.uid/s") : "";
		$page = input("param.page/d") && input("param.page/d")> 0 ? input("param.page/d") : 1;
		$num = input("param.num/d") && input("param.num/d")> 0 ? input("param.num/d") : 10;
		
		if($channel=='APP' && $uid){			
			$where['uid'] = $uid;
			$vids = model('favorite')->where($where)->column("vid");
			if($vids){
				$list = model("voice")->where("vid","in",$vids)->order("addtime DESC")->limit(($page-1)*$num, $num)->select();
				foreach($list as $key=>$value){
					$list[$key]['fav_num'] = $value['fav_num'] ? $value['fav_num'] : 0;
					$list[$key]['play_num'] = $value['play_num'];
					$list[$key]['comment_c'] = is_zan($uid, "",$value->voicecomment);
					$list[$key]['comment_t'] = is_zan($uid, "",$value->txtcomment);
					$list[$key]['backgrund'] = $value['backgrund'];
					$list[$key]['allbackgrund'] = $value['allbackgrund'];
					$type['tid'] = $value->getData("tid");
					$type['title'] = $value['tid'];
					$list[$key]['type'] = $type;
					$user['uid'] = $value->getData("uid");
					$user['nickname'] = $value['uid'];
					$list[$key]['is_focus'] = model("focus")->where(["uid"=>$uid,"cid"=>$user['uid']])->value("cid") ? 1 : 0;
					$headpic = $value->face;
					if($headpic){
						$headpic = stristr($headpic,'http') ? $headpic : config("__URL__").config("__UPLOAD__").$headpic;
					}
					$user['headpic'] = $headpic;
					$list[$key]['user'] = $user;
					
					unset($list[$key]['tid']);
					unset($list[$key]['uid']);
				}
				return json($list);	
			}else{
				return json(array());	
			}
		}else{
			return json('呵呵');
		}	
    }

	/**
	 * myVoiceToComments 获取我的语音评论
	 * $channel 渠道(必须)  默认值:APP
	 * $uid 用户ID(必须)
	 * $page 页数  默认值:1
	 * $num 数量  默认值:10
	 * 
	 * @return json(title：语音标题,vid:语音ID,comments:评论[content:评论内容,user:评论人,headpic:评论人头像] )
	 * @author zwy
	 */
	public function myVoiceToComments(){
		$channel = input("param.channel/s");
		$uid = input("param.uid/s");
		$page = input("param.page/d") && input("param.page/d")> 0 ? input("param.page/d") : 1;
		$num = input("param.num/d") && input("param.num/d")> 0 ? input("param.num/d") : 10;
		$type = input("param.type/d") && input("param.type/d")> 0 ? input("param.type/d") : 0;
		if($channel=='APP' && $uid){
			if(is_safe($uid)){
				$where['uid'] = $uid;
				$vids = model('voice')->where($where)->column("vid");
				if($vids){
					$wh['vid'] = array("in",$vids);
					$wh['type'] = 0;
					$list = model('comments')->where($wh)->field("cid,vid,uid,type,content,zan,clength")->order("addtime DESC")->limit(($page-1)*$num, $num)->select();
					$comments = $comm = $titles = array();
					foreach($list as $key=>$row){
						$comments[$key]['user'] = $row['uid'];
						$headpic = $row->userface;
						if($headpic){
							$headpic = stristr($row->userface,'http') ? $row->userface : config("__URL__").config("__UPLOAD__").$row->userface;
						}
						$title = model("voice")->where("vid",$row['vid'])->value("title");
						$vid = $row['vid'];
						$comments[$key]['headpic'] = $headpic;
						$comments[$key]['content'] = $row->getData("content");
						if(empty($comm[$vid])){
							$titles[] = $title;
							$comm[$vid][0] = $comments[$key];
						}else{
							$num = count($comm[$vid]);
							$comm[$vid][$num] = $comments[$key];
						}
					}
					$arr = array();
					$i = 0;
					foreach($comm as $k=>$row){
						$arr[$i]['vid'] = $k;
						$arr[$i]['title'] = $titles[$i];
						$arr[$i]['comments'] = $row;
						$i++;
					}
					return json($arr);
				}else{
					return json(array());
				}
			}else{
				return json("呵呵");
			}
		}else{
			return json('呵呵');
		}
	}
    
	/**
	 * dianZan 点赞
	 * $channel 渠道(必须)  默认值:APP
	 * $uid 用户ID(必须)
	 * $cid 评论ID(必须)
	 * 
	 * @return json(sid:状态[1:成功,0:失败,-1:已经点过赞])
	 * @author zwy
	 */
	public function dianZan()
    {    	
    	$channel = input("param.channel/s");		
		$uid = input("param.uid/s") ? input("param.uid/s") : "";
		$cid = input("param.cid/s") ? input("param.cid/s") : "";
		
		if($channel=='APP' && $uid && $cid){
			$zan = model("zan")->where(["cid"=>$cid,"uid"=>$uid])->value("zid");
			if($zan){
				return json(array("status"=>-1,"info"=>"你已经点过赞了"));
			}else{	
				$res = model("zan")->insert(['uid'=>$uid,'cid'=>$cid,'addtime'=>time()]);			
				if($res){
					model("comments")->where("cid",$cid)->setInc('zan');
					return json(array("status"=>1,"info"=>""));
				}else{
					return json(array("status"=>0,"info"=>"点赞失败"));
				}
			}			
		}else{
			return json('呵呵');
		}	
    }
	
	/**
	 * playnum  播放次数收集 
	 * $channel 渠道(必须)  默认值:APP
	 * $vid 语音ID(必须)
	 * 
	 * @return json(sid:状态[1:成功,0:失败,-1:语音ID有误])
	 * @author zwy
	 */
	public function playnum()
    {    	
    	$channel = input("param.channel/s");		
		$vid = input("param.vid/s") ? input("param.vid/s") : "";
		
		if($channel=='APP' && $vid){
			$voice = model("voice")->where("vid",$vid)->value("vid");
			if($voice){
				$res = model("voice")->where("vid",$vid)->setInc("play_num");		
				if($res){
					return json(array("status"=>1,"info"=>"成功"));
				}else{
					return json(array("status"=>0,"info"=>"失败"));
				}
			}else{	
				return json(array("status"=>-1,"info"=>"语音ID有误"));
			}			
		}else{
			return json('呵呵');
		}	
    }
	
	/**
	 * guanzhu 一键取消/关注
	 * $channel 渠道(必须)  默认值:APP
	 * $uid 用户ID(必须)
	 * $cid 被关注人的ID(必须)
	 * 
	 * @return json(sid:状态[1:成功,0:失败])
	 * @author zwy
	 */
	public function guanzhu()
    {    	
    	$channel = input("param.channel/s");		
		$uid = input("param.uid/s") ? input("param.uid/s") : "";
		$cid = input("param.cid/s") ? input("param.cid/s") : "";
		$quxiao = input("param.quxiao/d") ? input("param.quxiao/s") : 1;
		
		if($channel=='APP' && $uid && $cid){
			$focus = model("focus")->where(["uid"=>$uid,"cid"=>$cid])->value("cid");
			if($focus){
				$res = model("focus")->where(["uid"=>$uid,"cid"=>$cid])->delete();
				if($res){
					return json(array("status"=>1,"res"=>"0","info"=>"取消关注成功"));
				}else{
					return json(array("status"=>0,"info"=>"取消关注失败"));
				}
			}else{
				$res = db("focus")->insert(['uid'=>$uid,'cid'=>$cid,'addtime'=>time()]);			
				if($res){
					return json(array("status"=>1,"res"=>"1","info"=>"关注成功"));
				}else{
					return json(array("status"=>0,"info"=>"关注失败"));
				}
			}	
		}else{
			return json('呵呵');
		}	
    }
	
	/**
	 * favorites 一键(取消)收藏
	 * $channel 渠道(必须)  默认值:APP
	 * $uid 用户ID(必须)
	 * $vid 被收藏的语音ID(必须)
	 * $quxiao 是否取消收藏   默认 0
	 * 
	 * @return json(status:状态[1:成功,0:失败,2:已收藏])
	 * @author zwy
	 */
	public function favorites()
    {    	
    	$channel = input("param.channel/s");		
		$uid = input("param.uid/s") ? input("param.uid/s") : "";
		$vid = input("param.vid/s") ? input("param.vid/s") : "";
		$quxiao = input("param.quxiao/d") ? input("param.quxiao/d") : 0;
		
		if($channel=='APP' && $uid && $vid){
			$favorite = model("favorite")->where(["uid"=>$uid,"vid"=>$vid])->value("vid");
			if($favorite){
				if($quxiao){
					$wh['uid'] = $uid;
					$wh['vid'] = $vid;	
					$res = model("favorite")->where($wh)->delete();
					if($res){
						return json(array("status"=>1,"info"=>"取消收藏成功"));
					}else{
						return json(array("status"=>0,"info"=>"取消收藏失败"));
					}
				}else{
					return json(array("status"=>2,"info"=>"该语音以被当前用收藏"));
				}
			}else{
				$res = model("favorite")->insert(['uid'=>$uid,'vid'=>$vid,'addtime'=>time()]);			
				if($res){
					return json(array("status"=>1,"info"=>"收藏成功"));
				}else{
					return json(array("status"=>0,"info"=>"收藏失败"));
				}
			}	
		}else{
			return json('呵呵');
		}	
    }
	
	/**
	 * getUser 获取用户基本信息
	 * $channel 渠道(必须)  默认值:APP
	 * $uid 当前浏览用户ID(必须)
	 * $cid 被获取的用户ID(必须)
	 * 
	 * @return json(info[nickname:昵称,headpic:头像,guanzhu:关注数量,fensi:粉丝数量])
	 * @author zwy
	 */
    public function getUser()
    {    	
    	$channel = input("param.channel/s");		
		$uid = input("param.uid/s") ? input("param.uid/s") : "";
		$cid = input("param.cid/s") ? input("param.cid/s") : "";
		
		if($channel=='APP' && $uid){	
			$user = model("user")->where("uid",$cid)->field("uid,nickname,headpic")->find();
			$info['nickname'] = $user['nickname'];
			$info['headpic'] = stristr($user['headpic'],'http') ? $user['headpic'] : config("__URL__").config("__UPLOAD__").$user['headpic'];
			$info['guanzhu'] = $user->nums['bnum'];
			$info['fensi'] = $user->nums['cnum'];
			//$info['is_guanzhu'] = model("focus")->where(["uid"=>$uid,"cid"=>$cid])->value("cid") ? 1 : 0;
			return json($info);	
		}else{
			return json('呵呵');
		}	
    }
	
	/**
	 * uploadOSS 上传
	 * $channel 渠道(必须)  默认值:APP
	 * $uid 用户ID(必须)
	 * $backgroud 背景裁剪图片
	 * $allbackgroud 背景图片原图
	 * $reurl 语音路径
	 * 
	 * @return json(backgroud:图片地址,reurl:语音地址)
	 * @author zwy
	 */
	public function uploadOSS(){
		$channel = input("param.channel/s");		
		$uid = input("param.uid/s") ? input("param.uid/s") : "";
		
		if($channel=='APP' && $uid){
			$data = myUpload($uid);
			if(is_array($data)){
				return json(array("status"=>1,"info"=>"上传成功","data"=>$data));
			}else{
				return json(array("status"=>0,"info"=>"上传失败","data"=>$data));	
			}
		}else{
			return json("呵呵");
		}		
	}
}
