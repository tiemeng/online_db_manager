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
use PhpOffice\PhpSpreadsheet\Spreadsheet;
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
     * @param bool $path
     * @return bool
     * @throws \Exception
     */
    public static function sendEmail(
        string $to,
        string $name,
        string $content,
        string $subject = "SQL申请处理结果",
        bool $path = false
    ) {

        $mail = new PHPMailer(true);
        $attachFile = public_path().'/demo.xlsx';
        try {
            $mail->isSMTP();
            $mail->CharSet = "utf-8"; //utf-8;
            $mail->Encoding = "base64";
            $mail->Host = env('EMAIL_HOST');
            $mail->SMTPAuth = true;
            $mail->Username = env('EMAIL_USERNAME');
            $mail->Password = env('EMAIL_PASSWORD');
            $mail->SMTPSecure = env('EMAIL_SMTPSECURE');
            $mail->Port = env('EMAIL_PORT');
            $mail->setFrom(env('EMAIL_USERNAME'));
            $mail->addAddress($to, $name);
            $mail->isHTML(true);
            if($path){
                $mail->addAttachment($attachFile);
            }
            $mail->Subject = $subject;
            $mail->Body = $content;

            return $mail->send();
        } catch (\Exception $e) {
            \Log::debug($mail->ErrorInfo);
            throw new \Exception("Message could not be sent. Mailer Error: {$mail->ErrorInfo}");
        }
    }

    /**
     * 发送邮件消息队列
     * @param array $data
     * @return string
     * @throws \Exception
     */
    public static function applyNotice(array $data)
    {
        try {
            return ReQueue::getInstance()
                ->setName("sendEmail")
                ->setClassName("ApplyQueue")
                ->setData($data)
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

    /**
     * 导出excel
     * @param array $data
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public static function exportExcel(array $data)
    {
        if (empty($data)) {
            throw new \Exception("导出数据不能为空");
        }
        set_time_limit(0);
        ini_set('memory_limit', -1);
        $title = array_keys($data[0]);

        $newExcel = new Spreadsheet();  //创建一个新的excel文档
        $objSheet = $newExcel->getActiveSheet();  //获取当前操作sheet的对象
        $objSheet->setTitle('管理员表');  //设置当前sheet的标题
        $count = count($title);

        //设置宽度为true,不然太窄了
        $start = $i = 65;
        $end = 90;
        foreach ($title as $k => $value) {
            $col = chr($i);
            if ($i > $end) {
                $col .= chr($start + ($i - $end) - 1);
            }
            $objSheet->getColumnDimension($col)->setAutoSize(true);
            $objSheet->setCellValue($col . "1", $value);
            $i++;
        }


        //第二行起，每一行的值,setCellValueExplicit是用来导出文本格式的。
        //->setCellValueExplicit('C' . $k, $val['admin_password']PHPExcel_Cell_DataType::TYPE_STRING),可以用来导出数字不变格式
        foreach ($data as $k => $val) {
            $k = $k + 2;
            for ($j = $start; $j < $start + $count; $j++) {
                $col = chr($j);
                if ($j > $end) {
                    $col .= chr($start + ($j - $end) - 1);
                }
                $objSheet->setCellValue($col . $k, $val[$title[$j - 65]]);
            }
        }

        $format = 'Xlsx';
        // $format只能为 Xlsx 或 Xls
        if ($format == 'Xlsx') {
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        } elseif ($format == 'Xls') {
            header('Content-Type: application/vnd.ms-excel');
        }

        header("Content-Disposition: attachment;filename="
            . "管理员表" . date('Y-m-d') . '.' . strtolower($format));
        header('Cache-Control: max-age=0');
        $objWriter = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($newExcel, $format);

        $dir = "./demo.xlsx";
        //通过php保存在本地的时候需要用到
        $objWriter->save($dir);

        header('Cache-Control: max-age=1');
//        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0

    }

}