<?php
set_include_path(get_include_path() . PATH_SEPARATOR . '../lib');

include_once("aur.inc.php");
include_once("pkgfuncs.inc.php");

$path = $_SERVER['PATH_INFO'];
$tokens = explode('/', $path);

if (config_get_bool('options', 'enable-maintenance') && (empty($tokens[1]) || ($tokens[1] != "css" && $tokens[1] != "images"))) {
	if (!in_array($_SERVER['REMOTE_ADDR'], explode(" ", config_get('options', 'maintenance-exceptions')))) {
		header("HTTP/1.0 503 Service Unavailable");
		include "./503.php";
		return;
	}
}

if (!empty($tokens[1]) && '/' . $tokens[1] == get_pkg_route()) {
	if (!empty($tokens[2])) {
		/* TODO: Create a proper data structure to pass variables from
		 * the routing framework to the individual pages instead of
		 * initializing arbitrary variables here. */
		$pkgname = $tokens[2];
		$pkgid = pkg_from_name($pkgname);

		if (!$pkgid) {
			header("HTTP/1.0 404 Not Found");
			include "./404.php";
			return;
		}
	}

	include get_route('/' . $tokens[1]);
} elseif (!empty($tokens[1]) && '/' . $tokens[1] == get_pkgbase_route()) {
	if (!empty($tokens[2])) {
		/* TODO: Create a proper data structure to pass variables from
		 * the routing framework to the individual pages instead of
		 * initializing arbitrary variables here. */
		$pkgbase_name = $tokens[2];
		$base_id = pkgbase_from_name($pkgbase_name);

		if (!$base_id) {
			header("HTTP/1.0 404 Not Found");
			include "./404.php";
			return;
		}

		if (!empty($tokens[3])) {
			/* TODO: Remove support for legacy URIs and move these
			 * actions to separate modules. */
			switch ($tokens[3]) {
			case "adopt":
				$_POST['do_Adopt'] = __('Adopt');
				break;
			case "disown":
				include('pkgdisown.php');
				return;
			case "vote":
				$_POST['do_Vote'] = __('Vote');
				break;
			case "unvote":
				$_POST['do_UnVote'] = __('UnVote');
				break;
			case "notify":
				$_POST['do_Notify'] = __('Notify');
				break;
			case "unnotify":
				$_POST['do_UnNotify'] = __('UnNotify');
				break;
			case "flag":
				include('pkgflag.php');
				return;
			case "unflag":
				$_POST['do_UnFlag'] = __('UnFlag');
				break;
			case "flag-comment":
				include('pkgflagcomment.php');
				return;
			case "delete":
				include('pkgdel.php');
				return;
			case "merge":
				include('pkgmerge.php');
				return;
			case "voters":
				$_GET['N'] = $tokens[2];
				include('voters.php');
				return;
			case "request":
				include('pkgreq.php');
				return;
			case "comaintainers":
				include('comaintainers.php');
				return;
			case "edit-comment":
				include('commentedit.php');
				return;
			default:
				header("HTTP/1.0 404 Not Found");
				include "./404.php";
				return;
			}

			$_POST['IDs'] = array(pkgbase_from_name($tokens[2]) => '1');
		}
	}

	include get_route('/' . $tokens[1]);
} elseif (!empty($tokens[1]) && '/' . $tokens[1] == get_pkgreq_route()) {
	if (!empty($tokens[2])) {
		/* TODO: Create a proper data structure to pass variables from
		 * the routing framework to the individual pages instead of
		 * initializing arbitrary variables here. */
		if (!empty($tokens[3]) && $tokens[3] == 'close') {
			$pkgreq_id = $tokens[2];
		} else {
			$pkgreq_id = null;
		}

		if (!$pkgreq_id) {
			header("HTTP/1.0 404 Not Found");
			include "./404.php";
			return;
		}
	}

	include get_route('/' . $tokens[1]);
} elseif (!empty($tokens[1]) && '/' . $tokens[1] == get_user_route()) {
	if (!empty($tokens[2])) {
		$_REQUEST['ID'] = uid_from_username($tokens[2]);

		if (!$_REQUEST['ID']) {
			header("HTTP/1.0 404 Not Found");
			include "./404.php";
			return;
		}

		if (!empty($tokens[3])) {
			if ($tokens[3] == 'edit') {
				$_REQUEST['Action'] = "DisplayAccount";
			} elseif ($tokens[3] == 'update') {
				$_REQUEST['Action'] = "UpdateAccount";
			} elseif ($tokens[3] == 'delete') {
				$_REQUEST['Action'] = "DeleteAccount";
			} elseif ($tokens[3] == 'comments') {
				$_REQUEST['Action'] = "ListComments";
			} else {
				header("HTTP/1.0 404 Not Found");
				include "./404.php";
				return;
			}
		} else {
			$_REQUEST['Action'] = "AccountInfo";
		}
	}
	include get_route('/' . $tokens[1]);
} elseif (get_route($path) !== NULL) {
	include get_route($path);
} else {
	switch ($path) {
	case "/css/archweb.css":
	case "/css/aurweb.css":
	case "/css/cgit.css":
	case "/css/archnavbar/archnavbar.css":
		header("Content-Type: text/css");
		readfile("./$path");
		break;
	case "/images/ajax-loader.gif":
		header("Content-Type: image/gif");
		readfile("./$path");
		break;
	case "/css/archnavbar/archlogo.png":
	case "/css/archnavbar/aurlogo.png":
	case "/images/favicon.ico":
		header("Content-Type: image/png");
		readfile("./$path");
		break;
	case "/images/x.min.svg":
	case "/images/action-undo.min.svg":
	case "/images/pencil.min.svg":
	case "/images/pin.min.svg":
	case "/images/unpin.min.svg":
	case "/images/rss.svg":
		header("Content-Type: image/svg+xml");
		readfile("./$path");
		break;
	case "/js/bootstrap-typeahead.min.js":
		header("Content-Type: application/javascript");
		readfile("./$path");
		break;
	case "/packages.gz":
	case "/pkgbase.gz":
	case "/users.gz":
		header("Content-Type: text/plain");
		header("Content-Encoding: gzip");
		readfile("./$path");
		break;
	default:
		header("HTTP/1.0 404 Not Found");
		include "./404.php";
		break;
	}
}
