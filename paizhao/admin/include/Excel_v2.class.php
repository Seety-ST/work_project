<?php
/**
 * @desc:   ����excel������ ��ʽΪxls
 * @User:   xiao xiao (xiaojm@yueus.com)
 * @Date:   2015/7/23
 * @Time:   15:26
 * version: 1.0
 */
/*$head = array(
     array('title'=> '��1','width'=>200),
     array('title'=> '��2','width'=>200),
     array('title'=> '��3','width'=>200),
     array('title'=> '��4','width'=>200)
);
$data = array(
    array('2014-04-06 12:12:15','2014-04-06 12:12:15','2014-04-06 12:12:15','2014-04-06 12:12:15'),
    array('2014-04-06 12:12:15','2014-04-06 12:12:15','2014-04-06 12:12:15','2014-04-06 12:12:15'),
    array('2014-04-06 12:12:15','2014-04-06 12:12:15','2014-04-06 12:12:15','2014-04-06 12:12:15'),
    array('2014-04-06 12:12:15','2014-04-06 12:12:15','2014-04-06 12:12:15','2014-04-06 12:12:15')
);
Excel_v3::start($head,$data,'�ֻ�','s3');*/

class Excel_v2{
    /**
     * excel�ĵ�ͷ(���ص���)
     *
     * ����excel xml�淶��
     * @access private
     * @var string
     */
    private  static $header = "<?xml version=\"1.0\" encoding=\"GBK\"?\>
	<Workbook xmlns=\"urn:schemas-microsoft-com:office:spreadsheet\"
	xmlns:x=\"urn:schemas-microsoft-com:office:excel\"
	xmlns:ss=\"urn:schemas-microsoft-com:office:spreadsheet\"
	xmlns:html=\"http://www.w3.org/TR/REC-html40\">";
    /**
     * excelҳ��
     * ����excel xml�淶��
     *
     * @access private
     * @var string
     */
    private static $footer = "</Workbook>";

    /**
     * �ĵ���(��������)
     *
     * @access private
     * @var array
     */
    private static $lines = array ();

    private static $column = "";

    /**
     * ������(����)
     *
     * @access private
     * @var array
     */
    private static $worksheets = array ();
    /**
     * ��Ԫ����ʽ
     * @access private
     * @var string
     */
    private static $cellstyle = array(
        's1'=>'<Style ss:ID="s1"><Alignment ss:Horizontal="Center" ss:WrapText="1"/><Borders><Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1"/><Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1"/><Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1"/><Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1"/></Borders><Font ss:FontName="Tahoma" x:CharSet="134" ss:Size="11"/><Interior ss:Color="#FFFFFF" ss:Pattern="Solid"/></Style>',
        's2'=>'<Style ss:ID="s2"><Alignment ss:Horizontal="Center" ss:WrapText="1"/><Borders><Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1"/><Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1"/><Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1"/><Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1"/></Borders><Font ss:FontName="����" x:CharSet="134" ss:Size="11"/><Interior ss:Color="#FFFFFF" ss:Pattern="Solid"/></Style>',
        's3'=>'<Style ss:ID="s3"><Alignment ss:Horizontal="Center" ss:WrapText="1"/><Borders><Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1"/><Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1"/><Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1"/><Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1"/></Borders><Font ss:FontName="����" x:CharSet="134" ss:Size="11" ss:Color="#FF0000"/><Interior ss:Color="#FFFFFF" ss:Pattern="Solid"/></Style>'
    );

    /**
     * Ĭ�ϵ�Ԫ�����ݸ�ʽ
     * @access private
     * @var string
     */
    private static $default_cellformat = "String";

    private static  $default_headStyle ="s1";
    private static  $default_bodyStyle ="s2";
    /**
     * @var int
     */
    private static $cnt = 0; // ������
    /**
     * @var int
     */
    private static $limit = 100000;// ÿ��$limit�У�ˢ��һ�����buffer����Ҫ̫��Ҳ��Ҫ̫С

    public static function start($head,$body,$filename='',$headStyle='',$bodyStyle='')
    {
        if(!$filename)
            $filename = date('YmdHis',time());
        $encoded_filename = urlencode($filename);
        $encoded_filename = str_replace("+", "%20", $encoded_filename);
        //ͷ
        $ua = $_SERVER["HTTP_USER_AGENT"];
        header("Content-Type: application/vnd.ms-excel");
        if(preg_match("/MSIE/", $ua)){
            header('Content-Disposition: attachment; filename="'.$encoded_filename.'.xls"');
        }else if(preg_match("/Firefox/", $ua)){
            header('Content-Disposition: attachment; filename*="GBK\'\''.$filename.'.xls"');
        }else{
            header('Content-Disposition: attachment; filename="'.$filename.'.xls"');
        }
        header('Cache-Control: max-age=0');
        if(strlen($headStyle) >1) self::$default_headStyle = $headStyle;
        if(strlen($bodyStyle) >1) self::$default_bodyStyle = $bodyStyle;
        self::addHeader($head,$headStyle);
        self::addBody($body,$bodyStyle);
        self::addWorksheet($filename);
        echo stripslashes (self::$header);
        //��ʽ
        echo "\n<Styles>";
        foreach((array)self::$cellstyle as $k=>$v){
            echo "\n".$v;
        }
        echo "\n</Styles>";
        echo implode ("\n", self::$worksheets);
        echo self::$footer;
    }

    public static function addWorksheet($sheettitle)
    {
        //�޳������ַ�
        $sheettitle = preg_replace ("/[\\\|:|\/|\?|\*|\[|\]]/", "", $sheettitle);
        //����,������ٵ�����ĳ���
        $sheettitle = substr ($sheettitle, 0, 50);
        self::$worksheets[] = "\n<Worksheet ss:Name=\"$sheettitle\">\n<Table ss:DefaultRowHeight=\"20\">\n".
            self::$column."\n".
            implode ("\n", self::$lines).
            "</Table>\n</Worksheet>\n";
    }
    public static function addHeader($array){
        $cells = '';
        $column = '';
        $i = 1;
        $style_str = 'ss:StyleID="'.self::$default_headStyle.'"';
        foreach ($array as $k => $v){
            $width = 200;
            $title = '';
            if(is_array($v)){//�������ʱ��
                $title = trim($v['title']);
                $width = intval($v['width']) == 0 ? 200:intval($v['width']);
            }else{
                $title = trim($v);
            }
            $format_str = self::$default_cellformat;
            $cells .= "<Cell {$style_str}><Data ss:Type=\"{$format_str}\">{$title}</Data></Cell>\n";
            $column .= "<Column ss:Index=\"{$i}\" ss:AutoFitWidth=\"0\" ss:Width=\"{$width}\"/>";
            $i++;
        }
        //��ӱ�ͷ��������
        self::$lines[] = "<Row>\n" . $cells . "</Row>\n";
        self::$column = $column;
    }

    /**
     * ��ӵ�������
     *
     * @access private
     * @param array 1ά����
     * @todo �д���
     */
    private static function addRow ($array)
    {
        //��ʼ����Ԫ��
        $cells = "";
        //������Ԫ��
        $style_str = 'ss:StyleID="'.self::$default_bodyStyle.'"';
        $format_str = self::$default_cellformat;
        foreach ($array as $k => $v){
            $v= htmlspecialchars($v,ENT_COMPAT);
            $cells .= "<Cell {$style_str}><Data ss:Type=\"{$format_str}\">{$v}</Data></Cell>\n";
        }
        //����������
        self::$lines[] = "<Row>\n" . $cells . "</Row>\n";
    }

    public static function addBody($arr){
        foreach($arr as $arrBody){
            self::$cnt ++;
            if (self::$limit == self::$cnt) { //ˢ��һ�����buffer����ֹ�������ݹ����������
                ob_flush();
                flush();
                self::$cnt = 0;
            }
            self::addRow($arrBody);
        }
    }

    /**
     * ת�뺯��
     *
     * @param mixed $content
     * @param string $from
     * @param string $to
     * @return mixed
     */
    public function charset($content, $from='gbk', $to='utf-8') {
        $from = strtoupper($from) == 'UTF8' ? 'utf-8' : $from;
        $to = strtoupper($to) == 'UTF8' ? 'utf-8' : $to;
        if (strtoupper($from) === strtoupper($to) || empty($content)) {
            //���������ͬ��ת��
            return $content;
        }
        if (function_exists('mb_convert_encoding')) {
            if (is_array($content)){
                $content = var_export($content, true);
                $content = mb_convert_encoding($content, $to, $from);
                eval("\$content = $content;");return $content;
            }else {
                return mb_convert_encoding($content, $to, $from);
            }
        } elseif (function_exists('iconv')) {
            if (is_array($content)){
                $content = var_export($content, true);
                $content = iconv($from, $to, $content);
                eval("\$content = $content;");return $content;
            }else {
                return iconv($from,$to,$content);
            }
        } else {
            return $content;
        }
    }
}


