<?php
/**
 * 公共方法类
 * User: tiemeng
 * Date: 2019/7/25
 * Time: 15:51
 */

namespace App\Util;


use App\Models\DbConnection;
use App\Util\PHPMailer\PHPMailer\PHPMailer;

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
        $fileName = $path . "database1.php";
        try {
            $content = file_get_contents($fileName);
            $connections = DbConnection::getConnections();
            $content = str_replace("'connections' => [", "'connections' => [\n" . $connections . "\n", $content);
            return file_put_contents($path . "database.php", $content);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * 发送邮件
     * @param string $to
     * @param string $name
     * @param string $content
     * @param string $subject
     * @return bool
     * @throws \Exception
     */
    public static function sendEmail(string $to, string $name, string $content,string $subject = "SQL申请处理结果")
    {
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = env('EMAIL_HOST');
            $mail->SMTPAuth = true;
            $mail->Username = env('EMAIL_USERNAME');
            $mail->Password = env('EMAIL_PASSWORD');
            $mail->SMTPSecure = 'tls';
            $mail->Port = env('EMAIL_PORT');
            $mail->setFrom(env('EMAIL_USERNAME'));
            $mail->addAddress($to, $name);
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body = $content;

            return $mail->send();
        } catch (\Exception $e) {
            throw new \Exception("Message could not be sent. Mailer Error: {$mail->ErrorInfo}");
        }
    }

    /**
     * 执行sql发送email队列
     * @param string $email
     * @param string $name
     * @param string $msg
     * @return string
     * @throws \Exception
     */
    public static function applyNotice(string $email, string $name, string $msg)
    {
        try {
            return ReQueue::getInstance()
                ->setName("sendEmail")
                ->setClassName("ApplyQueue")
                ->setData(['email' => $email, 'msg' => $msg, 'name' => $name])
                ->run();
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }

    }

}