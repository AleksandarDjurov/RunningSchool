import { Component, Inject, ViewEncapsulation } from '@angular/core';
import { FormBuilder, FormControl, FormGroup } from '@angular/forms';
import { MAT_DIALOG_DATA, MatDialogRef } from '@angular/material/dialog';
import { CalendarEvent } from 'angular-calendar';

import { MatColors } from '@fuse/mat-colors';
import { CalendarEventModel } from 'app/main/course/scourse/calendar/event.model';

@Component({
    selector     : 'calendar-activate-form-dialog',
    templateUrl  : './activate-form.component.html',
    styleUrls    : ['./activate-form.component.scss'],
    encapsulation: ViewEncapsulation.None
})

export class ActivateFormDialogComponent
{
    action: string;
    event: CalendarEvent;
    eventForm: FormGroup;
    dialogTitle: string;

    course : any = {};
    ptLists: any[] = [];

    trainer_id : string;

    /**
     * Constructor
     *
     * @param {MatDialogRef<CalendarEventFormDialogComponent>} matDialogRef
     * @param _data
     * @param {FormBuilder} _formBuilder
     */
    constructor(
        public matDialogRef: MatDialogRef<ActivateFormDialogComponent>,
        @Inject(MAT_DIALOG_DATA) private _data: any,
        private _formBuilder: FormBuilder
    )
    {
        this.course = _data.course;
        this.ptLists = _data.personal;

        this.dialogTitle = 'Activate Course';
        this.event = new CalendarEventModel({
            start: _data.date,
            end  : _data.date
        });

        this.eventForm = this.createEventForm();
    }

    /**
     * Create the event form
     *
     * @returns {FormGroup}
     */
    createEventForm(): FormGroup
    {
        return new FormGroup({
            trainer : new FormControl(this.trainer_id),
        });
    }
}
