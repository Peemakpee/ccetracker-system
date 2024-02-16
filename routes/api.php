<?php

use App\Http\Controllers\user\UserAuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UploadFileController;
use App\Http\Controllers\ApprovePhController;
use App\Http\Controllers\ApproveDeanController;
use App\Http\Controllers\TbmPhController;
use App\Http\Controllers\TbmDeanController;
use App\Http\Controllers\SetDeadlinesController;
use App\Http\Controllers\MemosController;
use App\Http\Controllers\MessagesController;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\DeliverableTemplatesController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\RequestAccessController;
use App\Http\Controllers\SignatureController;
use App\Http\Controllers\ApprovedChangesController;
use App\Http\Controllers\CompliedChangesController;
use App\Http\Controllers\RepliesController;
use App\Http\Controllers\ArchiveUploadsController;
use App\Http\Controllers\ReuploadController;
use App\Http\Controllers\ReuploadDeanController;
use App\Http\Controllers\ReportController;
use App\Models\ApprovedDean;
use App\Http\Controllers\RetentionController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum', 'user')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::post('/logout', [UserAuthController::class, 'logout']);

    Route::get('/get-user-id', function () {
        return auth()->id();
    });
});

Route::post('/register', [UserAuthController::class, 'register']);

Route::post('/login', [UserAuthController::class, 'login']);

Route::post('/upload', [UploadFileController::class, 'store']);

Route::post('/update-upload-status', [UploadFileController::class, 'updateUploadStatus']);

Route::post('/update-reupload-status', [ReuploadController::class, 'updateReuploadStatus']);

Route::post('/update-reuploadDean-status', [ReuploadDeanController::class, 'updateReuploadDeanStatus']);

Route::post('/update-approve-status', [ApprovePhController::class, 'updateApproveStatus']);

Route::post('/archive-upload/{id}', [UploadFileController::class, 'archiveUpload']);

Route::delete('/delete-memo/{id}', [MemosController::class, 'deleteMemo']);

Route::post('/archive-approvePh/{id}', [ApprovePhController::class, 'archiveApprovedPh']);

Route::get('/get-upload', [UploadFileController::class, 'getUpload']);

Route::get('/get-upload-pending', [UploadFileController::class, 'getUploadPending']);

Route::get('/get-uploaded/{id}', [UploadFileController::class, 'getUploadedById']);

Route::get('/get-reuploaded/{id}', [ReuploadController::class, 'getReuploadedById']);

Route::get('/get-reuploadedDean/{id}', [ReuploadDeanController::class, 'getReuploadedDeanById']);

Route::get('/get-upload/{program}', [UploadFileController::class, 'getUploadByProgram']);

Route::post('/approved-ph', [ApprovePhController::class, 'storeApprovedPH']);

Route::post('/approved-dean', [ApproveDeanController::class, 'storeApprovedDean']);

Route::post('/tbm-ph', [TbmPhController::class, 'storeTbmPH']);

Route::post('/tbm-dean', [TbmDeanController::class, 'storeTbmDean']);

Route::get('/get-approvedPh', [ApprovePhController::class, 'getApprovedPh']);

Route::get('/get-tbmPh', [TbmPhController::class, 'getTbmPh']);

Route::get('/get-pending-files/{userId}', [UploadFileController::class, 'getPendingFiles']);

Route::get('/get-approved-ph-files/{userId}', [ApprovePhController::class, 'getApprovedFiles']);

Route::get('/get-approved-dean-files/{userId}', [ApproveDeanController::class, 'getApprovedDeanFiles']);

Route::get('/get-tbm-ph-files/{userId}', [TbmPhController::class, 'getTbmFiles']);

Route::get('/get-document-details/{documentId}', [UploadFileController::class, 'getFilesDetails']);

Route::get('/get-full-timeline/{documentId}', [UploadFileController::class, 'getFullTimeline']);

Route::get('/get-approvedPh/{id}', [ApprovePhController::class, 'getApprovedPhId']);

Route::get('/get-deliverable-counts', [UploadFileController::class, 'getDeliverableCounts']);

Route::get('/get-uploaded-counts', [UploadFileController::class, 'getUploadedCounts']);

Route::get('/get-deliverable-phcounts', [ApprovePhController::class, 'getDeliverablePhCounts']);

Route::get('/get-deliverable-deancounts', [ApproveDeanController::class, 'getDeliverableDeanCounts']);

Route::get('/get-deliverable-approveChanges', [ApprovedChangesController::class, 'getApproveChangesCounts']);

Route::get('/get-on-time-counts', [UploadFileController::class, 'getOnTimeCounts']);

Route::get('/get-late-submission-counts', [UploadFileController::class, 'getLateSubmissionCounts']);

Route::get('/get-did-not-submit-counts', [UploadFileController::class, 'getDidNotSubmitCounts']);

Route::get('/get-program-counts', [UploadFileController::class, 'getFileCountsByProgram']);

Route::post('/set-deadlines', [SetDeadlinesController::class, 'storeDeadlines']);

Route::get('/get-deadlines/{program}', [SetDeadlinesController::class, 'getDeadlinesByProgram']);

Route::get('/get-deadlines-upload/{deadlineId}', [SetDeadlinesController::class, 'getDeadlinesUpload']);

Route::get('/get-deadlines', [SetDeadlinesController::class, 'getFacultyCompliance']);

Route::delete('/delete-faculty-compliance/{id}', [SetDeadlinesController::class, 'deleteFacultyCompliance']);

Route::get('/get-upload-by-deadline/{deadlineId}', [UploadFileController::class, 'getUploadByDeadline']);

Route::get('/get-uploaded-userId/{user_id}', [UploadFileController::class, 'getUploadedByUserId']);

Route::get('/get-approved-deadlines/{user_id}', [ApproveDeanController::class, 'getApprovedDeadlines']);

Route::post('/upload-memo', [MemosController::class, 'uploadMemo']);

Route::get('/get-memo-recipient/{recipientName}', [MemosController::class, 'getMemosByRecipient']);

Route::get('/memos-for-program-heads/{phId}', [MemosController::class, 'getMemosByPH']);

Route::get('/get-detailed-memo/{memoId}', [MemosController::class, 'getDetailedMemo']);

Route::get('/get-users', [UserAuthController::class, 'getUsers']);

Route::get('/get-faculty-compliance/{program}', [SetDeadlinesController::class, 'getFacultyComplianceProgram']);

Route::get('/get-faculty-compliance', [SetDeadlinesController::class, 'getFacultyCompliance']);

Route::get('/get-non-compliant-users/{deadlineId}', [UploadFileController::class, 'getNonCompliantUsers']);

Route::post('/request-register', [RequestAccessController::class, 'requestStore']);

Route::get('/get-registration-requests', [RequestAccessController::class, 'getRegisterRequests']);

Route::post('/approve-request/{id}', [RequestAccessController::class, 'approveRequests']);

Route::post('/disapprove-request/{id}', [RequestAccessController::class, 'disapproveRequests']);

Route::post('/upload-template', [DeliverableTemplatesController::class, 'uploadTemplates']);

Route::get('/get-templates', [DeliverableTemplatesController::class, 'getTemplates']);

Route::get('/get-templatesOptions', [DeliverableTemplatesController::class, 'getTemplatesOptions']);

Route::get('/get-deliverable-types', [DeliverableTemplatesController::class, 'getUniqueDeliverableTypes']);

Route::delete('/delete-template/{type}', [DeliverableTemplatesController::class, 'deleteTemplate']);

Route::get('/get-template/{id}', [DeliverableTemplatesController::class, 'getTemplateById']);

Route::get('/get-templatesUser', [DeliverableTemplatesController::class, 'getTemplatesUser']);

Route::get('/get-detailed-templates/{id}', [DeliverableTemplatesController::class, 'getDetailedTemplates']);

Route::get('/users-by-program', [UserAuthController::class, 'getUsersByProgram']);

Route::get('/get-filterFacultyOptions', [UserAuthController::class, 'getFacultyOption']);

Route::get('/get-filterDeliverableOptions', [DeliverableTemplatesController::class, 'getTemplatesOptions']);

Route::get('/get-filterProgramOptions', [UserAuthController::class, 'getProgramOptions']);

Route::get('/program-head/{program}', [UserAuthController::class, 'getProgramHead']);

Route::get('/get-approvedByDean', [ApproveDeanController::class, 'getApprovedByDean']);

Route::get('/get-approvedByDean/{program}', [ApproveDeanController::class, 'getApprovedByDeanProgram']);

Route::post('/archive-approvedByDean/{id}', [ApproveDeanController::class, 'archiveApprovedDean']);

Route::post('/archive-approvedChanges/{id}', [ApprovedChangesController::class, 'archiveApprovedChanges']);

Route::post('/archive-compliedChanges/{id}', [CompliedChangesController::class, 'archiveCompliedChanges']);

Route::get('/get-approvedDean/{id}', [ApproveDeanController::class, 'getApprovedDean']);

Route::post('/changes-required', [ApprovedChangesController::class, 'storeApprovedChanges']);

Route::get('/get-approved-changes-files/{userId}', [ApprovedChangesController::class, 'getApprovedChangesFiles']);

Route::get('/get-detailed-approvedChanges/{docId}', [ApprovedChangesController::class, 'getDetailedApprovedChanges']);

Route::post('/upload-complied', [CompliedChangesController::class, 'storeCompliedChanges']);

Route::get('/get-tbm-dean-files/{userId}', [TbmDeanController::class, 'getTbmDeanFiles']);

Route::get('/get-sender/{userId}', [MessagesController::class, 'getSender']);

Route::post('/send-message', [MessagesController::class, 'sendMessage']);

Route::get('/get-messages/{recipientName}', [MessagesController::class, 'getMessages']);

Route::get('/get-detailed-message/{messageId}', [MessagesController::class, 'getDetailedMessage']);

Route::post('/send-reply', [RepliesController::class, 'sendReply']);

Route::get('/get-reply-message/{messageId}', [RepliesController::class, 'getReplyMessage']);

Route::get('/get-inbox/{adminName}', [MessagesController::class, 'getInbox']);

Route::delete('/delete-inbox/{id}', [MessagesController::class, 'deleteInbox']);

Route::delete('/delete-message/{id}', [MessagesController::class, 'deleteMessage']);

Route::get('/get-inboxMessage/{messageId}', [MessagesController::class, 'getInboxMessage']);

Route::get('/get-sent-messages/{adminName}', [MessagesController::class, 'getSentMessages']);

Route::get('/get-approved-deliverables/{userId}', [ApproveDeanController::class, 'getApprovedDeanFiles']);

Route::get('/get-compiled-changes', [CompliedChangesController::class, 'getCompiledChanges']);

Route::get('/get-compiled-changes/{program}', [CompliedChangesController::class, 'getCompiledChangesProgram']);

Route::get('/get-approved-changes', [ApprovedChangesController::class, 'getApprovedChanges']);

Route::get('/get-approved-changes/{program}', [ApprovedChangesController::class, 'getApprovedChangesProgram']);

Route::post('/update-approve-dean-show/{id}', [ApproveDeanController::class, 'updateApproveDeanShow']);

Route::get('/get-radar-program-counts', [UploadFileController::class, 'getRadarProgramCounts']);

Route::get('/get-deliverableTypes', [UploadFileController::class, 'getDeliverableTypes']);

Route::get('/submission-data/{deliverableType}', [UploadFileController::class, 'getSubmissionDataByDeliverableType']);

Route::get('/get-archive', [ArchiveUploadsController::class, 'getArchive']);

Route::get('/get-archive/{program}', [ArchiveUploadsController::class, 'getArchiveProgram']);

Route::delete('/delete-archive/{id}', [ArchiveUploadsController::class, 'deleteArchive']);

Route::post('/reupload', [ReuploadController::class, 'storeReuploads']);

Route::post('/reupload-dean', [ReuploadDeanController::class, 'storeReuploadDean']);

Route::get('/get-reuploads/{program}', [ReuploadController::class, 'getReuploadsProgram']);

Route::get('/get-reuploads', [ReuploadController::class, 'getReuploads']);

Route::get('/get-reuploadDean', [ReuploadDeanController::class, 'getReuploadDean']);

Route::get('/get-stacked-bar-data', [UploadFileController::class, 'getStackedBarChartData']);

Route::get('/get-pieChartModal-data/{deliverableType}', [UploadFileController::class, 'getPieChartModalData']);

Route::post('/send-reminder/{userId}/{program}/{deliverableType}', [SetDeadlinesController::class, 'sendReminder']);

Route::get('/get-stackedBarModal-data/{program}', [UploadFileController::class, 'getStackedBarModalData']);

Route::get('/validate-recipient', [UserAuthController::class, 'validateRecipient']);

Route::get('/get-recipient', [UserAuthController::class, 'getRecipient']);

Route::post('/generate-pie-report', [ReportController::class, 'generatePieReports']);

Route::delete('/delete-user-memo/{id}', [MemosController::class, 'deleteUserMemo']);

Route::put('/update-user-status/{userID}', [UserAuthController::class, 'setUserStatus']);

Route::get('/get-current-username', [UserAuthController::class, 'getCurrentUserName']);

Route::get('/retention-data', [RetentionController::class, 'getRetentionData']);

Route::delete('/delete-file/{id}', [RetentionController::class, 'deleteRetention']);

Route::get('/get-currrent-user/{currentUser}', [UserAuthController::class, 'getCurrentUser']);
