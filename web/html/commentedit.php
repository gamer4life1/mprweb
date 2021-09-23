<?php

set_include_path(get_include_path() . PATH_SEPARATOR . '../lib');

include_once("aur.inc.php");
include_once("pkgbasefuncs.inc.php");

$comment_id = intval($_REQUEST['comment_id']);
list($user_id, $comment) = comment_by_id($comment_id);

if (!isset($base_id) || !has_credential(CRED_COMMENT_EDIT, array($user_id)) || is_null($comment)) {
	header('Location: /');
	exit();
}

html_header(__("Edit comment"));
include('pkg_comment_box.php');
html_footer(AURWEB_VERSION);
