import { Component, Inject, ViewEncapsulation } from '@angular/core';
import { FormBuilder, FormControl, FormGroup } from '@angular/forms';
import { MAT_DIALOG_DATA, MatDialogRef } from '@angular/material/dialog';

@Component({
    selector     : 'feedback-form-dialog',
    templateUrl  : './feedback-form.component.html',
    styleUrls    : ['./feedback-form.component.scss'],
    encapsulation: ViewEncapsulation.None
})

export class FeedbackFormDialogComponent
{
    eventForm: FormGroup;
    dialogTitle: string;

    review : string;
    score : string;

    /**
     * Constructor
     *
     * @param {MatDialogRef<CalendarEventFormDialogComponent>} matDialogRef
     * @param _data
     * @param {FormBuilder} _formBuilder
     */
    constructor(
        public matDialogRef: MatDialogRef<FeedbackFormDialogComponent>,
        @Inject(MAT_DIALOG_DATA) private _data: any,
    )
    {
        this.review = _data.review;
        this.score = _data.score;

        this.dialogTitle = 'Feedback';
        this.eventForm = this.createEventForm();
    }

    createEventForm(): FormGroup
    {
        return new FormGroup({
            review : new FormControl(this.review),
            score : new FormControl(this.score),
        });
    }

    ratingComponentClick(clickObj: any): void {
      this.score = clickObj.rating;
    }
}
