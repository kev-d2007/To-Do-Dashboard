<h1>Mijn taken</h1>

<div class="task-input">
    <input type="text" placeholder="Voeg een nieuwe taak toe...">
    <button>Taak toevoegen</button>
</div>

<h3>ONVOLTOOIDE TAKEN</h3>

<div class="dashboard-grid">

    <!-- LEFT SIDE -->
    <div class="left-panel">

        <div class="task-card priority-high">
            <input type="checkbox">
            <span>Rapport afmaken voor deadline</span>
            <div class="badge">1</div>
        </div>

        <div class="task-card priority-high">
            <input type="checkbox">
            <span>Klantgesprek voorbereiden</span>
            <div class="badge">1</div>
        </div>

        <div class="task-card priority-medium">
            <input type="checkbox">
            <span>Vergadering voorbereiden</span>
            <div class="badge orange">2</div>
        </div>

        <div class="task-card priority-medium">
            <input type="checkbox">
            <span>Presentatie volgende week</span>
            <div class="badge orange">2</div>
        </div>

        <div class="task-card priority-low">
            <input type="checkbox">
            <span>Offerte opstellen</span>
            <div class="badge yellow">3</div>
        </div>

        <a href="#" class="all-tasks-link">alle taken</a>
        <div class="show-more-btn">
         ↓
        </div>

    </div>

    <!-- RIGHT SIDE -->
    <div class="right-panel">

        <div class="stats-card">

            <h4>Taakoverzicht vandaag</h4>

            <div class="stats-row">

                <div class="completed-box">
                    <span class="number">7</span>
                    <span>Voltooid</span>
                </div>

                <div class="open-box">
                    <span class="number">3</span>
                    <span>Open</span>
                </div>

            </div>

            <div class="progress-bar">
                <div class="progress-fill"></div>
            </div>

            <p>70% van de dagtaken klaar</p>

        </div>

        <div class="week-card">

            <h4>Voortgang deze week</h4>

            <div class="week-days">
                <div>Ma</div>
                <div>Di</div>
                <div>Wo</div>
                <div>Do</div>
                <div>Vr</div>
                <div>Za</div>
                <div>Zo</div>
            </div>

               <div class="week-legend">
               <span>⬜ Weinig</span>
               <span>🟩 Veel</span>

               </div>

           <div class="week-footer">
           <span>Gemiddeld voltooid</span>
           <strong>82%</strong>
           </div>

        </div>

    </div>

</div>