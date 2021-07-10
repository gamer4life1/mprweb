<?= '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"
	xml:lang="<?= htmlspecialchars($LANG, ENT_QUOTES) ?>" lang="<?= htmlspecialchars($LANG, ENT_QUOTES) ?>">
  <head>
    <title>MPR (<?= htmlspecialchars($LANG); ?>)<?php if ($title != "") { print " - " . htmlspecialchars($title); } ?></title>
	<link rel='stylesheet' type='text/css' href='/css/archweb.css' />
	<link rel='stylesheet' type='text/css' href='/css/aurweb.css' />
	<link rel='shortcut icon' href='/images/favicon.ico' />
	<link rel='alternate' type='application/rss+xml' title='Newest Packages RSS' href='<?= get_uri('/rss/'); ?>' />
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<?php if (isset($details['Description']) && !empty($details['Description'])): ?>
	<meta name="description" content="<?= htmlspecialchars($details['Description']) ?>" />
<?php endif; ?>
  </head>
	<body>
		<div id="archnavbar" class="anb-aur">
			<div id="archnavbarlogo"><h1><a href="/" title="Return to the main page">Arch Linux User Repository</a></h1></div>
			<div id="archnavbarmenu">
				<ul id="archnavbarlist">
					<li><a href="/" title="makedeb Package Repository">MPR</a></li>
					<li><a href="https://docs.hunterwittenborn.com/makedeb" title="makedeb Documentation">Docs</a></li>
					<li><a href="https://matrix.to/#/!KOdBeHhHDQPQNLgioI:hunterwittenborn.com?via=hunterwittenborn.com" title="Get Support">Support</a></li>
				</ul>
			</div>
		</div><!-- #archnavbar -->

		<div id="content">
			<div id="lang_sub">
				<form method="post" action="<?= htmlspecialchars($_SERVER["REQUEST_URI"], ENT_QUOTES) ?>">
					<fieldset>
						<div>
							<select name="setlang" id="id_setlang">
		<?php
		reset($SUPPORTED_LANGS);
		foreach ($SUPPORTED_LANGS as $lang => $lang_name) {

			print '<option value="' . htmlspecialchars($lang, ENT_QUOTES) . '"' .
				($lang == $LANG ? ' selected="selected"' : '') .
				'>' . htmlspecialchars($lang_name) . "</option>\n";
		}
		?>
							</select>
							<input type="submit" value="Go" />
						</div>
					</fieldset>
				</form>
			</div>
			<div id="archdev-navbar">
				<ul>
					<?php if (isset($_COOKIE['AURSID'])): ?>
						<li><a href="<?= get_uri('/'); ?>"><?= __("Dashboard"); ?></a></li>
						<li><a href="<?= get_uri('/packages/'); ?>"><?= __("Packages"); ?></a></li>
						<?php if (has_credential(CRED_PKGREQ_LIST)): ?>
						<li><a href="<?= get_uri('/requests/') ; ?>"><?= __("Requests"); ?></a></li>
						<?php endif; ?>
						<?php if (has_credential(CRED_ACCOUNT_SEARCH)): ?>
						<li><a href="<?= get_uri('/accounts/') ; ?>"><?= __("Accounts"); ?></a></li>
						<?php endif; ?>
						<li><a href="<?= get_user_uri(username_from_sid($_COOKIE['AURSID'])) . 'edit/'; ?>"><?= __(" My Account"); ?></a></li>
						<?php if (has_credential(CRED_TU_LIST_VOTES)): ?><li><a href="<?= get_uri('/tu/'); ?>"><?= __("Trusted User"); ?></a></li><?php endif; ?>
						<li><a href="<?= get_uri('/logout/'); ?>"><?= __("Logout"); ?></a></li>
					<?php else: ?>
						<li><a href="<?= get_uri('/'); ?>">MPR <?= __("Home"); ?></a></li>
						<li><a href="<?= get_uri('/packages/'); ?>"><?= __("Packages"); ?></a></li>
						<li><a href="<?= get_uri('/register/'); ?>"><?= __("Register"); ?></a></li>
						<?php if (config_get_bool('options', 'disable_http_login') && empty($_SERVER['HTTPS'])): ?>
						<li><a href="<?= get_uri('/login/', true); ?>"><?= __("Login"); ?></a></li>
						<?php else: ?>
						<li><a href="<?= get_uri('/login/'); ?>"><?= __("Login"); ?></a></li>
						<?php endif; ?>
					<?php endif; ?>
				</ul>
			</div><!-- #archdev-navbar -->
			<!-- Start of main content -->
