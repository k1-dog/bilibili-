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
use think\facade\Env;

// 应用公共文件
define('upFileSalt', '我永远喜欢樱岛麻衣鸢一折纸春日野穹');
define('PUBLIC_PATH',env::get('ROOT_PATH').'public\\');
define('UPLOAD_PATH',env::get('ROOT_PATH').'public\uploads\\');
define('FILE_PATH',env::get('ROOT_PATH').'public\uploads\fileUp\\');
define('FONT_PATH',env::get('ROOT_PATH').'public\static\FONT_FILE\\');
define('OPENSSL_CNF_PATH',env::get('ROOT_PATH').'openssl.cnf');