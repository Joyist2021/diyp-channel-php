##�汾˵��##
##21:15 2024/2/26
����xml2db.php���ݴ����߼�,
(1)����$deletetoday����,�� ����1ʱ,ÿ��ͬ��xml����ǰ��ɾ�����켰δ����epg��Ŀ������
(2)���Ķ�epg url�ɼ�Դʱ,���ɼ��Ⱥ�˳��,��ͬƵ����epg����,�������״βɼ�������

##22:01 2024/1/14
�޸�live.php, ���� m=1 ����,
/live.php?t=m3u ���ȥ���Һϲ�url
/live.php?t=m3u&m=1 ���ȥ��url
�޸�epg.php, ���epg_channel����channel_id��һ����Ӧ��name, ����Ҫע��epg_channel������˳��

##0:25 2024/1/14
����live.php 0111�汾���롰ͬ����ͬƵ����url��ַ����ȥ�غͺϲ�������Ƶ�������쳣������
�ϲ�m3u.php json.php live.php tvbox.phpΪ1���ļ�live.php��ͨ�����η��ز�ͬ��ʽ��Ƶ������
/live.php
/live.php?t=txt
/live.php?t=m3u
/live.php?t=json
/live.php?t=tvbox

##16:30 2024/1/11
�Ż�channel.php����sql�ű��Ĵ����߼�,ʵ���뵼�루�ٷ��汾[http://www.phpliteadmin.org/]����200����ֱ��Դ����Ҫ������������ʱʧ�ܣ�
�Ż�epg.php������xml��ʽ�����http://127.0.1/diyp/epg.php?ch=xml
�Ż�m3u.php��live.php���ݴ����߼�,��ͬ����ͬƵ����url��ַ����ȥ�غͺϲ�(#�ŷָ�)
�Ż�info.php������PHP�Ѽ���ģ���⣬������ȷ��php�����Ƿ����㱾Դ������Ҫ��

## 9:24 2024/1/2
�޸�channel.php ����php5.4 php7.4 PHP8.0

���в������ģ�����php��չ�Ƿ��������ģ�飺
extension=curl
extension=gettext
extension=openssl
extension=pdo_sqlite
extension=sockets
extension=soap
extension=sqlite3

## 14:34 2023/12/22
����epg.php�����ӷ�����־���Ʋ���$isw, Ĭ�Ϲر���־, �ɼ���epg��ѯ�ٶ�

## 11:29 2023/12/21
����epg.php���޸Ļ������

## 11:10 2023/12/19
����epg.php������$utf8����,���޸Ĵ�������л�,��$utf8 = trueʱ���UTF-8���������,$utf8 = falseʱ���Unicode���������

## 0:01 2023/12/19
����epg.php�����س���ֱ����epg����Ϊ�����ʽ���������ģ�

## 16:50 2023/12/17
����m3u.php�ӿ�, ����listƵ��������,����m3u��ʽ���ļ�

## 21:25 2023/12/12
������php�ļ�������myfile.php��������php7.4����,��֧��php5.4, Ĭ���û���Ϊadmin ����Ϊadmin123��ǿ�ҽ����޸����롣
Ϊ���ٷ���,ǿ�ҽ����޸��ļ���,���ⱻ�µ�����
http://127.0.0.1/myfile.php


## 11:49 2023/12/10
��ǿepg.php�ļ�����, Ĭ��ʹ��memcache���湦��, �Զ��ж�php�Ƿ����memcahce���


## 13:55 2023/12/9
����xml2db,֧��gzip,֧��https
����epg.php, Ĭ�Ϸ��ص���CCTV1�Ľ�Ŀ��,�������
����tvbox.php, ֧��TVBOX���ܽӿ�,ֻ��ֱ��Ƶ���ӿ�, ����ֱ���ӿں�EPG�ӿ�


## 23:17 2023/12/8
����xml2db,֧�ֶ��xmlԴ
����epg_channel�Ĺ�����ʶ,��Ϊtv_tag,����дƵ������


## 16:38 2023/12/7 
����epg.php, ���ݳ���ֱ����epg����
����ֱ��// http://127.0.0.1/epg.php?channel=cctv2&date=20231206
DIYPӰ��// http://127.0.0.1/epg.php?ch=CCTV2&date=2023-12-06


## 22:18 2023/12/4
## ������PHP5.x ��PHP7.x��web����
�ļ�˵��:
channel.php    :���ݿ����ҳ��
channel_epg.db :���ݿ�
epg.php        :epg�ӿ�,����DIYP��TVBOX������ֱ��
info.php       :php��������Ϣ��ѯҳ��
json.php       :json��ʽƵ���ӿ�,���ó���ֱ��
live.php       :txt��ʽƵ���ӿ�,����DIYP��,Ҳ������TVBOX��
tvbox.php      :json��ʽ�ӿ�,����TVBOX���ܽӿ�
xml2db.php     :epg���ݻ�ȡҳ��,�Զ�ͬ��xml��ʽ��epg���ݴ���channel_epg.db
�汾˵��.txt   :���ļ���PHP�汾�������� admin ��ʽʹ��ǰ��༭channel.php���޸ĵ�¼����
img.php        :ͬ��bingÿ��һͼ������ͼƬ����Ҫphp7.x֧��
m3u.php        :m3u��ʽ�ӿ�
myfile.php     :���׵������ļ������ߣ���������ļ�������ʹ�ã�
                ���޸ĵ�40�е����룬����ɾ�����ļ������ⱻ���

ʹ��˵����
1. PHP�汾��������admin�����channel.php�ļ����޸ķ������롣
2. ���channel_epg.db���ݿ���,����ɾ����,�����������һ��xml2db.php,�������ݿ��ʼ��
3. ���Ӱ�װֱ��APP��DIYPӰ��Final��.apk �� TVBOX
4. ����php��������Ĭ�ϱ���,�޸�php.ini,  
   �ҵ�default_charset,�޸�Ϊ default_charset = "UTF-8" ,��ȥ��ǰ��ķֺ�
   �ҵ�timezone,�޸�ʱ��Ϊdate.timezone=Asia/Shanghai ,��ȥ��ǰ��ķֺ� 
   �������memcache����������о���

php��չ�����������ģ�飺
extension=curl
extension=gettext
extension=openssl
extension=pdo_sqlite
extension=sockets
extension=soap
extension=sqlite3

##===================================================================
##<����ı���¼>��
EPG �����Ŀ�����أ�
http://epg.51zmt.top:8000/e.xml
http://epg.erw.cc/e.xml
http://epg.112114.xyz/pp.xml

Diyp & �ٴ�	���ѡ��ڵ�
https://epg.112114.xyz/ 
https://epg.112114.eu.org/
https://diyp.112114.xyz/
����ֱ��	https://epg.112114.xyz/epginfo
Xml��ʽ	https://epg.112114.xyz/pp.xml
https://epg.112114.xyz/pp.xml.gz
Bingÿ��ͼƬ	https://epg.112114.xyz/bingimg

��ȥ�����Ŀ�����أ�
https://epg.erw.cc/all.xml

DIYP epg�ӿ�
https://epg.erw.cc/api/diyp/

����һ�£�  һ�е�ǰ���Ǳ�������tvg-name �� tvg-id �Ż��н�Ŀ�� (���е����ͬ��̨Ƶ������Ĭ��ת�ɼ�������)
��DIYPӰ���� ����� https://epg.erw.cc/api/diyp/
��Kodi����https://epg.erw.cc/e.xml   ���� https://epg.erw.cc/cc.xml   ��ѡһ
��aptv��https://epg.erw.cc/api/diyp/  https://epg.erw.cc/e.xml  https://epg.erw.cc/cc.xml  ��ѡһ
��TV+�� ��https://epg.erw.cc/e.xml   ���� https://epg.erw.cc/cc.xml   ��ѡһ
