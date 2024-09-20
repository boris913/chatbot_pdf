<?php
require('fpdf/fpdf.php');

// Fonction pour convertir UTF-8 en ISO-8859-1
function utf8_to_iso8859($text) {
    return iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $text);
}

// Récupérer les données envoyées par le chatbot
$data = json_decode(file_get_contents('php://input'), true);

if (!$data) {
    // Gérer le cas où il n'y a pas de données envoyées
    http_response_code(400);
    echo json_encode(["error" => "Aucune donnée reçue"]);
    exit();
}

$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);

// Titre du document
$pdf->Cell(0, 10, utf8_to_iso8859('Formulaire de création de logo'), 0, 1, 'C');
$pdf->Ln(10);

// Questions associées aux réponses
$questions = [
    utf8_to_iso8859("Nom de l'entreprise / du client : "),
    utf8_to_iso8859("Adresse mail : "),
    utf8_to_iso8859("Numéro de téléphone : "),
    utf8_to_iso8859("Secteur d'activité : "),
    utf8_to_iso8859("Messages ou valeurs clés : "),
    utf8_to_iso8859("Mots-clés ou adjectifs : "),
    utf8_to_iso8859("Style de logo préféré : "),
    utf8_to_iso8859("Couleurs préférées : "),
    utf8_to_iso8859("Couleurs à éviter : "),
    utf8_to_iso8859("Éléments graphiques ou symboles spécifiques : "),
    utf8_to_iso8859("Signification des éléments graphiques : "),
    utf8_to_iso8859("Exemples de logos appréciés : "),
    utf8_to_iso8859("Typographie préférée : "),
    utf8_to_iso8859("Public cible : "),
    utf8_to_iso8859("Éléments spécifiques pour le public cible : "),
    utf8_to_iso8859("Utilisation prévue : "),
    utf8_to_iso8859("Suggestions d'éléments visuels : "),
    utf8_to_iso8859("Autres informations ou exigences : "),
    utf8_to_iso8859("Délai : "),
    utf8_to_iso8859("Commentaire : ")
];

// Générer le PDF avec les réponses
foreach ($data as $index => $response) {
    $pdf->SetFont('Arial', '', 12);
    $question = isset($questions[$index]) ? $questions[$index] : utf8_to_iso8859('Question inconnue : ');
    $pdf->Cell(0, 10, $question . utf8_to_iso8859($response), 0, 1);
}

// Envoyer le PDF au client
$pdf->Output('I', 'reponses.pdf');
