<?php 
# gwTreeSize for Webspace
# Copyright (c) 2020 by Georg Wallisch (mail@phpco.de)
#
# This program is free software: you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation, either version 3 of the License, or
# (at your option) any later version.
# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU General Public License for more details.
#
# You should have received a copy of the GNU General Public License
# along with this program.  If not, see <http://www.gnu.org/licenses/>.

# WARNINGS
# It is strongly recommended to limit access to this program by some kind of
# authentication like http basic auth using htaccess

# global definitions
$page_title = 'gwTreeSize'; 
$author = 'Georg Wallisch (mail@phpco.de)';
$errors = array();

# debug settings
$debug = false;
if(array_key_exists('debug', $_REQUEST) and $_REQUEST['debug'] != '') {
	$debug = true;
}

if(!headers_sent()) {
	header('Access-Control-Allow-Origin: *');
} else {
	$errors[] = "Headers already sent";
}

//ini_set('max_execution_time', 900);
ini_set('display_errors', 1);
//error_reporting(E_ALL);
error_reporting(E_ALL & ~ E_NOTICE);
$debug_info = array('max_execution_time' => ini_get('max_execution_time'));
//clearstatcache();

# function defs

function dirsize($path) {
    $s = 0;
    $result = array($path => 0);
    $handle = @opendir($path);
    if($handle !== false) {
        while(false !== ($file = readdir($handle))) {
            if($file != '.' and $file != '..') {
                $name = $path . '/' . $file;
                if(is_dir($name)) {
                    $ar = dirsize($name);
                    foreach($ar as $key => $value) {
                    	$s++;
                    	$result[$key] = $value;
                    }
                } else {
                	$result[$path] += @filesize($name);
                }
            }
        }
        closedir($handle);
    }
    return $result;
}


# begin main prog
if(array_key_exists('phpinfo', $_REQUEST) and $_REQUEST['phpinfo'] != '') {
	phpinfo();
	exit;
}

if(array_key_exists('get', $_REQUEST) and ($g = $_REQUEST['get']) != '') {
	ob_start(); //suppress all output
	if($g{0} != '/') {
		$g .= '/';
	}
	$dir = realpath($_SERVER['DOCUMENT_ROOT'].$_REQUEST['get']);
	$r = array();
	if($dir !== false) {
		$r = dirsize($dir);
	}
	
	if($debug) {
		$debug_info['ob_content'] = ob_get_contents();
	}
	
	ob_end_clean();
	
	if($debug) {
		header("Content-type: text/plain; charset=utf-8");
		 print_r($r);		 
		 echo "\n\n-----\n\n";
		 print_r($_REQUEST);
		 foreach($debug_info as $k => $v) {
		 	 echo "\n\n-----\n\n{$k}:\n\n{$v}";
		 }		
	} else {
		header("Content-type: application/json; charset=utf-8");
		echo json_encode($r);
	}
	exit;
}

# begin html skeleton

?><!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?php echo htmlentities($page_title); ?></title>

    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">


    <style>
      .bd-placeholder-img {
        font-size: 1.125rem;
        text-anchor: middle;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
      }

      @media (min-width: 768px) {
        .bd-placeholder-img-lg {
          font-size: 3.5rem;
        }
      }
    </style>
    <!-- Custom styles for this template -->
    <link href="gwtreesize.css" rel="stylesheet">
  </head>
  <body>
    <nav class="navbar navbar-expand-md navbar-dark bg-dark fixed-top">
  <a class="navbar-brand" href="#"><?php echo htmlentities($page_title); ?></a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarsExampleDefault">
    <ul class="navbar-nav mr-auto">
    	<li class="nav-item active">
    		<a class="nav-link" href="<?php echo $_SERVER["PHP_SELF"]; ?>">/</a>
    	</li>
        <li class="nav-item">
        	<a class="nav-link" href="<?php echo $_SERVER["PHP_SELF"].'?phpinfo=1'; ?>">phpinfo()</a>
        </li>
    </ul>
  </div>
</nav>

<main role="main" class="container" id="#main-container">

  <div class="lead_in">
    <h1><?php echo htmlentities($page_title); ?></h1>
    <p class="lead">Webspace Directory Tree Size Analyzing Tool</p>
  </div>
  
<?php

	if($dev_mode and count($errors) > 0) {
		echo '<div id="errors">'."\n<h2>Errors</h2>\n<ul>\n<li>";
		implode("</li>\n</li>", $errors);
		echo "</li>\n</ul>\n</div>\n";
	}

?>

</main><!-- /.container -->
<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" crossorigin="anonymous"></script>
<script src="gwtreesize.js"></script>
</html>