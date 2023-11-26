<?php

namespace App\Observers;

use App\Models\Bond;
use App\Models\Invoice;
use App\Models\StudentsToCourse;

class CourseInvoiceObserver
{
    /**
     * Handle the students to course "created" event.
     *
     * @param  \App\StudentsToCourse  $studentsToCourse
     * @return void
     */
    public function created(StudentsToCourse $studentsToCourse)
    {
        $invoice = new Invoice;
        $invoice->total = $studentsToCourse->total;
        $invoice->remaining=$studentsToCourse->total - $studentsToCourse->paid;
        $invoice->income_id=1;
        $invoice->course_id=$studentsToCourse->course_id;
        $invoice->student_id=$studentsToCourse->application->student_id;
        $invoice->save();
        $invoice_id = $invoice->id;

        $studentsToCourse->invoice =  $invoice_id;
        $studentsToCourse->save();

        $bond = new Bond;
        $bond->invoice_id =  $invoice_id;
        $bond->amount = $studentsToCourse->paid;
        $bond->createdBy = $studentsToCourse->user_id;
        $bond->save();
        
    }

    public function creating(StudentsToCourse $studentsToCourse)
    {

    }
    /**
     * Handle the students to course "updated" event.
     *
     * @param  \App\StudentsToCourse  $studentsToCourse
     * @return void
     */
    public function updated(StudentsToCourse $studentsToCourse)
    {
        //
    }

    /**
     * Handle the students to course "deleted" event.
     *
     * @param  \App\StudentsToCourse  $studentsToCourse
     * @return void
     */
    public function deleted(StudentsToCourse $studentsToCourse)
    {
        //
    }

    /**
     * Handle the students to course "restored" event.
     *
     * @param  \App\StudentsToCourse  $studentsToCourse
     * @return void
     */
    public function restored(StudentsToCourse $studentsToCourse)
    {
        //
    }

    /**
     * Handle the students to course "force deleted" event.
     *
     * @param  \App\StudentsToCourse  $studentsToCourse
     * @return void
     */
    public function forceDeleted(StudentsToCourse $studentsToCourse)
    {
        //
    }
}
