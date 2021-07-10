<?php if (count($results) == 0): ?>
<p><?= __("No requests matched your search criteria.") ?></p>
<?php else: ?>
<?php if ($show_headers): ?>
<div class="pkglist-stats">
	<p>
		<?= _n('%d package request found.', '%d package requests found.', $total) ?>
		<?= __('Page %d of %d.', $current, $pages) ?>
	</p>
	<?php if (count($templ_pages) > 1): ?>
	<p class="pkglist-nav">
		<?php foreach ($templ_pages as $pagenr => $pagestart): ?>
			<?php if ($pagestart === false): ?>
				<span class="page"><?= $pagenr ?></span>
			<?php elseif ($pagestart + 1 == $first): ?>
				<span class="page"><?= $pagenr ?></span>
			<?php else: ?>
				<a class="page" href="<?= get_uri('/requests/'); ?>?<?= mkurl('O=' . $pagestart) ?>"><?= $pagenr ?></a>
			<?php endif; ?>
		<?php endforeach; ?>
	</p>
	<?php endif; ?>
</div>
<?php endif; ?>

<table class="results">
<thead>
	<tr>
		<th><?= __("Package") ?></th>
		<th><?= __("Type") ?></th>
		<th><?= __("Comments") ?></th>
		<th><?= __("Filed by") ?></th>
		<th><?= __("Date") ?></th>
		<th><?= __("Status") ?></th>
	</tr>
</thead>
<tbody>

	<?php foreach ($results as $indx => $row): ?>
	<?php
	$idle_time = config_get_int('options', 'request_idle_time');
	$due = ($row['Open'] && time() - intval($row['RequestTS']) > $idle_time);
	if (!$due) {
		$time_left = $idle_time - (time() - intval($row['RequestTS']));
		if ($time_left > 48 * 3600) {
			$time_left_fmt = _n("~%d day left", "~%d days left", round($time_left / (24 * 3600)));
		} elseif ($time_left > 3600) {
			$time_left_fmt = _n("~%d hour left", "~%d hours left", round($time_left / 3600));
		} else {
			$time_left_fmt = __("<1 hour left");
		}
	}
	?>
	<tr class="<?= ($indx % 2 == 0) ? 'odd' : 'even' ?>">
		<?php if ($row['BaseID']): ?>
		<td><a href="<?= htmlspecialchars(get_pkgbase_uri($row["Name"]), ENT_QUOTES); ?>"><?= htmlspecialchars($row["Name"]) ?></a></td>
		<?php else: ?>
		<td><?= htmlspecialchars($row["Name"]) ?></td>
		<?php endif; ?>
		<?php if ($row['Type'] == 'merge'): ?>
		<td>
			<?= htmlspecialchars(ucfirst($row['Type']), ENT_QUOTES); ?>
			<?php if (!empty($row['MergeInto'])): ?>
			(<?= htmlspecialchars($row['MergeInto'], ENT_QUOTES); ?>)
			<?php endif; ?>
		</td>
		<?php else: ?>
		<td><?= htmlspecialchars(ucfirst($row['Type']), ENT_QUOTES); ?></td>
		<?php endif; ?>
		<td class="wrap"><?= htmlspecialchars($row['Comments'], ENT_QUOTES); ?></td>
		<td>
		<a href="<?= get_uri('/account/') . htmlspecialchars($row['User'], ENT_QUOTES) ?>" title="<?= __('View account information for %s', htmlspecialchars($row['User'])) ?>"><?= htmlspecialchars($row['User']) ?></a>
		</td>
		<td<?php if ($due): ?> class="flagged"<?php endif; ?>><?= date("Y-m-d H:i", intval($row['RequestTS'])) ?></td>
		<?php if ($row['Open'] && $show_headers): ?>
		<td>
			<?php if ($row['BaseID']): ?>
			<?php if ($row['Type'] == 'deletion'): ?>
			<a href="<?= get_pkgbase_uri($row['Name']) ?>delete/?via=<?= intval($row['ID']) ?>"><?= __('Accept') ?></a>
			<?php elseif ($row['Type'] == 'merge'): ?>
			<a href="<?= get_pkgbase_uri($row['Name']) ?>merge/?into=<?= urlencode($row['MergeInto']) ?>&via=<?= intval($row['ID']) ?>"><?= __('Accept') ?></a>
			<?php elseif ($row['Type'] == 'orphan' && $due): ?>
			<a href="<?= get_pkgbase_uri($row['Name']) ?>disown/?via=<?= intval($row['ID']) ?>"><?= __('Accept') ?></a>
			<?php elseif ($row['Type'] == 'orphan' && !$due): ?>
			<?= __('Locked') ?> (<?= $time_left_fmt ?>)
			<?php endif; ?>
			<br/ >
			<?php endif; ?>
			<a href="<?= get_pkgreq_route() . '/' . intval($row['ID']) ?>/close/"><?= __('Close') ?></a>
		</td>
		<?php elseif ($row['Open'] && !$show_headers): ?>
		<td><?= __("Pending") ?></td>
		<?php elseif ($row['Status'] == 1): ?>
		<td><?= __("Closed") ?></td>
		<?php elseif ($row['Status'] == 2): ?>
		<td><?= __("Accepted") ?></td>
		<?php elseif ($row['Status'] == 3): ?>
		<td><?= __("Rejected") ?></td>
		<?php else: ?>
		<td><?= __("unknown") ?></td>
		<?php endif; ?>
	</tr>
	<?php endforeach; ?>

</tbody>
</table>

<?php if ($show_headers): ?>
<div class="pkglist-stats">
	<p>
		<?= _n('%d package request found.', '%d package requests found.', $total) ?>
		<?= __('Page %d of %d.', $current, $pages) ?>
	</p>
	<?php if (count($templ_pages) > 1): ?>
	<p class="pkglist-nav">
		<?php foreach ($templ_pages as $pagenr => $pagestart): ?>
			<?php if ($pagestart === false): ?>
				<span class="page"><?= $pagenr ?></span>
			<?php elseif ($pagestart + 1 == $first): ?>
				<span class="page"><?= $pagenr ?></span>
			<?php else: ?>
				<a class="page" href="<?= get_uri('/requests/'); ?>?<?= mkurl('O=' . $pagestart) ?>"><?= $pagenr ?></a>
			<?php endif; ?>
		<?php endforeach; ?>
	</p>
	<?php endif; ?>
</div>
<?php endif; ?>
<?php endif; ?>
