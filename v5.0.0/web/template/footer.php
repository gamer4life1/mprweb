
			<!-- End of main content -->

			<div id="footer">
				<?php if ($ver): ?>
				<p>aurweb <a href="https://git.archlinux.org/aurweb.git/log/?h=<?= htmlspecialchars($ver, ENT_QUOTES) ?>"><?= htmlspecialchars($ver) ?></a></p>
				<?php endif; ?>
				<p>Copyright 2021 Hunter Wittenborn. Forked from aurweb.</p>
				<p>MPR packages are user produced content. Any use of the provided files is at your own risk.</p>
				<p>The MPR is not endored by Ubuntu, Arch Linux, or their affiliates.</p>
				<p><?= __('') ?></p>
			</div>
		</div>
	</body>
</html>
