// Récupère le canvas et son image en 2D
const canvas = document.getElementById('tetris');
const context = canvas.getContext('2d');

// Echelle poura agrandir les pixels
context.scale(20,20);

// Fonction pour supprimer les lignes complètes
function arenaSweep() {
    let rowCount = 1;
    // Parcours les lignes de bas en haut
    outer: for (let y = arena.length - 1; y > 0; --y) {
        for (let x = 0; x < arena[y].length; ++x) {
            if (arena[y][x] === 0) {
                continue outer;
            }
        }
        // Supprime la ligne complète et insère une nouvelle ligne vide en haut
        const row = arena.splice(y, 1)[0].fill(0);
        arena.unshift(row);
        ++y; //Vérifie après suppression

        // Augmente le score basé sur les lignes supprimées
        player.score += rowCount * 10;
        rowCount *=2;
    }
}

// Vérifie si le joueur entre en collision avec l'arène
function collide(arena, player) {
    const m = player.matrix;
    const o = player.pos;
    for (let y = 0; y < m.length; ++y) {
        for (let x = 0; x < m[y].length; ++x) {
            if (
                m[y][x] !== 0 && // Vérifie si la cellule du bloc n'est pas vide
                (arena[y + o.y] && arena[y + o.y][x + o.x]) !== 0 // Vérifie si elle entre en collision avec l'arène
            ) {
                return true;
            }
        }
    }
    return false;
}

function createMatrix(w, h) {
    const matrix = [];
    while (h--) {
        matrix.push(new Array(w).fill(0)); //Remplit chaque ligne avec des zéros
    }
    return matrix;
}

// Crée une matrice représentant les pièces en fonction de son type
function createPiece(type) {
    if (type === 'I') {
        return [
            [0, 1, 0, 0],
            [0, 1, 0, 0],
            [0, 1, 0, 0],
            [0, 1, 0, 0],
        ];
    } else if (type === 'L') {
        return [
            [0, 2, 0],
            [0, 2, 0],
            [0, 2, 2],
        ];
    } else if (type === 'J') {
        return [
            [0, 3, 0],
            [0, 3, 0],
            [3, 3, 0],
        ];
    } else if (type === 'O') {
        return [
            [4, 4],
            [4, 4],
        ];
    } else if (type === 'Z') {
        return [
            [5, 5, 0],
            [0, 5, 5],
            [0, 0, 0],
        ];
    } else if (type === 'S') {
        return [
            [0, 6, 6],
            [6, 6, 0],
            [0, 0, 0],

        ];
    } else if (type === 'T') {
        return [
            [0, 7, 0],
            [7, 7, 7],
            [0, 0, 0],

        ];
    }
}
// Dessine l'arène et le joueur
function drawMatrix(matrix, offset) {
    matrix.forEach((row, y) => {
        row.forEach((value, x) => {
            if (value !== 0) {
                context.fillStyle = colors[value]; // Couleur basés sur la valeur
                context.fillRect(x + offset.x, y + offset.y, 1, 1);
            }
        });
    });
}

// Dessine l'arène et le joueur
function draw() {
    context.fillStyle = '#000' ; //Fond noir
    context.fillRect(0, 0, canvas.clientWidth, canvas.height);

    drawMatrix(arena, {x: 0, y: 0}); // Dessine l'arène
    drawMatrix(player.matrix, player.pos); // Dessine le joueur
}

//Fusionne la pièce du joueur avec l'arène lorsqu'elle atteint une limite
function merge(arena, player) {
    player.matrix.forEach((row, y) => {
        row.forEach((value, x) => {
            if (value !== 0) {
                arena[y + player.pos.y][x + player.pos.x] = value; 
            }
        });
    });
}

// Gère la rotation des pièces dans la matrice
function rotate(matrix, dir) {
    for (let y = 0; y < matrix.length; ++y) {
        for (let x = 0; x < y; ++x) {
            [matrix[x][y], matrix[y][x]] = [matrix[y][x], matrix[x][y]];
        }
    }
    if (dir > 0) {
        matrix.forEach(row => row.reverse()); // Sens horaire
    } else {
        matrix.reverse(); // Sens antihoraire
    }
}
// Déplace le joueur vers le bas, fusionne la pièce si elle atteint une limite
function playerDrop() {
    player.pos.y++;
    if (collide(arena, player)) {
        player.pos.y--;
        merge(arena, player);
        playerReset();
        arenaSweep();
        updateScore();
    }
    dropCounter = 0;
}

// Déplace horizontalement la pièce du joueur
function playerMove(offset) {
    player.pos.x  += offset;
    if (collide(arena, player)) {
        player.pos.x -= offset;
    }
}

// Réinitialise la pièce du joueur lorsqu'une pièce atteint une limite
function playerReset() {
    const pieces = 'TJLOSZI';
    player.matrix = createPiece(pieces[pieces.length * Math.random() | 0]);
    player.pos.y = 0;
    player.pos.x = (arena[0].length / 2 | 0) - (player.matrix[0].length / 2 | 0);
    if (collide(arena, player)) {
        arena.forEach(row => row.fill(0)); // Réinitialise l'arène
        player.score = 0; // Réinitialise le score
        updateScore();
    }
}

//Fait tourner la pièce du joueur
function playerRotate(dir) {
    const pos = player.pos.x;
    let offset = 1;
    rotate(player.matrix, dir);
    while (collide(arena, player)) {
        player.pos.x += offset;
        offset = -(offset + (offset > 0 ? 1 : -1));
        if (offset > player.matrix[0].length) {
            rotate(player.matrix, -dir);
            player.pos.x = pos;
            return;
        }
    }
}

// Initialise les variables globales
let dropCounter = 0;
let dropInterval = 1000;

let lastTime = 0;
// Boucle principale du jeu
function update(time = 0) {
    const deltaTime = time - lastTime;

    dropCounter += deltaTime;
    if (dropCounter > dropInterval) {
        playerDrop();
    }

    lastTime = time;

    draw();
    requestAnimationFrame(update); //Appelle la fonction à chaque rafraîchissement
}

// Maj du Score
function updateScore() {
    document.getElementById('score').innerText = player.score;
}

// Ecouteur pour les touches (pour jouer)
document.addEventListener('keydown', event => {
    if (event.keyCode === 37) { // Flèche G
        playerMove(-1);
    } else if (event.keyCode === 39) { // FLèche D
        playerMove(1);
    } else if (event.keyCode === 40) { // Flèche B
        playerDrop();
    } else if (event.keyCode === 32) { // Espace pour rotation antihoraire
        playerRotate(-1);
    } else if (event.keyCode === 87) { // W pour rotation horaire
        playerRotate(1);
    }
});

// Définition des couleurs des pièces
const colors = [
    null,
    '#FF0D0D',
    '#110DFF',
    '#00B530',
    '#8C0094',
    '#FF9D00',
    '#38FFD8',
    '#38FCFF',
];

//Crée une arène 12x20
const arena = createMatrix(12,20);

//Initialise le joueur
const player = {
    pos: {x: 0, y: 0},
    matrix: null,
    score: 0,
};

// Lancement du jeu
playerReset();
updateScore();
update();