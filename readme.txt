##版本说明##
##21:15 2024/2/26
更新xml2db.php数据处理逻辑,
(1)增加$deletetoday参数,当 等于1时,每次同步xml数据前先删除当天及未来的epg节目单数据
(2)更改多epg url采集源时,按采集先后顺序,相同频道的epg数据,仅保存首次采集的数据

##22:01 2024/1/14
修改live.php, 增加 m=1 参数,
/live.php?t=m3u 输出去重且合并url
/live.php?t=m3u&m=1 输出去重url
修改epg.php, 输出epg_channel表中channel_id第一条对应的name, 所以要注意epg_channel表数据顺序

##0:25 2024/1/14
修正live.php 0111版本引入“同分组同频道的url地址进行去重和合并”导致频道排序异常的问题
合并m3u.php json.php live.php tvbox.php为1个文件live.php，通过传参返回不同格式的频道数据
/live.php
/live.php?t=txt
/live.php?t=m3u
/live.php?t=json
/live.php?t=tvbox

##16:30 2024/1/11
优化channel.php导入sql脚本的处理逻辑,实现秒导入（官方版本[http://www.phpliteadmin.org/]导入200多条直播源数据要几分钟甚至超时失败）
优化epg.php，新增xml格式输出，http://127.0.1/diyp/epg.php?ch=xml
优化m3u.php和live.php数据处理逻辑,对同分组同频道的url地址进行去重和合并(#号分隔)
优化info.php，增加PHP已加载模块检测，更方便确认php环境是否满足本源码允许要求

## 9:24 2024/1/2
修改channel.php 兼容php5.4 php7.4 PHP8.0

运行不正常的，请检查php扩展是否加载以下模块：
extension=curl
extension=gettext
extension=openssl
extension=pdo_sqlite
extension=sockets
extension=soap
extension=sqlite3

## 14:34 2023/12/22
更新epg.php，增加访问日志控制参数$isw, 默认关闭日志, 可加速epg查询速度

## 11:29 2023/12/21
更新epg.php，修改缓存规则

## 11:10 2023/12/19
更新epg.php，增加$utf8参数,请修改代码进行切换,当$utf8 = true时输出UTF-8编码的数据,$utf8 = false时输出Unicode编码的数据

## 0:01 2023/12/19
更新epg.php，返回超级直播的epg数据为编码格式（不是明文）

## 16:50 2023/12/17
新增m3u.php接口, 基于list频道表数据,返回m3u格式的文件

## 21:25 2023/12/12
新增单php文件管理工具myfile.php，适用于php7.4环境,不支持php5.4, 默认用户名为admin 密码为admin123，强烈建议修改密码。
为减少风险,强烈建议修改文件名,避免被猜到爆破
http://127.0.0.1/myfile.php


## 11:49 2023/12/10
加强epg.php的兼容性, 默认使用memcache缓存功能, 自动判断php是否加载memcahce插件


## 13:55 2023/12/9
更新xml2db,支持gzip,支持https
更新epg.php, 默认返回当天CCTV1的节目单,方便调试
新增tvbox.php, 支持TVBOX的总接口,只含直播频道接口, 配置直播接口和EPG接口


## 23:17 2023/12/8
更新xml2db,支持多个xml源
更新epg_channel的关联标识,改为tv_tag,即大写频道名字


## 16:38 2023/12/7 
升级epg.php, 兼容超级直播的epg请求
超级直播// http://127.0.0.1/epg.php?channel=cctv2&date=20231206
DIYP影音// http://127.0.0.1/epg.php?ch=CCTV2&date=2023-12-06


## 22:18 2023/12/4
## 适用于PHP5.x 和PHP7.x的web环境
文件说明:
channel.php    :数据库管理页面
channel_epg.db :数据库
epg.php        :epg接口,兼容DIYP、TVBOX、超级直播
info.php       :php服务器信息查询页面
json.php       :json格式频道接口,适用超级直播
live.php       :txt格式频道接口,适用DIYP壳,也适用于TVBOX壳
tvbox.php      :json格式接口,适用TVBOX壳总接口
xml2db.php     :epg数据获取页面,自动同步xml格式的epg数据存入channel_epg.db
版本说明.txt   :本文件，PHP版本管理密码 admin 正式使用前请编辑channel.php，修改登录密码
img.php        :同步bing每日一图，返回图片，需要php7.x支持
m3u.php        :m3u格式接口
myfile.php     :简易的在线文件管理工具，方便更新文件，如需使用，
                请修改第40行的密码，或者删除该文件，避免被恶搞

使用说明：
1. PHP版本管理密码admin，请打开channel.php文件，修改访问密码。
2. 如果channel_epg.db数据库损坏,可以删除后,再浏览器访问一次xml2db.php,进行数据库初始化
3. 电视安装直播APP：DIYP影音Final版.apk 或 TVBOX
4. 设置php服务器的默认编码,修改php.ini,  
   找到default_charset,修改为 default_charset = "UTF-8" ,或去掉前面的分号
   找到timezone,修改时区为date.timezone=Asia/Shanghai ,且去掉前面的分号 
   建议加载memcache插件（自行研究）

php扩展必须加载以下模块：
extension=curl
extension=gettext
extension=openssl
extension=pdo_sqlite
extension=sockets
extension=soap
extension=sqlite3

##===================================================================
##<零碎的备忘录>：
EPG 当天节目单下载：
http://epg.51zmt.top:8000/e.xml
http://epg.erw.cc/e.xml
http://epg.112114.xyz/pp.xml

Diyp & 百川	点击选择节点
https://epg.112114.xyz/ 
https://epg.112114.eu.org/
https://diyp.112114.xyz/
超级直播	https://epg.112114.xyz/epginfo
Xml格式	https://epg.112114.xyz/pp.xml
https://epg.112114.xyz/pp.xml.gz
Bing每日图片	https://epg.112114.xyz/bingimg

过去三天节目单下载：
https://epg.erw.cc/all.xml

DIYP epg接口
https://epg.erw.cc/api/diyp/

补充一下：  一切的前提是必须配上tvg-name 或 tvg-id 才会有节目单 (已有的祖国同胞台频道名称默认转成简体中文)
【DIYP影音】 用这个 https://epg.erw.cc/api/diyp/
【Kodi】用https://epg.erw.cc/e.xml   或者 https://epg.erw.cc/cc.xml   二选一
【aptv】https://epg.erw.cc/api/diyp/  https://epg.erw.cc/e.xml  https://epg.erw.cc/cc.xml  三选一
【TV+】 用https://epg.erw.cc/e.xml   或者 https://epg.erw.cc/cc.xml   二选一
