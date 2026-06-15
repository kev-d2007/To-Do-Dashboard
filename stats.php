<?php require_once 'functions.php';
if (!isset($conn) || $conn === null) {
    require_once 'database.php';
}
?>
<h1>Statistieken</h1>
<h4 style="color: #666; font-size: 14px;">
    Statistieken zijn mogelijk niet accuraat. Log opnieuw in om de nieuwste gegevens te zien.
</h4>

<div class="stats-top">

    <div class="big-stat green">
        <span>
            <?php
                $counts = taken_tellen();
                echo htmlspecialchars($counts['voltooid']);
            ?>
        </span>
        <p>Taken voltooid</p>
    </div>

    <div class="big-stat orange">
        <span><?php echo htmlspecialchars($counts['onvoltooid'] ?? 0); ?></span>
        <p>Taken onvoltooid</p>
    </div>

    <div class="percent-card">
        <div class="circle">
            <span><?php echo isset($counts['voltooid']) && isset($counts['onvoltooid']) ? round(($counts['voltooid'] / ($counts['voltooid'] + $counts['onvoltooid'])) * 100, 2) : 0; ?>%</span>
        </div>

        <div>
            <h5><?php 
            $prestatie = prestatie();
            echo isset($prestatie) ? $prestatie : 'Geen gegevens beschikbaar'; ?></h5>
            <p><?php echo isset($counts['voltooid']) && isset($counts['onvoltooid']) ? round(($counts['voltooid'] / ($counts['voltooid'] + $counts['onvoltooid'])) * 100, 2) : 0; ?>% voltooid<br><?php echo htmlspecialchars($counts['voltooid'] ?? 0); ?> van <?php echo htmlspecialchars($counts['totaal'] ?? 0); ?></p>
        </div>
    </div>

</div>

<h3>Laatste 7 dagen — aanmaak per dag</h3>

<?php
$user_id = (int)($_SESSION['user_id'] ?? 0);
$date_col = null;
$resCol = $conn->query("SHOW COLUMNS FROM taken");
if ($resCol) {
    while ($col = $resCol->fetch_assoc()) {
        $name = $col['Field'];
        if (in_array($name, ['gemaakt_op','created_at','created'])) { $date_col = $name; break; }
    }
}

$prio_counts = ['1'=>0,'2'=>0,'3'=>0];
$stmtp = $conn->prepare("SELECT prioriteit, COUNT(*) as cnt FROM taken WHERE user_id = ? GROUP BY prioriteit");
if ($stmtp) {
    $stmtp->bind_param('i', $user_id);
    $stmtp->execute();
    $rp = $stmtp->get_result();
    while ($rpRow = $rp->fetch_assoc()) {
        $p = (int)$rpRow['prioriteit'];
        $prio_counts[(string)$p] = (int)$rpRow['cnt'];
    }
    $stmtp->close();
}

$prio_total = array_sum($prio_counts);

$matrix = [
    '1'=>['open'=>0,'done'=>0],
    '2'=>['open'=>0,'done'=>0],
    '3'=>['open'=>0,'done'=>0]
];
$stmtm = $conn->prepare("SELECT prioriteit, afgerond, COUNT(*) as cnt FROM taken WHERE user_id = ? GROUP BY prioriteit, afgerond");
if ($stmtm) {
    $stmtm->bind_param('i', $user_id);
    $stmtm->execute();
    $rm = $stmtm->get_result();
    while ($mrow = $rm->fetch_assoc()) {
        $p = (string)((int)$mrow['prioriteit']);
        $done = (int)$mrow['afgerond'] ? 'done' : 'open';
        if (!isset($matrix[$p])) $matrix[$p] = ['open'=>0,'done'=>0];
        $matrix[$p][$done] = (int)$mrow['cnt'];
    }
    $stmtm->close();
}

$recent = [];
$stmtr = $conn->prepare("SELECT id, titel, prioriteit FROM taken WHERE user_id = ? ORDER BY id DESC LIMIT 7");
if ($stmtr) {
    $stmtr->bind_param('i', $user_id);
    $stmtr->execute();
    $rr = $stmtr->get_result();
    while ($rrow = $rr->fetch_assoc()) { $recent[] = $rrow; }
    $stmtr->close();
}
?>

<div class="stats-grid">
    <div class="prio-box big">
        <h4>Prioriteit verdeling</h4>
        <div class="prio-list">
            <?php foreach (['1'=>'Hoog','2'=>'Gemiddeld','3'=>'Laag'] as $k=>$label):
                $cnt = $prio_counts[$k] ?? 0;
                $pct = $prio_total ? round($cnt/$prio_total*100) : 0;
            ?>
            <div class="prio-row">
                <div class="prio-label"><?php echo $label; ?></div>
                <div class="prio-bar"><div class="fill p<?php echo $k; ?>" style="width:<?php echo $pct; ?>%"></div></div>
                <div class="prio-count"><?php echo $cnt; ?> (<?php echo $pct; ?>%)</div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="words-box">
        <h4>Open vs Afgerond per prioriteit</h4>
        <div class="matrix-list">
            <?php foreach (['1'=>'Hoog','2'=>'Gemiddeld','3'=>'Laag'] as $k=>$label):
                $open = $matrix[$k]['open'] ?? 0;
                $done = $matrix[$k]['done'] ?? 0;
                $total = $open + $done;
                $open_pct = $total ? round($open / $total * 100) : 0;
                $done_pct = $total ? round($done / $total * 100) : 0;
            ?>
            <div class="matrix-row">
                <div class="matrix-label"><?php echo $label; ?></div>
                <div class="matrix-bars">
                    <div class="bar open"><div class="fill" style="width:<?php echo $open_pct; ?>%"></div></div>
                    <div class="bar done"><div class="fill" style="width:<?php echo $done_pct; ?>%"></div></div>
                </div>
                <div class="matrix-count"><?php echo $open; ?> / <?php echo $done; ?><h5>openstaand/afgerond</h5></div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>