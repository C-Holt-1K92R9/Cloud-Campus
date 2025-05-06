// For Waitlist, checking availaibility of seat
namespace App\Observers;

use App\Models\Enrollment;
use App\Models\WaitlistLog;

class EnrollmentObserver
{
    public function deleted(Enrollment $enrollment)
    {
        if ($enrollment->status === 'registered') {
            $section = $enrollment->section;
            
            // Promote first waitlisted student if any
            $nextWaitlisted = $section->waitlistedStudents()->first();
            if ($nextWaitlisted) {
                $nextWaitlisted->update([
                    'status' => 'registered',
                    'waitlist_position' => null
                ]);

                // Log the promotion
                WaitlistLog::create([
                    'enrollment_id' => $nextWaitlisted->id,
                    'action' => 'promoted',
                    'notes' => 'Promoted from waitlist due to a drop'
                ]);

                // Update other waitlist positions
                $section->waitlistedStudents()
                    ->where('id', '!=', $nextWaitlisted->id)
                    ->decrement('waitlist_position');
            }
        }
    }
}