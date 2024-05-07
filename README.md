##  laravel-sql-log
#### 记录Laravel运行过程中的所有Sql语句以及执行时间
#### 安装命令：
- composer require cyokup/laravel-sql-log

#### 发布配置：
- php artisan vendor:publish --provider="Cyokup\SqlLog\SqlLogServiceProvider"
- 全局生效:在app/Http/Kernel.php文件对应位置添加
```angular2html
protected $middleware = [
   // ...
    \Cyokup\SqlLog\HandleSqlLog::class
];
```

#### 简介
- 安装配置完成并且开启后，会在日志目录下生成sql.log文件，记录所有Sql语句以及执行时间。

### 其他
- 欢迎提PR一起完善项目
- 作者微信：cyokup
- 承接网站开发，小程序开发，微信公众号开发，APP开发等定制开发项目
