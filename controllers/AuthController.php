<?php
class AuthController extends Controller {

    public function showCreateCompany(): void {
        $this->view('auth/create_company');
    }

    public function createCompany(): void {
        $name = trim($_POST['company_name'] ?? '');
        $userName = trim($_POST['user_name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $department = trim($_POST['department'] ?? '');

        if (!$name || !$userName || !$email || !$password || !$department) {
            $this->view('auth/create_company', ['error' => 'All fields are required.']);
            return;
        }

        $companyModel = new Company();
        $company = $companyModel->create($name);

        $userModel = new User();
        $userId = $userModel->create([
            'company_id' => $company['id'],
            'name' => $userName,
            'email' => $email,
            'password' => $password,
            'role' => 'leader',
            'department' => $department
        ]);

        // Set session
        SessionHelper::set('user_id', $userId);
        SessionHelper::set('role', 'leader');
        SessionHelper::set('company_id', $company['id']);
        SessionHelper::set('department', $department);

        // Send welcome email (basic mail(), wrap in try to avoid crash if SMTP not configured)
        @mail($email, 'Welcome to Internal Asset Exchange',
            "Your company code is: {$company['company_code']}\nEmail: $email\nPassword: (the one you chose)");

        // Show company code popup via flash
        SessionHelper::set('flash_company_code', $company['company_code']);

        $this->redirect('/dashboard');
    }

    public function showLogin(): void {
        $this->view('auth/login');
    }

    public function login(): void {
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $companyCode = trim($_POST['company_code'] ?? '');

        $userModel = new User();
        $user = $userModel->findByEmailAndCompanyCode($email, $companyCode);

        if (!$user || !password_verify($password, $user['password'])) {
            $this->view('auth/login', ['error' => 'Invalid credentials.']);
            return;
        }

        SessionHelper::set('user_id', $user['id']);
        SessionHelper::set('role', $user['role']);
        SessionHelper::set('company_id', $user['company_id']);
        SessionHelper::set('department', $user['department']);
        SessionHelper::set('flash_message', 'Login successful');

        $this->redirect('/dashboard');
    }

    public function logout(): void {
        SessionHelper::destroy();
        $this->redirect('/login');
    }
}
