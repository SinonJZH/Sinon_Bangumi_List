=== Sinon的追番列表 ===
Contributors: sinonjzh
Donate link: https://sinon.top/donate/
Tags: bangumi_list
Requires at least: 4.6
Tested up to: 5.1.1
Stable tag: 1.1.3
Requires PHP: 5.2.4
License: GPLv3
License URI: https://www.gnu.org/licenses/gpl-3.0.html

Sinon的追番列表插件，安装插件后，使用短代码[bangumi]在页面上生成追番列表，在“工具”菜单中配置追番列表。

== Description ==

Sinon的追番列表插件，安装插件后，使用短代码[bangumi]在页面上生成追番列表，在“工具”菜单中配置追番列表。
本插件使用[bangumi 番组计划](http://bgm.tv/)所提供的API获取番剧数据，
请在使用时先去此网站上搜索对应的番剧id(即对应的番剧页面url中的那串数字)，
输入后插件会自动调用API获取番剧信息并保存。
## 注意
本插件使用了第三方api,api提供者为[bangumi 番组计划](http://bgm.tv/),api地址为：http://bgm.tv。
详细api文档请参见：https://github.com/bangumi/api。
请注意，目前而言bgm.tv是一个可靠的网站，但不保证该网站api服务提供始终有效，作者不对任何因调用api而产生的问题负责。
另外也请参阅bgm.tv的版权声明:https://bgm.tv/about/copyright。

This plugin uses a third-party api, the api provider is [bangumi 番组计划] (http://bgm.tv/), and the api address is: http://bgm.tv.
Detailed api documentation can be found at https://github.com/bangumi/api.
Please note that bgm.tv is currently a reliable website, but there is no guarantee that the website api service will always be available. 
The author is not responsible for any problems caused by calling the api.
Also see the copyright notice for bgm.tv: https://bgm.tv/about/copyright.

== Installation ==

1.在WordPress插件面板上传插件，或在插件搜索栏直接搜索插件，安装并启用。
2.在“工具”菜单中配置追番列表。

== Frequently Asked Questions ==

= 如何获取番剧id？ =

请至bgm.tv上搜索番剧并查看id(在番剧页面url)


== Screenshots ==

1. /assets/screenshot-1.png
2. /assets/screenshot-2.png

== Changelog ==

= 1.1.3 =
* 完成WordPress插件标准化，加强安全性。

= 1.1.2 =
* 根据WordPress插件规范对插件做出修改。

= 1.1.1 =
* 取消占位格子显示。

= 1.1 =
* 优化了后台菜单样式，添加了删除指定id的番剧以及删除所有番剧的功能。

= 1.0 =
* 提供基础的番剧列表生成和管理功能，后续会添加其他新的功能。

== Upgrade Notice ==

= 1.1.3 =
* 停用并删除旧版本插件，下载新版本插件，使用原来的方式安装即可，用户数据不会被删除。

== Arbitrary section ==

None