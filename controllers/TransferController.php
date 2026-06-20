<?php
class TransferController extends Controller {

    public function request(int $assetId): void {
        AuthHelper::requireLogin();

        $assetModel = new Asset();
        $companyId = (int)SessionHelper::get('company_id');
        $asset = $assetModel->findById($assetId, $companyId);

        if (!$asset || $asset['status'] !== 'available') {
            SessionHelper::set('flash_message', 'This asset is not available for transfer.');
            $this->redirect('/assets');
            return;
        }

        $transferModel = new TransferLog();
        $transferModel->create([
            'company_id' => $companyId,
            'asset_id' => $assetId,
            'requester_id' => (int)SessionHelper::get('user_id'),
            'from_department' => $asset['department'],
            'to_department' => SessionHelper::get('department'),
            'asset_value' => $asset['value']
        ]);

        $assetModel->updateStatus($assetId, 'pending_transfer', $companyId);

        // Notify leader by email
        $userModel = new User();
        $leader = $userModel->getLeaderByCompany($companyId);
        if ($leader) {
            MailHelper::send(
                $leader['email'],
                $leader['name'],
                'New Transfer Request — Asset Exchange Platform',
                "Hello {$leader['name']},\n\nA new transfer request has been submitted.\n\nAsset: {$asset['name']}\nFrom Department: {$asset['department']}\nTo Department: " . SessionHelper::get('department') . "\n\nLog in to review and approve or reject it.\n\nAsset Exchange Platform"
            );
        }

        SessionHelper::set('flash_message', 'Transfer request submitted');
        $this->redirect('/assets');
    }

    public function pending(): void {
        AuthHelper::requireRole('leader');

        $transferModel = new TransferLog();
        $companyId = (int)SessionHelper::get('company_id');
        $pendingRequests = $transferModel->getPendingByCompany($companyId);

        $this->view('transfers/pending', ['requests' => $pendingRequests]);
    }

    public function approve(int $id): void {
        AuthHelper::requireRole('leader');

        $companyId = (int)SessionHelper::get('company_id');
        $transferModel = new TransferLog();
        $request = $transferModel->findById($id, $companyId);

        if (!$request || $request['status'] !== 'pending') {
            SessionHelper::set('flash_message', 'Invalid request.');
            $this->redirect('/transfers/pending');
            return;
        }

        $assetModel = new Asset();
        $assetModel->updateStatus($request['asset_id'], 'assigned', $companyId);
        $transferModel->updateStatus($id, 'approved', (int)SessionHelper::get('user_id'));

        // Notify requester by email
        $userModel = new User();
        $requester = $userModel->findById($request['requester_id']);
        if ($requester) {
            MailHelper::send(
                $requester['email'],
                $requester['name'],
                'Transfer Request Approved — Asset Exchange Platform',
                "Hello {$requester['name']},\n\nYour transfer request for asset \"{$request['asset_name']}\" has been approved.\n\nAsset Exchange Platform"
            );
        }

        SessionHelper::set('flash_message', 'Transfer approved');
        $this->redirect('/transfers/pending');
    }

    public function reject(int $id): void {
        AuthHelper::requireRole('leader');

        $companyId = (int)SessionHelper::get('company_id');
        $transferModel = new TransferLog();
        $request = $transferModel->findById($id, $companyId);

        if (!$request || $request['status'] !== 'pending') {
            SessionHelper::set('flash_message', 'Invalid request.');
            $this->redirect('/transfers/pending');
            return;
        }

        $assetModel = new Asset();
        $assetModel->updateStatus($request['asset_id'], 'available', $companyId);
        $transferModel->updateStatus($id, 'rejected');

        // Notify requester by email
        $userModel = new User();
        $requester = $userModel->findById($request['requester_id']);
        if ($requester) {
            MailHelper::send(
                $requester['email'],
                $requester['name'],
                'Transfer Request Rejected — Asset Exchange Platform',
                "Hello {$requester['name']},\n\nYour transfer request for asset \"{$request['asset_name']}\" has been rejected. The asset is now available again.\n\nAsset Exchange Platform"
            );
        }

        SessionHelper::set('flash_message', 'Transfer rejected');
        $this->redirect('/transfers/pending');
    }

    public function history(): void {
        AuthHelper::requireRole('leader');

        $transferModel = new TransferLog();
        $companyId = (int)SessionHelper::get('company_id');

        $filters = [
            'from_department' => $_GET['department'] ?? '',
            'date_from' => $_GET['date_from'] ?? '',
            'date_to' => $_GET['date_to'] ?? ''
        ];

        $transfers = $transferModel->getApprovedByCompany($companyId, $filters);
        $totalSavings = $transferModel->getTotalSavings($companyId);

        $this->view('transfers/history', [
            'transfers' => $transfers,
            'totalSavings' => $totalSavings,
            'filters' => $filters
        ]);
    }
}