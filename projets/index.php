<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Jeu 1v1 - Attaque/Défense</title>
  <style>
    html, body {
      margin: 0;
      padding: 0;
      height: 100%;
      background-color: #3caa3c;
      font-family: 'Press Start 2P', monospace;
      overflow: hidden;
    }

    canvas {
      display: block;
      background-color: #3caa3c;
    }

    .score-bar {
      position: absolute;
      top: 20px;
      left: 0;
      width: 100vw;
      display: flex;
      justify-content: space-between;
      align-items: center;
      z-index: 3;
      pointer-events: none;
    }
    .score {
      background: rgba(255,255,255,0.7);
      color: #000;
      border: 4px solid #000;
      border-radius: 16px;
      text-align: center;
      font-family: 'Press Start 2P', monospace;
      box-shadow: 0 8px 32px rgba(0,0,0,0.15), 8px 8px 0px #000;
      font-size: 32px;
      width: 110px;
      height: 60px;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      opacity: 0.85;
      margin: 0 16px;
      pointer-events: none;
    }

    .score.red {
      color: magenta;
      border-color: magenta;
      box-shadow: 0 8px 32px rgba(255,0,255,0.10), 8px 8px 0px #000;
    }

    .score.blue {
      color: cyan;
      border-color: cyan;
      box-shadow: 0 8px 32px rgba(0,255,255,0.10), 8px 8px 0px #000;
    }

    .score-label {
      font-size: 13px;
      color: #222;
      margin-bottom: 2px;
      font-family: inherit;
      opacity: 0.7;
    }

    .center-text {
      position: absolute;
      top: 40%;
      left: 50%;
      transform: translate(-50%, -50%);
      font-size: 48px;
      color: rgba(255, 255, 255, 0.2);
      z-index: 1;
      pointer-events: none;
    }

    .game-count {
      position: static;
      background: rgba(255,255,255,0.7);
      color: #000;
      border: 4px solid #000;
      border-radius: 16px;
      padding: 8px 32px;
      font-family: 'Press Start 2P', monospace;
      box-shadow: 0 8px 32px rgba(0,0,0,0.15), 8px 8px 0px #000;
      text-align: center;
      font-weight: bold;
      letter-spacing: 1px;
      font-size: 18px;
      opacity: 0.85;
      margin: 0 16px;
      display: inline-block;
      pointer-events: none;
    }

    .flash {
      position: fixed;
      top: 0; left: 0;
      width: 100%; height: 100%;
      background: white;
      opacity: 0;
      pointer-events: none;
      z-index: 99;
      transition: opacity 0.2s;
    }

    .overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        background: rgba(20, 20, 20, 0.4);
        backdrop-filter: blur(8px);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 5000;
    }


    .rules-card {
        background: #000;
        color: #fff;
        border: 4px solid #fff;
        border-radius: 20px;
        text-align: center;
        font-family: 'Press Start 2P', monospace;
        box-shadow: 0 8px 32px rgba(0,0,0,0.25), 8px 8px 0px #000;
        margin: 0 auto;
        transition: transform 0.2s;
        transform: scale(1);
        padding: 32px 24px;
        width: 340px;
    }

    .rules-card h2 {
        margin-bottom: 18px;
        font-size: 28px;
        letter-spacing: 2px;
    }

    .rules-card p {
        font-size: 16px;
        margin-bottom: 24px;
        line-height: 1.7;
    }

    #startGameBtn {
        background: #222;
        color: #fff;
        border: none;
        border-radius: 10px;
        font-size: 18px;
        padding: 12px 0;
        width: 90%;
        margin: 10px auto;
        cursor: pointer;
        box-shadow: 0 2px 8px rgba(0,0,0,0.10);
        transition: background 0.2s;
        display: block;
    }

    #startGameBtn:hover {
        background: #444;
    }


    #controlsOverlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100vw;
    height: 100vh;
    background: rgba(20, 20, 20, 0.4);
    backdrop-filter: blur(8px);
    display: none;
    justify-content: center;
    align-items: center;
    z-index: 5001;
    }
    .controls-card {
        background: #000;
        color: #fff;
        width: 340px;
        padding: 32px 24px;
        border: 4px solid #fff;
        border-radius: 20px;
        text-align: center;
        font-family: 'Press Start 2P', monospace;
        box-shadow: 0 8px 32px rgba(0,0,0,0.25), 8px 8px 0px #000;
        margin: 0 auto;
        transition: transform 0.2s;
        transform: scale(1);
    }
    .controls-card h2 {
        margin-bottom: 18px;
        font-size: 20px;
        letter-spacing: 2px;
    }
    .controls-card .player-controls {
        margin-bottom: 18px;
        text-align: left;
    }
    .controls-card .key-btn {
        display: inline-block;
        background: #222;
        border: 2px solid #fff;
        border-radius: 6px;
        padding: 6px 12px;
        margin: 2px;
        font-size: 13px;
        cursor: pointer;
        min-width: 32px;
        font-family: inherit;
        color: #fff;
    }
    .controls-card .key-btn.active {
        background: #ffd700;
        border-color: #444;
        color: #000;
    }
    #closeControlsBtn {
        background: #222;
        color: #fff;
        border: none;
        padding: 12px 0;
        font-size: 16px;
        width: 90%;
        cursor: pointer;
        border-radius: 10px;
        margin: 10px auto 0 auto;
        box-shadow: 0 2px 8px rgba(0,0,0,0.10);
        transition: background 0.2s;
        display: block;
    }
    #closeControlsBtn:hover {
        background: #444;
    }

    .menu-card {
      background: #000;
      color: #fff;
      border: 4px solid #fff;
      border-radius: 20px;
      box-shadow: 0 8px 32px rgba(0,0,0,0.25), 8px 8px 0px #000;
      font-family: 'Press Start 2P', monospace;
      width: 340px;
      padding: 32px 24px;
      text-align: center;
      margin: 0 auto;
    }
    .menu-card h2 {
      font-size: 20px;
      margin-bottom: 24px;
      font-family: 'Press Start 2P', monospace;
      letter-spacing: 2px;
    }
    .menu-btn {
      background: #222;
      color: #fff;
      border: none;
      border-radius: 10px;
      font-size: 16px;
      font-family: inherit;
      padding: 12px 0;
      width: 90%;
      margin: 10px auto;
      cursor: pointer;
      box-shadow: 0 2px 8px rgba(0,0,0,0.10);
      transition: background 0.2s;
      display: block;
    }
    .menu-btn:hover {
      background: #444;
    }

  </style>
  <link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&display=swap" rel="stylesheet">
</head>

<div id="rulesOverlay" class="overlay">
    <div class="rules-card">
        <h2>Règles du jeu</h2>
        <p>
            • L’attaquant doit atteindre la zone opposée sans se faire toucher.<br><br>
            • Le défenseur doit toucher l’attaquant pour gagner.<br><br>
            • Bonne chance !
        </p>
        <button id="startGameBtn">Commencer</button>
    </div>
</div>


<div id="controlsOverlay" class="overlay">
    <div class="controls-card">
      <h2>Contrôles</h2>
      <div class="player-controls">
        <div style="text-align:center; margin-bottom:8px;">Joueur 1 (BLEU):</div>
        <div style="display:flex; flex-direction:column; align-items:center; gap:8px;">
          <span class="key-btn" data-player="1" data-key="up" style="margin-bottom:8px;">Z</span>
          <div style="display:flex; gap:8px;">
            <span class="key-btn" data-player="1" data-key="left">Q</span>
            <span class="key-btn" data-player="1" data-key="down">S</span>
            <span class="key-btn" data-player="1" data-key="right">D</span>
          </div>
        </div>
      </div>
      <div class="player-controls" style="margin-top:24px;">
        <div style="text-align:center; margin-bottom:8px;">Joueur 2 (ROUGE):</div>
        <div style="display:flex; flex-direction:column; align-items:center; gap:8px;">
          <span class="key-btn" data-player="2" data-key="up" style="margin-bottom:8px;">O</span>
          <div style="display:flex; gap:8px;">
            <span class="key-btn" data-player="2" data-key="left">K</span>
            <span class="key-btn" data-player="2" data-key="down">L</span>
            <span class="key-btn" data-player="2" data-key="right">M</span>
          </div>
        </div>
      </div>
      <button id="closeControlsBtn" style="margin-top:24px;">Retour</button>
    </div>
</div>


<div id="menuOverlay" class="overlay" style="display:none;">
  <div class="controls-card menu-card">
    <h2>Menu</h2>
    <button id="btnModifyKeys" class="menu-btn">Modifier les touches</button>
    <button id="btnChangeMode" class="menu-btn">Changer le mode de jeu</button>
    <button id="btnCloseMenu" class="menu-btn">Retour</button>
  </div>
</div>


<div id="modeOverlay" class="overlay" style="display:none;">
  <div class="controls-card menu-card">
    <h2>Mode de jeu</h2>
    <button id="btnPvP" class="menu-btn">Joueur vs Joueur</button>
    <button id="btnPvIA" class="menu-btn">Joueur vs IA</button>
    <button id="btnCloseMode" class="menu-btn">Retour</button>
  </div>
</div>


<div id="levelOverlay" class="overlay" style="display:none;">
  <div class="controls-card menu-card">
    <h2>Niveau de l'IA</h2>
    <button id="btnLevelEasy" class="menu-btn">Débutant</button>
    <button id="btnLevelMedium" class="menu-btn">Intermédiaire</button>
    <button id="btnLevelHard" class="menu-btn">Expert</button>
    <button id="btnCloseLevel" class="menu-btn">Retour</button>
  </div>
</div>

  <canvas id="gameCanvas"></canvas>
  <div class="score-bar">
    <div class="score red" id="scoreRed">
      <span class="score-label">Joueur 1</span>
      <span id="scoreRedValue">0</span>
    </div>
    <div class="game-count" id="gameCount">Partie n° 1</div>
    <div class="score blue" id="scoreBlue">
      <span class="score-label">Joueur 2</span>
      <span id="scoreBlueValue">0</span>
    </div>
  </div>
  <div class="center-text" id="attackerDisplay">BLEU ATTAQUE</div>
  <div class="flash" id="flash"></div>
  <script>
    const canvas = document.getElementById("gameCanvas");
    const ctx = canvas.getContext("2d");
    canvas.width = window.innerWidth;
    canvas.height = window.innerHeight;

    const lineWidth = 5;
    const zoneOffset = 100;
    const playerSize = 50;
    const speed = 5;

    let player1 = {
    x: 0,
    y: canvas.height / 2 - playerSize / 2,
      color: "blue",
      score: 0,
      keys: { up: "z", down: "s", left: "q", right: "d" },
    };


    let player2 = {
    x: canvas.width - playerSize,
    y: canvas.height / 2 - playerSize / 2,
      color: "red",
      score: 0,
      keys: { up: "o", down: "l", left: "k", right: "m" },
    };

    let isBlueAttacker = true;
    let keysPressed = {};
    let gameCount = 0;
    let controlsActive = true;
    let gameActive = false;
    let mode = "pvp";
    let iaLevel = "medium";
    let iaSpeed = 5;

    document.addEventListener("keydown", (e) => {
      keysPressed[e.key.toLowerCase()] = true;
    });

    document.addEventListener("keyup", (e) => {
      keysPressed[e.key.toLowerCase()] = false;
    });

    function drawField() {
      ctx.clearRect(0, 0, canvas.width, canvas.height);
      ctx.fillStyle = "white";
      ctx.fillRect(zoneOffset, 0, lineWidth, canvas.height);
      ctx.fillRect(canvas.width - zoneOffset - lineWidth, 0, lineWidth, canvas.height);
    }

    function drawPlayer(p) {
      ctx.fillStyle = p.color;
      ctx.fillRect(p.x, p.y, playerSize, playerSize);
    }

    function updatePlayerPosition(player) {
      if (!controlsActive || !gameActive) return;


    if (mode === "pvIA" && player === player2) {
        const safeZoneLeft = zoneOffset + lineWidth;
        if (iaLevel === "easy") iaSpeed = speed * 0.6;
        else if (iaLevel === "medium") iaSpeed = speed * 0.7;
        else iaSpeed = speed * 0.8;

        if (player2.y < player1.y - 2 && player2.y + playerSize < canvas.height) player2.y += iaSpeed;
        else if (player2.y > player1.y + 2 && player2.y > 0) player2.y -= iaSpeed;

        if (player2.x < player1.x - 2 && player2.x + playerSize < canvas.width) player2.x += iaSpeed;
        else if (player2.x > player1.x + 2 && player2.x > safeZoneLeft) player2.x -= iaSpeed;

        player2.y = Math.max(0, Math.min(canvas.height - playerSize, player2.y));
        player2.x = Math.max(safeZoneLeft, Math.min(canvas.width - playerSize, player2.x));
        return;
    }


    const playerSpeed = speed;
    const isDefender = (mode === "pvp" && ((isBlueAttacker && player === player2) || (!isBlueAttacker && player === player1)));
    let leftBoundary = 0;
    let rightBoundary = canvas.width;

    if (mode === "pvp" && isDefender) {
        leftBoundary = isBlueAttacker ? zoneOffset + lineWidth : 0;
        rightBoundary = isBlueAttacker ? canvas.width : canvas.width - zoneOffset - lineWidth;
    }

    if (keysPressed[player.keys.up] && player.y > 0) player.y -= playerSpeed;
    if (keysPressed[player.keys.down] && player.y + playerSize < canvas.height) player.y += playerSpeed;
    if (keysPressed[player.keys.left] && player.x > leftBoundary) player.x -= playerSpeed;
    if (keysPressed[player.keys.right] && player.x + playerSize < rightBoundary) player.x += playerSpeed;
    }

    function checkScore() {
      const attacker = isBlueAttacker ? player1 : player2;
      const attackerEdge = isBlueAttacker ? attacker.x + playerSize : attacker.x;
      const line = isBlueAttacker ? canvas.width - zoneOffset : zoneOffset;

      if ((isBlueAttacker && attackerEdge > line) || (!isBlueAttacker && attackerEdge < line)) {
        return true;
      }
      return false;
    }

    function checkCollision() {
      const a = isBlueAttacker ? player1 : player2;
      const d = isBlueAttacker ? player2 : player1;

      return (
        a.x < d.x + playerSize &&
        a.x + playerSize > d.x &&
        a.y < d.y + playerSize &&
        a.y + playerSize > d.y
      );
    }

    function resetPositions() {
    player1.x = 0;
    player1.y = canvas.height / 2 - playerSize / 2;
    player2.x = canvas.width - playerSize;
    player2.y = canvas.height / 2 - playerSize / 2;
    }

    function flashScreen() {
      const flash = document.getElementById("flash");
      flash.style.opacity = 1;
      setTimeout(() => {
        flash.style.opacity = 0;
      }, 100);
    }

    function gameLoop() {
      drawField();
      updatePlayerPosition(player1);
      updatePlayerPosition(player2);
      drawPlayer(player1);
      drawPlayer(player2);


      let attacker = player1;
      let defender = player2;
      if (mode === "pvIA") {

        const attackerEdge = attacker.x + playerSize;
        const line = canvas.width - zoneOffset;
        if (attackerEdge > line) {
            flashScreen();
            player1.score++;
            document.getElementById("scoreRedValue").innerText = player1.score;
            resetPositions();
            gameCount++;
            document.getElementById("gameCount").innerText = `Partie n° ${gameCount+1}`;
            return requestAnimationFrame(gameLoop);
        }

        if (
            attacker.x < defender.x + playerSize &&
            attacker.x + playerSize > defender.x &&
            attacker.y < defender.y + playerSize &&
            attacker.y + playerSize > defender.y
        ) {
            flashScreen();
            player2.score++;
            document.getElementById("scoreBlueValue").innerText = player2.score;
            resetPositions();
            gameCount++;
            document.getElementById("gameCount").innerText = `Partie n° ${gameCount+1}`;
            return requestAnimationFrame(gameLoop);
        }
      }

      if (checkScore()) {
        flashScreen();
        if (isBlueAttacker) {
          player1.score++;
          document.getElementById("scoreRedValue").innerText = player1.score;
        } else {
          player2.score++;
          document.getElementById("scoreBlueValue").innerText = player2.score;
        }
        isBlueAttacker = !isBlueAttacker;
        document.getElementById("attackerDisplay").innerText = isBlueAttacker ? "BLEU ATTAQUE" : "ROUGE ATTAQUE";
        resetPositions();
        gameCount++;
        document.getElementById("gameCount").innerText = `Partie n° ${gameCount+1}`;
      }

      if (checkCollision()) {
        flashScreen();
        if (isBlueAttacker) {
          player2.score++;
          document.getElementById("scoreBlueValue").innerText = player2.score;
        } else {
          player1.score++;
          document.getElementById("scoreRedValue").innerText = player1.score;
        }
        isBlueAttacker = !isBlueAttacker;
        document.getElementById("attackerDisplay").innerText = isBlueAttacker ? "BLEU ATTAQUE" : "ROUGE ATTAQUE";
        resetPositions();
        gameCount++;
        document.getElementById("gameCount").innerText = `Partie n° ${gameCount+1}`;
      }


      if (mode === "pvIA") {
        isBlueAttacker = false;
      }

      requestAnimationFrame(gameLoop);
    }


document.getElementById("startGameBtn").addEventListener("click", () => {
    document.getElementById("rulesOverlay").style.display = "none";
    gameActive = true;

});


let waitingKey = null;


    window.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            document.getElementById('menuOverlay').style.display = 'flex';
            controlsActive = false;
        }
    });


const menuOverlay = document.getElementById('menuOverlay');
const btnModifyKeys = document.getElementById('btnModifyKeys');
const btnChangeMode = document.getElementById('btnChangeMode');
const btnCloseMenu = document.getElementById('btnCloseMenu');
const controlsOverlay = document.getElementById('controlsOverlay');
const modeOverlay = document.getElementById('modeOverlay');
const btnPvP = document.getElementById('btnPvP');
const btnPvIA = document.getElementById('btnPvIA');
const btnCloseMode = document.getElementById('btnCloseMode');
const levelOverlay = document.getElementById('levelOverlay');
const btnLevelEasy = document.getElementById('btnLevelEasy');
const btnLevelMedium = document.getElementById('btnLevelMedium');
const btnLevelHard = document.getElementById('btnLevelHard');
const btnCloseLevel = document.getElementById('btnCloseLevel');

btnModifyKeys.addEventListener('click', () => {
    menuOverlay.style.display = 'none';
    controlsOverlay.style.display = 'flex';
});
btnChangeMode.addEventListener('click', () => {
    menuOverlay.style.display = 'none';

    if (mode === "pvp") {
        document.getElementById('btnPvP').style.display = 'none';
        document.getElementById('btnPvIA').style.display = 'block';
    } else {
        document.getElementById('btnPvP').style.display = 'block';
        document.getElementById('btnPvIA').style.display = 'block';
    }
    modeOverlay.style.display = 'flex';
});
btnCloseMenu.addEventListener('click', () => {
    menuOverlay.style.display = 'none';
    controlsActive = true;
});
btnPvP.addEventListener('click', () => {
    mode = "pvp";
    modeOverlay.style.display = 'none';
    controlsActive = true;
    resetPositions();
});
btnPvIA.addEventListener('click', () => {
    modeOverlay.style.display = 'none';
    levelOverlay.style.display = 'flex';
});
btnCloseMode.addEventListener('click', () => {
    modeOverlay.style.display = 'none';
    controlsActive = true;
});
btnLevelEasy.addEventListener('click', () => {
    mode = "pvIA";
    iaLevel = "easy";
    levelOverlay.style.display = 'none';
    controlsActive = true;
    player1.score = 0;
    player2.score = 0;
    document.getElementById("scoreRedValue").innerText = player1.score;
    document.getElementById("scoreBlueValue").innerText = player2.score;
    isBlueAttacker = true;
    resetPositions();
});
btnLevelMedium.addEventListener('click', () => {
    mode = "pvIA";
    iaLevel = "medium";
    levelOverlay.style.display = 'none';
    controlsActive = true;
    player1.score = 0;
    player2.score = 0;
    document.getElementById("scoreRedValue").innerText = player1.score;
    document.getElementById("scoreBlueValue").innerText = player2.score;
    isBlueAttacker = true;
    resetPositions();
});
btnLevelHard.addEventListener('click', () => {
    mode = "pvIA";
    iaLevel = "hard";
    levelOverlay.style.display = 'none';
    controlsActive = true;
    player1.score = 0;
    player2.score = 0;
    document.getElementById("scoreRedValue").innerText = player1.score;
    document.getElementById("scoreBlueValue").innerText = player2.score;
    isBlueAttacker = true;
    resetPositions();
});
btnCloseLevel.addEventListener('click', () => {
    levelOverlay.style.display = 'none';
    controlsActive = true;
});


    const closeControlsBtn = document.getElementById('closeControlsBtn');
    if (closeControlsBtn) {
        closeControlsBtn.addEventListener('click', () => {
            controlsOverlay.style.display = 'none';
            waitingKey = null;
            controlsActive = true;
        });
    }

    const keyBtns = document.querySelectorAll('.key-btn');
    keyBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            keyBtns.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            waitingKey = btn;
        });
    });

    window.addEventListener('keydown', (e) => {
        if (waitingKey && !['Escape'].includes(e.key)) {
            const player = waitingKey.getAttribute('data-player');
            const keyType = waitingKey.getAttribute('data-key');
            const newKey = e.key.toLowerCase();
            let keysObj = player === '1' ? player1.keys : player2.keys;
            let alreadyUsed = Object.entries(keysObj).find(([dir, val]) => val === newKey);
            if (alreadyUsed) {
                keysObj[alreadyUsed[0]] = '';
                document.querySelector('.key-btn[data-player="'+player+'"][data-key="'+alreadyUsed[0]+'"]').textContent = '';
            }
            waitingKey.textContent = e.key.toUpperCase();
            waitingKey.classList.remove('active');
            keysObj[keyType] = newKey;
            waitingKey = null;
        }
    });

    function goBack(currentOverlay) {
    switch(currentOverlay) {
        case 'controlsOverlay':
            controlsOverlay.style.display = 'none';
            menuOverlay.style.display = 'flex';
            break;
        case 'modeOverlay':
            modeOverlay.style.display = 'none';
            menuOverlay.style.display = 'flex';
            break;
        case 'levelOverlay':
            levelOverlay.style.display = 'none';
            modeOverlay.style.display = 'flex';
            break;
        case 'menuOverlay':
            menuOverlay.style.display = 'none';
            controlsActive = true;
            break;
    }
  }

  document.getElementById('closeControlsBtn').addEventListener('click', () => {
      goBack('controlsOverlay');
  });

  document.getElementById('btnCloseMenu').addEventListener('click', () => {
      goBack('menuOverlay');
  });

  document.getElementById('btnCloseMode').addEventListener('click', () => {
      goBack('modeOverlay');
  });

  document.getElementById('btnCloseLevel').addEventListener('click', () => {
      goBack('levelOverlay');
  });

    gameLoop();
  </script>
</body>
</html>
