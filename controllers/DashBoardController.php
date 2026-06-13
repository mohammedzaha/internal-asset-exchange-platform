<?php
class DashboardController extends Controller {
    public function index(): void {
        AuthHelper::requireLogin();
        $this->view('dashboard', [
            'role' => SessionHelper::get('role'),
            'department' => SessionHelper::get('department'),
            'companyCode' => SessionHelper::get('flash_company_code'),
            'flashMessage' => SessionHelper::get('flash_message')
        ]);
        // Clear one-time flashes after showing
        unset($_SESSION['flash_company_code'], $_SESSION['flash_message']);
    }
}