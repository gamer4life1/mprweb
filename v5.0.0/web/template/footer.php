
			<!-- End of main content -->

			<div id="footer">
				<?php if ($ver): ?>
				<p>mprweb <a target="_blank" rel="noopener noreferrer" href="https://github.com/makedeb/mprweb/tree/main/<?= htmlspecialchars($ver, ENT_QUOTES) ?>"><?= htmlspecialchars($ver) ?></a>. Forked from <a target="_blank" rel="noopener noreferrer" href="https://gitlab.archlinux.org/archlinux/aurweb/-/tree/<?= htmlspecialchars($ver, ENT_QUOTES) ?>">aurweb</a>.</p>
				<?php endif; ?>
				<p>Copyright &copy; 2021 Hunter Wittenborn.</p>
				<p>MPR packages are user produced content. Any use of the provided files is at your own risk.</p>
				<p>The MPR is not endored by Ubuntu, Arch Linux, or their affiliates.</p>
				<p><?= __('') ?></p>
			</div>
		</div>
	</body>
</html>
