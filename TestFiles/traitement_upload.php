<?php
// Affichage des erreurs pour le développement
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "<h1>Gestionnaire d'upload de fichiers</h1>";

// Fonction pour afficher les informations d'un fichier
function afficherInfosFichier($fichier) {
    echo "<pre>";
    echo "Nom original : " . $fichier['name'] . "\n";
    echo "Type MIME : " . $fichier['type'] . "\n";
    echo "Taille : " . ($fichier['size'] / 1024) . " Ko\n";
    echo "Fichier temporaire : " . $fichier['tmp_name'] . "\n";
    echo "Code d'erreur : " . $fichier['error'] . "\n";
    echo "</pre>";
}

// Fonction pour redimensionner avec GD
function redimensionnerImageGD($cheminSource, $cheminDestination, $largeurMax = 800, $hauteurMax = 800) {
    list($largeur, $hauteur, $type) = getimagesize($cheminSource);
    
    // Calcul des nouvelles dimensions
    $ratio = min($largeurMax / $largeur, $hauteurMax / $hauteur);
    if ($ratio >= 1) {
        return copy($cheminSource, $cheminDestination); // Pas besoin de redimensionner
    }
    
    $nouvelleLargeur = round($largeur * $ratio);
    $nouvelleHauteur = round($hauteur * $ratio);
    
    $imageDestination = imagecreatetruecolor($nouvelleLargeur, $nouvelleHauteur);
    
    // Gestion de la transparence pour PNG
    if ($type === IMAGETYPE_PNG) {
        imagealphablending($imageDestination, false);
        imagesavealpha($imageDestination, true);
    }
    
    // Création de l'image source selon le type
    switch ($type) {
        case IMAGETYPE_JPEG:
            $imageSource = imagecreatefromjpeg($cheminSource);
            break;
        case IMAGETYPE_PNG:
            $imageSource = imagecreatefrompng($cheminSource);
            break;
        case IMAGETYPE_GIF:
            $imageSource = imagecreatefromgif($cheminSource);
            break;
        default:
            return false;
    }
    
    // Redimensionnement
    imagecopyresampled(
        $imageDestination, $imageSource,
        0, 0, 0, 0,
        $nouvelleLargeur, $nouvelleHauteur,
        $largeur, $hauteur
    );
    
    // Sauvegarde selon le type
    switch ($type) {
        case IMAGETYPE_JPEG:
            imagejpeg($imageDestination, $cheminDestination, 85);
            break;
        case IMAGETYPE_PNG:
            imagepng($imageDestination, $cheminDestination, 8);
            break;
        case IMAGETYPE_GIF:
            imagegif($imageDestination, $cheminDestination);
            break;
    }
    
    imagedestroy($imageSource);
    imagedestroy($imageDestination);
    return true;
}

// Fonction pour redimensionner avec ImageMagick
function redimensionnerImageMagick($cheminSource, $cheminDestination, $largeurMax = 800, $hauteurMax = 800) {
    try {
        $image = new Imagick($cheminSource);
        
        // Préservation du profil ICC et des métadonnées
        $image->stripImage();
        $image->setImageCompressionQuality(85);
        
        // Redimensionnement en conservant les proportions
        $image->scaleImage($largeurMax, $hauteurMax, true);
        
        // Optimisation pour le web
        $image->setInterlaceScheme(Imagick::INTERLACE_PLANE);
        
        // Sauvegarde
        $image->writeImage($cheminDestination);
        $image->destroy();
        return true;
    } catch (Exception $e) {
        error_log("Erreur ImageMagick : " . $e->getMessage());
        return false;
    }
}

// Fonction pour vérifier si c'est une image
function estImage($fichier) {
    $types_autorises = ['image/jpeg', 'image/png', 'image/gif'];
    return in_array($fichier['type'], $types_autorises);
}

// Traitement d'un seul fichier
if (isset($_FILES['fichier'])) {
    echo "<h2>Traitement d'un fichier unique</h2>";
    
    // Vérification s'il n'y a pas d'erreur
    if ($_FILES['fichier']['error'] === UPLOAD_ERR_OK) {
        afficherInfosFichier($_FILES['fichier']);
        
        if (estImage($_FILES['fichier'])) {
            $dossierDestination = 'uploads/';
            $dossierRedimensionne = 'uploads/redimensionne/';
            
            // Création des dossiers nécessaires
            foreach ([$dossierDestination, $dossierRedimensionne] as $dossier) {
                if (!is_dir($dossier)) {
                    mkdir($dossier, 0777, true);
                }
            }
            
            $nomFichierFinal = $dossierDestination . basename($_FILES['fichier']['name']);
            $nomFichierRedimensionneGD = $dossierRedimensionne . 'gd_' . basename($_FILES['fichier']['name']);
            $nomFichierRedimensionneMagick = $dossierRedimensionne . 'imagick_' . basename($_FILES['fichier']['name']);
            
            if (move_uploaded_file($_FILES['fichier']['tmp_name'], $nomFichierFinal)) {
                echo "✅ Image uploadée avec succès dans : " . $nomFichierFinal . "<br>";
                
                // Redimensionnement avec GD
                if (redimensionnerImageGD($nomFichierFinal, $nomFichierRedimensionneGD)) {
                    echo "✅ Image redimensionnée avec GD : " . $nomFichierRedimensionneGD . "<br>";
                }
                
                // Redimensionnement avec ImageMagick si disponible
                if (class_exists('Imagick')) {
                    if (redimensionnerImageMagick($nomFichierFinal, $nomFichierRedimensionneMagick)) {
                        echo "✅ Image redimensionnée avec ImageMagick : " . $nomFichierRedimensionneMagick . "<br>";
                    }
                } else {
                    echo "ℹ️ ImageMagick n'est pas disponible sur ce serveur<br>";
                }
            } else {
                echo "❌ Erreur lors de l'upload de l'image";
            }
        } else {
            echo "❌ Le fichier n'est pas une image valide";
        }
    } else {
        echo "❌ Erreur code : " . $_FILES['fichier']['error'];
    }
}

// Traitement des fichiers multiples
if (isset($_FILES['fichiers'])) {
    echo "<h2>Traitement des fichiers multiples</h2>";
    
    // Parcours de chaque fichier
    foreach ($_FILES['fichiers']['name'] as $index => $nom) {
        echo "<h3>Fichier #" . ($index + 1) . "</h3>";
        
        // Reconstruction du tableau pour chaque fichier
        $fichier = array(
            'name' => $_FILES['fichiers']['name'][$index],
            'type' => $_FILES['fichiers']['type'][$index],
            'tmp_name' => $_FILES['fichiers']['tmp_name'][$index],
            'error' => $_FILES['fichiers']['error'][$index],
            'size' => $_FILES['fichiers']['size'][$index]
        );
        
        if ($fichier['error'] === UPLOAD_ERR_OK) {
            afficherInfosFichier($fichier);
            
            if (estImage($fichier)) {
                $dossierDestination = 'uploads/';
                $dossierRedimensionne = 'uploads/redimensionne/';
                
                // Création des dossiers nécessaires
                foreach ([$dossierDestination, $dossierRedimensionne] as $dossier) {
                    if (!is_dir($dossier)) {
                        mkdir($dossier, 0777, true);
                    }
                }
                
                $nomFichierFinal = $dossierDestination . basename($fichier['name']);
                $nomFichierRedimensionneGD = $dossierRedimensionne . 'gd_' . basename($fichier['name']);
                $nomFichierRedimensionneMagick = $dossierRedimensionne . 'imagick_' . basename($fichier['name']);
                
                if (move_uploaded_file($fichier['tmp_name'], $nomFichierFinal)) {
                    echo "✅ Image uploadée avec succès dans : " . $nomFichierFinal . "<br>";
                    
                    // Redimensionnement avec GD
                    if (redimensionnerImageGD($nomFichierFinal, $nomFichierRedimensionneGD)) {
                        echo "✅ Image redimensionnée avec GD : " . $nomFichierRedimensionneGD . "<br>";
                    }
                    
                    // Redimensionnement avec ImageMagick si disponible
                    if (class_exists('Imagick')) {
                        if (redimensionnerImageMagick($nomFichierFinal, $nomFichierRedimensionneMagick)) {
                            echo "✅ Image redimensionnée avec ImageMagick : " . $nomFichierRedimensionneMagick . "<br>";
                        }
                    } else {
                        echo "ℹ️ ImageMagick n'est pas disponible sur ce serveur<br>";
                    }
                } else {
                    echo "❌ Erreur lors de l'upload de l'image";
                }
            } else {
                echo "❌ Le fichier n'est pas une image valide";
            }
        } else {
            echo "❌ Erreur code : " . $fichier['error'];
        }
        echo "<hr>";
    }
}

// Si aucun fichier n'a été envoyé
if (!isset($_FILES['fichier']) && !isset($_FILES['fichiers'])) {
    echo "<p>Aucun fichier n'a été envoyé.</p>";
}
?>

<p><a href="upload_form.html">Retour au formulaire</a></p> 