<?php
namespace App\Libraries;

use App\Model\SeoSettings;

class Functions
{
    function activity_log($mod_pavadinimas, $text) {
        if (isset($_SESSION['tvs_user'])) {
            mysql_query("INSERT INTO activity_log (`user`, `module`, `timestamp`, `text`) VALUES ('$_SESSION[tvs_user]', '$mod_pavadinimas', '".time()."', '$text')");
        }
    }

    function add_p ($str) {
        $tmp = split("[\r\n]+", $str);
        $tmp = implode('</p><p>', $tmp);
        $tmp = "<p>$tmp</p>";
        return $tmp;
    }

    function copy_resized($file, $new_name, $max_prod_width, $max_prod_height, $add_watermark = false) {
        // 1 = GIF, 2 = JPG, 3 = PNG, 4 = SWF, 5 = PSD, 6 = BMP, 7 = TIFF(intel byte order), 8 = TIFF(motorola byte order), 9 = JPC, 10 = JP2, 11 = JPX, 12 = JB2, 13 = SWC, 14 = IFF, 15 = WBMP, 16 = XBM.
        if (($size_array=getimagesize($file['tmp_name'])) && ($size_array[2]<=3)) {
            list($width_orig, $height_orig) = $size_array;

            $prod_width = $max_prod_width;
            $prod_height = $max_prod_height;

            if (($width_orig>$prod_width) || ($height_orig>$prod_height)) {
                if (($width_orig/$prod_width) < ($height_orig/$prod_height)) {
                    $prod_width = ($prod_height / $height_orig) * $width_orig;
                } else {
                    $prod_height = ($prod_width / $width_orig) * $height_orig;
                }
            } else {
                $prod_width = $width_orig;
                $prod_height = $height_orig;
            }

            switch ($size_array[2]) {
                case 1:
                    $image = imagecreatefromgif($file['tmp_name']);
                    break;
                case 2:
                    $image = imagecreatefromjpeg($file['tmp_name']);
                    break;
                case 3:
                    $image = imagecreatefrompng($file['tmp_name']);
                    break;
            }
            $image_p = imagecreatetruecolor($prod_width, $prod_height);

            if ($size_array[2]==1 || $size_array[2]==3) {
                $transparent_index = imagecolortransparent($image_p);
                if ($transparent_index >= 0) { // GIF
                    imagepalettecopy($image, $image_p);
                    imagefill($image_p, 0, 0, $transparent_index);
                    imagecolortransparent($image_p, $transparent_index);
                    imagetruecolortopalette($image_p, true, 256);
                }
                else { //PNG
                    imagealphablending($image_p, false);
                    imagesavealpha($image_p,true);
                    $transparent = imagecolorallocatealpha($image_p, 255, 255, 255, 127);
                    imagefilledrectangle($image_p, 0, 0, $prod_width, $prod_height, $transparent);
                }
            }

            imagecopyresampled($image_p, $image, 0, 0, 0, 0, $prod_width, $prod_height, $width_orig, $height_orig);

            if($add_watermark == true)
            {
                global $HC_CONFIG;
                $images_dir = $HC_CONFIG['img_url'];
                $watermark_source = imagecreatefrompng($images_dir.'watermark.png');
                imagealphablending($image, true);
                $watermark_size = getimagesize($images_dir.'watermark.png');
                $watermark_width = $watermark_size[0];
                $watermark_height = $watermark_size[1];
                $watermark_width_resized = ($watermark_width * $prod_width) / 100 * 0.25;
                $watermark_height_resized = ($watermark_height * $prod_height) / 100 * 0.4;
                if($watermark_width_resized > $watermark_width || $watermark_height_resized > $watermark_height)
                {
                    $watermark_width_resized = $watermark_width;
                    $watermark_height_resized = $watermark_height;
                }
                $dest_x = $prod_width - $watermark_width_resized - 10;
                $dest_y = $prod_height - $watermark_height_resized - 10;
                imagecopyresampled($image_p, $watermark_source, $dest_x, $dest_y, 0, 0, $watermark_width_resized, $watermark_height_resized, $watermark_width, $watermark_height);
            }

            switch ($size_array[2]) {
                case 1:
                    imagegif($image_p, $new_name);
                    break;
                case 2:
                    imagejpeg($image_p, $new_name, 95);
                    break;
                case 3:
                    imagepng($image_p, $new_name, 0);
                    break;
            }

            return true;
        } else return false;
    }

    function copy_resized_cropped($file, $new_name, $max_prod_width, $max_prod_height, $add_watermark = false) {
        // 1 = GIF, 2 = JPG, 3 = PNG, 4 = SWF, 5 = PSD, 6 = BMP, 7 = TIFF(intel byte order), 8 = TIFF(motorola byte order), 9 = JPC, 10 = JP2, 11 = JPX, 12 = JB2, 13 = SWC, 14 = IFF, 15 = WBMP, 16 = XBM.
        if (($size_array=getimagesize($file['tmp_name'])) && ($size_array[2]<=3)) {
            list($width_orig, $height_orig) = $size_array;

            $prod_width = $max_prod_width;
            $prod_height = $max_prod_height;

            if (($width_orig>$prod_width) || ($height_orig>$prod_height)) {
                if (($width_orig/$prod_width) < ($height_orig/$prod_height)) {
                    $prod_height = ($prod_width / $width_orig) * $height_orig;
                } else {
                    $prod_width = ($prod_height / $height_orig) * $width_orig;
                }
            } else {
                $prod_width = $width_orig;
                $prod_height = $height_orig;
            }

            $dst_offset_x = -round(($prod_width-$max_prod_width)/2);
            $dst_offset_y = -round(($prod_height-$max_prod_height)/2);

            switch ($size_array[2]) {
                case 1:
                    $image = imagecreatefromgif($file['tmp_name']);
                    break;
                case 2:
                    $image = imagecreatefromjpeg($file['tmp_name']);
                    break;
                case 3:
                    $image = imagecreatefrompng($file['tmp_name']);
                    break;
            }

            $image_p = imagecreatetruecolor($max_prod_width, $max_prod_height);

            if ($size_array[2]==1 || $size_array[2]==3) {
                $transparent_index = imagecolortransparent($image_p);
                if ($transparent_index >= 0) { // GIF
                    imagepalettecopy($image, $image_p);
                    imagefill($image_p, 0, 0, $transparent_index);
                    imagecolortransparent($image_p, $transparent_index);
                    imagetruecolortopalette($image_p, true, 256);
                }
                else { //PNG
                    imagealphablending($image_p, false);
                    imagesavealpha($image_p,true);
                    $transparent = imagecolorallocatealpha($image_p, 255, 255, 255, 127);
                    imagefilledrectangle($image_p, 0, 0, $prod_width, $prod_height, $transparent);
                }
            }

            imagecopyresampled($image_p, $image, $dst_offset_x, $dst_offset_y, 0, 0, $prod_width, $prod_height, $width_orig, $height_orig);

            if($add_watermark == true)
            {
                global $HC_CONFIG;
                $images_dir = $HC_CONFIG['img_url'];
                $watermark_source = imagecreatefrompng($images_dir.'watermark.png');
                imagealphablending($image, true);
                $watermark_size = getimagesize($images_dir.'watermark.png');
                $watermark_width = $watermark_size[0];
                $watermark_height = $watermark_size[1];
                $watermark_width_resized = ($watermark_width * $max_prod_width) / 100 * 0.25;
                $watermark_height_resized = ($watermark_height * $max_prod_height) / 100 * 0.4;
                $dest_x = $max_prod_width - $watermark_width_resized - 10;
                $dest_y = $max_prod_height - $watermark_height_resized - 10;
                imagecopyresampled($image_p, $watermark_source, $dest_x, $dest_y, 0, 0, $watermark_width_resized, $watermark_height_resized, $watermark_width, $watermark_height);
            }

            switch ($size_array[2]) {
                case 1:
                    imagegif($image_p, $new_name);
                    break;
                case 2:
                    imagejpeg($image_p, $new_name, 95);
                    break;
                case 3:
                    imagepng($image_p, $new_name, 0);
                    break;
            }

            return true;
        } else return false;
    }


    function copy_resized_grey($file, $new_name, $max_prod_width, $max_prod_height) {
        // 1 = GIF, 2 = JPG, 3 = PNG, 4 = SWF, 5 = PSD, 6 = BMP, 7 = TIFF(intel byte order), 8 = TIFF(motorola byte order), 9 = JPC, 10 = JP2, 11 = JPX, 12 = JB2, 13 = SWC, 14 = IFF, 15 = WBMP, 16 = XBM.
        if (($size_array=getimagesize($file['tmp_name'])) && ($size_array[2]<=3)) {
            list($width_orig, $height_orig) = $size_array;

            $prod_width = $max_prod_width;
            $prod_height = $max_prod_height;

            if (($width_orig>$prod_width) || ($height_orig>$prod_height)) {
                if (($width_orig/$prod_width) < ($height_orig/$prod_height)) {
                    $prod_width = ($prod_height / $height_orig) * $width_orig;
                } else {
                    $prod_height = ($prod_width / $width_orig) * $height_orig;
                }
            } else {
                $prod_width = $width_orig;
                $prod_height = $height_orig;
            }

            switch ($size_array[2]) {
                case 1:
                    $image = imagecreatefromgif($file['tmp_name']);
                    break;
                case 2:
                    $image = imagecreatefromjpeg($file['tmp_name']);
                    break;
                case 3:
                    $image = imagecreatefrompng($file['tmp_name']);
                    break;
            }
            $image_p = imagecreatetruecolor($prod_width, $prod_height);

            if ($size_array[2]==1 || $size_array[2]==3) {
                $transparent_index = imagecolortransparent($image_p);
                if ($transparent_index >= 0) { // GIF
                    imagepalettecopy($image, $image_p);
                    imagefill($image_p, 0, 0, $transparent_index);
                    imagecolortransparent($image_p, $transparent_index);
                    imagetruecolortopalette($image_p, true, 256);
                }
                else { //PNG
                    imagealphablending($image_p, false);
                    imagesavealpha($image_p,true);
                    $transparent = imagecolorallocatealpha($image_p, 255, 255, 255, 127);
                    imagefilledrectangle($image_p, 0, 0, $prod_width, $prod_height, $transparent);
                }
            }

            imagecopyresampled($image_p, $image, 0, 0, 0, 0, $prod_width, $prod_height, $width_orig, $height_orig);
            imagefilter($image_p, IMG_FILTER_GRAYSCALE);

            switch ($size_array[2]) {
                case 1:
                    imagegif($image_p, $new_name);
                    break;
                case 2:
                    imagejpeg($image_p, $new_name, 95);
                    break;
                case 3:
                    imagepng($image_p, $new_name, 0);
                    break;
            }

            return true;
        } else return false;
    }


    function copy_rounded($file, $new_name) {
        global $HC_CONFIG;
        $image_file = $file;
        $corner_radius = 10;
        $angle = 0;
        $topleft = true;
        $bottomleft = true;
        $bottomright = true;
        $topright = true;

        $images_dir = $HC_CONFIG['img_url'];
        $corner_source = imagecreatefrompng($HC_CONFIG['adm_path'].'img/rounded_corner.png');

        $corner_width = imagesx($corner_source);
        $corner_height = imagesy($corner_source);
        $corner_resized = ImageCreateTrueColor($corner_radius, $corner_radius);
        ImageCopyResampled($corner_resized, $corner_source, 0, 0, 0, 0, $corner_radius, $corner_radius, $corner_width, $corner_height);

        $corner_width = imagesx($corner_resized);
        $corner_height = imagesy($corner_resized);
        $image = imagecreatetruecolor($corner_width, $corner_height);
        $image = imagecreatefromjpeg($image_file);
        $size = getimagesize($image_file);
        $white = ImageColorAllocate($image,255,255,255);
        $black = ImageColorAllocate($image,0,0,0);

        if ($topleft == true) {
            $dest_x = 0;
            $dest_y = 0;
            imagecolortransparent($corner_resized, $black);
            imagecopymerge($image, $corner_resized, $dest_x, $dest_y, 0, 0, $corner_width, $corner_height, 100);
        }

        if ($bottomleft == true) {
            $dest_x = 0;
            $dest_y = $size[1] - $corner_height;
            $rotated = imagerotate($corner_resized, 90, 0);
            imagecolortransparent($rotated, $black);
            imagecopymerge($image, $rotated, $dest_x, $dest_y, 0, 0, $corner_width, $corner_height, 100);
        }

        if ($bottomright == true) {
            $dest_x = $size[0] - $corner_width;
            $dest_y = $size[1] - $corner_height;
            $rotated = imagerotate($corner_resized, 180, 0);
            imagecolortransparent($rotated, $black);
            imagecopymerge($image, $rotated, $dest_x, $dest_y, 0, 0, $corner_width, $corner_height, 100);
        }

        if ($topright == true) {
            $dest_x = $size[0] - $corner_width;
            $dest_y = 0;
            $rotated = imagerotate($corner_resized, 270, 0);
            imagecolortransparent($rotated, $black);
            imagecopymerge($image, $rotated, $dest_x, $dest_y, 0, 0, $corner_width, $corner_height, 100);
        }

        imagejpeg($image, $new_name, 95);
        imagedestroy($corner_source);
    }

    function decrypt($data) {
        global $HC_CONFIG;
        $key = $HC_CONFIG['rc4_key'];
        return rc4crypt($key, base64_decode($data));
    }

    function encode_file_name($str, $length=0, $def_ext='') {
        $str = mb_strtolower($str, "utf-8");
        $ext = file_ext($str);
        $fname = substr($str, 0, strlen($str)-strlen($ext));
        if ($def_ext) $ext = $def_ext;
        $fname = url_translator($fname);
        if ($length) $fname = substr($fname, 0, $length);
        if ($ext) $fname = "$fname.$ext";
        return $fname;
    }

    function encrypt($data) {
        global $HC_CONFIG;
        $key = $HC_CONFIG['rc4_key'];
        return base64_encode(rc4crypt($key, $data));
    }

    function file_ext($file_name) {
        $rpos = strrpos($file_name, ".");
        $ext = ($rpos===false)?"":strtolower(substr($file_name, $rpos+1));
        return $ext;
    }

    function fix_img_src($text) {
        $ka = "http://".$_SERVER['HTTP_HOST']."/";
        $kuo = "/";
        return str_replace($ka, $kuo, $text);
    }

    function fix_post () {
        $numargs = func_num_args();
        $arg_list = func_get_args();
        for ($i = 0; $i < $numargs; $i++) {
            if (isset($_POST[$arg_list[$i]]) && (!preg_match('/^\s*$/', $_POST[$arg_list[$i]]))) {
                if (get_magic_quotes_gpc()) $_POST[$arg_list[$i]] = stripslashes($_POST[$arg_list[$i]]);
                $_POST[$arg_list[$i]] = htmlspecialchars($_POST[$arg_list[$i]], ENT_QUOTES);
                $_POST[$arg_list[$i]] = addslashes($_POST[$arg_list[$i]]);
            } else {
                $_POST[$arg_list[$i]] = "";
            }
        }
    }

    function format_help($id, $tekstas) {
        global $HC_IMG_PATH, $HC_CONFIG;

        $tekstas = nl2br(html_entity_decode($tekstas));
        //$rez = "<span class=\"help_klaustukas\">[<a class=\"link\" href=\"#\" onclick=\"show_help_div('hc_help_$id', event); return false;\">?</a>]</span>";
        $rez = "
    <div id=\"hc_help_$id\" class=\"hc_help_div\">
        <div style=\"padding:8px;\">
            $tekstas
            <p style=\"text-align:right;\"><a class=\"link\" href=\"#\" onclick=\"hide_help_div('hc_help_$id'); return false;\">Uždaryti</a></p>
        </div>
    </div>";
        $rez .= "<span class=\"help_keyword\" onclick=\"show_help_div('hc_help_$id', event);\">";
        return $rez;
    }

    function __formuoti_medi_f(&$PSL, &$MOD_CONFIG, &$submenu, &$medis, $aktyvios_grupes, $tevas=0, $gylis=0, $url='') {
        //global $PSL;//$MOD_CONFIG, $PSL, $submenu;
        $gylis++;
        $query = "SELECT * FROM $MOD_CONFIG[db_table] WHERE tevas='$tevas' AND aktyvus='1' AND page='$PSL[id]' AND tipas='2' ORDER BY pozicija";
        $rez = mysql_query($query);
        while ($row = mysql_fetch_assoc($rez)) {
            $row['gylis'] = $gylis;
            $row['path'] = "$url/$row[url]";
            $row['grupe_aktyvi'] = isset($aktyvios_grupes[$gylis]) && ($aktyvios_grupes[$gylis]==$row['url']);
            $medis[] = $row;
            /*if($gylis==1){
                $tmp=$row;
                $tmp['ilgas_pavadinimas']=$PSL['full_url'].$row['path'].'.html';
                $submenu[]=$tmp;
                unset($tmp);
            }*/
            if ($row['grupe_aktyvi'] && mysql_num_rows(mysql_query("SELECT * FROM $MOD_CONFIG[db_table] WHERE tevas='$row[id]' AND tipas='2' AND page='$PSL[id]'")))
                formuoti_medi($PSL, $MOD_CONFIG, $submenu, $medis, $aktyvios_grupes, $row['id'], $gylis, $row['path']);
        }
    }

    function formuoti_medi_f(&$id, &$MOD_CONFIG, &$medis, $tevas=0, $gylis=0) {
        global $MOD_CONFIG, $PAGE;
        $gylis++;
        $query = "SELECT * FROM $MOD_CONFIG[db_table] WHERE tevas='$tevas' AND page='$id'";
        $query .= " AND tipas='2'";
        $query .= " ORDER BY pozicija";
        $rez = mysql_query($query);
        while ($row = mysql_fetch_assoc($rez)) {
            if (!in_array($row['id'], $ignore)) {
                $row['gylis'] = $gylis;
                $medis[] = $row;
                if (mysql_num_rows(mysql_query("SELECT * FROM $MOD_CONFIG[db_table] WHERE tevas='$row[id]' AND page='$id'")))
                    formuoti_medi_f($id, $MOD_CONFIG, $medis, $row['id'], $gylis);
            }
        }
    }

    function full_url($id, $LANG="lt") {
        $row = mysql_fetch_array(mysql_query("SELECT * FROM struktura WHERE id='$id'"));
        $full_url = $row['url'];
        while (($row['tevas']!=0) && ($row=mysql_fetch_array(mysql_query("SELECT * FROM struktura WHERE lang='$LANG' AND id='$row[tevas]'")))) {
            $full_url = $row['url']."/".$full_url;
        }
        return $full_url;
    }

    function changeNewsVars($row){
        global $LANG;

        $row['pavadinimas'] = $row['pavadinimas_'.$LANG];
        $row['trumpas_aprasymas'] = $row['trumpas_aprasymas_'.$LANG];
        $row['pilnas_aprasymas'] = $row['pilnas_aprasymas_'.$LANG];
        $row['url'] = $row['url_'.$LANG];
        $row['img'] = $row['img_'.$LANG];

        return $row;
    }

    function full_url_by_type($type, $lang='lt') {
        $row = mysql_fetch_array(mysql_query("SELECT * FROM struktura WHERE tipas='$type' AND lang='$lang' AND aktyvus='1' ORDER BY tevas, pozicija LIMIT 1"));
        $full_url = $row['url'];
        while (($row['tevas']!=0) && ($row=mysql_fetch_array(mysql_query("SELECT * FROM struktura WHERE lang='$lang' AND id='$row[tevas]'")))) {
            $full_url = $row['url']."/".$full_url;
        }
        return $full_url;
    }

    function generuoti_koda($ilgis) {
        $str = 'QWERTYUPASDFGHJKLZXCVBNM'.
            'qwertyuipasdfghjkzxcvbnm'.
            '23456789'.
            '23456789';
        $kodas = "";
        for ($i=0; $i<$ilgis; $i++) {
            $sk = rand (0, strlen($str)-1);
            $kodas .= $str[$sk];
        }
        return $kodas;
    }

    function human_file_size( $bytes, $decimal = '2' ) {
        if( is_numeric( $bytes ) ) {
            $position = 0;
            $units = array( " Bytes", " KB", " MB", " GB", " TB" );
            while( $bytes >= 1024 && ( $bytes / 1024 ) >= 1 ) {
                $bytes /= 1024;
                $position++;
            }
            return round( $bytes, $decimal ) . $units[$position];
        }
        else {
            return "0 Bytes";
        }
    }

    function id_by_type($type, $lang='lt') {
        $row = mysql_fetch_array(mysql_query("SELECT `id` FROM struktura WHERE tipas='$type' AND lang='$lang' AND aktyvus='1' ORDER BY tevas, pozicija LIMIT 1"));
        $id = $row['id'];
        return $id;
    }


    function obj_by_type($type, $lang='lt') {
        $row = mysql_fetch_array(mysql_query("SELECT * FROM struktura WHERE tipas='$type' AND lang='$lang' AND aktyvus='1' ORDER BY tevas, pozicija LIMIT 1"));
        return $row;
    }

    function init_db() {
        global $HC_CONFIG;
        $db = @mysql_connect($HC_CONFIG['dbhost'], $HC_CONFIG['dbuser'], $HC_CONFIG['dbpass']) or die('Nepavyko prisijungti prie DB!');
        @mysql_select_db ($HC_CONFIG['dbname']) or die('Tokios DB nera!');
        mysql_query("SET NAMES utf8");
        return $db;
    }

    function is_tvs_user($user, $pass) {
        $rez = 0;
        $user = strtolower($user);
        if ($row = mysql_fetch_array(mysql_query("SELECT * FROM administratoriai WHERE user='$user' AND aktyvus='1'"))) {
            if (md5(decrypt($row['pass']))==$pass)
                $rez = 1;
        }
        return $rez;
    }

    function is_registered_user($user, $pass) {
        $rez = 0;
        $user = strtolower($user);
        if ($row = mysql_fetch_array(mysql_query("SELECT * FROM registruoti_vartotojai WHERE email='$user'"))) {
            if (md5(decrypt($row['pass']))==$pass)
                $rez = $row['aktyvus'];
        }
        return $rez;
    }

    function is_valid_email($email) {
        if (preg_match('/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/', $email ))
            return true;
        else
            return false;
    }

    function is_valid_pass($pass) {
        return ereg('[_a-zA-Z0-9]{6,}', $pass);
    }

    function page_data($id, $lang='lt') {
        return mysql_fetch_array(mysql_query("SELECT * FROM struktura WHERE id='$id' AND lang='$lang' AND aktyvus='1' LIMIT 1"));
    }

    function post_strong_conditions () {
        $rez = true;
        $numargs = func_num_args();
        $arg_list = func_get_args();
        for ($i = 0; $i < $numargs; $i++) {
            if (!isset($_POST[$arg_list[$i]]) || (preg_match('/^\s*$/', $_POST[$arg_list[$i]]))) {
                $rez = false;
                break;
            }
        }
        return $rez;
    }


    function puslapiavimas($aktyvus_psl, $el_per_psl, $viso, $url) {
        $limit = 15;
        $parts = 4;
        $width = 5;
        $puslapiu_sk = ceil($viso / $el_per_psl);
        $numeriai = array();

        if ($puslapiu_sk > $limit) {
            $zingsnis = floor($puslapiu_sk / $parts);

            // zingsniuojam is kaires
            $psl = 1;
            while ($psl < $aktyvus_psl) {
                if (!in_array($psl, $numeriai)) array_push($numeriai, $psl);
                $psl += $zingsnis;
            }

            // zingsniuojam is desines
            $psl = $puslapiu_sk;
            while ($psl > $aktyvus_psl) {
                if (!in_array($psl, $numeriai)) array_push($numeriai, $psl);
                $psl -= $zingsnis;
            }

            // formuojam puslapiavima apie aktyvu psl
            $radius = floor($width/2);
            if ($aktyvus_psl-$radius<1) $psl = 1;
            elseif ($aktyvus_psl+$radius>$puslapiu_sk) $psl = $puslapiu_sk-$width+1;
            else $psl = $aktyvus_psl-$radius;
            for ($i=1; $i<=$width; $i++) {
                if (!in_array($psl, $numeriai)) array_push($numeriai, $psl);
                $psl+=1;
            }
            asort($numeriai);
        } else {
            for ($psl=1; $psl<=$puslapiu_sk; $psl++) {
                if (!in_array($psl, $numeriai)) array_push($numeriai, $psl);
            }

        }

        if ($puslapiu_sk>1) {
            $rez = '<div class="pagerTop">';
            $last = 0;
            foreach ($numeriai as $i) {
                $rez .= ($aktyvus_psl==$i) ? "<span class=\"curentlinkTop\"><a href=\"?$url&amp;psl=$i\">".$i."</a></span>" : "<span><a href=\"?$url&amp;psl=$i\">".$i."</a></span>";
                if ($puslapiu_sk!=$i)
                    $rez .= "";
                $last = $i;
            }
            $rez.="</div>";
        } else $rez = "";
        return $rez;
    }

    function rc4crypt ($pwd, $data, $ispwdHex = 0) {
        if ($ispwdHex)
            $pwd = @pack('H*', $pwd); // valid input, please!

        $key[] = '';
        $box[] = '';
        $cipher = '';

        $pwd_length = strlen($pwd);
        $data_length = strlen($data);

        for ($i = 0; $i < 256; $i++) {
            $key[$i] = ord($pwd[$i % $pwd_length]);
            $box[$i] = $i;
        }
        for ($j = $i = 0; $i < 256; $i++) {
            $j = ($j + $box[$i] + $key[$i]) % 256;
            $tmp = $box[$i];
            $box[$i] = $box[$j];
            $box[$j] = $tmp;
        }
        for ($a = $j = $i = 0; $i < $data_length; $i++) {
            $a = ($a + 1) % 256;
            $j = ($j + $box[$a]) % 256;
            $tmp = $box[$a];
            $box[$a] = $box[$j];
            $box[$j] = $tmp;
            $k = $box[(($box[$a] + $box[$j]) % 256)];
            $cipher .= chr(ord($data[$i]) ^ $k);
        }
        return $cipher;
    }

    function url_translator($str){
        // --> keiciam lt raides i sveplas
        $lt=array("ą","č","ę","ė","į","š","ų","ū","ž","A","Č","Ę","Ė","Į","Š","Ų","Ū","Ž");
        $rlt=array("a","c","e","e","i","s","u","u","z","A","C","E","E","I","S","U","U","Z");
        $str=str_replace($lt,$rlt,$str);
        // <--

        //$str = mb_strtolower($str, "utf-8");
        $str = $this->cyr_to_en($str);
        $str = strtolower($str);

        $str=html_entity_decode($str, ENT_QUOTES);
        $str= preg_replace("#[^a-zA-Z0-9]+#", '-', $str);
        $str=preg_replace("#^_+#", '', $str);
        $str=preg_replace('#_+$#', '', $str);

        return $str;
    }

// ---- konvertuoja cyrillic -> latin ------------------------------------------
    function cyr_to_en($thestring) {
        $specialchars = array(
            "Ё" => 'Jo',
            "ё" => 'jo',
            "Ж" => 'Zh',
            "ж" => 'zh',
            "ч" => "ch",
            "Ч" => "Ch",
            "ш" => "sh",
            "Ш" => "Sh",
            "щ" => "sch",
            "Щ" => "Sch",
            "ю" => "iu",
            "Ю" => "Iu",
            "я" => "ja",
            "Я" => "Ja",
            'а' => 'a',
            'б' => 'b',
            'в' => 'v',
            'г' => 'g',
            'д' => 'd',
            'е' => 'e',
            'з' => 'z',
            'и' => 'i',
            'й' => 'j',
            'к' => 'k',
            'л' => 'l',
            'м' => 'm',
            'н' => 'n',
            'о' => 'o',
            'п' => 'p',
            'р' => 'r',
            'с' => 's',
            'т' => 't',
            'у' => 'u',
            'ы' => 'y',
            'ф' => 'f',
            'х' => 'h',
            'ц' => 'c',
            'ъ' => 'u',
            'А' => 'A',
            'Б' => 'B',
            'В' => 'V',
            'Г' => 'G',
            'Д' => 'D',
            'Е' => 'E',
            'З' => 'Z',
            'И' => 'I',
            'Й' => 'J',
            'К' => 'K',
            'Л' => 'L',
            'М' => 'M',
            'Н' => 'N',
            'О' => 'O',
            'П' => 'P',
            'Р' => 'R',
            'С' => 'S',
            'Т' => 'T',
            'У' => 'U',
            'Ф' => 'F',
            'Х' => 'H',
            'Ц' => 'C',
            'Ъ' => 'U',
            'Ы' => 'Y',
        );
        $translate = strtr($thestring, $specialchars);
        return $translate;
    }

    function valid_email($email){
        $p = '/^[a-z0-9!#$%&*+-=?^_`{|}~]+(\.[a-z0-9!#$%&*+-=?^_`{|}~]+)*';
        $p.= '@([-a-z0-9]+\.)+([a-z]{2,6})$/ix';
        return ((preg_match($p, $email)) ? $email : null);
    }

    function unique_url($url_prefix, $table, $name, $id=0) {
        $where='';
        if ($id>0) {
            $where=" and id!='".$id."'";
        }
        $url_ok = $url = url_translator($name);
        $i=1;
        while (mysql_fetch_array(mysql_query("SELECT * FROM ".$table." WHERE url".$url_prefix."='$url_ok' ".$where))) {
            $i++;
            $url_ok = $url."_$i";
        }
        $url = $url_ok;
        return $url;
    }

    function file_parts($file_name) {
        $rpos = strrpos($file_name, ".");
        $ext = ($rpos===false)?"":".".strtolower(substr($file_name, $rpos+1));
        $name= ($rpos===false)?$file_name:strtolower(substr($file_name, 0, $rpos));
        return array('ext'=>$ext, 'name'=>$name);
    }

    function format_date($lang, $date) {
        $date=date('Y-m-d', $date);
        $months['lt']=array("1"=>"Sausio", "2"=>"Vasario", "3"=>"Kovo", "4"=>"Balandžio", "5"=>"Gegužės", "6"=>"Birželio", "7"=>"Liepos", "8"=>"Rugpjūčio", "9"=>"Rugsėjo", "10"=>"Spalio", "11"=>"Lapkričio", "12"=>"Gruodžio");
        $weekdays['lt']=array("1"=>"Pirmadienis", "2"=>"Antradienis", "3"=>"Trečiadienis", "4"=>"Ketvirtadienis", "5"=>"Penktadienis", "6"=>"Šeštadienis", "0"=>"Sekmadienis");
        $months['en']=array("1"=>"January", "2"=>"February", "3"=>"March", "4"=>"April", "5"=>"May", "6"=>"June", "7"=>"July", "8"=>"August", "9"=>"September", "10"=>"October", "11"=>"November", "12"=>"December");
        $weekdays['en']=array("1"=>"Monday", "2"=>"Tuesday", "3"=>"Wednesday", "4"=>"Thursday", "5"=>"Friday", "6"=>"Saturday", "0"=>"Sunday");
        $months['ru']=array("1"=>"Январь", "2"=>"Февраль", "3"=>"Март", "4"=>"Апрель", "5"=>"Май", "6"=>"Июнь", "7"=>"Июль", "8"=>"Август", "9"=>"Сентябрь", "10"=>"Октябрь", "11"=>"Ноябрь", "12"=>"Декабрь");
        $weekdays['ru']=array("1"=>"Monday", "2"=>"Tuesday", "3"=>"Wednesday", "4"=>"Thursday", "5"=>"Friday", "6"=>"Saturday", "0"=>"Sunday");
        $month=(int)substr($date, 5, 2);
        $year=substr($date, 0, 4);
        $day=(int)substr($date, 8, 2);
        $weekday=$weekdays[$lang][date('w')];
        return array('year'=>$year, 'month'=>$months[$lang][$month], 'day'=>$day, 'weekday'=>$weekday);
    }

    function mail_sender($adress, $subject, $mailBody){
        global $CONFIG, $HC_CONFIG;
        require_once('classes/class.phpmailer.php');
        $mail = new PHPMailer();
        if(is_array($adress)){
            foreach($adress as $v){
                $mail->AddAddress($v);
            }
        }else{
            $mail->AddAddress($adress);
        }

        $mail->SetFrom($CONFIG['nustatymai']['emailas'], $HC_CONFIG['domenas']);
        $mail->From = $CONFIG['nustatymai']['emailas'];
        $mail->Sender = $CONFIG['nustatymai']['emailas'];
        $mail->FromName = $HC_CONFIG['domenas'];
        $mail->IsHTML(true);
        $mail->IsSMTP();
        $mail->SMTPAuth   = false;
        $mail->Host       = "mail.laisvesaleja.eu";
        $mail->Port       = 80;
        $mail->Username   = "info@laisvesaleja.eu";
        $mail->Password   = "qazWSXedcRFV1234";
        $mail->Subject = $subject;
        $mail->Body = $mailBody;
        $mail->Send();

        unset($mail);
    }

    function image_rebuild($moduliai) {
        set_time_limit(0);

        // Einam per visus modulius
        foreach($moduliai as $modulis) {
            if ($modulis['id']=='mikroautobusai') {

                // Einam per modulio settingus
                foreach($modulis as $prop_name => $contents) {
                    // Jei nors viename settinge randamas zodis 'foto'
                    if(strpos($prop_name, "foto") !== false) {
                        if(isset($contents['img_dir'])) {
                            $img_dir = $contents['img_dir'];
                            if (is_dir($img_dir)) {
                                // Paimam paveikslus
                                $images = array_diff(scandir($img_dir), array('.', '..'));
                                // Paimam pirma raide
                                $prefix = $contents['original'][0];
                                // Paimama paskutini skaiciu
                                $suffix = $contents['original'][strlen($contents['original'])-1];
                                // Padarom taga
                                $original_tag = $prefix . $suffix;
                                // Padarom originaliu paveikslu masyva
                                $original_images = array();
                                foreach($images as $image) {
                                    // Jei originalas - uzsaugom
                                    if(strpos($image, $original_tag) === 0) {
                                        $original_images[] = $image;
                                        // Kitaip trinam paveiksla
                                    } else {
                                        //@unlink($img_dir . $image);
                                    }
                                }

                                // Pergeneruojam paveikslus
                                foreach($original_images as $original_image) {
                                    for($i = 1; $i <= 5; $i++) {
                                        if(isset($contents['size'.$i])) {
                                            //if (!file_exists($img_dir.'s'.$i.substr($original_image, 2))) {
                                            if($i == 4){
                                                if($contents['size'.$i.'_mode'] == 'crop' && $contents['original'] != 'size'.$i) {
                                                    if($i == 4){
                                                        copy_resized(array("tmp_name" => $img_dir.$original_image), $img_dir.'s'.$i.substr($original_image, 2), $contents['size'.$i.'_w'], $contents['size'.$i.'_h']);
                                                        copy_resized_cropped(array("tmp_name" => $img_dir.'s'.$i.substr($original_image, 2)), $img_dir.'s'.$i.substr($original_image, 2), $contents['size'.$i.'_w'], $contents['size'.$i.'_h']);
                                                    }else{

                                                        copy_resized_cropped(array("tmp_name" => $img_dir.$original_image), $img_dir.'s'.$i.substr($original_image, 2), $contents['size'.$i.'_w'], $contents['size'.$i.'_h']);
                                                    }
                                                } else {
                                                    $WATER = false;
                                                    //if($i == 3) $WATER = true;
                                                    copy_resized(array("tmp_name" => $img_dir.$original_image), $img_dir.'s'.$i.substr($original_image, 2), $contents['size'.$i.'_w'], $contents['size'.$i.'_h'], $WATER);
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                        die('gdfg');
                        break;
                    }
                }
            }
        }
    }


    function ToExcelString($number) {
        $number=$number+1;
        $dividend = $number;
        $columnName = "";
        $modulo=0;

        while ($dividend > 0)
        {
            $modulo = ($dividend - 1) % 26;
            $columnName = ToExcelChar($modulo).$columnName;
            $dividend = floor(($dividend - $modulo) / 26);
        }

        return $columnName;
    }


    function ToExcelChar($number) {
        $alphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        if ($number > 25 || $number < 0) {
            return 'A';
        }
        return $alphabet[$number];
    }




    function getCartCount() {
        $count=0;
        $price=0;
        if (is_array($_SESSION['cart']['items'])) {
            foreach($_SESSION['cart']['items'] as $product) {
                $count+=$product['quantity'];
                $price+=($product['quantity']*$product['price']);
            }
        }
        $price=number_format(round($price, 2), 2, ".", " ");
        $data=array('count'=>$count, 'price'=>$price);
        return $data;
    }


    function getProdUrl($id) {
        $itemq=mysql_query("select cat from darbai where id='".$id."'");
        if (mysql_num_rows($itemq)>0) {
            $item=mysql_fetch_assoc($itemq);
            $topcat=$item['cat'];
            while($topcat!=0) {
                $tmpq=mysql_query("select id, url, tevas from gaminiu_kategorijos where id='".$topcat."' ");
                $tmp=mysql_fetch_assoc($tmpq);
                $patharr[]=$tmp['url'];
                $topcat=$tmp['tevas'];
            }
        }
        if (count($patharr)>0) {
            $patharr=array_reverse($patharr);
            $pathstring=implode("/", $patharr);
            return $pathstring;
        }
        else {
            return "";
        }
    }


    function getProductInfo($id, $packid) {
        global $LANG;
        $product=null;
        $product=mysql_fetch_array(mysql_query("select darbai.*, pakuotes.id as pack, pakuotes.pavadinimas as packtitle, pakuotes.kaina as pack_price, pakuotes.akcija as akcija, gamintojai.title as gamtitle from darbai left join pakuotes on pakuotes.preke=darbai.id and pakuotes.id='".$packid."' left join gamintojai on gamintojai.id=darbai.gamintojas where darbai.id='".$id."'"));
        if ($product['haspacks']) {
            $product['price']=$product['pack_price'];
        }
        else {
            $product['pack']=0;
        }
        $product['price']=number_format($product['price'], 2, ".", " ");
        $product['full_url']=getProdUrl($product['id']);
        return $product;
    }

    function recalcDiscount() {
        $discountprice=0;

        if (isset($_SESSION['cart']['discounts']['codediscount'])) {
            if (is_array($_SESSION['cart']['items'])) {
                foreach($_SESSION['cart']['items'] as $prod) {
                    $rez=mysql_query("select darbai.id, pakuotes.id as pack from darbai left join pakuotes on pakuotes.preke=darbai.id and pakuotes.id='".$prod['pack']."' where darbai.id='".mysql_real_escape_string($prod['id'])."'");
                    while ($row = mysql_fetch_array($rez)) {
                        if (!$row['pack']) {
                            $row['pack']=0;
                        }
                        $product=getProductInfo($row['id'], $row['pack']);
                        $product['sum']=round($prod['quantity']*$product['price'], 2);
                        $price+=$product['sum'];
                        if (!$product['akcija'] && isset($_SESSION['cart']['discounts']['codediscount'])) {
                            $itemcodediscount=round($product['sum']*$_SESSION['cart']['discounts']['codediscount']['amount']/100, 2);
                            $discountprice+=$itemcodediscount;
                        }
                    }
                }
            }
        }
        $_SESSION['cart']['discounts']['codediscount']['price']=$discountprice;
    }



    function youtube_getid($url) {
        if(!filter_var($url, FILTER_VALIDATE_URL)){
            return "";
        }
        $domain=parse_url($url,PHP_URL_HOST);
        if($domain=='www.youtube.com' OR $domain=='youtube.com') {
            if($querystring=parse_url($url,PHP_URL_QUERY))
            {
                parse_str($querystring);
                if(!empty($v)) return $v;
                else return "";
            }
            else return "";
        }
        elseif($domain == 'youtu.be') {
            $v= str_replace('/','', parse_url($url, PHP_URL_PATH));
            return (empty($v)) ? "" : $v;
        }
        else return "";
    }


    function setUserDiscount(&$preke) {
        $now=mktime();
        if (isset($_SESSION['user'])) {
            $userdiscountinfoq=mysql_query("select discount_type, discount_percent, discount_cat, discount_items from users where id='".$_SESSION['user']['id']."' and discount_from<='".$now."' and discount_to>='".$now."'");
            if (mysql_num_rows($userdiscountinfoq)>0) {
                $user_discount=mysql_fetch_assoc($userdiscountinfoq);
                $discount_items=array();
                $preke_cats=array();
                if ($preke['cat']>0) {
                    $preke_cats=getProdTopCats($preke['cat']);
                }
                if (strlen($user_discount['discount_items'])>0) {
                    $discount_items=explode(";", $user_discount['discount_items']);
                }
                if ($user_discount['discount_type']=='1' || in_array($preke['id'], $discount_items) || in_array($user_discount['discount_cat'], $preke_cats)) {
                    if (!$preke['akcija']) {
                        $preke['price']=round($preke['price']-(($preke['price']*$user_discount['discount_percent'])/100), 2);
                        $preke['price']=number_format($preke['price'], 2, ".", "");
                    }
                    if (is_array($preke['packages'])) {
                        foreach($preke['packages'] as &$package) {
                            if (!$package['akcija']) {
                                $package['kaina']=round($package['kaina']-(($package['kaina']*$user_discount['discount_percent'])/100), 2);
                                $package['kaina']=number_format($package['kaina'], 2, ".", "");
                            }
                        }
                    }
                }
            }
        }
    }


    function getProdTopCats($id) {
        $cats=array();
        $itemq=mysql_query("select id, tevas from gaminiu_kategorijos where id='".$id."'");
        if (mysql_num_rows($itemq)>0) {
            $item=mysql_fetch_assoc($itemq);
            $cats[]=$item['id'];
            $topcat=$item['tevas'];
            while($topcat!=0) {
                $tmpq=mysql_query("select id, tevas from gaminiu_kategorijos where id='".$topcat."' ");
                $tmp=mysql_fetch_assoc($tmpq);
                $cats[]=$tmp['id'];
                $topcat=$tmp['tevas'];
            }
        }
        return $cats;
    }

    function sendBirthdayEmail($text) {
        global $CONFIG;
        include_once('classes/class.phpmailer.php');
        $mail = new PHPMailer();
        $emailfrom='sales@yzipet.com';
        $emailfromname='Yzipet';
        $mail->AddAddress($CONFIG['nustatymai']['gimtadieniu_mailas']);
        $mailBody=$text;
        $mail->From = $emailfrom;
        $mail->Sender = $emailfrom;
        $mail->FromName = $emailfromname;
        $mail->IsHTML(true);
        $mail->Subject = 'Yzipet.com augintinių gimtadieniai';
        $mail->Body = $mailBody;
        $mail->Send();
    }



    function setVenipakData($order) {
        global $HC_CONFIG;

        $from=mktime(0, 0, 0, date('m', $order['date']), date('d', $order['date']), date('Y', $order['date']));
        $till=mktime(0, 0, 0, date('m', $order['date']), date('d', $order['date'])+1, date('Y', $order['date']));

        $daynr=1;
        list($maxdaynr)=mysql_fetch_array(mysql_query("select MAX(veni_daynr) as lastnr from orders where `date`>'".$from."' and `date`<'".$till."' and orders.id!='".$order['id']."'"));
        if ($maxdaynr) {
            $daynr=$maxdaynr+1;
        }

        $totalnr=1;
        list($maxtotalnr)=mysql_fetch_array(mysql_query("select MAX(veni_totalnr) as lastnr from orders where orders.id!='".$order['id']."'"));
        if ($maxtotalnr) {
            $totalnr=$maxtotalnr+1;
        }

        $packnr=str_pad($totalnr, 7, "0", STR_PAD_LEFT);

        $fullnr='V'.$HC_CONFIG['venipakid'].'E'.$packnr;

        mysql_query("update orders set veni_daynr='".$daynr."', veni_totalnr='".$totalnr."', veni_fullnr='".$fullnr."' where id='".$order['id']."'");
    }



    function sendVenipakData($orderId) {
        global $HC_CONFIG;

        $orderq=mysql_query("select * from orders where id='".$orderId."'");
        if (mysql_num_rows($orderq)>0) {
            $order=mysql_fetch_assoc($orderq);

            $xmldata=getVenipakXML($order);

            $params = array(
                'user' => $HC_CONFIG['venipakuser'],
                'pass' => $HC_CONFIG['venipakpass'],
                'xml_text' => $xmldata
            );

            $response=curlPost($HC_CONFIG['venipakurl'], $params);

            $errors_string='';
            $errors=array();


            $respXML = new SimpleXMLElement($response);
            $attributes=reset($respXML->attributes());
            $type=$attributes['type'];

            if ($type=='error') {
                $status=0;
                foreach($respXML->error as $error) {
                    $errors[]=(String)$error->text;
                }
            }
            else {
                $status=1;
            }
            if (count($errors)>0) {
                $errors_string=serialize($errors);
            }

            mysql_query("update orders set veni_status='".$status."', veni_response='".mysql_real_escape_string($response)."', veni_errors='".mysql_real_escape_string($errors_string)."' where id='".$order['id']."'");
        }
    }



    function getVenipakXML($order) {
        global $HC_CONFIG;

        $daynr=str_pad($order['veni_daynr'], 3, "0", STR_PAD_LEFT);
        $packnr=str_pad($order['veni_totalnr'], 7, "0", STR_PAD_LEFT);

        $mtitle=$HC_CONFIG['venipakid'].date('y').date('m').date('d').$daynr;

        $xml='<?xml version="1.0" encoding="UTF-8"?>';
        $xml.='<description type="1">';
        $xml.='<manifest title="'.$mtitle.'">';
        $xml.='<shipment>';
        $xml.='<consignee>';
        $xml.='<name>'.$order['name'].' '.$order['surname'].'</name>';
        $xml.='<country>LT</country>';
        $xml.='<city>'.$order['delivery_city'].'</city>';
        $xml.='<address>'.$order['delivery_address'].'</address>';
        $xml.='<post_code>'.$order['delivery_zip_code'].'</post_code>';
        $xml.='<contact_tel>'.$order['phone'].'</contact_tel>';
        $xml.='</consignee>';
        if ($order['payondel']==1) {
            $xml.='<attribute>';
            $xml.='<cod>'.$order['final_sum'].'</cod>';
            $xml.='<cod_type>EUR</cod_type>';
            $xml.='</attribute>';
        }
        $xml.='<pack>';
        $xml.='<pack_no>'.$order['veni_fullnr'].'</pack_no>';
        $xml.='<weight>'.$order['totalweight'].'</weight>';
        $xml.='</pack>';
        $xml.='</shipment>';
        $xml.='</manifest>';
        $xml.='</description>';
        return $xml;
    }


    function curlPost($url,$params) {
        $postData = '';
        foreach($params as $k => $v) {
            $postData .= $k . '='.$v.'&';
        }
        rtrim($postData, '&');

        $ch = curl_init();

        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
        curl_setopt($ch,CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_POST, count($postData));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);

        $output=curl_exec($ch);

        curl_close($ch);
        return $output;
    }

    function saveSeo($table,$id,$lang,$meta_key,$meta_desc){
        $seoTableExist = SeoSettings::where('lenta',$table)->where('id',$id)->where('lang',$lang)->first();
        if ($seoTableExist){
            $seoTableExist->update(['meta_key'=>$meta_key,'meta_desc'=>$meta_desc]);
        } else {
            SeoSettings::create([
                'id'=>$id,
                'lenta'=>$table,
                'lang'=>$lang,
                'meta_key'=>$meta_key,
                'meta_desc'=>$meta_desc,
            ]);
        }
    }

    function getSeo($table,$id,$lang){
        $data=array();
        $rez = mysql_query("SELECT * FROM `seo_nustatymai` WHERE lenta='$table' AND id='$id' AND lang='$lang' LIMIT 1");
        if (mysql_num_rows($rez)>0){
            $data=mysql_fetch_assoc($rez);
        }
        return $data;
    }
}

?>