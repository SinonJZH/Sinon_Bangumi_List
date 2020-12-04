# Sinon_Bangumi_List
Sinon的追番列表——Wordpress插件
## 更新日志
#### V2.0.1
* Bug Fix
### V2.0
* 代码重构
#### V1.2.5
* 取消待追番状态番剧的周目显示
#### V1.2.4
* 部分体验优化
* 优化代码
* 添加多周目功能
#### V1.2.3
* 允许输入小数格式的追番进度
* 其他小修正
#### V1.2.2
* 后台样式优化
* 优化代码逻辑
#### V1.2.1
* 添加了编辑番剧信息的功能
* 获取番剧信息时，若中文名为空，自动将原名移至中文名一栏
### v1.2
* 添加了在插件内直接搜索番剧的功能
* 添加了后台按状态筛选番剧的过滤器
#### v1.1.4
* 在正在追番的番剧下添加“进度+1”按钮
* 将后台菜单的番剧列表按照追番状态排序
* 优化CSS样式
#### v1.1.3
* 完成WordPress插件标准化，加强安全性。
#### v1.1.2
* 根据WordPress插件规范对插件做出修改。
#### v1.1.1
* 取消占位格子显示。
### v1.1
* 优化了后台菜单样式，添加了删除指定id的番剧以及删除所有番剧的功能。
### v1.0
* 提供基础的番剧列表生成和管理功能，后续会添加其他新的功能。

## 项目发布及预览
[预览地址](https://sinon.top/bangumi)
发布地址：见该仓库的release

## 更新方法
停用并删除旧版本插件，下载新版本插件，使用原来的方式安装即可，用户数据不会被删除。

## 安装方法
下载GitHub中release的zip文件，在WordPress的插件安装界面上传安装后即可使用。

## 使用方法
本插件使用[bangumi 番组计划](http://bgm.tv/)所提供的API获取番剧数据。
使用时只需在插件页面搜索番剧名称，并在搜索结果中选择要添加的番剧添加即可，
也可以在番组计划的官网搜索番剧id(即对应的番剧页面url中的那串数字)，
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
