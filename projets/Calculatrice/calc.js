const display = document.getElementById('display');
let currentInput = '';
let operator = null;

// Sélectionne tous les boutons à l'intérieur de l'élément avec la classe "buttons"
document.querySelectorAll('.buttons button').forEach(button => {

    // Ajoute un écouteur d'évenement 'click' à chaque bouton
    button.addEventListener('click', function() {
        
        // Récupère le texte du bouton sur lequel l'utilisateur a cliqué
        const value= this.textContent;

        // Vérifie si la valeur cliquée est un nombre ou un point (pour les décimales)
        if (!isNaN(value) || value ==='.') {
            handleNumber(value); // Appelle la fonction pour gérer l'entrée des nombres
        }
        // Si la touche 'C' est cliquée, on efface tout (clear)
        else if (value === 'C') {
            clearDisplay(); // Appelle la fonction pour effacer l'affichage
        }
        // Si la touche '=' est cliquée, on calcule le résultat
        else if (value === '=') {
            calculateResult(); // Appelle la fonction pour effectuer le calcul
        }
        // Pour tout autre bouton (opérateur : +, -, *, /), one le gère comme un opérateur
        else {
            handleOperator(value); // Appelle la fonction pour gérer l'opérateur
        }
    });
});

function handleNumber(num) {
    currentInput += num;// Concaténer le numéro à l'expression actuelle
    display.textContent = currentInput;
}
function clearDisplay() {
    currentInput ='';
    operator = null;
    display.textContent = '0';
}

function handleOperator(op) {
    currentInput += ' '+ op + ' '; // Ajouter l'opérateur avec des espaces pour la lisibilité
    display.textContent = currentInput;
}

function calculateResult() {
    try {
        //Utiliser eval pour évaluer l'expression complète
        currentInput = eval(currentInput).toString(); // Convertir le résultat en chaîne
        display.textContent = currentInput;
    }   catch (error) {
        display.textContent = "Erreur"; // Gérer les erreurs (par exemple, si l'expression est invalide)
        currentInput ='';
    }
}