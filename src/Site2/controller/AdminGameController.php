<?php
namespace Controller;

class AdminGameController extends AdminBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->model = new \Model\Game();
        $this->entityName = 'jeu';
        $this->viewFolder = 'games';
    }

    protected function validateData($data)
    {
        $errors = [];
        if (empty($data['title'])) {
            $errors[] = "Le titre du jeu est requis.";
        }
        if (empty($data['description'])) {
            $errors[] = "La description du jeu est requise.";
        }
        if (!isset($data['price']) || !is_numeric($data['price'])) {
            $errors[] = "Le prix du jeu doit être un nombre valide.";
        }
        if (empty($data['editor_id'])) {
            $errors[] = "L'éditeur du jeu est requis.";
        }
        
        if (!empty($errors)) {
            self::$View->errors = $errors;
            return false;
        }
        
        $validatedData = [
            'title' => $data['title'],
            'description' => $data['description'],
            'price' => $data['price'],
            'editor_id' => $data['editor_id'],
            'release_date' => $data['release_date'] ?? date('Y-m-d'),
            'is_online' => isset($data['is_online']) ? 1 : 0,
            'availability_date' => $data['availability_date'] ?? date('Y-m-d'),
            'is_on_promotion' => isset($data['is_on_promotion']) ? 1 : 0,
            'promotion_price' => $data['promotion_price'] ?? null
        ];

        // Gestion de l'upload d'image
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $uploadDir = DIR_BASE . 'public/images/';
            $fileExtension = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];

            if (in_array($fileExtension, $allowedExtensions)) {
                $newFileName = uniqid() . '.' . $fileExtension;
                $uploadFile = $uploadDir . $newFileName;

                if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadFile)) {
                    $validatedData['image_url'] = '/images/' . $newFileName;
                } else {
                    self::$View->errors[] = "Erreur lors du téléchargement de l'image.";
                    return false;
                }
            } else {
                self::$View->errors[] = "Le type de fichier n'est pas autorisé. Utilisez JPG, JPEG, PNG ou GIF.";
                return false;
            }
        }

        return $validatedData;
    }

    public function create()
    {
        $editorModel = new \Model\Editor();
        self::$View->editors = $editorModel->getAll();

        parent::create();
    }

    public function edit($_params = [])
    {
        $editorModel = new \Model\Editor();
        self::$View->editors = $editorModel->getAll();

        parent::edit($_params);
    }
}
