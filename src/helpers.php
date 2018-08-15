<?php

/**
 * Redis实例
 * @return \Service\RedisService
 */
function redis()
{
    return app()->get('redis');
}

/**
 * @return bool
 */
function is_dev()
{
    return config()->get('environment') == 'dev';
}

/**
 * 获取日志对象
 * @param string $filename 文件名
 * @param int $level 日志等级
 * @return \Monolog\Logger monolog对象
 */
function myLog($filename = "debug", $level = \Monolog\Logger::DEBUG)
{
    //实例化一个Logger对象
    $log = new \Monolog\Logger($filename);
    $log_path = app()->getPath()."/runtime/logs/";

    //日志文件保留天数，0为保留所有，n为保留最近n天的日志，可配置
    $max_file = config()->get("log_limit", 0);
    //是否记录日志，可配置，true为记录，false为不记录
    $is_log = config()->get("is_log", true);
    $log->pushHandler(
        (new \Monolog\Handler\RotatingFileHandler($log_path.$filename, $max_file, $level))
            ->setFormatter(
                new \Monolog\Formatter\LineFormatter(
                    \Monolog\Formatter\LineFormatter::SIMPLE_FORMAT,
                    "H:i:s.u"
                )
            )//记录毫秒数
    );

    if (!$is_log) {
        $log->pushHandler(new \Monolog\Handler\NullHandler($level));
    }
    return $log;
}

/**
 * @return \EasyWeChat\Foundation\Application;
 */
function wechat()
{
    return app()->get("wechat");
}


function error($code)
{
    throw new Exception(\Constant\ErrorCode::msg($code),$code);
}

/**
 * 批量更新
 *
 * $data = [["id"=>xxx,"field"=>xxx],["id"=>xxx,"field"=>xxx]]
 *
 * @param string $table_name 表名
 * @param array $data 更新数组
 * @param string $field 主键字段
 * @return boolean 返回影响行数
 */
function batch_update($table_name = '', $data = array(), $field = 'id') {
    if (!$table_name || !$data || !$field) {
        return false;
    } else {
        $sql = 'UPDATE ' . config()->get('database')['default']['prefix'] .$table_name;
    }
    $con = array();
    $con_sql = array();
    $fields = array();
    foreach ($data as $key => $value) {
        $x = 0;
        foreach ($value as $k => $v) {
            if ($k != $field && empty($con[$x]) && $x == 0) {
                $con[$x] = " set {$k} = (CASE {$field} ";
            } elseif ($k != $field && empty($con[$x]) && $x > 0) {
                $con[$x] = " {$k} = (CASE {$field} ";
            }
            if ($k != $field) {
                $temp = $value[$field];

                empty($con_sql[$x]) && $con_sql[$x] = '';
                $con_sql[$x] .= " WHEN '{$temp}' THEN '{$v}' ";
                $x++;
            }
        }
        $temp = $value[$field];
        if (!in_array($temp, $fields)) {
            $fields[] = $temp;
        }
    }
    $num = count($con) - 1;
    foreach ($con as $key => $value) {
        foreach ($con_sql as $k => $v) {
            if ($k == $key && $key < $num) {
                $sql .= $value . $v . ' end),';
            } elseif ($k == $key && $key == $num) {
                $sql .= $value . $v . ' end)';
            }
        }
    }
    $str = implode(',', $fields);
    $sql .= " where {$field} in({$str})";
    $data = database()->query($sql);
    return $data->rowCount();
}

function levelEndTime($end_time){

    $gap = $end_time - time();

    return bcdiv($gap, 86400, 0);
}

function learnPercentCount($view_count)
{
    $num = bcdiv($view_count, 10);

    return bcmul($num , 100);
}
