<?php
class AssetController extends Controller {

    public function index(): void {
        AuthHelper::requireLogin();
        $assetModel = new Asset();
        $filters = [
            'category' => $_GET['category'] ?? '',
            'department' => $_GET['department'] ?? '',
            'search' => $_GET['search'] ?? ''
        ];
        $assets = $assetModel->getAvailableByCompany((int)SessionHelper::get('company_id'), $filters);
        $this->view('assets/index', ['assets' => $assets, 'filters' => $filters]);
    }

    public function create(): void {
        AuthHelper::requireLogin();
        $this->view('assets/create');
    }

    public function store(): void {
        AuthHelper::requireLogin();

        $name = trim($_POST['name'] ?? '');
        $category = trim($_POST['category'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $value = $_POST['value'] ?? 0;
        $condition = trim($_POST['condition'] ?? '');
        $department = SessionHelper::get('department');

        if (!$name || !$category || !$value) {
            $this->view('assets/create', ['error' => 'Please fill all required fields.']);
            return;
        }

        $imagePath = null;
        if (!empty($_FILES['image']['name'])) {
            $allowed = ['jpg','jpeg','png','gif'];
            $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
            if (in_array($ext, $allowed) && $_FILES['image']['error'] === 0) {
                $filename = uniqid('asset_') . '.' . $ext;
                $destination = __DIR__ . '/../public/uploads/assets/' . $filename;
                if (move_uploaded_file($_FILES['image']['tmp_name'], $destination)) {
                    $imagePath = 'uploads/assets/' . $filename;
                }
            }
        }

        $assetModel = new Asset();
        $assetModel->create([
            'company_id' => (int)SessionHelper::get('company_id'),
            'name' => $name,
            'category' => $category,
            'description' => $description,
            'value' => $value,
            'condition' => $condition,
            'image_path' => $imagePath,
            'added_by' => (int)SessionHelper::get('user_id'),
            'department' => $department
        ]);

        SessionHelper::set('flash_message', 'Asset added successfully!');
        $this->redirect('/assets');
    }

    public function show(int $id): void {
        AuthHelper::requireLogin();
        $assetModel = new Asset();
        $asset = $assetModel->findById($id, (int)SessionHelper::get('company_id'));

        if (!$asset) {
            http_response_code(404);
            echo "Asset not found";
            return;
        }

        $canUndo = ($asset['status'] === 'available' && $asset['department'] === SessionHelper::get('department'));
        $this->view('assets/show', ['asset' => $asset, 'canUndo' => $canUndo]);
    }

    public function destroy(int $id): void {
        AuthHelper::requireLogin();
        $assetModel = new Asset();
        $asset = $assetModel->findById($id, (int)SessionHelper::get('company_id'));

        if (!$asset || $asset['status'] !== 'available' || $asset['department'] !== SessionHelper::get('department')) {
            SessionHelper::set('flash_message', 'You cannot delete this asset.');
            $this->redirect('/assets');
            return;
        }

        $assetModel->delete($id, (int)SessionHelper::get('company_id'));
        SessionHelper::set('flash_message', 'Asset removed');
        $this->redirect('/assets');
    }
}

