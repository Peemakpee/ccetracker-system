<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Models\Deadline;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use App\Models\UploadFile;


class SendDeadlineReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-deadline-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send email reminders for upcoming deadlines within 3 days';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = Carbon::now();

        $upcomingDeadlines = Deadline::where('deadline', '<=', $now->addDays(3))->get();

        foreach ($upcomingDeadlines as $deadline) {
            $facultyEmails = $this->getFacultyEmails($deadline->program, $deadline->id);

            $data = [
                'program' => $deadline->program,
                'deadline' => $deadline->deadline,
                'deliverable_type' => $deadline->deliverable_type,
            ];

            foreach ($facultyEmails as $facultyEmail) {
                Mail::to($facultyEmail)->send(new \App\Mail\DeadlineReminder($data));

                sleep(1);
            }
        }
    }

    private function getFacultyEmails($program, $deadlineId)
    {
        $submittedUserIds = UploadFile::where('deadline_id', $deadlineId)
            ->pluck('user_id')
            ->toArray();

        return User::where([
            ['is_admin', '=', false], 
            ['program', '=', $program], 
        ])->where(function ($query) use ($submittedUserIds) {
            $query->whereNotIn('id', $submittedUserIds);
        })->pluck('email')->toArray();
    }
}
