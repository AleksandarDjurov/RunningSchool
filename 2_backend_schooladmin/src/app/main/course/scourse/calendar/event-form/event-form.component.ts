import { Component, Inject, ViewEncapsulation } from '@angular/core';
import { FormBuilder, FormControl, FormGroup } from '@angular/forms';
import { MAT_DIALOG_DATA, MatDialogRef } from '@angular/material/dialog';
import { CalendarEvent } from 'angular-calendar';

import { MatColors } from '@fuse/mat-colors';
import { CalendarEventModel } from 'app/main/course/scourse/calendar/event.model';

@Component({
    selector     : 'calendar-event-form-dialog',
    templateUrl  : './event-form.component.html',
    styleUrls    : ['./event-form.component.scss'],
    encapsulation: ViewEncapsulation.None
})

export class CalendarEventFormDialogComponent
{
    action: string;
    event: CalendarEvent;
    eventForm: FormGroup;
    dialogTitle: string;
    presetColors = MatColors.presets;

    /**
     * Constructor
     *
     * @param {MatDialogRef<CalendarEventFormDialogComponent>} matDialogRef
     * @param _data
     * @param {FormBuilder} _formBuilder
     */
    constructor(
        public matDialogRef: MatDialogRef<CalendarEventFormDialogComponent>,
        @Inject(MAT_DIALOG_DATA) private _data: any,
        private _formBuilder: FormBuilder
    )
    {
        this.event = _data.event;
        this.action = _data.action;

        if ( this.action === 'edit' )
            this.dialogTitle = 'Edit';
        else if( this.action === 'view')
            this.dialogTitle = 'View';
        else if( this.action === 'new' )
        {
            this.dialogTitle = 'New';
            this.event = new CalendarEventModel({
                start: _data.date,
                end  : _data.date
            });
        }

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
            title : new FormControl(this.event.title),
            desc : new FormControl(this.event.desc),
            start : new FormControl(this.event.start),
            start_time : new FormControl(this.event.start_time),
            end : new FormControl(this.event.end),
            end_time : new FormControl(this.event.end_time),
            allDay: new FormControl(this.event.allDay),
            color : this._formBuilder.group({
                primary  : new FormControl(this.event.color.primary),
                secondary: new FormControl(this.event.color.secondary)
            }),
            meta  :
                this._formBuilder.group({
                    location: new FormControl(this.event.meta.location),
                    notes   : new FormControl(this.event.meta.notes)
                })
        });
    }
}
