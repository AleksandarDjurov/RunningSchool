import { Component, Inject, ViewEncapsulation } from '@angular/core';
import { FormBuilder, FormControl, FormGroup } from '@angular/forms';
import { MAT_DIALOG_DATA, MatDialogRef } from '@angular/material/dialog';
import { CalendarEvent } from 'angular-calendar';

import { MatColors } from '@fuse/mat-colors';
import { CalendarEventModel } from 'app/main/course/scourse/calendar/event.model';

@Component({
    selector     : 'calendar-scourse-form-dialog',
    templateUrl  : './scourse-form.component.html',
    styleUrls    : ['./scourse-form.component.scss'],
    encapsulation: ViewEncapsulation.None
})

export class SCourseFormDialogComponent
{
    action: string;
    event: CalendarEvent;
    eventForm: FormGroup;
    dialogTitle: string;

    qcourse : any = {};
    levels: any[] = [];

    level : string;

    /**
     * Constructor
     *
     * @param {MatDialogRef<CalendarEventFormDialogComponent>} matDialogRef
     * @param _data
     * @param {FormBuilder} _formBuilder
     */
    constructor(
        public matDialogRef: MatDialogRef<SCourseFormDialogComponent>,
        @Inject(MAT_DIALOG_DATA) private _data: any,
        private _formBuilder: FormBuilder
    )
    {
        this.qcourse = _data.quarter;
        this.levels = _data.levels;

        this.dialogTitle = 'New Course';
        this.event = new CalendarEventModel({
            start: _data.date,
            end  : _data.date
        });

        this.eventForm = this.createEventForm();
    }

    // -----------------------------------------------------------------------------------------------------
    // @ Public methods
    // -----------------------------------------------------------------------------------------------------

    /**
     * Create the event form
     *
     * @returns {FormGroup}
     */
    createEventForm(): FormGroup
    {
        return new FormGroup({
            maincourse   : new FormControl(this.qcourse.course_name),
            course_id : new FormControl(this.qcourse.id),
            level : new FormControl(this.level),
            title : new FormControl(this.event.title),
            desc : new FormControl(this.event.desc),
            price : new FormControl(this.event.price),
            seat : new FormControl(this.event.seat),
            start : new FormControl(this.event.start),
            end   : new FormControl(this.event.end),
            meta  :
                this._formBuilder.group({
                    location: new FormControl(this.event.meta.location),
                    notes   : new FormControl(this.event.meta.notes)
                })
        });
    }
}
