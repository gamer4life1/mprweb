<?php

set_include_path(get_include_path() . PATH_SEPARATOR . '../lib');

include_once("aur.inc.php");
include_once("pkgfuncs.inc.php");

html_header(__("Package Deletion"));

if (has_credential(CRED_PKGBASE_DELETE)): ?>
<div class="box">
	<h2><?= __('Delete Package') ?>: <?= htmlspecialchars($pkgbase_name) ?></h2>
	<p>
		<?= __('Use this form to delete the package base %s%s%s and the following packages from the AUR: ',
			'<strong>', htmlspecialchars($pkgbase_name), '</strong>'); ?>
	</p>
	<ul>
		<?php foreach(pkgbase_get_pkgnames($base_id) as $pkgname): ?>
		<li><?= htmlspecialchars($pkgname) ?></li>
		<?php endforeach; ?>
	</ul>
	<p>
		<?= __('Deletion of a package is permanent. '); ?>
		<?= __('Select the checkbox to confirm action.') ?>
	</p>
	<form action="<?= get_pkgbase_uri($pkgbase_name); ?>" method="post">
		<fieldset>
			<input type="hidden" name="IDs[<?= $base_id ?>]" value="1" />
			<input type="hidden" name="ID" value="<?= $base_id ?>" />
			<input type="hidden" name="token" value="<?= htmlspecialchars($_COOKIE['AURSID']) ?>" />
			<?php if (isset($_GET['via'])): ?>
			<input type="hidden" name="via" value="<?= intval($_GET['via']) ?>" />
			<?php endif; ?>
			<p><label class="confirmation"><input type="checkbox" name="confirm" value="1" />
			<?= __("Confirm package deletion") ?></label></p>
			<p><input type="submit" class="button" name="do_Delete" value="<?= __("Delete") ?>" /></p>
		</fieldset>
	</form>
</div>

<?php else:
	print __("Only Trusted Users and Developers can delete packages.");
endif;

html_footer(AURWEB_VERSION);
