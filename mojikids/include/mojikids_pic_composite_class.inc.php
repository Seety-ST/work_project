<?php
/*
 * 图片合成类
 */
class mojikids_pic_composite_class extends POCO_TDG
{

    //图片保存路径
    private $img_path = "/disk/data/htdocs233/pic0/";

    //模版路径
    private $conf_path = "/disk/data/htdocs232/conf/mojikids/";
    /**
     * 构造函数
     */
    public function __construct()
    {

    }


    /**
     * 裁缩略图
     * @param $width
     * @param $height
     * @param $source_pic
     * @param $target_pic
     * @param string $size
     */
    public function resize_pic($width,$height,$source_pic,$target_pic,$size="600x600")
    {
        //宽小于高
        if($width<$height)
        {
            $cmd = "convert -quality 100 -strip {$source_pic} -resize {$size}^ -gravity center -extent {$size}+0-20 {$target_pic}";
            system($cmd,$output);
        }
        else
        {
            $cmd = "convert -quality 100 -strip {$source_pic} -resize {$size}^ -gravity center -extent {$size}+0-0 {$target_pic}";
            system($cmd,$output);
        }
    }


    /**
     * 图片合成
     * @param string $type boy,girl,poster1到poster9
     * @param array $pic_array 图片  传一维数组
     * @param array $text_array 文字 传一维数组
     * @return array
     */
    public function composite_pic($type,$pic_array,$text_array)
    {
        $month = date("Ym");
        $file_dir = $this->img_path."mojikids/composite/{$month}/final/";
        $tmp_file_dir = $this->img_path."mojikids/composite/{$month}/tmp/";

        if (!file_exists($file_dir))
        {
            mkdir($file_dir,0777,true);
        }

        if (!file_exists($tmp_file_dir))
        {
            mkdir($tmp_file_dir,0777,true);
        }

        $pic_str = implode(',',$pic_array);
        $text_str = implode(',',$text_array);
        $date = date("Ymd");
        $final_md5 = md5($type.$pic_str.$text_str.$date);

        $final_pic = $file_dir.$final_md5.".jpg";

        $arial_font_path = $this->conf_path."font/arial.ttf";

        if(file_exists($final_pic))
        {
            $ret['final_pic'] = $final_pic;
            return $ret;
        }

        $log_arr['type'] = $type;
        $log_arr['pic_array'] = $pic_array;
        $log_arr['text_array'] = $text_array;
        $log_arr['final_pic'] = $final_pic;
        pai_log_class::add_log($log_arr, 'composite_pic', 'composite_pic');

        switch($type)
        {
            case "boy":

                $source_pic = $pic_array[0];

                $text = $text_array[0];

                $tpl_pic = $this->conf_path."pic_tpl/m2/2.jpg";

                $text_bg = $this->conf_path."pic_tpl/m2/text_bg2.png";

                $jia = $this->conf_path."pic_tpl/m2/jia1.png";

                $font_path = $this->conf_path."font/msyhbd.ttc";

                $tmp_pic_jpg = $tmp_file_dir."tmp_jpg_".rand(100000,999999).time().".jpg";

                $tmp_pic_png = $tmp_file_dir."tmp_png_".rand(100000,999999).time().".png";

                $img_size = getimagesize ( $source_pic );


                $this->resize_pic($img_size[0],$img_size[1],$source_pic,$tmp_pic_jpg,"600x600");

                $cmd = "convert {$tmp_pic_jpg}  \( +clone  -alpha extract  -draw 'fill black polygon 0,0 0,15 15,0 fill white circle 15,15 15,0'   \( +clone -flip \) -compose Multiply -composite  \( +clone -flop \) -compose Multiply -composite  \) -alpha off -compose CopyOpacity -composite  {$tmp_pic_png}";
                system($cmd,$output);

                $cmd = "composite -quality 100 -geometry +0-238 -gravity center {$tmp_pic_png} {$tpl_pic} {$final_pic}";
                system($cmd,$output);

                $cmd = "composite -quality 100 -geometry +0+95 -gravity center {$text_bg} {$final_pic} {$final_pic}";
                system($cmd,$output);

                $cmd = "composite -quality 100 -geometry -200-540 -gravity center {$jia} {$final_pic} {$final_pic}";
                system($cmd,$output);

                $cmd = "convert -font {$font_path} -gravity center  -fill black -pointsize 40 -draw \"text 0,95 '`echo {$text}|iconv -f GB2312 -t UTF-8`'\" {$final_pic} {$final_pic}";
                system($cmd,$output);

                break;

            case "girl":

                $source_pic = $pic_array[0];

                $text = $text_array[0];

                $tpl_pic = $this->conf_path."pic_tpl/m2/1.jpg";

                $text_bg = $this->conf_path."pic_tpl/m2/text_bg1.png";

                $jia = $this->conf_path."pic_tpl/m2/jia1.png";

                $font_path = $this->conf_path."font/msyhbd.ttc";

                $tmp_pic_jpg = $tmp_file_dir."tmp_jpg_".rand(100000,999999).time().".jpg";

                $tmp_pic_png = $tmp_file_dir."tmp_png_".rand(100000,999999).time().".png";

                $img_size = getimagesize ( $source_pic );

                $this->resize_pic($img_size[0],$img_size[1],$source_pic,$tmp_pic_jpg,"600x600");


                $cmd = "convert {$tmp_pic_jpg}  \( +clone  -alpha extract  -draw 'fill black polygon 0,0 0,15 15,0 fill white circle 15,15 15,0'   \( +clone -flip \) -compose Multiply -composite  \( +clone -flop \) -compose Multiply -composite  \) -alpha off -compose CopyOpacity -composite  {$tmp_pic_png}";
                system($cmd,$output);

                $cmd = "composite -quality 100 -geometry +0-238 -gravity center {$tmp_pic_png} {$tpl_pic} {$final_pic}";
                system($cmd,$output);

                $cmd = "composite -quality 100 -geometry +0+95 -gravity center {$text_bg} {$final_pic} {$final_pic}";
                system($cmd,$output);

                $cmd = "composite -quality 100 -geometry -200-540 -gravity center {$jia} {$final_pic} {$final_pic}";
                system($cmd,$output);

                $cmd = "convert -font {$font_path} -gravity center  -fill black -pointsize 40 -draw \"text 0,95 '`echo {$text}|iconv -f GB2312 -t UTF-8`'\" {$final_pic} {$final_pic}";
                system($cmd,$output);

                break;


            case "poster1":

                $source_pic = $pic_array[0];

                $tmp_pic_jpg = $tmp_file_dir."tmp_jpg_poster1_".rand(100000,999999).time().".jpg";

                $tpl_pic = $this->conf_path."pic_tpl/m1/1.jpg";

                $img_size = getimagesize ( $source_pic );

                $this->resize_pic($img_size[0],$img_size[1],$source_pic,$tmp_pic_jpg,"640x640");

                $cmd = "composite -quality 100 -geometry +0-73 -gravity center {$tmp_pic_jpg} {$tpl_pic} {$final_pic}";
                system($cmd,$output);

                $day = date("Y.m.d");
                $cmd = "convert -font {$arial_font_path} -pointsize 25 -fill black -gravity center -draw \"text -248,570 '{$day}'\" {$final_pic} {$final_pic}";
                system($cmd,$output);

                break;

            case "poster2":

                $source_pic1 = $pic_array[0];
                $source_pic2 = $pic_array[1];

                $tmp_pic_jpg1 = $tmp_file_dir."tmp_jpg_poster2_1".rand(100000,999999).time().".jpg";
                $tmp_pic_jpg2 = $tmp_file_dir."tmp_jpg_poster2_2".rand(100000,999999).time().".jpg";

                $tpl_pic = $this->conf_path."pic_tpl/m1/2.jpg";

                $times_png = $this->conf_path."pic_tpl/m1/times.png";

                $img_size1 = getimagesize ( $source_pic1 );
                $img_size2 = getimagesize ( $source_pic2 );

                $this->resize_pic($img_size1[0],$img_size1[1],$source_pic1,$tmp_pic_jpg1,"540x540");

                $this->resize_pic($img_size2[0],$img_size2[1],$source_pic2,$tmp_pic_jpg2,"410x410");


                $cmd = "composite -quality 100 -geometry -52-217 -gravity center {$tmp_pic_jpg1} {$tpl_pic} {$final_pic}";
                system($cmd,$output);

                $cmd = "composite -quality 100 -geometry -118+304 -gravity center {$tmp_pic_jpg2} {$final_pic} {$final_pic}";
                system($cmd,$output);

                $cmd = "composite -geometry +180+180 -gravity center {$times_png} {$final_pic} {$final_pic}";
                system($cmd,$output);

                $day = date("Y.m.d");
                $cmd = "convert -font {$arial_font_path} -pointsize 30 -fill black -gravity center -draw \"text -248,600 '{$day}'\" {$final_pic} {$final_pic}";
                system($cmd,$output);

                break;

            case "poster3":

                $source_pic1 = $pic_array[0];
                $source_pic2 = $pic_array[1];
                $source_pic3 = $pic_array[2];

                $tmp_pic_jpg1 = $tmp_file_dir."tmp_jpg_poster3_1".rand(1000000,9999999).time().".jpg";
                $tmp_pic_jpg2 = $tmp_file_dir."tmp_jpg_poster3_2".rand(1000000,9999999).time().".jpg";
                $tmp_pic_jpg3 = $tmp_file_dir."tmp_jpg_poster3_3".rand(1000000,9999999).time().".jpg";

                $tpl_pic = $this->conf_path."pic_tpl/m1/3.jpg";

                $img_size1 = getimagesize ( $source_pic1 );
                $img_size2 = getimagesize ( $source_pic2 );
                $img_size3 = getimagesize ( $source_pic3 );

                $this->resize_pic($img_size1[0],$img_size1[1],$source_pic1,$tmp_pic_jpg1,"365x365");

                $this->resize_pic($img_size2[0],$img_size2[1],$source_pic2,$tmp_pic_jpg2,"365x365");

                $this->resize_pic($img_size3[0],$img_size3[1],$source_pic3,$tmp_pic_jpg3,"365x365");

                $cmd = "composite -quality 100 -geometry +123-374 -gravity center {$tmp_pic_jpg1} {$tpl_pic} {$final_pic}";
                system($cmd,$output);

                $cmd = "composite -quality 100 -geometry +123+373 -gravity center {$tmp_pic_jpg2} {$final_pic} {$final_pic}";
                system($cmd,$output);

                $cmd = "composite -quality 100 -geometry -125+0 -gravity center {$tmp_pic_jpg3} {$final_pic} {$final_pic}";
                system($cmd,$output);

                $day = date("Y.m.d");
                $cmd = "convert -font {$arial_font_path} -pointsize 25 -fill black -gravity center -draw \"text -188,310 '{$day}'\" {$final_pic} {$final_pic}";
                system($cmd,$output);

                break;

            case "poster4":

                $source_pic1 = $pic_array[0];
                $source_pic2 = $pic_array[1];
                $source_pic3 = $pic_array[2];
                $source_pic4 = $pic_array[3];

                $tmp_pic_jpg1 = $tmp_file_dir."tmp_jpg_poster4_1".rand(10000000,99999999).time().".jpg";
                $tmp_pic_jpg2 = $tmp_file_dir."tmp_jpg_poster4_2".rand(10000000,99999999).time().".jpg";
                $tmp_pic_jpg3 = $tmp_file_dir."tmp_jpg_poster4_3".rand(10000000,99999999).time().".jpg";
                $tmp_pic_jpg4 = $tmp_file_dir."tmp_jpg_poster4_4".rand(10000000,99999999).time().".jpg";

                $tpl_pic = $this->conf_path."pic_tpl/m1/4.jpg";

                $img_size1 = getimagesize ( $source_pic1 );
                $img_size2 = getimagesize ( $source_pic2 );
                $img_size3 = getimagesize ( $source_pic3 );
                $img_size4 = getimagesize ( $source_pic4 );

                $this->resize_pic($img_size1[0],$img_size1[1],$source_pic1,$tmp_pic_jpg1,"365x365");

                $this->resize_pic($img_size2[0],$img_size2[1],$source_pic2,$tmp_pic_jpg2,"285x285");

                $this->resize_pic($img_size3[0],$img_size3[1],$source_pic3,$tmp_pic_jpg3,"285x285");

                $this->resize_pic($img_size4[0],$img_size4[1],$source_pic4,$tmp_pic_jpg4,"365x365");


                $cmd = "composite -quality 100 -geometry -148-350 -gravity center {$tmp_pic_jpg1} {$tpl_pic} {$final_pic}";
                system($cmd,$output);

                $cmd = "composite -quality 100 -geometry +188-10 -gravity center {$tmp_pic_jpg2} {$final_pic} {$final_pic}";
                system($cmd,$output);

                $cmd = "composite -quality 100 -geometry -148+364 -gravity center {$tmp_pic_jpg4} {$final_pic} {$final_pic}";
                system($cmd,$output);

                $cmd = "composite -quality 100 -geometry +188+288 -gravity center {$tmp_pic_jpg3} {$final_pic} {$final_pic}";
                system($cmd,$output);

                $day = date("Y.m.d");
                $cmd = "convert -font {$arial_font_path} -pointsize 27 -fill black -gravity center -draw \"text +122,-410 '{$day}'\" {$final_pic} {$final_pic}";
                system($cmd,$output);
                break;

            case "poster5":

                $source_pic1 = $pic_array[0];
                $source_pic2 = $pic_array[1];
                $source_pic3 = $pic_array[2];
                $source_pic4 = $pic_array[3];
                $source_pic5 = $pic_array[4];

                $tmp_pic_jpg1 = $tmp_file_dir."tmp_jpg_poster5_1".rand(10000000,99999999).time().".jpg";
                $tmp_pic_jpg2 = $tmp_file_dir."tmp_jpg_poster5_2".rand(10000000,99999999).time().".jpg";
                $tmp_pic_jpg3 = $tmp_file_dir."tmp_jpg_poster5_3".rand(10000000,99999999).time().".jpg";
                $tmp_pic_jpg4 = $tmp_file_dir."tmp_jpg_poster5_4".rand(10000000,99999999).time().".jpg";
                $tmp_pic_jpg5 = $tmp_file_dir."tmp_jpg_poster5_5".rand(10000000,99999999).time().".jpg";

                $tpl_pic = $this->conf_path."pic_tpl/m1/5.jpg";

                $img_size1 = getimagesize ( $source_pic1 );
                $img_size2 = getimagesize ( $source_pic2 );
                $img_size3 = getimagesize ( $source_pic3 );
                $img_size4 = getimagesize ( $source_pic4 );
                $img_size5 = getimagesize ( $source_pic5 );

                $this->resize_pic($img_size1[0],$img_size1[1],$source_pic1,$tmp_pic_jpg1,"270x270");

                $this->resize_pic($img_size2[0],$img_size2[1],$source_pic2,$tmp_pic_jpg2,"296x296");

                $this->resize_pic($img_size3[0],$img_size3[1],$source_pic3,$tmp_pic_jpg3,"296x296");

                $this->resize_pic($img_size4[0],$img_size4[1],$source_pic4,$tmp_pic_jpg4,"296x296");

                $this->resize_pic($img_size5[0],$img_size5[1],$source_pic5,$tmp_pic_jpg5,"296x296");


                $cmd = "composite -quality 100 -geometry -176-356 -gravity center {$tmp_pic_jpg1} {$tpl_pic} {$final_pic}";
                system($cmd,$output);

                $cmd = "composite -quality 100 -geometry -165+73 -gravity center {$tmp_pic_jpg2} {$final_pic} {$final_pic}";
                system($cmd,$output);

                $cmd = "composite -quality 100 -geometry +166+73 -gravity center {$tmp_pic_jpg3} {$final_pic} {$final_pic}";
                system($cmd,$output);

                $cmd = "composite -quality 100 -geometry -165+403 -gravity center {$tmp_pic_jpg4} {$final_pic} {$final_pic}";
                system($cmd,$output);

                $cmd = "composite -quality 100 -geometry +166+403 -gravity center {$tmp_pic_jpg5} {$final_pic} {$final_pic}";
                system($cmd,$output);

                $day = date("Y.m.d");
                $cmd = "convert -font {$arial_font_path} -pointsize 27 -fill black -gravity center -draw \"text +78,-377 '{$day}'\" {$final_pic} {$final_pic}";
                system($cmd,$output);

                break;

            case "poster6":

                $source_pic1 = $pic_array[0];
                $source_pic2 = $pic_array[1];
                $source_pic3 = $pic_array[2];
                $source_pic4 = $pic_array[3];
                $source_pic5 = $pic_array[4];
                $source_pic6 = $pic_array[5];

                $tmp_pic_jpg1 = $tmp_file_dir."tmp_jpg_poster6_1".rand(10000000,99999999).time().".jpg";
                $tmp_pic_jpg2 = $tmp_file_dir."tmp_jpg_poster6_2".rand(10000000,99999999).time().".jpg";
                $tmp_pic_jpg3 = $tmp_file_dir."tmp_jpg_poster6_3".rand(10000000,99999999).time().".jpg";
                $tmp_pic_jpg4 = $tmp_file_dir."tmp_jpg_poster6_4".rand(10000000,99999999).time().".jpg";
                $tmp_pic_jpg5 = $tmp_file_dir."tmp_jpg_poster6_5".rand(10000000,99999999).time().".jpg";
                $tmp_pic_jpg6 = $tmp_file_dir."tmp_jpg_poster6_6".rand(10000000,99999999).time().".jpg";

                $tpl_pic = $this->conf_path."pic_tpl/m1/6.jpg";

                $img_size1 = getimagesize ( $source_pic1 );
                $img_size2 = getimagesize ( $source_pic2 );
                $img_size3 = getimagesize ( $source_pic3 );
                $img_size4 = getimagesize ( $source_pic4 );
                $img_size5 = getimagesize ( $source_pic5 );
                $img_size6 = getimagesize ( $source_pic6 );

                $this->resize_pic($img_size1[0],$img_size1[1],$source_pic1,$tmp_pic_jpg1,"269x269");

                $this->resize_pic($img_size2[0],$img_size2[1],$source_pic2,$tmp_pic_jpg2,"269x269");

                $this->resize_pic($img_size3[0],$img_size3[1],$source_pic3,$tmp_pic_jpg3,"269x269");

                $this->resize_pic($img_size4[0],$img_size4[1],$source_pic4,$tmp_pic_jpg4,"269x269");

                $this->resize_pic($img_size5[0],$img_size5[1],$source_pic5,$tmp_pic_jpg5,"269x269");

                $this->resize_pic($img_size6[0],$img_size6[1],$source_pic6,$tmp_pic_jpg6,"269x269");


                $cmd = "composite -quality 100 -geometry -82-404 -gravity center {$tmp_pic_jpg1} {$tpl_pic} {$final_pic}";
                system($cmd,$output);

                $cmd = "composite -quality 100 -geometry +195-404 -gravity center {$tmp_pic_jpg2} {$final_pic} {$final_pic}";
                system($cmd,$output);

                $cmd = "composite -quality 100 -geometry -82-127 -gravity center {$tmp_pic_jpg3} {$final_pic} {$final_pic}";
                system($cmd,$output);

                $cmd = "composite -quality 100 -geometry +195-127 -gravity center {$tmp_pic_jpg4} {$final_pic} {$final_pic}";
                system($cmd,$output);

                $cmd = "composite -quality 100 -geometry +195+151 -gravity center {$tmp_pic_jpg5} {$final_pic} {$final_pic}";
                system($cmd,$output);

                $cmd = "composite -quality 100 -geometry +195+428 -gravity center {$tmp_pic_jpg6} {$final_pic} {$final_pic}";
                system($cmd,$output);

                $day = date("Y.m.d");
                $cmd = "convert -font {$arial_font_path} -pointsize 30 -fill black -gravity center -draw \"text -80,+385 '{$day}'\" {$final_pic} {$final_pic}";
                system($cmd,$output);

                break;

            case "poster7":

                $source_pic1 = $pic_array[0];
                $source_pic2 = $pic_array[1];
                $source_pic3 = $pic_array[2];
                $source_pic4 = $pic_array[3];
                $source_pic5 = $pic_array[4];
                $source_pic6 = $pic_array[5];
                $source_pic7 = $pic_array[6];

                $tmp_pic_jpg1 = $tmp_file_dir."tmp_jpg_poster7_1".rand(10000000,99999999).time().".jpg";
                $tmp_pic_jpg2 = $tmp_file_dir."tmp_jpg_poster7_2".rand(10000000,99999999).time().".jpg";
                $tmp_pic_jpg3 = $tmp_file_dir."tmp_jpg_poster7_3".rand(10000000,99999999).time().".jpg";
                $tmp_pic_jpg4 = $tmp_file_dir."tmp_jpg_poster7_4".rand(10000000,99999999).time().".jpg";
                $tmp_pic_jpg5 = $tmp_file_dir."tmp_jpg_poster7_5".rand(10000000,99999999).time().".jpg";
                $tmp_pic_jpg6 = $tmp_file_dir."tmp_jpg_poster7_6".rand(10000000,99999999).time().".jpg";
                $tmp_pic_jpg7 = $tmp_file_dir."tmp_jpg_poster7_7".rand(10000000,99999999).time().".jpg";

                $tpl_pic = $this->conf_path."pic_tpl/m1/7.jpg";

                $img_size1 = getimagesize ( $source_pic1 );
                $img_size2 = getimagesize ( $source_pic2 );
                $img_size3 = getimagesize ( $source_pic3 );
                $img_size4 = getimagesize ( $source_pic4 );
                $img_size5 = getimagesize ( $source_pic5 );
                $img_size6 = getimagesize ( $source_pic6 );
                $img_size7 = getimagesize ( $source_pic7 );

                $this->resize_pic($img_size1[0],$img_size1[1],$source_pic1,$tmp_pic_jpg1,"216x216");

                $this->resize_pic($img_size2[0],$img_size2[1],$source_pic2,$tmp_pic_jpg2,"216x216");

                $this->resize_pic($img_size3[0],$img_size3[1],$source_pic3,$tmp_pic_jpg3,"439x439");

                $this->resize_pic($img_size4[0],$img_size4[1],$source_pic4,$tmp_pic_jpg4,"224x224");

                $this->resize_pic($img_size5[0],$img_size5[1],$source_pic5,$tmp_pic_jpg5,"224x224");

                $this->resize_pic($img_size6[0],$img_size6[1],$source_pic6,$tmp_pic_jpg6,"224x224");

                $this->resize_pic($img_size7[0],$img_size7[1],$source_pic7,$tmp_pic_jpg7,"224x224");


                $cmd = "composite -quality 100 -geometry -226-367 -gravity center {$tmp_pic_jpg1} {$tpl_pic} {$final_pic}";
                system($cmd,$output);

                $cmd = "composite -quality 100 -geometry -226-144 -gravity center {$tmp_pic_jpg2} {$final_pic} {$final_pic}";
                system($cmd,$output);

                $cmd = "composite -quality 100 -geometry +107-256 -gravity center {$tmp_pic_jpg3} {$final_pic} {$final_pic}";
                system($cmd,$output);

                $cmd = "composite -quality 100 -geometry -12+256 -gravity center {$tmp_pic_jpg4} {$final_pic} {$final_pic}";
                system($cmd,$output);

                $cmd = "composite -quality 100 -geometry +219+256 -gravity center {$tmp_pic_jpg5} {$final_pic} {$final_pic}";
                system($cmd,$output);

                $cmd = "composite -quality 100 -geometry -12+487 -gravity center {$tmp_pic_jpg6} {$final_pic} {$final_pic}";
                system($cmd,$output);

                $cmd = "composite -quality 100 -geometry +219+487 -gravity center {$tmp_pic_jpg7} {$final_pic} {$final_pic}";
                system($cmd,$output);

                $day = date("Y.m.d");
                $cmd = "convert -font {$arial_font_path} -pointsize 25 -fill black -gravity center -draw \"text +275,+35 '{$day}'\" {$final_pic} {$final_pic}";
                system($cmd,$output);

                break;

            case "poster8":

                $source_pic1 = $pic_array[0];
                $source_pic2 = $pic_array[1];
                $source_pic3 = $pic_array[2];
                $source_pic4 = $pic_array[3];
                $source_pic5 = $pic_array[4];
                $source_pic6 = $pic_array[5];
                $source_pic7 = $pic_array[6];
                $source_pic8 = $pic_array[7];

                $tmp_pic_jpg1 = $tmp_file_dir."tmp_jpg_poster8_1".rand(10000000,99999999).time().".jpg";
                $tmp_pic_jpg2 = $tmp_file_dir."tmp_jpg_poster8_2".rand(10000000,99999999).time().".jpg";
                $tmp_pic_jpg3 = $tmp_file_dir."tmp_jpg_poster8_3".rand(10000000,99999999).time().".jpg";
                $tmp_pic_jpg4 = $tmp_file_dir."tmp_jpg_poster8_4".rand(10000000,99999999).time().".jpg";
                $tmp_pic_jpg5 = $tmp_file_dir."tmp_jpg_poster8_5".rand(10000000,99999999).time().".jpg";
                $tmp_pic_jpg6 = $tmp_file_dir."tmp_jpg_poster8_6".rand(10000000,99999999).time().".jpg";
                $tmp_pic_jpg7 = $tmp_file_dir."tmp_jpg_poster8_7".rand(10000000,99999999).time().".jpg";
                $tmp_pic_jpg8 = $tmp_file_dir."tmp_jpg_poster8_8".rand(10000000,99999999).time().".jpg";

                $tpl_pic = $this->conf_path."pic_tpl/m1/8.jpg";

                $img_size1 = getimagesize ( $source_pic1 );
                $img_size2 = getimagesize ( $source_pic2 );
                $img_size3 = getimagesize ( $source_pic3 );
                $img_size4 = getimagesize ( $source_pic4 );
                $img_size5 = getimagesize ( $source_pic5 );
                $img_size6 = getimagesize ( $source_pic6 );
                $img_size7 = getimagesize ( $source_pic7 );
                $img_size8 = getimagesize ( $source_pic8 );

                $this->resize_pic($img_size1[0],$img_size1[1],$source_pic1,$tmp_pic_jpg1,"216x216");

                $this->resize_pic($img_size2[0],$img_size2[1],$source_pic2,$tmp_pic_jpg2,"216x216");

                $this->resize_pic($img_size3[0],$img_size3[1],$source_pic3,$tmp_pic_jpg3,"439x439");

                $this->resize_pic($img_size4[0],$img_size4[1],$source_pic4,$tmp_pic_jpg4,"249x249");

                $this->resize_pic($img_size5[0],$img_size5[1],$source_pic5,$tmp_pic_jpg5,"249x249");

                $this->resize_pic($img_size6[0],$img_size6[1],$source_pic6,$tmp_pic_jpg6,"216x216");

                $this->resize_pic($img_size7[0],$img_size7[1],$source_pic7,$tmp_pic_jpg7,"216x216");

                $this->resize_pic($img_size8[0],$img_size8[1],$source_pic8,$tmp_pic_jpg8,"216x216");


                $cmd = "composite -quality 100 -geometry -222-475 -gravity center {$tmp_pic_jpg1} {$tpl_pic} {$final_pic}";
                system($cmd,$output);

                $cmd = "composite -quality 100 -geometry -222-252 -gravity center {$tmp_pic_jpg2} {$final_pic} {$final_pic}";
                system($cmd,$output);

                $cmd = "composite -quality 100 -geometry +111-364 -gravity center {$tmp_pic_jpg3} {$final_pic} {$final_pic}";
                system($cmd,$output);

                $cmd = "composite -quality 100 -geometry +206-14 -gravity center {$tmp_pic_jpg4} {$final_pic} {$final_pic}";
                system($cmd,$output);

                $cmd = "composite -quality 100 -geometry +206+241 -gravity center {$tmp_pic_jpg5} {$final_pic} {$final_pic}";
                system($cmd,$output);

                $cmd = "composite -quality 100 -geometry -222+480 -gravity center {$tmp_pic_jpg6} {$final_pic} {$final_pic}";
                system($cmd,$output);

                $cmd = "composite -quality 100 -geometry +0+480 -gravity center {$tmp_pic_jpg7} {$final_pic} {$final_pic}";
                system($cmd,$output);

                $cmd = "composite -quality 100 -geometry +223+480 -gravity center {$tmp_pic_jpg8} {$final_pic} {$final_pic}";
                system($cmd,$output);

                $day = date("Y.m.d");
                $cmd = "convert -font {$arial_font_path} -pointsize 25 -fill black -gravity center -draw \"text -145,+115 '{$day}'\" {$final_pic} {$final_pic}";
                system($cmd,$output);

                break;

            case "poster9":

                $source_pic1 = $pic_array[0];
                $source_pic2 = $pic_array[1];
                $source_pic3 = $pic_array[2];
                $source_pic4 = $pic_array[3];
                $source_pic5 = $pic_array[4];
                $source_pic6 = $pic_array[5];
                $source_pic7 = $pic_array[6];
                $source_pic8 = $pic_array[7];
                $source_pic9 = $pic_array[8];

                $tmp_pic_jpg1 = $tmp_file_dir."tmp_jpg_poster9_1".rand(10000000,99999999).time().".jpg";
                $tmp_pic_jpg2 = $tmp_file_dir."tmp_jpg_poster9_2".rand(10000000,99999999).time().".jpg";
                $tmp_pic_jpg3 = $tmp_file_dir."tmp_jpg_poster9_3".rand(10000000,99999999).time().".jpg";
                $tmp_pic_jpg4 = $tmp_file_dir."tmp_jpg_poster9_4".rand(10000000,99999999).time().".jpg";
                $tmp_pic_jpg5 = $tmp_file_dir."tmp_jpg_poster9_5".rand(10000000,99999999).time().".jpg";
                $tmp_pic_jpg6 = $tmp_file_dir."tmp_jpg_poster9_6".rand(10000000,99999999).time().".jpg";
                $tmp_pic_jpg7 = $tmp_file_dir."tmp_jpg_poster9_7".rand(10000000,99999999).time().".jpg";
                $tmp_pic_jpg8 = $tmp_file_dir."tmp_jpg_poster9_8".rand(10000000,99999999).time().".jpg";
                $tmp_pic_jpg9 = $tmp_file_dir."tmp_jpg_poster9_9".rand(10000000,99999999).time().".jpg";

                $tpl_pic = $this->conf_path."pic_tpl/m1/9.jpg";

                $img_size1 = getimagesize ( $source_pic1 );
                $img_size2 = getimagesize ( $source_pic2 );
                $img_size3 = getimagesize ( $source_pic3 );
                $img_size4 = getimagesize ( $source_pic4 );
                $img_size5 = getimagesize ( $source_pic5 );
                $img_size6 = getimagesize ( $source_pic6 );
                $img_size7 = getimagesize ( $source_pic7 );
                $img_size8 = getimagesize ( $source_pic8 );
                $img_size9 = getimagesize ( $source_pic9 );

                $this->resize_pic($img_size1[0],$img_size1[1],$source_pic1,$tmp_pic_jpg1,"216x216");

                $this->resize_pic($img_size2[0],$img_size2[1],$source_pic2,$tmp_pic_jpg2,"216x216");

                $this->resize_pic($img_size3[0],$img_size3[1],$source_pic3,$tmp_pic_jpg3,"439x439");

                $this->resize_pic($img_size4[0],$img_size4[1],$source_pic4,$tmp_pic_jpg4,"215x215");

                $this->resize_pic($img_size5[0],$img_size5[1],$source_pic5,$tmp_pic_jpg5,"215x215");

                $this->resize_pic($img_size6[0],$img_size6[1],$source_pic6,$tmp_pic_jpg6,"215x215");

                $this->resize_pic($img_size7[0],$img_size7[1],$source_pic7,$tmp_pic_jpg7,"215x215");

                $this->resize_pic($img_size8[0],$img_size8[1],$source_pic8,$tmp_pic_jpg8,"215x215");

                $this->resize_pic($img_size9[0],$img_size9[1],$source_pic9,$tmp_pic_jpg9,"215x215");


                $cmd = "composite -quality 100 -geometry -222-478 -gravity center {$tmp_pic_jpg1} {$tpl_pic} {$final_pic}";
                system($cmd,$output);

                $cmd = "composite -quality 100 -geometry -222-255 -gravity center {$tmp_pic_jpg2} {$final_pic} {$final_pic}";
                system($cmd,$output);

                $cmd = "composite -quality 100 -geometry +111-367 -gravity center {$tmp_pic_jpg3} {$final_pic} {$final_pic}";
                system($cmd,$output);

                $cmd = "composite -quality 100 -geometry -223+258 -gravity center {$tmp_pic_jpg4} {$final_pic} {$final_pic}";
                system($cmd,$output);

                $cmd = "composite -quality 100 -geometry -1+258 -gravity center {$tmp_pic_jpg5} {$final_pic} {$final_pic}";
                system($cmd,$output);

                $cmd = "composite -quality 100 -geometry +222+258 -gravity center {$tmp_pic_jpg6} {$final_pic} {$final_pic}";
                system($cmd,$output);

                $cmd = "composite -quality 100 -geometry -223+481 -gravity center {$tmp_pic_jpg7} {$final_pic} {$final_pic}";
                system($cmd,$output);

                $cmd = "composite -quality 100 -geometry -1+481 -gravity center {$tmp_pic_jpg8} {$final_pic} {$final_pic}";
                system($cmd,$output);

                $cmd = "composite -quality 100 -geometry +222+481 -gravity center {$tmp_pic_jpg9} {$final_pic} {$final_pic}";
                system($cmd,$output);

                $day = date("Y.m.d");
                $cmd = "convert -font {$arial_font_path} -pointsize 25 -fill black -gravity center -draw \"text +270,+15 '{$day}'\" {$final_pic} {$final_pic}";
                system($cmd,$output);

                break;

        }

        $ret['final_pic'] = $final_pic;
        return $ret;
    }
}
