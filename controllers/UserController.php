<?php
class UserController extends Controller {

    public function showChangePassword(): void {
        AuthHelper::requireLogin();
        $this->view('change_password');
    }

    public function changePassword(): void {
        AuthHelper::requireLogin();

        $current = $_POST['current_password'] ?? '';
        $new = $_POST['new_password'] ?? '';
        $confirm = $_POST['confirm_password'] ?? '';

        $userModel = new User();
        $user = $userModel->findById((int)SessionHelper::get('user_id'));

        if (!password_verify($current, $user['password'])) {
            $this->view('change_password', ['error' => 'Current password is incorrect.']);
            return;
        }

        if ($new !== $confirm || strlen($new) < 6) {
            $this->view('change_password', ['error' => 'New password must be at least 6 characters and match confirmation.']);
            return;
        }

        $userModel->updatePassword($user['id'], $new);

        MailHelper::send(
            $user['email'],
            $user['name'],
            'Password Changed — Asset Exchange Platform',
            "Hello {$user['name']},\n\nYour password was successfully updated.\n\nIf you did not make this change, contact your Team Leader immediately.\n\nAsset Exchange Platform"
        );

        SessionHelper::set('flash_message', 'Password changed successfully!');
        $this->redirect('/dashboard');
    }
}