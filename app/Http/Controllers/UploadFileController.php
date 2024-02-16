<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use App\Models\UploadFile;
use App\Models\ApprovedPh;
use App\Models\ApprovedDean;
use App\Models\TbmPh;
use App\Models\TbmDean;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Deadline;
use App\Models\ArchiveUploads;
use App\Models\Reupload;
use Carbon\Carbon;

class UploadFileController extends Controller
{
    public function store(Request $request)
    {
        Log::debug('Store method was called');
        $request->validate([
            'file_name' => 'required|string',
            'file_path' => 'required|string',
            'firebase_url' => 'required|string',
            'program' => 'required|string',
            'academic_year' => 'required|string',
            'user_id' => 'required|integer',
            'deliverable_type' => 'required|string',
            'deadline_id' => 'required|integer'
        ]);

        $uploadFile = UploadFile::create($request->all());

        // $programHeadEmail = $this->getProgramHeadEmail($request->input('program'));
        // $data = [
        //     'user_name' => $request->input('user_name'),
        //     'program' => $request->input('program'),
        //     'file_name' => $request->input('file_name'),
        //     'deliverable_type' => $request->input('deliverable_type'),
        // ];

        // Mail::to($programHeadEmail)->send(new \App\Mail\DeliverableUploaded($data)); 

        return response()->json($uploadFile, 201);
    }

    public function getUploadPending()
    {
        $allData = UploadFile::all();
        return response()->json($allData);
    }

    public function getUpload()
    {
        $uploads = UploadFile::whereNull('status')->get();

        if ($uploads->isEmpty()) {
            return response()->json(['data' => [], 'message' => 'No approved documents found']);
        }

        return response()->json($uploads);
    }

    public function getUploadedById($id)
    {
        $upload = UploadFile::find($id);
        if (!$upload) {
            return response()->json(['message' => 'Document not found'], 404);
        }
        return response()->json($upload);
    }

    public function getUploadedByUserId($userId)
    {
        $userFiles = UploadFile::where('user_id', $userId)->get();
        return response()->json($userFiles);
    }

    public function getUploadByProgram($program)
    {
        if ($program === 'dean') {
            $uploads = UploadFile::whereNull('status')->get();
        } else {
            $uploads = UploadFile::where('program', $program)->whereNull('status')->get();
        }

        if ($uploads->isEmpty()) {
            return response()->json(['data' => [], 'message' => 'No documents found']);
        }

        return response()->json($uploads);
    }

    public function getFilesDetails($id)
    {
        $upload = UploadFile::find($id);
        if (!$upload) {
            return response()->json(['message' => 'Document not found'], 404);
        }
        return response()->json($upload);
    }

    public function archiveUpload(Request $request, $id)
    {
        $source = $request->input('source');

        $upload = null;
        $archiveStatus = '';

        if ($source === 'phase1') {
            $upload = UploadFile::find($id);
            $archiveStatus = 'upload_files';
        } elseif ($source === 'reupload') {
            $upload = Reupload::where('document_id', $id)->first();
            $archiveStatus = 'reupload_files';
        }

        if (!$upload) {
            return response()->json(['message' => 'Upload not found'], 404);
        }

        $archiveUpload = new ArchiveUploads();
        $archiveUpload->document_id = ($source === 'phase1') ? $upload->id : $upload->document_id;
        $archiveUpload->status = $archiveStatus;
        $archiveUpload->file_name = $upload->file_name;
        $archiveUpload->firebase_url = $upload->firebase_url;
        $archiveUpload->program = $upload->program;
        $archiveUpload->user_id = $upload->user_id;
        $archiveUpload->user_name = $upload->user_name;
        $archiveUpload->academic_year = $upload->academic_year;
        $archiveUpload->date_uploadedByUser = $upload->created_at;
        $archiveUpload->deliverable_type = $upload->deliverable_type;

        $archiveUpload->save();

        $upload->delete();

        return response()->json(['message' => 'Successfully archived']);
    }


    public function getPendingFiles($userId)
    {
        $userFiles = UploadFile::where('user_Id', $userId)->get();
        return response()->json($userFiles);
    }

    public function updateUploadStatus(Request $request)
    {
        try {
            $documentId = $request->input('documentId');
            $status = $request->input('status');

            DB::table('upload_files')
                ->where('id', $documentId)
                ->update(['status' => $status]);

            return response()->json(['message' => 'Upload Files Status Updated']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to update Upload Files Status'], 500);
        }
    }

    public function getFullTimeline($documentId)
    {
        $pendingData = UploadFile::where('id', $documentId)->get();

        $phData = ApprovedPh::where('document_id', $documentId)->get();

        $deanData = ApprovedDean::where('document_id', $documentId)->get();

        $tbmData = TbmPh::where('document_id', $documentId)->get();

        $tbmDeanData = TbmDean::where('document_id', $documentId)->get();

        $fullTimeline = $pendingData->concat($phData)->concat($deanData)->concat($tbmData)->concat($tbmDeanData)->sortBy('date');

        return response()->json($fullTimeline);
    }

    private function getProgramHeadEmail($programName)
    {
        $programHead = User::where([
            ['program', '=', $programName],
            ['is_admin', '=', true]
        ])->first();

        return $programHead ? $programHead->email : null;
    }

    public function getDeliverableCounts(Request $request)
    {
        try {
            $fromDate = $request->input('fromDate');
            $toDate = $request->input('toDate');
            $academicYear = $request->input('academicYear');

            $query = DB::table('upload_files')
                ->select(DB::raw('COUNT(*) as count, deliverable_type'))
                ->where(function ($query) {
                    $query->where(function ($query) {
                        $query->where('status', '!=', 'To Be Modified')
                            ->orWhereNull('status');
                    })
                        ->orWhereIn('id', function ($query) {
                            $query->select('document_id')
                                ->from('reuploads')
                                ->whereNull('status');
                        });
                })
                ->groupBy('deliverable_type');

            if (!empty($academicYear)) {
                $query->where('academic_year', $academicYear);
            }

            if (!empty($fromDate) && !empty($toDate)) {
                $startOfDay = Carbon::parse($fromDate)->startOfDay();
                $endOfDay = Carbon::parse($toDate)->endOfDay();
                $query->whereBetween('created_at', [$startOfDay, $endOfDay]);
            }

            $counts = $query->get();

            $deliverableCounts = [];
            foreach ($counts as $count) {
                $deliverableCounts[$count->deliverable_type] = $count->count;
            }

            return response()->json($deliverableCounts);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to fetch deliverable counts'], 500);
        }
    }

    public function getFileCountsByProgram()
    {
        try {
            $counts = DB::table('upload_files')
                ->select(DB::raw('COUNT(*) as count, program'))
                ->groupBy('program')
                ->get();

            $programCounts = [];
            foreach ($counts as $count) {
                $programCounts[$count->program] = $count->count;
            }

            return response()->json($programCounts);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to fetch program counts'], 500);
        }
    }

    public function getUploadByDeadline($deadlineId)
    {
        Log::info("Fetching uploads for deadline: $deadlineId");
        $uploads = UploadFile::where('deadline_id', $deadlineId)->get();

        if ($uploads->isEmpty()) {
            return response()->json([], 200);
        }

        return response()->json($uploads);
    }

    public function getNonCompliantUsers($deadlineId)
    {
        $deadlineProgram = Deadline::find($deadlineId)->program;

        $allFacultyForProgram = User::where('program', $deadlineProgram)->where('is_admin', 0)->get();

        $compliantUsers = UploadFile::where('deadline_id', $deadlineId)->pluck('user_id')->toArray();

        $nonCompliantUsers = $allFacultyForProgram->whereNotIn('id', $compliantUsers);

        return response()->json($nonCompliantUsers);
    }

    public function getRadarProgramCounts(Request $request)
    {
        try {
            $fromDate = $request->input('fromDate');
            $toDate = $request->input('toDate');
            $academicYear = $request->input('academicYear');

            $query = DB::table('upload_files')
                ->select(DB::raw('COUNT(*) as count, program, deliverable_type'))
                ->groupBy('program', 'deliverable_type');

            if (!empty($academicYear)) {
                $query->where('academic_year', $academicYear);
            }

            if (!empty($fromDate) && !empty($toDate)) {
                $startOfDay = Carbon::parse($fromDate)->startOfDay();
                $endOfDay = Carbon::parse($toDate)->endOfDay();
                $query->whereBetween('created_at', [$startOfDay, $endOfDay]);
            }

            $counts = $query->get();

            $labels = [];
            $programData = [];

            foreach ($counts as $count) {
                if (!in_array($count->deliverable_type, $labels)) {
                    $labels[] = $count->deliverable_type;
                }

                if (!isset($programData[$count->program])) {
                    $programData[$count->program] = [];
                }

                $programData[$count->program][$count->deliverable_type] = $count->count;
            }

            $datasets = [];
            foreach ($programData as $program => $programCounts) {
                $data = [];
                foreach ($labels as $label) {
                    $data[] = $programCounts[$label] ?? 0;
                }

                $datasets[] = [
                    'label' => $program,
                    'data' => $data
                ];
            }

            $result = [
                'labels' => $labels,
                'datasets' => $datasets
            ];

            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to fetch radar program counts'], 500);
        }
    }

    public function getDeliverableTypes()
    {
        try {

            $query = DB::table('upload_files')
                ->select('deliverable_type')
                ->distinct();

            $types = $query->pluck('deliverable_type');

            $deliverableTypes = $types->toArray();

            return response()->json($deliverableTypes);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to fetch deliverable types'], 500);
        }
    }

    public function getSubmissionDataByDeliverableType($deliverableType)
    {
        try {
            $submissionData = DB::table('upload_files')
                ->select(DB::raw('COUNT(*) as count, program'))
                ->where('deliverable_type', $deliverableType)
                ->groupBy('program')
                ->get();

            $data = [];
            foreach ($submissionData as $item) {
                $data[$item->program] = $item->count;
            }

            return response()->json($data);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to fetch submission data'], 500);
        }
    }

    public function getStackedBarChartData(Request $request)
    {
        try {
            $fromDate = $request->input('fromDate');
            $toDate = $request->input('toDate');
            $academicYear = $request->input('academicYear');

            $query = DB::table('upload_files')
                ->select('program', 'deliverable_type', DB::raw('COUNT(*) as count'))
                ->where(function ($query) {
                    $query->where(function ($query) {
                        $query->where('status', '!=', 'To Be Modified')
                            ->orWhereNull('status');
                    })
                        ->orWhereIn('id', function ($query) {
                            $query->select('document_id')
                                ->from('reuploads')
                                ->whereNull('status');
                        });
                })
                ->groupBy('program', 'deliverable_type');

            if (!empty($academicYear)) {
                $query->where('academic_year', $academicYear);
            }

            if (!empty($fromDate) && !empty($toDate)) {
                $startOfDay = Carbon::parse($fromDate)->startOfDay();
                $endOfDay = Carbon::parse($toDate)->endOfDay();
                $query->whereBetween('created_at', [$startOfDay, $endOfDay]);
            }

            $data = $query->get();

            $labels = [];
            $programData = [];

            foreach ($data as $item) {
                if (!in_array($item->program, $labels)) {
                    $labels[] = $item->program;
                }

                if (!isset($programData[$item->deliverable_type])) {
                    $programData[$item->deliverable_type] = [];
                }

                $programData[$item->deliverable_type][$item->program] = $item->count;
            }

            $datasets = [];
            foreach ($programData as $deliverable_type => $delTypeCounts) {
                $data = [];
                foreach ($labels as $label) {
                    $data[] = $delTypeCounts[$label] ?? 0;
                }

                $datasets[] = [
                    'label' => $deliverable_type,
                    'data' => $data,
                ];
            }

            $result = [
                'labels' => $labels,
                'datasets' => $datasets
            ];

            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to fetch stacked bar chart data'], 500);
        }
    }

    public function getUploadedCounts(Request $request)
    {
        try {
            $fromDate = $request->input('fromDate');
            $toDate = $request->input('toDate');
            $academicYear = $request->input('academicYear');

            $query = DB::table('upload_files')
                ->select('program', DB::raw('count(*) as count'))
                ->where(function ($query) {
                    $query->where(function ($query) {
                        $query->where('status', '!=', 'To Be Modified')
                            ->orWhereNull('status');
                    })
                        ->orWhereIn('id', function ($query) {
                            $query->select('document_id')
                                ->from('reuploads')
                                ->whereNull('status');
                        });
                })
                ->groupBy('program');

            if (!empty($academicYear)) {
                $query->where('academic_year', $academicYear);
            }

            if (!empty($fromDate) && !empty($toDate)) {
                $startOfDay = Carbon::parse($fromDate)->startOfDay();
                $endOfDay = Carbon::parse($toDate)->endOfDay();
                $query->whereBetween('created_at', [$startOfDay, $endOfDay]);
            }

            $counts = $query->get();

            return response()->json($counts);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to fetch uploaded counts'], 500);
        }
    }


    public function getPieChartModalData(Request $request, $deliverableType)
    {
        $fromDate = $request->input('fromDate');
        $toDate = $request->input('toDate');
        $academicYear = $request->input('academicYear');

        $query = DB::table('upload_files')
            ->select('user_name', 'program', 'academic_year', 'subject', 'subject_code')
            ->where(function ($query) {
                $query->where(function ($query) {
                    $query->where('status', '!=', 'To Be Modified')
                        ->orWhereNull('status');
                })
                    ->orWhereIn('id', function ($query) {
                        $query->select('document_id')
                            ->from('reuploads')
                            ->whereNull('status');
                    });
            })
            ->where('deliverable_type', $deliverableType);

        if (!empty($academicYear)) {
            $query->where('academic_year', $academicYear);
        }

        if ($fromDate && $toDate) {
            $toDate = date('Y-m-d', strtotime($toDate . ' +1 day'));
            $query->whereBetween('created_at', [$fromDate, $toDate]);
        }

        $data = $query->get();

        return response()->json($data);
    }


    public function getStackedBarModalData(Request $request, $program)
    {
        $fromDate = $request->query('fromDate');
        $toDate = $request->query('toDate');
        $academicYear = $request->input('academicYear');

        $query = DB::table('upload_files')
            ->select('user_name', 'program', 'academic_year', 'deliverable_type', 'subject', 'subject_code')
            ->where(function ($query) {
                $query->where(function ($query) {
                    $query->where('status', '!=', 'To Be Modified')
                        ->orWhereNull('status');
                })
                    ->orWhereIn('id', function ($query) {
                        $query->select('document_id')
                            ->from('reuploads')
                            ->whereNull('status');
                    });
            })
            ->where('program', $program);

        if (!empty($academicYear)) {
            $query->where('academic_year', $academicYear);
        }


        if ($fromDate && $toDate) {
            $toDate = date('Y-m-d', strtotime($toDate . ' +1 day'));
            $query->whereBetween('created_at', [$fromDate, $toDate]);
        }


        $data = $query->get();

        return response()->json($data);
    }


    public function getOnTimeCounts(Request $request)
    {
        try {
            $fromDate = $request->input('fromDate');
            $toDate = $request->input('toDate');
            $academicYear = $request->input('academicYear');

            $query = DB::table('upload_files')
                ->select('upload_files.program as program', DB::raw('count(*) as count'))
                ->join('deadlines', 'upload_files.deadline_id', '=', 'deadlines.id')
                ->where(function ($query) {
                    $query->where(function ($query) {
                        $query->where('upload_files.status', '!=', 'To Be Modified')
                            ->orWhereNull('upload_files.status');
                    })
                        ->orWhereIn('upload_files.id', function ($query) {
                            $query->select('reuploads.document_id')
                                ->from('reuploads')
                                ->whereNull('reuploads.status');
                        });
                })
                ->where('upload_files.created_at', '<=', DB::raw('deadlines.deadline'));

            if (!empty($academicYear)) {
                $query->where('academic_year', $academicYear);
            }

            if (!empty($fromDate) && !empty($toDate)) {
                $startOfDay = Carbon::parse($fromDate)->startOfDay();
                $endOfDay = Carbon::parse($toDate)->endOfDay();
                $query->whereBetween('upload_files.created_at', [$startOfDay, $endOfDay]);
            }

            $counts = $query->groupBy('program')->get();

            return response()->json($counts);
        } catch (\Exception $e) {
            Log::error('Exception in getOnTimeCounts: ' . $e->getMessage() . "\n" . $e->getTraceAsString());
            return response()->json(['message' => 'Failed to fetch on-time counts'], 500);
        }
    }


    public function getLateSubmissionCounts(Request $request)
    {
        try {
            $fromDate = $request->input('fromDate');
            $toDate = $request->input('toDate');
            $academicYear = $request->input('academicYear');

            $query = DB::table('upload_files')
                ->select('upload_files.program as program', DB::raw('count(*) as count'))
                ->join('deadlines', 'upload_files.deadline_id', '=', 'deadlines.id')
                ->where(function ($query) {
                    $query->where(function ($query) {
                        $query->where('upload_files.status', '!=', 'To Be Modified')
                            ->orWhereNull('upload_files.status');
                    })
                        ->orWhereIn('upload_files.id', function ($query) {
                            $query->select('reuploads.document_id')
                                ->from('reuploads')
                                ->whereNull('reuploads.status');
                        });
                })
                ->where('upload_files.created_at', '>=', DB::raw('deadlines.deadline'));

            if (!empty($academicYear)) {
                $query->where('academic_year', $academicYear);
            }

            if (!empty($fromDate) && !empty($toDate)) {
                $startOfDay = Carbon::parse($fromDate)->startOfDay();
                $endOfDay = Carbon::parse($toDate)->endOfDay();
                $query->whereBetween('upload_files.created_at', [$startOfDay, $endOfDay]);
            }

            $counts = $query->groupBy('program')->get();

            return response()->json($counts);
        } catch (\Exception $e) {
            Log::error('Exception in getOnTimeCounts: ' . $e->getMessage() . "\n" . $e->getTraceAsString());
            return response()->json(['message' => 'Failed to fetch on-time counts'], 500);
        }
    }

    public function getDidNotSubmitCounts(Request $request)
    {
        try {
            $fromDate = $request->input('fromDate');
            $toDate = $request->input('toDate');

            $allDeadlines = Deadline::all();

            $nonCompliantUserCounts = collect();

            foreach ($allDeadlines as $deadline) {
                $deadlineProgram = $deadline->program;

                $query = User::where('program', $deadlineProgram)->where('is_admin', 0);

                $compliantUserSubquery = UploadFile::where('deadline_id', $deadline->id)
                    ->select('user_id')
                    ->where(function ($query) {
                        $query->where('status', '!=', 'To Be Modified')
                            ->orWhereNull('status');
                    })
                    ->orWhereIn('id', function ($query) {
                        $query->select('document_id')
                            ->from('reuploads')
                            ->whereNull('status');
                    })
                    ->where('created_at', '>=', $deadline->deadline);


                if (!empty($fromDate) && !empty($toDate)) {
                    $startOfDay = Carbon::parse($fromDate)->startOfDay();
                    $endOfDay = Carbon::parse($toDate)->endOfDay();
                    $compliantUserSubquery->whereBetween('created_at', [$startOfDay, $endOfDay]);
                }

                $query->whereNotIn('id', $compliantUserSubquery);

                $nonCompliantUserCount = $query->count();

                $nonCompliantUserCounts->push([
                    'program' => $deadlineProgram,
                    'count' => $nonCompliantUserCount,
                ]);
            }

            return response()->json($nonCompliantUserCounts);
        } catch (\Exception $e) {
            Log::error('Exception in getAllNonCompliantUserCounts: ' . $e->getMessage() . "\n" . $e->getTraceAsString());
            return response()->json(['message' => 'Failed to fetch non-compliant user counts'], 500);
        }
    }
}
