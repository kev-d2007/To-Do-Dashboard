<?php require_once 'functions.php'?>
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
            <span><?php echo htmlspecialchars($counts['percentage'] ?? 0); ?>%</span>
        </div>

        <div>
            <h2><?php echo htmlspecialchars($counts['percentage'] ?? 0); ?>%</h2>
            <p>Aantal % voltooid<br><?php echo htmlspecialchars($counts['voltooid'] ?? 0); ?> van <?php echo htmlspecialchars($counts['totaal'] ?? 0); ?></p>
        </div>
    </div>

</div>

<h3>Wekelijkse taken — voltooid vs aangemaakt per dag</h3>
<h4>Kan ik alleen nog niet werkend krijgen, dus voorlopig is dit niet beschikbaar.</h4>

<div class="weekly-stats">

    <div class="day-circle">
        <div class="circle small"><span>80%</span></div>
        <strong>Ma</strong>
        <p>80/100</p>
    </div>

    <div class="day-circle">
        <div class="circle small"><span>67%</span></div>
        <strong>Di</strong>
        <p>60/90</p>
    </div>

    <div class="day-circle">
        <div class="circle small"><span>89%</span></div>
        <strong>Wo</strong>
        <p>85/95</p>
    </div>

    <div class="day-circle">
        <div class="circle small"><span>88%</span></div>
        <strong>Do</strong>
        <p>70/80</p>
    </div>

    <div class="day-circle">
        <div class="circle small"><span>59%</span></div>
        <strong>Vr</strong>
        <p>50/85</p>
    </div>

    <div class="day-circle">
        <div class="circle small"><span>90%</span></div>
        <strong>Za</strong>
        <p>90/100</p>
    </div>

</div>