<?php
namespace Controller;

abstract class AdminBaseController extends Main
{
    protected $model;
    protected $entityName;
    protected $viewFolder;
    protected $itemsPerPage = 5;

    public function __construct()
    {
        parent::__construct();
        if (!self::$User || !in_array('Administrateur', explode(',', self::$User['profiles']))) {
            \Controller\Error::HttpError(403);
        }
        self::$View->is_admin_page = true;
    }

    public function index()
    {
        $page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
        $offset = ($page - 1) * $this->itemsPerPage;

        $items = $this->model->getPaginated(intval($this->itemsPerPage), intval($offset));
        $totalItems = $this->model->getTotal();
        $totalPages = max(1, ceil($totalItems / $this->itemsPerPage));

        self::$View->items = $items;
        self::$View->currentPage = $page;
        self::$View->totalPages = $totalPages;
        self::$View->title = "Gestion des {$this->entityName}s";
        self::$View->Display("admin/{$this->viewFolder}/index");
    }

    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = $this->validateData($_POST);
            if ($data) {
                $this->model->create($data);
                header("Location: /admin/{$this->viewFolder}");
                exit;
            }
        }
        self::$View->title = "Créer un {$this->entityName}";
        self::$View->Display("admin/{$this->viewFolder}/form");
    }

    public function edit($_params = [])
    {
        $id = intval($_params['id']);
        $item = $this->model->getById($id);
        if (!$item) {
            \Controller\Error::HttpError(404);
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = $this->validateData($_POST);
            if ($data) {
                $this->model->update($id, $data);
                header("Location: /admin/{$this->viewFolder}");
                exit;
            }
        }

        self::$View->item = $item;
        self::$View->title = "Modifier un {$this->entityName}";
        self::$View->Display("admin/{$this->viewFolder}/form");
    }

    public function delete($_params = [])
    {
        $id = intval($_params['id']);
        $isXhr = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

        if ($this->model->delete($id)) {
            if ($isXhr) {
                echo json_encode(['success' => true]);
            } else {
                header("Location: /admin/{$this->viewFolder}?success=1");
            }
        } else {
            if ($isXhr) {
                echo json_encode(['success' => false]);
            } else {
                header("Location: /admin/{$this->viewFolder}?error=1");
            }
        }
        exit;
    }

    abstract protected function validateData($data);
}
