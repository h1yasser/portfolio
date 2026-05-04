const canvas = document.getElementById('canvas'); // Sélection du canevas HTML où le jeu sera dessiné.
const ctx = canvas.getContext('2d'); // Contexte 2D utilisé pour dessiner sur le canevas.
const img = new Image();
const img2 = new Image(); // Création d'un objet Image pour charger les ressources graphiques.
img.src = "jaune.png";
img2.src = "rouge.png"; // Chemin vers l'image contenant les sprites du jeu.

let gamePlaying = false;// Indique si le jeu est en cours.
const gravity = 0.5; // Force de gravité qui fait descendre l'oiseau.
const speed = 4.2; // Vitesse de déplacement des tuyaux et du fond.
const size = [51, 36]; // Taille de l'oiseau (largeur et hauteur en pixels).
const jump = -11.5; // Valeur du saut lorsque l'oiseau vole vers le haut.
const cTenth = (canvas.width / 10);
const cTenth2 = (canvas.width / 10); // Position horizontale fixe de l'oiseau (1/10ème de la largeur du canevas).

// Variables pour la gestion du jeu.
let index = 0, // Index utilisé pour animer les éléments du jeu.
    bestScore = 0, // Meilleur score atteint.
    flight,
    flight2, // Vitesse verticale de l'oiseau (gravité ou saut).
    flyHeight,
    flyHeight2, // Position verticale de l'oiseau.
    currentScore, // Score actuel.
    pipes; // Tableau contenant les positions des tuyaux.

// Dimensions et écart entre les tuyaux.
const pipeWidth = 78; // Largeur des tuyaux.
const pipeGap = 270; // Écart vertical entre les tuyaux (espace pour passer).
const pipeLoc = () => (Math.random() * ((canvas.height - (pipeGap + pipeWidth)) - pipeWidth)) + pipeWidth; 
// Génère une position aléatoire pour le sommet du tuyau inférieur.

// Fonction de configuration initiale ou de réinitialisation du jeu.
const setup = () => {
    currentScore = 0; // Réinitialisation du score.
    flight = jump;
    flight2 = jump; // Réinitialisation de la vitesse verticale de l'oiseau.
    flyHeight = (canvas.height / 2) - (size[1] / 2);
    flyHeight2 = (canvas.height / 1.8) - (size[1] / 2); // Position de départ de l'oiseau (au centre vertical).

    // Création de trois tuyaux avec des positions horizontales décalées.
    pipes = Array(3).fill().map((_, i) => [canvas.width + (i * (pipeGap + pipeWidth)), pipeLoc()]);
}

// Fonction principale de rendu, appelée à chaque frame.
const render = () => {
    index++; // Incrémentation pour animer le jeu.

    // Dessin du fond en boucle pour simuler un scrolling horizontal.
    ctx.drawImage(img, 0, 0, canvas.width, canvas.height, -((index * (speed / 2)) % canvas.width) + canvas.width, 0, canvas.width, canvas.height);
    ctx.drawImage(img, 0, 0, canvas.width, canvas.height, -(index * (speed / 2)) % canvas.width, 0, canvas.width, canvas.height);

    if (gamePlaying) {
        // Mise à jour et dessin des tuyaux.
        pipes.map(pipe => {
            pipe[0] -= speed; // Déplacement des tuyaux vers la gauche.

            // Dessin du tuyau supérieur.
            ctx.drawImage(img, 432, 588 - pipe[1], pipeWidth, pipe[1], pipe[0], 0, pipeWidth, pipe[1]);

            // Dessin du tuyau inférieur.
            ctx.drawImage(img, 432 + pipeWidth, 108, pipeWidth, canvas.height - pipe[1] + pipeGap, pipe[0], pipe[1] + pipeGap, pipeWidth, canvas.height - pipe[1] + pipeGap);

            // Si un tuyau sort de l'écran à gauche, il est repositionné à droite.
            if (pipe[0] <= -pipeWidth) {
                currentScore++; // Incrémentation du score.
                bestScore = Math.max(bestScore, currentScore); // Mise à jour du meilleur score.
                pipes = [...pipes.slice(1), [pipes[pipes.length - 1][0] + pipeGap + pipeWidth, pipeLoc()]]; // Ajout d'un nouveau tuyau.
            }

            // Vérification des collisions entre l'oiseau et les tuyaux.
            if ([
                pipe[0] <= cTenth + size[0], // Collision sur l'axe horizontal.
                pipe[0] + pipeWidth >= cTenth, // Collision sur l'autre bord horizontal.
                pipe[1] > flyHeight || pipe[1] + pipeGap < flyHeight + size[1] // Collision verticale.
            ].every(Boolean)) {
                gamePlaying = false; // Arrêt du jeu en cas de collision.
                setup(); // Réinitialisation du jeu.
            }
            if ([
                pipe[0] <= cTenth2 + size[0], // Collision sur l'axe horizontal.
                pipe[0] + pipeWidth >= cTenth, // Collision sur l'autre bord horizontal.
                pipe[1] > flyHeight2 || pipe[1] + pipeGap < flyHeight2 + size[1] // Collision verticale.
            ].every(Boolean)) {
                gamePlaying = false; // Arrêt du jeu en cas de collision.
                setup(); // Réinitialisation du jeu.
            }
        })
    }

    // Si le jeu est en cours.
    if (gamePlaying) {
        // Dessin de l'oiseau en mouvement.
        ctx.drawImage(img, 432, Math.floor((index % 9) / 3) * size[1], ...size, cTenth, flyHeight, ...size);
        flight += gravity; // Application de la gravité.
        flyHeight = Math.min(flyHeight + flight, canvas.height - size[1]); // Mise à jour de la position verticale.

        document.addEventListener('keydown', (event) => {
            if (event.code === 'Space') { 
                if (!gamePlaying) {
                    gamePlaying = true;
                }
                flight = jump;
            }
        })
    } else {
        // Dessin de l'oiseau immobile avant le début du jeu.
        ctx.drawImage(img, 432, Math.floor((index % 9) / 3) * size[1], ...size, (canvas.width / 2) - (size[0] / 2), flyHeight, ...size);
        flyHeight = (canvas.height / 2) - (size[1] / 2); // Réinitialisation de la position de l'oiseau.

        // Affichage du texte d'instructions et du meilleur score.
        ctx.fillText(`Best score : ${bestScore}`, 85, 245);
        ctx.fillText('Click to play', 90, 535);
        ctx.font = "bold 30px courier";
    }
        // Si le jeu est en cours.
        if (gamePlaying) {
            // Dessin de l'oiseau en mouvement.
            ctx.drawImage(img2, 432, Math.floor((index % 9) / 3) * size[1], ...size, cTenth2, flyHeight2, ...size);
            flight2 += gravity; // Application de la gravité.
            flyHeight2 = Math.min(flyHeight2 + flight2, canvas.height - size[1]); // Mise à jour de la position verticale.

            document.addEventListener('keydown', (event) => {
                if (event.code === 'Enter') { 
                    if (!gamePlaying) {
                        gamePlaying = true;
                    }
                    flight2 = jump;
                }
            })

        } else {
            // Dessin de l'oiseau immobile avant le début du jeu.
            ctx.drawImage(img2, 432, Math.floor((index % 9) / 3) * size[1], ...size, (canvas.width / 2) - (size[0] / 2), flyHeight2, ...size);
            flyHeight2 = (canvas.height / 1.8) - (size[1] / 2); // Réinitialisation de la position de l'oiseau.
    
            // Affichage du texte d'instructions et du meilleur score.
            ctx.fillText(`Best score : ${bestScore}`, 85, 245);
            ctx.fillText('Click to play', 90, 535);
            ctx.fillText('Space for Yellow', 90, 565);
            ctx.fillText('Enter for Red', 90, 595);
            ctx.font = "bold 30px courier";
        }
    // Mise à jour des scores affichés sur la page HTML.
    document.getElementById('bestScore').innerHTML = `Best : ${bestScore}`;
    document.getElementById('currentScore').innerHTML = `Current : ${currentScore}`;

    // Demande une nouvelle frame de rendu.
    window.requestAnimationFrame(render);
}

// Initialisation du jeu.
setup();
img.onload = render; // Commence le rendu une fois l'image chargée.
document.addEventListener('click', () => gamePlaying = true);

