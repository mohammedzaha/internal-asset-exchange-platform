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

    public function generateDescription(): void {
        AuthHelper::requireLogin();
        header('Content-Type: application/json');

        $name = trim($_POST['name'] ?? '');
        $category = trim($_POST['category'] ?? '');

        if (!$name) {
            echo json_encode(['error' => 'Asset name is required.']);
            return;
        }

        $config = require __DIR__ . '/../config/ai.php';
        $apiKey = $config['gemini_api_key'];

        $prompt = "Write a short, professional 2-sentence description for an office/company asset listing. "
                . "Asset name: \"$name\". Category: \"$category\". "
                . "Keep it factual and concise, no marketing language, no quotes around the output.";

        $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key=$apiKey";

        $payload = json_encode([
            'contents' => [
                ['parts' => [['text' => $prompt]]]
            ]
        ]);

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_TIMEOUT, 15);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode !== 200 || !$response) {
            echo json_encode(['error' => 'Could not generate description. Try again.']);
            return;
        }

        $data = json_decode($response, true);
        $text = $data['candidates'][0]['content']['parts'][0]['text'] ?? null;

        if (!$text) {
            echo json_encode(['error' => 'Could not generate description. Try again.']);
            return;
        }

        echo json_encode(['description' => trim($text)]);
    }


public function interpretQuery(): void {
    AuthHelper::requireLogin();
    header('Content-Type: application/json');

    $query = trim($_POST['query'] ?? '');
    if (!$query) {
        echo json_encode(['error' => 'Please enter a search.']);
        return;
    }

    $config = require __DIR__ . '/../config/ai.php';
    $apiKey = $config['gemini_api_key'];

    $prompt = "Extract search filters from this user query about office assets. "
            . "Return ONLY valid JSON with these exact keys: search (item name keyword or empty string), "
            . "category (or empty string), department (or empty string). "
            . "No explanation, no markdown, just the raw JSON object.\n\n"
            . "Query: \"$query\"";

    $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key=$apiKey";

    $payload = json_encode([
        'contents' => [['parts' => [['text' => $prompt]]]]
    ]);

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    curl_setopt($ch, CURLOPT_TIMEOUT, 15);
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode !== 200 || !$response) {
        echo json_encode(['error' => 'Could not process search. Try again.']);
        return;
    }

    $data = json_decode($response, true);
    $text = $data['candidates'][0]['content']['parts'][0]['text'] ?? null;

    if (!$text) {
        echo json_encode(['error' => 'Could not process search. Try again.']);
        return;
    }

    // Clean up: AI sometimes wraps JSON in ```json ... ``` markdown
    $text = trim(str_replace(['```json', '```'], '', $text));
    $filters = json_decode($text, true);

    if (!$filters) {
        echo json_encode(['error' => 'Could not understand that search.']);
        return;
    }

    echo json_encode([
        'search' => $filters['search'] ?? '',
        'category' => $filters['category'] ?? '',
        'department' => $filters['department'] ?? ''
    ]);
}
}

