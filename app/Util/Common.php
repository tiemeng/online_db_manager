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
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;

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
    public static function sendEmail(string $to, string $name, string $content, string $subject = "SQL申请处理结果")
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
            \Log::info($e->getMessage());
            throw new \Exception($e->getMessage());
        }

    }

    /**
     * 生成word文档
     * @param $tables
     * @param $tbinfo
     * @return bool
     * @throws \Exception
     */
    public static function generateWord($tables, $tbinfo)
    {
        try {
            $phpWord = new PhpWord();
            $section = $phpWord->addSection();
            $styleTable = ['borderSize' => 6, 'borderColor' => '000000', 'cellMargin' => 80];//表格样式
            $fontStyle = ['align' => 'center'];//文字样式
            $i = 1;
            foreach ($tables as $t => $v) {
                $fontStyleName = 'oneUserDefinedStyle';
                $phpWord->addFontStyle(
                    $fontStyleName,
                    array('name' => '华文仿宋', 'size' => 15, 'color' => '1B2232', 'bold' => true)
                );
                $section->addText(
                    $v ? ($i . "、" . $t . "(" . $v . ")表结构如下") : ($i . "、" . $t . "表结构如下"),
                    $fontStyleName
                );
                $section->addText(PHP_EOL);
                $phpWord->addTableStyle('table_' . $i, $styleTable);//定义表格样式
                $table = $section->addTable('table_' . $i);
                $table->addRow(400);
                $table->addCell(2000)->addText('字段', $fontStyle);
                $table->addCell(2000)->addText('数据类型', $fontStyle);
                $table->addCell(2000)->addText('索引', $fontStyle);
                $table->addCell(2000)->addText('是否为空', $fontStyle);
                $table->addCell(2000)->addText('默认值', $fontStyle);
                $table->addCell(2000)->addText('注释', $fontStyle);
                foreach ($tbinfo[$t] as $item) {
                    $table->addRow(1000);
                    $table->addCell(2000)->addText($item->column_name, $fontStyle);
                    $table->addCell(2000)->addText($item->column_type, $fontStyle);
                    $table->addCell(2000)->addText($item->column_key, $fontStyle);
                    $table->addCell(2000)->addText($item->is_nullable, $fontStyle);
                    $table->addCell(2000)->addText($item->COLUMN_default, $fontStyle);
                    $table->addCell(2000)->addText($item->column_comment, $fontStyle);
                }
                $section->addText(PHP_EOL);
                $i++;
            }

            $objWriter = IOFactory::createWriter($phpWord, 'Word2007');
            $objWriter->save('DataStructure.docx');
            return true;
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }

    }

}