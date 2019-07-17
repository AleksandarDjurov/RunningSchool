import { Component, OnInit, ViewEncapsulation, isDevMode } from '@angular/core';
import { FormGroup } from '@angular/forms';
import { MatDialog, MatDialogRef } from '@angular/material/dialog';
import { Subject } from 'rxjs';
import { startOfDay, isSameDay, isSameMonth } from 'date-fns';
import { CalendarEvent, CalendarEventAction, CalendarEventTimesChangedEvent, CalendarMonthViewDay } from 'angular-calendar';

import { FuseConfirmDialogComponent } from '@fuse/components/confirm-dialog/confirm-dialog.component';
import { fuseAnimations } from '@fuse/animations';

import { CalendarService } from 'app/main/course/scourse/calendar/calendar.service';
import { CalendarEventModel } from 'app/main/course/scourse/calendar/event.model';
import { CalendarEventFormDialogComponent } from 'app/main/course/scourse/calendar/event-form/event-form.component';

import { environment as env } from '../../../../../environments/environment';
import { environment as envProd } from '../../../../../environments/environment.prod';
import { DatePipe } from '@angular/common';

import { Location } from '@angular/common';


@Component({
    selector: 'calendar',
    templateUrl: './calendar.component.html',
    styleUrls: ['./calendar.component.scss'],
    encapsulation: ViewEncapsulation.None,
    animations: fuseAnimations
})

export class CalendarComponent implements OnInit {
    actions: CalendarEventAction[];
    activeDayIsOpen: boolean;
    confirmDialogRef: MatDialogRef<FuseConfirmDialogComponent>;
    dialogRef: any;
    events: CalendarEvent[];
    refresh: Subject<any> = new Subject();
    selectedDay: any;
    view: string;
    viewDate: Date;

    constructor(
        public _calendarService: CalendarService,
        private _matDialog: MatDialog,
        private datePipe: DatePipe,
        private _location: Location
    ) {
        // Set the defaults
        this.view = 'month';
        this.viewDate = new Date();
        //this.activeDayIsOpen = true;
        this.selectedDay = { date: startOfDay(new Date()) };

        this.actions = [
            {
                label: '<i class="material-icons s-16">edit</i>',
                onClick: ({ event }: { event: CalendarEvent }): void => {
                    this.editEvent('edit', event);
                }
            },
            {
                label: '<i class="material-icons s-16">delete</i>',
                onClick: ({ event }: { event: CalendarEvent }): void => {
                    this.deleteEvent(event);
                }
            }
        ];

        /**
         * Get events from service/server
         */
        this.setEvents();
    }

    // -----------------------------------------------------------------------------------------------------
    // @ Lifecycle hooks
    // -----------------------------------------------------------------------------------------------------

    /**
     * On init
     */
    ngOnInit(): void {
        /**
         * Watch re-render-refresh for updating db
         */
        this.refresh.subscribe(updateDB => {
            if (updateDB) {
                this._calendarService.updateEvents(this.events);
            }
        });

        this._calendarService.onEventsUpdated.subscribe(events => {
            this.setEvents();
            this.refresh.next();
        });
    }

    // -----------------------------------------------------------------------------------------------------
    // @ Public methods
    // -----------------------------------------------------------------------------------------------------

    /**
     * Set events
     */
    setEvents(): void {
        this.events = this._calendarService.events.map(item => {
            item.actions = this.actions;
            return new CalendarEventModel(item);
        });
    }

    /**
     * Before View Renderer
     *
     * @param {any} header
     * @param {any} body
     */
    beforeMonthViewRender({ header, body }): void {
        /**
         * Get the selected day
         */
        const _selectedDay = body.find((_day) => {
            return _day.date.getTime() === this.selectedDay.date.getTime();
        });

        if (_selectedDay) {
            /**
             * Set selected day style
             * @type {string}
             */
            _selectedDay.cssClass = 'cal-selected';
        }
    }

    /**
     */

    prevMillisecs: number = 0;
    prevSecs: number = 0;
    dayClicked(day: CalendarMonthViewDay): void {
        const date: Date = day.date;
        const events: CalendarEvent[] = day.events;

        if (isSameMonth(date, this.viewDate)) {
            if ((isSameDay(this.viewDate, date) && this.activeDayIsOpen === true) || events.length === 0) {
                this.activeDayIsOpen = false;
            }
            else {
                this.activeDayIsOpen = true;
                this.viewDate = date;
            }
        }
        this.selectedDay = day;
        this.refresh.next();
    }

    /**
     * Event times changed
     * Event dropped or resized
     *
     * @param {CalendarEvent} event
     * @param {Date} newStart
     * @param {Date} newEnd
     */
    eventTimesChanged({ event, newStart, newEnd }: CalendarEventTimesChangedEvent): void {
        event.start = newStart;
        event.end = newEnd;
        // console.warn('Dropped or resized', event);
        this.refresh.next(true);
    }

    /**
     * Delete Event
     *
     * @param event
     */
    deleteEvent(event): void {
        
        this.confirmDialogRef = this._matDialog.open(FuseConfirmDialogComponent, {
            disableClose: false
        });

        this.confirmDialogRef.componentInstance.confirmMessage = 'Are you sure you want to delete?';

        this.confirmDialogRef.afterClosed().subscribe(result => {
            if (result) {
                const eventIndex = this.events.indexOf(event);
                this.events.splice(eventIndex, 1);
                this.refresh.next(true);
            }
            this.confirmDialogRef = null;
        });

    }

    /**
     * Edit Event
     *
     * @param {string} action
     * @param {CalendarEvent} event
     */
    editEvent(action: string, param: any): void {
        var d = new Date();
        var n = d.getMilliseconds();
        var s = d.getSeconds();

        if ((s * 1000 + n) - (this.prevSecs * 1000 + this.prevMillisecs) > 400) {
            this.prevMillisecs = n;
            this.prevSecs = s;

            return;
        }
        else {
            this.prevMillisecs = 0;
            this.prevSecs = 0;
        }

        let event: CalendarEvent;
        event = param.day.events[0];

        let status = this._calendarService.status;

        if (event == null) {
            if( status ==='0' )
            {
                this.selectedDay = param.day;
                this.addEvent();
            }

            return;
        }

        if( status !== '0' )
            action = 'view';

        const eventIndex = this.events.indexOf(event);
        this.dialogRef = this._matDialog.open(CalendarEventFormDialogComponent, {
            panelClass: 'event-form-dialog',
            data: {
                event: event,
                action: action
            }
        });

        this.dialogRef.afterClosed()
            .subscribe(response => {
                if (!response) 
                    return;

                const actionType: string = response[0];
                const formData: FormGroup = response[1];
                switch (actionType) {
                    case 'save':
                        this.events[eventIndex] = Object.assign(this.events[eventIndex], formData.getRawValue());
                        this.refresh.next(true);
                        break;
                    case 'delete':
                        this.deleteEvent(event);
                        break;
                }
            });
    }

    /**
     * Add Event
     */
    addEvent(): void {
        this.dialogRef = this._matDialog.open(CalendarEventFormDialogComponent, {
            panelClass: 'event-form-dialog',
            data: {
                action: 'new',
                date: this.selectedDay.date
            }
        });
        this.dialogRef.afterClosed()
            .subscribe((response: FormGroup) => {
                if (!response)
                    return;

                const newEvent = response.getRawValue();

                newEvent.actions = this.actions;
                this.events.push(newEvent);
                this.refresh.next(true);
            });

        // let newEvent: any = {};

        // newEvent.actions = this.actions;
        // newEvent.allDay = false;
        // newEvent.color = { primary: "#1e90ff", secondary: "#D1E8FF" };
        // newEvent.start = this.selectedDay.date;
        // newEvent.end = this.selectedDay.date;
        // newEvent.meta = { location: "s", notes: "s" };
        // newEvent.title = "";

        // this.events.push(newEvent);
        // this.refresh.next(true);
    }

    changeImage(path) {
        var base_url = `${isDevMode() && env.basePageUrl || envProd.basePageUrl}`;
        var background_val = 'url(./assets/images/courses/athlete_default.jpg)';

        if (path.length > 5 && path !== null) {
            background_val = 'url(' + base_url + path + ')';
        }

        return background_val;
    }

    getLessonContent(event) {
        let szDate  = event.start;
        let szStart = event.start_time;
        let szEnd   = event.end_time;
        let szTitle = event.title;

        let arrStart = szStart.split(':');
        let arrEnd = szEnd.split(':');

        let st = new Date(szDate);
        return this.datePipe.transform(st, 'yyyy-MM-dd') + ' - ' + arrStart[0] +'h' + arrStart[1] +'m' + ' ~ ' + arrEnd[0] + 'h' + arrEnd[1] + 'm' + ' - `' + szTitle + '`';
    }

    deleteSchedule(event) {
        let status = this._calendarService.status;
        if( status !=='0' )
            return;
            
        this.deleteEvent(event);
    }

    getPeriods(szDate) {
        if( szDate == null || szDate.length < 1)
            return '';

        var retDates = "";
        var arrDates = szDate.split(',');
        for (let i = 0; i < arrDates.length; i++) {
            var dates = arrDates[i].split('-');
            retDates += (dates[1] + "/" + dates[2] + ",");
        }

        return retDates.substring(0, retDates.length - 1);
    }

    goBack()
    {
      this._location.back();
      // var url = '/apps/school/school/'+ this.globalVars.g_nSchoolID;
      // this.router.navigate([url]);
    }
}