<section class="stats-grid">
<div class="stat-card"><span class="stat-icon">👥</span><span class="muted">Total players</span><strong><?= e($stats['players']) ?></strong></div>
<div class="stat-card"><span class="stat-icon">✓</span><span class="muted">Active players</span><strong><?= e($stats['active']) ?></strong></div>
<div class="stat-card"><span class="stat-icon">🏆</span><span class="muted">Upcoming matches</span><strong><?= e($stats['matches']) ?></strong></div>
<div class="stat-card"><span class="stat-icon">📅</span><span class="muted">Training sessions</span><strong><?= e($stats['trainings']) ?></strong></div>
</section>
<section class="panel"><div class="panel-head"><div><span class="eyebrow">FIXTURES</span><h2>Upcoming matches</h2></div><a class="btn" href="index.php?route=matches/form">+ Schedule match</a></div>
<?php if(!$upcoming): ?><p class="empty">No upcoming matches scheduled yet.</p><?php else: ?><div class="table-wrap"><table><thead><tr><th>Opponent</th><th>Date & time</th><th>Venue</th><th>Competition</th></tr></thead><tbody><?php foreach($upcoming as $m): ?><tr><td><b><?= e($m['opponent']) ?></b></td><td><?= e(date('d M Y, H:i',strtotime($m['match_date']))) ?></td><td><?= e($m['venue']) ?></td><td><?= e($m['competition']) ?></td></tr><?php endforeach; ?></tbody></table></div><?php endif; ?></section>