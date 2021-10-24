<?php

set_include_path(get_include_path() . PATH_SEPARATOR . '../lib');

include_once("aur.inc.php");
include_once('stats.inc.php');

if (isset($_COOKIE["AURSID"])) {
	html_header( __("Dashboard") );
} else {
	html_header( __("Home") );
}

?>

<div id="content-left-wrapper">
	<div id="content-left">
		<?php if (isset($_COOKIE["AURSID"])): ?>
		<div id="intro" class="box">
			<h2><?= __("Dashboard"); ?></h2>
			<h3><?= __("My Flagged Packages"); ?></h3>
			<?php
			$params = array(
				'PP' => 50,
				'SeB' => 'M',
				'K' => username_from_sid($_COOKIE["AURSID"]),
				'outdated' => 'on',
				'SB' => 'l',
				'SO' => 'a'
			);
			pkg_search_page($params, false, $_COOKIE["AURSID"]);
			?>
			<h3><?= __("My Requests"); ?></h3>
			<?php
			$archive_time = config_get_int('options', 'request_archive_time');
			$from = time() - $archive_time;
			$results = pkgreq_list(0, 50, uid_from_sid($_COOKIE["AURSID"]), $from);
			$show_headers = false;
			include('pkgreq_results.php');
			?>
		</div>
		<div id="intro" class="box">
			<h2><?= __("My Packages"); ?></h2>
			<p><a href="<?= get_uri('/packages/') ?>?SeB=m&amp;K=<?= username_from_sid($_COOKIE["AURSID"]); ?>"><?= __('Search for packages I maintain') ?></a></p>
			<?php
			$params = array(
				'PP' => 50,
				'SeB' => 'm',
				'K' => username_from_sid($_COOKIE["AURSID"]),
				'SB' => 'l',
				'SO' => 'd'
			);
			pkg_search_page($params, false, $_COOKIE["AURSID"]);
			?>
		</div>
		<div id="intro" class="box">
			<h2><?= __("Co-Maintained Packages"); ?></h2>
			<p><a href="<?= get_uri('/packages/') ?>?SeB=c&amp;K=<?= username_from_sid($_COOKIE["AURSID"]); ?>"><?= __('Search for packages I co-maintain') ?></a></p>
			<?php
			$params = array(
				'PP' => 50,
				'SeB' => 'c',
				'K' => username_from_sid($_COOKIE["AURSID"]),
				'SB' => 'l',
				'SO' => 'd'
			);
			pkg_search_page($params, false, $_COOKIE["AURSID"]);
			?>
		</div>
		<?php else: ?>
		<div id="intro" class="box">
			<h2>MPR <?= __("Home"); ?></h2>
			<p>Welcome to the MPR! Please read the <a href="https://docs.hunterwittenborn.com/makedeb/makedeb-package-repository/intro">MPR Docs</a> for more information.</p>
			<?php
			echo __(
				'Contributed PKGBUILDs %smust%s conform to the %sMPR User Guidelines%s or they will be subject to deletion!',
				'<strong>', '</strong>',
				'<a href="https://docs.hunterwittenborn.com/makedeb/makedeb-package-repository/mpr-user-guidelines">',
				'</a>'
				);
			?>
			<?= __('Remember to vote for your favorite packages!'); ?>
			</p>
			<p class="important">
			<?= __('DISCLAIMER') ?>:
			<?= __('MPR packages are user produced content. Any use of the provided files is at your own risk.'); ?>
			</p>
			<p class="readmore"><a href="https://docs.hunterwittenborn.com/makedeb/makedeb-package-repository/intro"><?= __('Learn more...') ?></a></p>
		</div>
		<div id="news">
			<h3><a><?= __('Support') ?></a><span class="arrow"></span></h3>
			<h4><?= __('Submitting Packages') ?></h4>
			<div class="article-content">
			<?php if (config_section_exists('fingerprints')): ?>
			<p>
				<?= __('The following SSH fingerprints are used for the MPR:') ?>
			</p>
			<ul>
				<?php foreach (config_items('fingerprints') as $type => $fingerprint): ?>
				<li><code><?= htmlspecialchars($type) ?></code>: <code><?= htmlspecialchars($fingerprint) ?></code></li>
				<?php endforeach; ?>
			</ul>
			<?php endif; ?>
			</div>
			<h4><?= __('Discussion') ?></h4>
			<div class="article-content">
			<p>General discussion regarding the MPR takes place on the <a href="https://matrix.to/#/#mpr:hunterwittenborn.com">MPR Matrix room</a>.</p>
			</div>
			<h4><?= __('Bug Reporting') ?></h4>
			<div class="article-content">
			<p>Issues and bugs related to the MPR should be posted on the GitHub project's <a href="https://github.com/makedeb/mprweb/issues">issue page</a>.</p>
			</div>
		</div>
		<?php endif; ?>
	</div>
</div>
<div id="content-right">
	<div id="pkgsearch" class="widget">
		<form id="pkgsearch-form" method="get" action="<?= get_uri('/packages/'); ?>">
			<fieldset>
				<label for="pkgsearch-field"><?= __('Package Search') ?>:</label>
				<input type="hidden" name="O" value="0" />
				<input id="pkgsearch-field" type="text" name="K" size="30" value="<?php if (isset($_REQUEST["K"])) { print stripslashes(trim(htmlspecialchars($_REQUEST["K"], ENT_QUOTES))); } ?>" maxlength="35" />
			</fieldset>
		</form>
	</div>
	<div id="pkg-updates" class="widget box">
		<?php updates_table(); ?>
	</div>
	<div id="pkg-stats" class="widget box">
		<?php general_stats_table(); ?>
	</div>
	<?php if (isset($_COOKIE["AURSID"])): ?>
	<div id="pkg-stats" class="widget box">
		<?php user_table(uid_from_sid($_COOKIE["AURSID"])); ?>
	</div>
	<?php endif; ?>

</div>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
<script type="text/javascript" src="/js/bootstrap-typeahead.min.js"></script>
<script type="text/javascript">
$(document).ready(function() {
    $('#pkgsearch-field').typeahead({
        source: function(query, callback) {
            $.getJSON('<?= get_uri('/rpc'); ?>', {type: "suggest", arg: query}, function(data) {
                callback(data);
            });
        },
        matcher: function(item) { return true; },
        sorter: function(items) { return items; },
        menu: '<ul class="pkgsearch-typeahead"></ul>',
        items: 20,
        updater: function(item) {
            document.location = '/packages/' + item;
            return item;
	}
    }).attr('autocomplete', 'off');

    $('#pkgsearch-field').keydown(function(e) {
        if (e.keyCode == 13) {
            var selectedItem = $('ul.pkgsearch-typeahead li.active');
            if (selectedItem.length == 0) {
                $('#pkgsearch-form').submit();
            }
        }
    });
});
</script>
<?php
html_footer(AURWEB_VERSION);
