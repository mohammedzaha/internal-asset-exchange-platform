<?php
class DashboardController extends Controller {
    public function index(): void {
        AuthHelper::requireLogin();

        $companyId = (int)SessionHelper::get('company_id');
        $role = SessionHelper::get('role');

        $assetModel = new Asset();
        $transferModel = new TransferLog();

        $stats = [
            'availableCount' => $assetModel->countAvailableByCompany($companyId),
            'transferredCount' => $assetModel->countTransferredByCompany($companyId),
            'totalSavings' => $transferModel->getTotalSavings($companyId),
            'topAssets' => $assetModel->getTopValuedAvailable($companyId, 3),
            'pendingCount' => $role === 'leader' ? $transferModel->countPendingByCompany($companyId) : 0
        ];

        $this->view('dashboard', [
            'role' => $role,
            'department' => SessionHelper::get('department'),
            'companyCode' => SessionHelper::get('flash_company_code'),
            'flashMessage' => SessionHelper::get('flash_message'),
            'memberEmail' => SessionHelper::get('flash_member_email'),
            'memberTempPassword' => SessionHelper::get('flash_member_temp_password'),
            'stats' => $stats
        ]);

        unset($_SESSION['flash_company_code'], $_SESSION['flash_message'],
              $_SESSION['flash_member_email'], $_SESSION['flash_member_temp_password']);
    }
}



