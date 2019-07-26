<?php
/**
 * 公共方法类
 * User: tiemeng
 * Date: 2019/7/25
 * Time: 15:51
 */

namespace App\Util;


use App\Models\DbConnection;

class Common
{
    /**
     * 更新数据库配置文件
     * @return bool|int
     * @throws \Exception
     */
    public static function updateDatabaseFile()
    {
        $path = __DIR__ . "/../../config/";
        $fileName = $path."database1.php";
        try {
            $content = file_get_contents($fileName);
            $connections = DbConnection::getConnections();
            $content = str_replace("'connections' => [", "'connections' => [\n" . $connections."\n", $content);
            return file_put_contents($path."database.php", $content);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }



}