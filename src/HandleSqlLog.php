<?php

namespace Cyokup\SqlLog;

use Closure;
use Illuminate\Config\Repository;
use Illuminate\Support\Facades\DB;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class HandleSqlLog
{

    protected $config;

    public function __construct(Repository $config)
    {
        $this->config = $config->get('sqllog');
    }


    public function handle($request, Closure $next)
    {
        if (!$this->config['sql_debug']) {
            return $next($request);
        }
        // 定义新的日志文件
        $monolog = $this->monolog();
        DB::listen(
            function ($query) use ($monolog, $request) {
                $tmp = str_replace('?', '"' . '%s' . '"', $query->sql);
                $qBindings = [];
                foreach ($query->bindings as $key => $value) {
                    if (is_numeric($key)) {
                        $qBindings[] = $value;
                    } else {
                        $tmp = str_replace(':' . $key, '"' . $value . '"', $tmp);
                    }
                }
                $tmp = vsprintf($tmp, $qBindings);
                $tmp = str_replace("\\", "", $tmp);
                $monolog->info($tmp . " [RunTime: $query->time ms]\n\t", $request->all());
            }
        );
        return $next($request);
    }

    /**
     * 重新定义一个sql日志文件
     */
    public function monolog()
    {
        $monolog = new Logger('sql');
        $dateFormat = "Y-m-d H:i:s";
        $formatter = new LineFormatter(null, $dateFormat);
        // 创建文件处理器并设置格式化器
        $handler = new StreamHandler(storage_path('logs/sql.log'), Logger::INFO);
        $handler->setFormatter($formatter);
        // 将处理器添加到日志器
        return $monolog->pushHandler($handler);
    }

}
