
class CourseSelectionController extends Controller
{
    public function index(Student $student)
    {
        $semester = Semester::current();
        $courses = Course::with(['sections' => function($query) use ($semester) {
            $query->whereHas('enrollments', function($q) use ($semester) {
                $q->where('semester_id', $semester->id);
            }, '<', \DB::raw('courses.max_capacity'));
        }])->get();

        $registered = $student->currentEnrollments($semester)
            ->where('status', 'registered')
            ->with('section.course')
            ->get();

        $waitlisted = $student->currentEnrollments($semester)
            ->where('status', 'waitlisted')
            ->with('section.course')
            ->get();

        $remainingCredits = 12 - $student->currentCreditTotal($semester);

        return view('course-selection', compact(
            'student',
            'semester',
            'courses',
            'registered',
            'waitlisted',
            'remainingCredits'
        ));
    }

    public function enroll(Student $student, Section $section)
    {
        $semester = Semester::current();
        $canEnroll = $student->canEnrollInSection($section, $semester);

        if (!$canEnroll['success']) {
            return back()->with('error', $canEnroll['message']);
        }

        if ($section->hasAvailableSeats()) {
            $enrollment = Enrollment::create([
                'student_id' => $student->id,
                'section_id' => $section->id,
                'status' => 'registered',
                'semester_id' => $semester->id
            ]);

            // Update section enrollment count
            $section->increment('current_enrollment');

            return back()->with('success', 'Successfully enrolled in the section.');
        } else {
            // Add to waitlist
            $waitlistPosition = $section->waitlistedStudents()->count() + 1;
            
            $enrollment = Enrollment::create([
                'student_id' => $student->id,
                'section_id' => $section->id,
                'status' => 'waitlisted',
                'waitlist_position' => $waitlistPosition,
                'semester_id' => $semester->id
            ]);

            // Log waitlist addition
            WaitlistLog::create([
                'enrollment_id' => $enrollment->id,
                'action' => 'added',
                'notes' => "Added to waitlist at position $waitlistPosition"
            ]);

            return back()->with('warning', 'Course is full. You have been added to the waitlist at position ' . $waitlistPosition);
        }
    }

    public function drop(Student $student, Enrollment $enrollment)
    {
        $semester = Semester::current();

        if ($enrollment->student_id != $student->id) {
            abort(403);
        }

        if ($enrollment->semester_id != $semester->id) {
            return back()->with('error', 'Cannot drop enrollment from a previous semester.');
        }

        if ($enrollment->status == 'registered') {
            // If dropping a registered course, check if we can promote someone from waitlist
            $section = $enrollment->section;
            $section->decrement('current_enrollment');

            // Promote first waitlisted student if any
            $nextWaitlisted = $section->waitlistedStudents()->first();
            if ($nextWaitlisted) {
                $nextWaitlisted->update([
                    'status' => 'registered',
                    'waitlist_position' => null
                ]);

                $section->increment('current_enrollment');

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

        $enrollment->delete();

        return back()->with('success', 'Successfully dropped the course.');
    }
}