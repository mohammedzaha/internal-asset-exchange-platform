<?php
class MemberController extends Controller {

    public function create(): void {
        AuthHelper::requireRole('leader');
        $this->view('members/create');
    }

    public function store(): void {
        AuthHelper::requireRole('leader');

        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $department = trim($_POST['department'] ?? '');
        $role = $_POST['role'] ?? 'member';

        if (!$name || !$email || !$department) {
            $this->view('members/create', ['error' => 'All fields are required.']);
            return;
        }

        $userModel = new User();

        $existingUser = $userModel->findByEmailAndCompany($email, (int)SessionHelper::get('company_id'));
        if ($existingUser) {
            $this->view('members/create', ['error' => 'This email is already registered in your company.']);
            return;
        }
        $result = $userModel->createMember([
            'company_id' => (int)SessionHelper::get('company_id'),
            'name' => $name,
            'email' => $email,
            'department' => $department,
            'role' => $role
        ]);

        $companyModel = new Company();
        $company = $companyModel->findById((int)SessionHelper::get('company_id'));

        MailHelper::send($email, $name, 'Welcome to Asset Exchange Platform',
            "Hello $name,\n\nYou got added to the workspace of the company: {$company['name']}.\n\n Here are the login info: \nEmail: $email\n Password: {$result['temp_password']}.\nCompany Code: {$company['company_code']}\nPlease log in and update your password.\n\nAsset Exchange Platform");

        SessionHelper::set('flash_member_email', $email);
        SessionHelper::set('flash_member_temp_password', $result['temp_password']);

        $this->redirect('/dashboard');
    }
}
