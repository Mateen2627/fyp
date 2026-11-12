<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class SecureLmsAttachments extends Command
{
    protected $signature = 'lms:secure-attachments';
    protected $description = 'Move existing LMS materials and submissions from public storage to private storage';

    public function handle()
    {
        $count = 0;
        foreach (['assignments', 'quizzes', 'student_assignment_submissions', 'student_quiz_attempts'] as $table) {
            foreach (DB::table($table)->whereNotNull('attachment_path')->pluck('attachment_path')->unique() as $path) {
                if (!preg_match('#^(assignment_materials|quiz_materials|student_submissions|quiz_attempts)/[^.][^\\\\]*$#', $path) || strpos($path, '..') !== false) {
                    $this->error('An attachment has an unexpected path. Resolve that record before continuing.');
                    return 1;
                }
                $public = Storage::disk('public'); $private = Storage::disk('local');
                if (!$public->exists($path)) { continue; }
                $contents = $public->get($path);
                if ($private->exists($path) && hash('sha256', $private->get($path)) !== hash('sha256', $contents)) {
                    $this->error('A private attachment conflicts with a public file. No conflicting file was changed.');
                    return 1;
                }
                if (!$private->exists($path)) { $private->put($path, $contents); }
                if (hash('sha256', $private->get($path)) !== hash('sha256', $contents)) {
                    $this->error('Attachment verification failed; the public original was retained.');
                    return 1;
                }
                $public->delete($path);
                $count++;
            }
        }
        $this->info($count.' LMS attachments moved to private storage.');
        return 0;
    }
}
