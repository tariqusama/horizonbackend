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
    Route::get('/documents', [DocumentController::class, 'index']);
    Route::post('/documents/upload', [DocumentController::class, 'store']);
    Route::post('/documents/{id}/upload', [DocumentController::class, 'upload']);
    Route::get('/messages', [\App\Http\Controllers\ChatController::class, 'index']);
    Route::post('/messages', [\App\Http\Controllers\ChatController::class, 'store']);
    Route::get('/notifications', [\App\Http\Controllers\Admin\NotificationController::class, 'index']);
    Route::put('/notifications/mark-read', [\App\Http\Controllers\Admin\NotificationController::class, 'markAsRead']);
    Route::delete('/notifications/clear', [\App\Http\Controllers\Admin\NotificationController::class, 'clearAll']);

    Route::get('/manager/assigned-cases', [\App\Http\Controllers\ManagerController::class, 'assignedCases']);
    Route::get('/manager/unassigned-cases', [\App\Http\Controllers\ManagerController::class, 'unassignedCases']);
    Route::post('/manager/applications/{id}/request-assignment', [\App\Http\Controllers\ManagerController::class, 'requestAssignment']);
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
});

Route::post('/auth/send-otp', [AuthController::class, 'sendOtp']);
Route::post('/auth/verify-otp', [AuthController::class, 'verifyOtp']);
Route::post('/newsletter/subscribe', [\App\Http\Controllers\NewsletterController::class, 'subscribe']);

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
});

Route::post('/payment/process', [\App\Http\Controllers\PaymentController::class, 'processPayment']);
