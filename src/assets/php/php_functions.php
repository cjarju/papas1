<?php

/* prevent display of error messages in browser: production mode */
/*ini_set('display_errors', '0');*/

/* displays flash message */
function showNotifications() {

    /* display flash message if present */
    if ($_SESSION['flash']) {
        $html_str = "<ul>";
        foreach($_SESSION['flash'] as $message) {
            $html_str .= "<li>$message</li>";
        }
        $html_str .= "</ul>";
        echo $html_str;
    }

    /* reset flash session variable */
    $_SESSION['flash'] = array();
}

/*
 * You can use the header() function to send a new HTTP header, but
 * this must be sent to the browser before any HTML or text
 * (so before the <!DOCTYPE ...> declaration, for example). 
 * 
 */

function redirect($url, $statusCode = 303){
    header('Location: ' . $url, true, $statusCode);
    die();
}

/* upload image */
function uploadImage($upload_dir, $file_input_id){

    $uploadOk = 1;
    $target_dir = $upload_dir;
    $uploaded_file = $_FILES["$file_input_id"];

    $path_parts = pathinfo($uploaded_file["name"]);
	$image_filename =  $path_parts['filename'];
	$image_extension =  $path_parts['extension'];
	$image_basename =  $path_parts['basename'];
    
	$uniq_filename = "img-".uniqid()."-".date("YmdHis").".".$image_extension; 
    $target_file = $target_dir . $uniq_filename;

    if (!empty($image_filename)) {

    /* Check if image file is a actual image or fake image */
        if(isset($_POST["submit"])) {
            $check = getimagesize($uploaded_file["tmp_name"]);
            if($check !== false) {
                /* echo "File is an image - " . $check["mime"] . "."; */
                $uploadOk = 1;
            } else {
                /* echo "File is not an image."; */
                array_push($_SESSION['flash'], "File is not an image.");
                $uploadOk = 0;
            }
        }
    /* Check if file already exists */
        if (file_exists($target_file)) {
            /* echo "Sorry, file already exists."; */
            array_push($_SESSION['flash'], "Sorry, file already exists.");
            $uploadOk = 0;
        }
    /* Check file size */
        if ($_FILES["file_to_upload"]["size"] > 25000000) {
            /* echo "Sorry, your file is too large."; */
            array_push($_SESSION['flash'], "Sorry, your file is too large.");
            $uploadOk = 0;
        }
    /* Allow certain file formats */
        if($image_extension != "jpg" && $image_extension != "png" && $image_extension != "jpeg" && $image_extension != "gif" ) {
            /* echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed."; */
            array_push($_SESSION['flash'], "Sorry, only JPG, JPEG, PNG & GIF files are allowed.");
            echo $image_extension;
            $uploadOk = 0;
        }
    /* Check if $uploadOk is set to 0 by an error */
        if ($uploadOk == 0) {
            /* echo "Sorry, your file was not uploaded."; */
            array_push($_SESSION['flash'], "Sorry, your file was not uploaded.");

    /* if everything is ok, try to upload file */
        } else {
            if (move_uploaded_file($uploaded_file["tmp_name"], $target_file)) {
                /* echo "The file ". $image_basename . " has been uploaded."; */
                $msg = "The file ". $image_basename . " has been uploaded.";
                array_push($_SESSION['flash'], $msg);
                return $uniq_filename;
            } else {
                /* echo "Sorry, there was an error uploading your file."; */
                array_push($_SESSION['flash'], "Sorry, there was an error uploading your file.");
                return "";
            }
        }
        return "";
    }
}

function resizeImage($originalFile, $newWidth, $newHeight, $targetFile) {

    $info = getimagesize($originalFile);
    $mime = $info['mime'];

    switch ($mime) {
        case 'image/jpeg':
            $image_create_func = 'imagecreatefromjpeg';
            $image_save_func = 'imagejpeg';
            $new_image_ext = 'jpg';
            break;

        case 'image/png':
            $image_create_func = 'imagecreatefrompng';
            $image_save_func = 'imagepng';
            $new_image_ext = 'png';
            break;

        case 'image/gif':
            $image_create_func = 'imagecreatefromgif';
            $image_save_func = 'imagegif';
            $new_image_ext = 'gif';
            break;

        default:
            throw Exception('Unknown image type.');
    }
	
    list($width, $height) = getimagesize($originalFile);

    $tmp = imagecreatetruecolor($newWidth, $newHeight);
	$img = $image_create_func($originalFile);
	
    /* you will get black background if you resize a transparent image. this will fix it */
    imagealphablending($tmp, false);
    imagesavealpha($tmp,true);
    $transparent = imagecolorallocatealpha($tmp, 255, 255, 255, 127);
    imagefilledrectangle($tmp, 0, 0, $newWidth, $newHeight, $transparent);
    /* this will fix it */

    imagecopyresampled($tmp, $img, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

    if (file_exists($targetFile)) {
        unlink($targetFile);
    }

    $image_save_func($tmp, "$targetFile.$new_image_ext");

    imagedestroy($tmp);
}

/* resize given a new width, pass 0 to newHeight as it will derived from calculation and vice versa */
function resizeImageAspectRatio($originalFile, $newWidth, $newHeight, $targetFile) {

    $info = getimagesize($originalFile);
    $mime = $info['mime'];

    switch ($mime) {
        case 'image/jpeg':
            $image_create_func = 'imagecreatefromjpeg';
            $image_save_func = 'imagejpeg';
            $new_image_ext = 'jpg';
            break;

        case 'image/png':
            $image_create_func = 'imagecreatefrompng';
            $image_save_func = 'imagepng';
            $new_image_ext = 'png';
            break;

        case 'image/gif':
            $image_create_func = 'imagecreatefromgif';
            $image_save_func = 'imagegif';
            $new_image_ext = 'gif';
            break;

        default:
            throw Exception('Unknown image type.');
    }
    
    list($origWidth, $origHeight) = getimagesize($originalFile);

	$origRatio = $origWidth/$origHeight;
    
	if ($newWidth != 0) {
		$newHeight = ceil($newWidth/$origRatio);
	} else {
		$newWidth = ceil($newHeight*$origRatio);
	}

    $tmp = imagecreatetruecolor($newWidth, $newHeight);
	$img = $image_create_func($originalFile);
	
    /* you will get black background if you resize a transparent image. this will fix it */
    imagealphablending($tmp, false);
    imagesavealpha($tmp,true);
    $transparent = imagecolorallocatealpha($tmp, 255, 255, 255, 127);
    imagefilledrectangle($tmp, 0, 0, $newWidth, $newHeight, $transparent);
    /* this will fix it */

    imagecopyresampled($tmp, $img, 0, 0, 0, 0, $newWidth, $newHeight, $origWidth, $origHeight);

    if (file_exists($targetFile)) {
        unlink($targetFile);
    }

    $image_save_func($tmp, "$targetFile.$new_image_ext");

    imagedestroy($tmp);
}

/* 
  get pagination data
  return pagination data as an array 
*/
function getPagData($conn, $table_name, $cond='', $limit=4) {
    
    /* 
	   A variable declared outside a function has a global scope and can only be accessed outside a function.
       I need to pass the already established db connection object to the function or include the database 
	   config file to initialize my connection

     include '../config/db/_database.php';

    */


	/* $limit = How many items to list per page */
	
    /* Find out how many items are in the table */

    if ($cond){
        $sql = "SELECT COUNT(*) FROM $table_name where $cond";
    } else {
        $sql = "SELECT COUNT(*) FROM $table_name";
    }

    $result = $conn->query($sql);
    $row = $result->fetch_row();
    $total = $row[0];

    /* How many pages will there be */
    $pages = ceil($total / $limit);

    /* What page are we currently on? Get the number from the URL for example /gallery.php?page=1 */
	/* $_GET['page']; $_POST['page'] */
	if (isset($_GET['page'])) {
		$page = min($pages, filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT, array(
			'options' => array(
				'default'   => 1,
				'min_range' => 1,
			),
		)));
	}
    else {
		$page = min($pages, filter_input(INPUT_POST, 'page', FILTER_VALIDATE_INT, array(
			'options' => array(
				'default'   => 1,
				'min_range' => 1,
			),
		)));
	}

    /* Calculate the offset for the query */
    $offset = ($page - 1)  * $limit;

    /* Some information to display to the user. current record interval on display. for example showing $start-$end of $total */
    $start = $offset + 1;
    $end = min(($offset + $limit), $total);
	
	/* --------Get the start and ending values for the loop to display page numbers dynamically--------- */

    $max_visible = 4;

    if ($max_visible % 2 == 0) {
        /* EVEN number of max visible pages */
        /*  _ _ _ _ 8 _ _ _
              _ _ _ 6 _ _
                _ _ 4 _
        */
        $adj_left = $max_visible / 2;
        $adj_right = $adj_left - 1;
        $adj_end = $max_visible - 1;

        if ($page >= $max_visible) {
            if ($page < $pages) {
                $start_loop = $page - $adj_left;
                $end_loop = $page + $adj_right;
            }
            else {
                $start_loop = $pages - $adj_end;
                $end_loop = $pages;
            }
        } else {
            $start_loop = 1;
            if ($pages > $max_visible)
                $end_loop = $max_visible;
            else
                $end_loop = $pages;
        }
    }
    else {
        /* ODD number of max visible pages */
        /*  _ _ _ 7 _ _ _
              _ _ 5 _ _
        */
        $adj_mid = floor($max_visible / 2);
        $adj_end = $max_visible - 1;

        if ($page >= $max_visible) {
            $start_loop = $page - $adj_mid;
            if ($pages > $page + $adj_mid)
                $end_loop = $page + $adj_mid;
            else if ($page <= $pages && $page > $pages - $adj_end) {
                $start_loop = $pages - $adj_end;
                $end_loop = $pages;
            } else {
                $end_loop = $pages;
            }
        } else {
            $start_loop = 1;
            if ($pages > $max_visible)
                $end_loop = $max_visible;
            else
                $end_loop = $pages;
        }
    }
	/* ------------------------------------------------------------------------------------------------- */

    return array('total' => $total, 'limit' => $limit, 'offset' => $offset, 'pages' => $pages, 'page' => $page, 'start' => $start, 'end' => $end, 'start_loop' => $start_loop, 'end_loop' => $end_loop);
}

/* 						WITHOUT AJAX 
*  without AJAX page is reloaded when pagination links are clicked. 
*  
*/

/* 
 receive an array of data for pagination 
 return html string  
 to display: 
			$prevlink Page $page of $pages. $start-$end of $total records $nextlink
*/
function paginate1($paginatedata='') {
	
    $total = $paginatedata['total'];
	$limit = $paginatedata['limit'];
	$pages = $paginatedata['pages'];
	$page = $paginatedata['page'];
	$start = $paginatedata['start'];
	$end = $paginatedata['end'];
	
	/* create links */
	
    /* The "back" link */
    $prevlink = ($page > 1) ? '<span class="paginglinks"><a href="?page=1" title="First page">&laquo;</a> <a href="?page=' . ($page - 1) . '" title="Previous page">&lsaquo;</a></span>' : '<span class="disabled">&laquo;</span> <span class="disabled">&lsaquo;</span>';

    /* The "forward" link */
    $nextlink = ($page < $pages) ? '<span class="paginglinks"><a href="?page=' . ($page + 1) . '" title="Next page">&rsaquo;</a> <a href="?page=' . $pages . '" title="Last page">&raquo;</a></span>' : '<span class="disabled">&rsaquo;</span> <span class="disabled">&raquo;</span>';
    
	/* info */
	$pageinfo = "Page $page of $pages. Showing $start of $end of $total results.";

	/* bootstrap css for pagination */
$html = <<<HTML
<div id="paging" class="center_align">
		$prevlink $pageinfo $nextlink
</div>
HTML;
	return $html;
}

/* 
 receive an array of data for pagination. 
 return html string  
 to display: 
			Page $page of $pages. $start-$end of $total records
			$prevlink [max 7 dynamic page numbers] $nextlink
*/
function paginate2($paginatedata='') {
	
    $total = $paginatedata['total'];
	$limit = $paginatedata['limit'];
	$pages = $paginatedata['pages'];
	$page = $paginatedata['page'];
	$start = $paginatedata['start'];
	$end = $paginatedata['end'];
	$start_loop = $paginatedata['start_loop'];
	$end_loop = $paginatedata['end_loop'];
	$pagelinks = "";
	
	/* create links */
	
    /* The "back" link */
    $prevlink = ($page > 1) ? '<li><a href="?page=1" title="First page">&laquo;</a> <a href="?page=' . ($page - 1) . '" title="Previous page">&lsaquo;</a></li>' : '<li class="disabled"><span>&laquo;</span> <span>&lsaquo;</span></li>';

    /* The "forward" link */
    $nextlink = ($page < $pages) ? '<li><a href="?page=' . ($page + 1) . '" title="Next page">&rsaquo;</a> <a href="?page=' . $pages . '" title="Last page">&raquo;</a></span></li>' : '<li class="disabled"><span>&rsaquo;</span> <span>&raquo;</span></li>';
    
	/* page links */
	for ( $i = $start_loop ; $i <= $end_loop; $i++ ) {
        $class  = ( $page == $i ) ? "active" : "";
        $pagelinks   .= '<li class="' . $class . '"><a href="?page=' . $i . '">' . $i . '</a></li>';
    }

	/* page info */
	$pageinfo = "Page $page of $pages. Showing $start of $end of $total results.";

	/* bootstrap css for pagination */
$html = <<<HTML
<div id="paging" class="center_align">
	$pageinfo  
	<nav>
		<ul class="pagination">
			$prevlink
			$pagelinks
			$nextlink
		</ul>
	</nav>
</div>
HTML;
	return $html;
}

/* 						WITH AJAX 
*  with AJAX page is not reloaded when pagination links are clicked. 
*  only the pagination info and data from table is refreshed 
*/

/* 
 receive an array of data for pagination 
 return html string  
 to display: 
			$prevlink Page $page of $pages. $start-$end of $total records $nextlink
*/
function paginateAJAX1($paginatedata='') {
	
    $total = $paginatedata['total'];
	$limit = $paginatedata['limit'];
	$pages = $paginatedata['pages'];
	$page = $paginatedata['page'];
	$start = $paginatedata['start'];
	$end = $paginatedata['end'];
	
	/* create links */
	
    /* The "back" link */
    $prevlink = ($page > 1) ? '<span class="paginglinks"><a href="" p="1" title="First page">&laquo;</a> <a href="" p="' . ($page - 1) . '" title="Previous page">&lsaquo;</a></span>' : '<span class="disabled">&laquo;</span> <span class="disabled">&lsaquo;</span>';

    /* The "forward" link */
    $nextlink = ($page < $pages) ? '<span class="paginglinks"><a href="" p="2" title="Next page">&rsaquo;</a> <a href="" p="' . $pages . '" title="Last page">&raquo;</a></span>' : '<span class="disabled">&rsaquo;</span> <span class="disabled">&raquo;</span>';
    
	/* info */
	$pageinfo = "Page $page of $pages. Showing $start of $end of $total results.";

	/* bootstrap css for pagination */
$html = <<<HTML
<div id="paging" class="center_align">
		$prevlink $pageinfo $nextlink
</div>

HTML;
	return $html;
}

/* 
 receive an array of data for pagination. 
 return html string  
 to display: 
			Page $page of $pages. $start-$end of $total records
			$prevlink [max 7 dynamic page numbers] $nextlink
*/
function paginateAJAX2($paginatedata='') {
	
    $total = $paginatedata['total'];
	$limit = $paginatedata['limit'];
	$pages = $paginatedata['pages'];
	$page = $paginatedata['page'];
	$start = $paginatedata['start'];
	$end = $paginatedata['end'];
	$start_loop = $paginatedata['start_loop'];
	$end_loop = $paginatedata['end_loop'];
	$pagelinks = "";
	
	/* create links */
	
    /* The "back" link */
    $prevlink = ($page > 1) ? '<li><a href="" p="1" title="First page">&laquo;</a> <a href="" p="' . ($page - 1) . '" title="Previous page">&lsaquo;</a></li>' : '<li class="disabled"><span>&laquo;</span> <span>&lsaquo;</span></li>';

    /* The "forward" link */
    $nextlink = ($page < $pages) ? '<li><a href="" p="' . ($page + 1) . '" title="Next page">&rsaquo;</a> <a href="" p="' . $pages . '" title="Last page">&raquo;</a></span></li>' : '<li class="disabled"><span>&rsaquo;</span> <span>&raquo;</span></li>';
    
	/* page links */
	for ( $i = $start_loop ; $i <= $end_loop; $i++ ) {
        $class  = ( $page == $i ) ? "active" : "";
        $pagelinks   .= '<li class="' . $class . '"><a href="" p="' . $i . '">' . $i . '</a></li>';
    }

	/* page info */
	$pageinfo = "Page $page of $pages. Showing $start of $end of $total results.";

	/* bootstrap css for pagination */
$html = <<<HTML
<div id="paging" class="center_align">
	$pageinfo  
	<nav>
		<ul class="pagination">
			$prevlink
			$pagelinks
			$nextlink
		</ul>
	</nav>
</div>
HTML;
	return $html;
}

/* return current page URL */
function curPageURL() {
    $pageURL = 'http';
    if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
    $pageURL .= "://";
    if ($_SERVER["SERVER_PORT"] != "80") {
        $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
    } else {
        $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
    }
    return $pageURL;
}

/* generate a random token for a given form
   create a cookie on client with a definite expiry time and store token
   store the token on the server in a session variable
   send the form to the client
*/

function generateFormToken($form_name, $expiry) {

    $formName = $form_name; /* name of the form */
    $random   = uniqid().date("YmdHis");  /* mt_rand() ; create a unique random value */
    $salt     = "8077_(-(àyhvboyr(à"; /* secret salt */
    $token    = md5(sha1($salt.$random).sha1($salt.$formName));

    /* set cookie: time()+3600 = now + 1 hour
      set cookie: time()+300 = now + 5 min
    */

    setcookie("token",$token, time()+$expiry);

    /* save token on server side */
    $_SESSION['token'] = $token;
}

function resetFormToken() {
    setcookie("token",'', time()-5); /* destruct cookie */
    $_SESSION['token'] = "";  /* OR destruct session */

    array_push($_SESSION['flash'], "<span class='error_color'>Form has expired. Try Again.</span>");
}

/* server side validation functions */

/* presence */
function isEmpty($el_variable) {
    if (empty($el_variable)) {
        return true;
    } else {
        return false;
    }
}

/* numeric */
function isNumeric($el_variable) {
    if (preg_match("/^[\-\+]?[\d\,]*\.?[\d]*$/",$el_variable)) {
        return true;
    } else {
        return false;
    }
}

/* alphas, numeric, special characters _&|*!*/
function isSafeAlphaNum1 ($el_variable) {
    if (!preg_match("/^[a-zA-Z0-9_&|*!]*$/",$el_variable)) {
        return true;
    } else {
        return false;
    }
}

/* alphas, numeric, space, special characters _&|*!*/
function isSafeAlphaNum2 ($el_variable) {
    if (!preg_match("/^[a-zA-Z0-9 _&|*!]*$/",$el_variable)) {
        return true;
    } else {
        return false;
    }
}

/* nl to paragraph */
function nl2p($string)
{
    $paragraphs = '';

    foreach (explode("\n", $string) as $line) {
        if (trim($line)) {
            $paragraphs .= '<p>' . $line . '</p>';
        }
    }

    return $paragraphs;
}

/* write to console */
function debug_to_console($data) {
    if(is_array($data) || is_object($data))
	{
		echo("<script>console.log('PHP: ".json_encode($data)."');</script>");
	} else {
        /* escape string */
		echo("<script>console.log('PHP: ".addslashes($data)."');</script>");
	}
}

/*
  start session and set flash variable is if not set
  set session setting before starting it
  session settings like expiry time should be defined before starting it
*/

session_start();

/* if session variable is not set it must be initialized in document contains HTML code */
if (!isset($_SESSION['flash'])) {
    $_SESSION['flash'] = array();
}
