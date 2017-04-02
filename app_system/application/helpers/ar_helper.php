<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//helper
//created by : Julius Michael Rinaldo


function generateSearchString($search,$sort_by = "",$order = "",$page = 1,$limit = ""){
    $str = "";
    $page--;
    $start = $page * $limit;
    $str .= " AND ( ";
    foreach($search as $key=>$val){

        if($key != 0){
            $str .= " OR ";
        }
        
        $str .= " ".$val[1]." ".$val[2]." '".$val[0]."'";
        
    }
    $str .= " ) ";
    if($sort_by != ""){
        $str .= " ORDER BY $sort_by $order";
    }
    if($limit != ""){
        $str .= " limit $start,$limit";
    }

    return $str;
}
function search_access_array($id, $array) {
    if(count($array)>0){
        foreach ($array as $key => $val) {
            if ($val['id'] === $id) {
               return $val;
            }
        }
    }
   return null;
}

function ismobile()
    {
        $mobile_browser = '0';

        if (preg_match('/(up.browser|up.link|mmp|symbian|smartphone|midp|wap|phone|android)/i', strtolower($_SERVER['HTTP_USER_AGENT']))) {
                $mobile_browser++;
        }

        if ((strpos(strtolower($_SERVER['HTTP_ACCEPT']),'application/vnd.wap.xhtml+xml') > 0) or ((isset($_SERVER['HTTP_X_WAP_PROFILE']) or isset($_SERVER['HTTP_PROFILE'])))) {
                $mobile_browser++;
        }

        $mobile_ua = strtolower(substr($_SERVER['HTTP_USER_AGENT'], 0, 4));
        $mobile_agents = array(
                        'w3c ','acs-','alav','alca','amoi','audi','avan','benq','bird','blac',
                        'blaz','brew','cell','cldc','cmd-','dang','doco','eric','hipt','inno',
                        'ipaq','java','jigs','kddi','keji','leno','lg-c','lg-d','lg-g','lge-',
                        'maui','maxo','midp','mits','mmef','mobi','mot-','moto','mwbp','nec-',
                        'newt','noki','oper','palm','pana','pant','phil','play','port','prox',
                        'qwap','sage','sams','sany','sch-','sec-','send','seri','sgh-','shar',
                        'sie-','siem','smal','smar','sony','sph-','symb','t-mo','teli','tim-',
                        'tosh','tsm-','upg1','upsi','vk-v','voda','wap-','wapa','wapi','wapp',
                        'wapr','webc','winw','winw','xda ','xda-');

        if (in_array($mobile_ua,$mobile_agents)) {
                $mobile_browser++;
        }

        if (strpos(strtolower($_SERVER['HTTP_USER_AGENT']),'windows') > 0) {
                $mobile_browser = 0;
        }

        if (strpos(strtolower($_SERVER['HTTP_USER_AGENT']),'mac') > 0) {
                $mobile_browser = 0;
        }

        if (strpos(strtolower($_SERVER['HTTP_USER_AGENT']),'ios') > 0) {
                $mobile_browser = 1;
        }
        if (strpos(strtolower($_SERVER['HTTP_USER_AGENT']),'android') > 0) {
                $mobile_browser = 1;
        }
        if (strpos(strtolower($_SERVER['HTTP_USER_AGENT']),'windows phone') > 0) {
                $mobile_browser = 1;
        }	
        if (strpos(strtolower($_SERVER['HTTP_USER_AGENT']),'iphone os') > 0) {
                $mobile_browser = 1;
        }
        return $mobile_browser;
}
function getPrefixNo($id)
{
	
    $ci =& get_instance(); 
    $ci->load->model("prefix_model");
	$ci->load->database();
	$structure = $ci->db->query("SELECT * FROM prefix WHERE id = '$id' ORDER BY running_number DESC LIMIT 1")->row_array();
	
	$intial = $structure['prefix'];
	$length = $structure['length'];

	if ($structure['running_number'] >= 1)
	{
		$number = intval($structure['running_number']);
	}else{
		$number = 0;
	}
	$number++;
	$tmp= "";
	for ($i=0; $i < ($length-strlen($number)) ; $i++)
	{
		$tmp = $tmp."0";
	}
	$ci->prefix_model->update($id,array('running_number'=>$number));
	//return generate ID
	return strval($intial.$tmp.$number);
}

function upload_handler($file_name,$new_name,$new_path, $required = true,$old_file_path = ""){
    $ci =& get_instance();
    $returnData = array();
    $config['upload_path'] = $new_path;
    $config['allowed_types'] = 'jpg|png|jpeg';
    $config['max_size'] = '1024';
    $config['max_width']  = '1024';
    $config['max_height']  = '1024';
    $config['overwrite'] = TRUE;
    $config['file_name'] = $new_name;
    $ci->load->library('upload', $config);
    if(isset($_FILES[$file_name]))
    {
        if (!$ci->upload->do_upload($file_name))
        {
            
            $returnData['error'] =  $ci->upload->display_errors();
            $returnData['status'] = 0;
        }
        else
        {
         
            $upload_data = $ci->upload->data();
            $returnData['file_path'] = $new_path.$upload_data['raw_name'].$upload_data['file_ext'];
       
            $parts = explode(".",$upload_data['full_path']);
            $ci->load->library('image_lib');
                /* read the source image */
                if($upload_data['file_type'] == "image/jpeg" || $upload_data['file_type'] == "image/jpg"){
                     $source_image = imagecreatefromjpeg($upload_data['full_path']);
                }
                else if($upload_data['file_type'] == "image/png"){
                    $source_image = imagecreatefrompng($upload_data['full_path']);
                }
                else if($upload_data['file_type'] == "image/gif"){
                    $source_image = imagecreatefromgif($upload_data['full_path']);
                }

                $width = imagesx($source_image);
                $height = imagesy($source_image);
                /* create a new, "virtual" image */
              
                
                // first resize the image 
                $thumbnail_size = 200;
                if ($width < $height){
                    $desired_width = $thumbnail_size;
                    $desired_height = floor($height * ($desired_width / $width));
                    $w_start = 0;
                    $h_start = $desired_height / 2 - ($thumbnail_size/2);
                }else{
                     $desired_height = $thumbnail_size;
                    $desired_width = floor($width * ($desired_height / $height));
                    $w_start = $desired_width / 2 - ($thumbnail_size/2);
                    $h_start = 0;
                }

                /* copy source image at a resized size based on the shortest length */
                $virtual_image = imagecreatetruecolor(200, 200);
                imagecopyresampled($virtual_image, $source_image, -$w_start, -$h_start, 0, 0, $desired_width, $desired_height, $width, $height);
            
                /* create the physical thumbnail image to its destination */
                $thumbnail_path = $upload_data['file_path']."/".$upload_data['raw_name']."_thumb".$upload_data['file_ext'];
                $returnData['thumbnail_path'] = $new_path."/".$upload_data['raw_name']."_thumb".$upload_data['file_ext'];
                if($upload_data['file_type'] == "image/jpeg" || $upload_data['file_type'] == "image/jpg"){
                     imagejpeg($virtual_image, $thumbnail_path);
                }
                else if($upload_data['file_type'] == "image/png"){
                     imagepng($virtual_image, $thumbnail_path);
                }
                else if($upload_data['file_type'] == "image/gif"){
                     imagegif($virtual_image, $thumbnail_path);
                }
              $returnData['status'] = 1;
            //END CREATING THUMBNAILS
            //BEGIN DELETE OLD FILE
                if($old_file_path != ""){
                    if(file_exists($old_file_path)){
                        unlink($old_file_path);
                    }
                    $parts = explode(".",$old_file_path);
                    $old_thumb_path = $parts[0]."_thumb.".$parts[1];
                    if(file_exists($old_thumb_path)){
                        unlink($old_thumb_path);
                    }
                }
            //END DELETE OLD FILE
        }
    }
    else{
        if($required){
            $returnData['status'] = 1;
        }
        else{
            $returnData['status'] = 0;
        }
    }

    return $returnData;
}
function labelStatus($status){
	$label 	= '';
	if($status == "PENDING"){
		$label .= '<span class="label label-info">';
	}else if($status == "PROCESSED"){
		$label .= '<span class="label label-primary">';
	}else if($status == "OPEN"){
		$label .= '<span class="label label-success">';
	}else if($status == "PAID"){
		$label .= '<span class="label label-success">';
	}else if($status == "CLOSE"){
		$label .= '<span class="label label-default">';
	}else if($status == "CLOSED"){
		$label .= '<span class="label label-default">';
	}else if($status == "UNPAID"){
		$label .= '<span class="label label-warning">';
	}else if($status == "FINISHING"){
		$label .= '<span class="label label-info">';
		$class = 'Info';
	}else if($status == "FINISHED"){
		$label .= '<span class="label label-default">';
	}else if($status == "PARTITIAL PAID"){
        $label .= '<span class="label label-warning">';
    }
	$label .= $status."</span>";
	return $label;
}

function generate_table_header(){

}

/* created by samuel */
function time_ago( $date )
{
	if( empty( $date ) )
	{
		return "No date provided";
	}

	$periods = array("second", "minute", "hour", "day", "week", "month", "year", "decade");

	$lengths = array("60","60","24","7","4.35","12","10");

	$now = time();

	$unix_date = strtotime( $date );

	// check validity of date

	if( empty( $unix_date ) )
	{
		return "Bad date";
	}

	// is it future date or past date

	if( $now > $unix_date )
	{
		$difference = $now - $unix_date;
		$tense = "ago";
	}
	else
	{
		$difference = $unix_date - $now;
		$tense = "from now";
	}

	for( $j = 0; $difference >= $lengths[$j] && $j < count($lengths)-1; $j++ )
	{
		$difference /= $lengths[$j];
	}

	$difference = round( $difference );

	if( $difference != 1 )
	{
		$periods[$j].= "s";
	}

	return "$difference $periods[$j] {$tense}";

}


function generateBarcode($code){
	$ci =& get_instance();
	$path = "./public/barcode/";
	$ci->load->library('Zend');
    //load in folder Zend
    $ci->zend->load('Zend/Barcode');
    //generate barcode
    $file = Zend_Barcode::draw('code128', 'image', array('text'=>$code), array());
	$code = time().$code;
	$store_image = imagepng($file,$path."{$code}.png");
	return $path."{$code}.png";
}
function preprocess_date($date_data){
    if($date_data == "" || $date_data == "0000-00-00"){
        $ret_data = NULL;
    }else{
        $ret_data = date("Y-m-d",strtotime($date_data));
    }
    return $ret_data;
}
function display_date($date_data){
    $ci->load->model("generic_model");
    $company_settings_model = new generic_model("company_settings");
    $company_settings = $company_settings_model->find(1);

    if($date_data == "" || $date_data == "0000-00-00"){
        $date_data = "";
    }else{
        $date_data = date($company_settings_model['default_date_format'],strtotime($date_data));
    }
    return $date_data;
}



function get_timeago( $ptime )
{
    $estimate_time = time() - $ptime;

    if( $estimate_time < 1 )
    {
        return 'less than 1 second ago';
    }

    $condition = array(
                12 * 30 * 24 * 60 * 60  =>  'year',
                30 * 24 * 60 * 60       =>  'month',
                24 * 60 * 60            =>  'day',
                60 * 60                 =>  'hour',
                60                      =>  'minute',
                1                       =>  'second'
    );

    foreach( $condition as $secs => $str )
    {
        $d = $estimate_time / $secs;

        if( $d >= 1 )
        {
            $r = round( $d );
            return 'about ' . $r . ' ' . $str . ( $r > 1 ? 's' : '' ) . ' ago';
        }
    }
}

function send_mail($to="",$subject="",$message=""){ 
    $ci =& get_instance();
    $ci->load->config('email');
    $ci->load->library('email');

    $ci->email->set_newline("\r\n");
    
    $from = $ci->config->item('smtp_user');
    $ci->email->from($from);
    //$to = $to;
    //$to = 'mft.aang@gmail.com';
    
    $ci->email->to($to);
    $ci->email->subject($subject);
    $ci->email->message($message);
    
    try {
        $ci->email->send();
        $email_debug = $ci->email->print_debugger();
        print_r($email_debug);  
        return 0; 
    } catch (Exception $e) {
        return 1;;
    }
    
}


    function view_date($date_data){
   
        if($date_data == "" || $date_data == "0000-00-00"){
            $date_data = "";
        }else{
            $date_data = date("d-m-Y",strtotime($date_data));
        }
        return $date_data;
    }


