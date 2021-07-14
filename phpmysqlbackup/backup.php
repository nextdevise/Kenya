<?php

require_once 'vendor/autoload.php';
/*
// backup($path = '备份路径', 
		  $tableArray = [需要备份的表集合], 
		  $bool = '是否同时备份数据 默认false',
		  [
			'is_compress' => '是否写入内容文件进行压缩',
			'is_download' => '是否进行下载'
		  ])

*/
$config = [
        // 服务器地址
        'host'        => 'localhost',
        // 数据库名
        'database'    => 'db_zc',
        // 用户名
        'user'        => 'root',
        // 密码
        'password'    => 'H9MvYSqY3JmAC4aj',
        // 端口
        'port'        => '3306',
        // 字符编码
        'charset'     => 'utf8'
];
// 备份
$dir = "./backup";//备份路径
$data = cocolait\sql\Backup::instance($config)->backUp($dir,[],true,['is_compress' => 0]);
print_r($data);die;






