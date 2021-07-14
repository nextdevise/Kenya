# backup
composer PHP Mysql备份扩展包

## 链接
- 博客：http://www.mgchen.com
- github：https://github.com/cocolait
- gitee：http://gitee.com/cocolait

# 安装
```php
composer require cocolait/backup
```

# 版本要求
> PHP >= 5.3
> MySQL PDO扩展

# 使用说明
> 该扩展包可以嵌套到任何框架中 不过必须支持版本要求

# 使用案例
```php
<?php
// 加载扩展 TODO 如果你使用的框架已支持composer那么这一行可直接忽略
require_once 'vendor/autoload.php';
// backup($path = '备份路径', $tableArray = [需要备份的表集合], $bool = '是否同时备份数据 默认false',['is_compress' => '是否写入内容文件进行压缩','is_download' => '是否进行下载'])
$config = [
        // 服务器地址
        'host'        => 'xx.xx.xx.xx',
        // 数据库名
        'database'    => 'xxx',
        // 用户名
        'user'        => 'xxx',
        // 密码
        'password'    => 'xxx',
        // 端口
        'port'        => '3306',
        // 字符编码
        'charset'     => 'utf8'
];
// 备份
$dir = "./backup/sql";//备份路径
$data = cocolait\sql\Backup::instance($config)->backUp($dir,[],true,['is_compress' => 0]);
print_r($data);die;

// 还原
$data = cocolait\sql\Backup::instance($config)->recover('xxx_20180512072455_194757120.sql',$dir);
print_r($data);die;
```
