<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\DocumentController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/password/forgot', [AuthController::class, 'sendPasswordResetLink']);
Route::post('/password/reset', [AuthController::class, 'resetPassword']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);
    Route::get('/profile', [\App\Http\Controllers\Admin\ProfileController::class, 'show']);
    Route::put('/profile', [\App\Http\Controllers\Admin\ProfileController::class, 'update']);
    Route::post('/profile', [\App\Http\Controllers\Admin\ProfileController::class, 'update']);
    Route::get('/applications', [ApplicationController::class, 'index']);
    Route::post('/applications', [ApplicationController::class, 'store']);
    Route::post('/applications/{id}/i90', [ApplicationController::class, 'saveI90']);
    Route::post('/applications/{id}/g1145', [ApplicationController::class, 'saveG1145']);
    Route::post('/applications/{id}/i130', [ApplicationController::class, 'saveI130']);
    Route::post('/applications/{id}/i130a', [ApplicationController::class, 'saveI130A']);
    Route::post('/applications/{id}/i485', [ApplicationController::class, 'saveI485']);
    Route::post('/applications/{id}/i751', [ApplicationController::class, 'saveI751']);
    Route::post('/applications/{id}/i765', [ApplicationController::class, 'saveI765']);
    Route::post('/applications/{id}/i765ws', [ApplicationController::class, 'saveI765WS']);
    Route::post('/applications/{id}/i821d', [ApplicationController::class, 'saveI821D']);
    Route::post('/applications/{id}/i864', [ApplicationController::class, 'saveI864']);
    Route::post('/applications/{id}/n400', [ApplicationController::class, 'saveN400']);
    Route::post('/applications/{id}/submit', [ApplicationController::class, 'submit']);
    Route::put('/applications/{id}/save-progress', [ApplicationController::class, 'saveFormData']);
    Route::post('/applications/{id}/invites', [\App\Http\Controllers\ApplicationInviteController::class, 'store']);
    Route::post('/applications/invites/{token}/accept', [\App\Http\Controllers\ApplicationInviteController::class, 'accept']);
    Route::get('/documents', [DocumentController::class, 'index']);
    Route::post('/documents/upload', [DocumentController::class, 'store']);
    Route::post('/documents/{id}/upload', [DocumentController::class, 'upload']);
    
    Route::get('/checklists', [\App\Http\Controllers\ChecklistController::class, 'index']);
    
    Route::get('/messages', [\App\Http\Controllers\ChatController::class, 'index']);
    Route::post('/messages', [\App\Http\Controllers\ChatController::class, 'store']);
    Route::get('/notifications', [\App\Http\Controllers\Admin\NotificationController::class, 'index']);
    Route::put('/notifications/mark-read', [\App\Http\Controllers\Admin\NotificationController::class, 'markAsRead']);
    Route::delete('/notifications/clear', [\App\Http\Controllers\Admin\NotificationController::class, 'clearAll']);

    Route::get('/manager/assigned-cases', [\App\Http\Controllers\ManagerController::class, 'assignedCases']);
    Route::get('/manager/unassigned-cases', [\App\Http\Controllers\ManagerController::class, 'unassignedCases']);
    Route::post('/manager/applications/{id}/request-assignment', [\App\Http\Controllers\ManagerController::class, 'requestAssignment']);
    Route::post('/manager/applications/{id}/invite-beneficiary', [\App\Http\Controllers\ManagerController::class, 'inviteBeneficiary']);
    Route::put('/manager/applications/{id}', [\App\Http\Controllers\ManagerController::class, 'updateApplication']);
    Route::get('/manager/applications/{applicationId}/messages', [\App\Http\Controllers\Manager\CaseWorkspaceController::class, 'messages']);
    Route::post('/manager/applications/{applicationId}/messages', [\App\Http\Controllers\Manager\CaseWorkspaceController::class, 'storeMessage']);
    Route::get('/manager/applications/{applicationId}/documents', [\App\Http\Controllers\Manager\CaseWorkspaceController::class, 'documents']);
    Route::post('/manager/applications/{applicationId}/documents/requests', [\App\Http\Controllers\Manager\CaseWorkspaceController::class, 'requestDocuments']);
    Route::put('/manager/applications/{applicationId}/checklist/{categoryId}/{documentName}', [\App\Http\Controllers\Manager\CaseWorkspaceController::class, 'updateChecklistStatus']);
    
    // Guide Engine Backend
    Route::get('/admin/guide-engine/stats', [\App\Http\Controllers\Admin\GuideEngineController::class, 'getStats']);
    Route::post('/admin/guide-engine/analyze', [\App\Http\Controllers\Admin\GuideEngineController::class, 'analyze']);
    Route::post('/manager/applications/{applicationId}/escalate', [\App\Http\Controllers\Manager\CaseWorkspaceController::class, 'escalate']);
    Route::get('/services', [\App\Http\Controllers\Admin\ServiceController::class, 'index']);
    Route::post('/payment/verify', [\App\Http\Controllers\PaymentController::class, 'verifyPayment']);
    Route::post('/support/tickets', [\App\Http\Controllers\TicketController::class, 'store']);
    
    // Public Guide Engine endpoint for fetching forms
    Route::get('/guide-engine/forms/{slug}', [\App\Http\Controllers\PublicFormEngineController::class, 'getFormBySlug']);
});

Route::post('/auth/send-otp', [AuthController::class, 'sendOtp']);
Route::post('/auth/verify-otp', [AuthController::class, 'verifyOtp']);
Route::get('/beneficiary-invites/{token}', [\App\Http\Controllers\ApplicationController::class, 'getBeneficiaryInvite']);
Route::post('/beneficiary-invites/{token}', [\App\Http\Controllers\ApplicationController::class, 'saveBeneficiaryInvite']);
Route::get('/applications/invites/{token}', [\App\Http\Controllers\ApplicationInviteController::class, 'show']);
Route::post('/newsletter/subscribe', [\App\Http\Controllers\NewsletterController::class, 'subscribe']);

Route::get('/public/services', [\App\Http\Controllers\PublicServiceController::class, 'index']);
Route::get('/public/signup-pathways', [\App\Http\Controllers\PublicSignupController::class, 'getPathways']);
Route::post('/public/signup-pricing', [\App\Http\Controllers\PublicSignupController::class, 'getPricing']);

Route::get('/stats/signups', function () {
    return response()->json([
        'count' => \App\Models\User::where('role', 'user')->count()
    ]);
});

Route::middleware(['auth:sanctum', 'admin'])->prefix('admin')->group(function () {
    Route::get('/clients', [\App\Http\Controllers\AdminController::class, 'getClients']);
    Route::get('/applications', [\App\Http\Controllers\AdminController::class, 'getApplications']);
    Route::put('/applications/{id}', [\App\Http\Controllers\AdminController::class, 'updateApplication']);
    Route::put('/documents/{id}', [\App\Http\Controllers\AdminController::class, 'updateDocumentStatus']);

    // User & Role Management
    Route::get('/users', [\App\Http\Controllers\Admin\UserController::class, 'index']);
    Route::get('/users/{id}', [\App\Http\Controllers\Admin\UserController::class, 'show']);
    Route::post('/users', [\App\Http\Controllers\Admin\UserController::class, 'store']);
    Route::put('/users/{id}', [\App\Http\Controllers\Admin\UserController::class, 'update']);
    Route::put('/users/{id}/role', [\App\Http\Controllers\Admin\UserController::class, 'updateRole']);
    Route::put('/users/{id}/status', [\App\Http\Controllers\Admin\UserController::class, 'updateStatus']);
    Route::delete('/users/{id}', [\App\Http\Controllers\Admin\UserController::class, 'destroy']);

    // Case & Assignment Management
    Route::get('/cases', [\App\Http\Controllers\Admin\CaseController::class, 'index']);
    Route::put('/cases/{id}/assign', [\App\Http\Controllers\Admin\CaseController::class, 'assignManager']);
    Route::get('/assignment-requests', [\App\Http\Controllers\Admin\CaseController::class, 'getRequests']);
    Route::get('/assignment-requests/{id}', [\App\Http\Controllers\Admin\CaseController::class, 'showRequest']);
    Route::put('/assignment-requests/{id}', [\App\Http\Controllers\Admin\CaseController::class, 'updateRequest']);
    Route::patch('/cases/{id}/questionnaire', [\App\Http\Controllers\Admin\CaseController::class, 'updateQuestionnaire']);

    // Revenue & Services
    Route::post('/services', [\App\Http\Controllers\Admin\ServiceController::class, 'store']);
    Route::put('/services/{id}', [\App\Http\Controllers\Admin\ServiceController::class, 'update']);
    Route::delete('/services/{id}', [\App\Http\Controllers\Admin\ServiceController::class, 'destroy']);

    Route::get('/revenue', [\App\Http\Controllers\Admin\RevenueController::class, 'getRevenueData']);

    // Tickets, Audit, Search
    Route::get('/tickets', [\App\Http\Controllers\Admin\SupportController::class, 'index']);
    Route::put('/tickets/{id}/assign', [\App\Http\Controllers\Admin\SupportController::class, 'assignManager']);
    Route::put('/tickets/{id}/status', [\App\Http\Controllers\Admin\SupportController::class, 'updateStatus']);
    Route::put('/tickets/{id}/update-package', [\App\Http\Controllers\Admin\SupportController::class, 'updatePackage']);
    Route::post('/tickets/{id}/reply', [\App\Http\Controllers\Admin\SupportController::class, 'reply']);

    Route::get('/audit-logs', [\App\Http\Controllers\Admin\AuditController::class, 'index']);
    Route::get('/search', [\App\Http\Controllers\Admin\SearchController::class, 'search']);

    // Notifications
    Route::get('/notifications', [\App\Http\Controllers\Admin\NotificationController::class, 'index']);
    Route::put('/notifications/mark-read', [\App\Http\Controllers\Admin\NotificationController::class, 'markAsRead']);
    Route::delete('/notifications/clear', [\App\Http\Controllers\Admin\NotificationController::class, 'clearAll']);

    // Profile
    Route::get('/profile', [\App\Http\Controllers\Admin\ProfileController::class, 'show']);
    Route::put('/profile', [\App\Http\Controllers\Admin\ProfileController::class, 'update']);
    Route::post('/profile', [\App\Http\Controllers\Admin\ProfileController::class, 'update']);

    // Analytics & Dashboard
    Route::get('/dashboard/stats', [\App\Http\Controllers\Admin\AnalyticsController::class, 'getDashboardStats']);
    Route::get('/dashboard/activity', [\App\Http\Controllers\Admin\AnalyticsController::class, 'getRecentActivity']);
    Route::get('/analytics/data', [\App\Http\Controllers\Admin\AnalyticsController::class, 'getAnalyticsData']);
    Route::get('/control-center/data', [\App\Http\Controllers\Admin\AnalyticsController::class, 'getControlCenterData']);
    Route::get('/staff-performance/data', [\App\Http\Controllers\Admin\AnalyticsController::class, 'getStaffPerformanceData']);

    // Checklists
    Route::get('/checklists', [\App\Http\Controllers\Admin\ChecklistController::class, 'index']);
    Route::post('/checklists', [\App\Http\Controllers\Admin\ChecklistController::class, 'store']);
    Route::get('/checklists/{id}', [\App\Http\Controllers\Admin\ChecklistController::class, 'show']);
    Route::put('/checklists/{id}', [\App\Http\Controllers\Admin\ChecklistController::class, 'update']);
    Route::delete('/checklists/{id}', [\App\Http\Controllers\Admin\ChecklistController::class, 'destroy']);

    // Signup Setup
    Route::get('/signup-goals', [\App\Http\Controllers\Admin\SignupSetupController::class, 'getGoals']);
    Route::post('/signup-goals', [\App\Http\Controllers\Admin\SignupSetupController::class, 'storeGoal']);
    Route::put('/signup-goals/{id}', [\App\Http\Controllers\Admin\SignupSetupController::class, 'updateGoal']);
    Route::delete('/signup-goals/{id}', [\App\Http\Controllers\Admin\SignupSetupController::class, 'destroyGoal']);

    Route::get('/signup-goals/{goalId}/questions', [\App\Http\Controllers\Admin\SignupSetupController::class, 'getQuestions']);
    Route::post('/signup-goals/{goalId}/questions', [\App\Http\Controllers\Admin\SignupSetupController::class, 'storeQuestion']);
    Route::put('/signup-questions/{id}', [\App\Http\Controllers\Admin\SignupSetupController::class, 'updateQuestion']);
    Route::delete('/signup-questions/{id}', [\App\Http\Controllers\Admin\SignupSetupController::class, 'destroyQuestion']);

    // Guide Engine Form Builder
    Route::get('/guide-engine/forms', [\App\Http\Controllers\Admin\FormBuilderController::class, 'getForms']);
    Route::post('/guide-engine/forms', [\App\Http\Controllers\Admin\FormBuilderController::class, 'createForm']);
    Route::post('/guide-engine/forms/{id}/connect', [\App\Http\Controllers\Admin\FormBuilderController::class, 'connectForm']);
    Route::post('/guide-engine/forms/{id}/unlink', [\App\Http\Controllers\Admin\FormBuilderController::class, 'unlinkForm']);
    Route::post('/guide-engine/forms/{id}/toggle-required', [\App\Http\Controllers\Admin\FormBuilderController::class, 'toggleRequired']);
    Route::get('/guide-engine/forms/{id}', [\App\Http\Controllers\Admin\FormBuilderController::class, 'getForm']);
    Route::post('/guide-engine/forms/{id}/sections', [\App\Http\Controllers\Admin\FormBuilderController::class, 'addSection']);
    Route::post('/guide-engine/forms/{id}/import-pdf-fields', [\App\Http\Controllers\Admin\FormBuilderController::class, 'importPdfFields']);
    Route::put('/guide-engine/forms/{id}/reorder-sections', [\App\Http\Controllers\Admin\FormBuilderController::class, 'reorderSections']);
    Route::post('/guide-engine/sections/{id}/questions', [\App\Http\Controllers\Admin\FormBuilderController::class, 'addQuestion']);
    Route::put('/guide-engine/sections/{id}/reorder-questions', [\App\Http\Controllers\Admin\FormBuilderController::class, 'reorderQuestions']);
    Route::post('/guide-engine/sections/{id}/update', [\App\Http\Controllers\Admin\FormBuilderController::class, 'updateSection']);
    Route::delete('/guide-engine/sections/{id}', [\App\Http\Controllers\Admin\FormBuilderController::class, 'deleteSection']);
    Route::put('/guide-engine/questions/{id}', [\App\Http\Controllers\Admin\FormBuilderController::class, 'updateQuestion']);
    Route::delete('/guide-engine/questions/{id}', [\App\Http\Controllers\Admin\FormBuilderController::class, 'deleteQuestion']);
    Route::delete('/guide-engine/forms/{id}', [\App\Http\Controllers\Admin\FormBuilderController::class, 'deleteForm']);
});

Route::post('/payment/process', [\App\Http\Controllers\PaymentController::class, 'processPayment']);
