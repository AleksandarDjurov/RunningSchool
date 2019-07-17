import { environment as env } from '../../../../environments/environment';
import { environment as envProd } from '../../../../environments/environment.prod';
import { Injectable, isDevMode } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { ActivatedRouteSnapshot, Resolve, RouterStateSnapshot } from '@angular/router';
import { Observable, Subject } from 'rxjs';
import { LocalsessionService } from '../../../services/localsession.service';
import { DatePipe } from '@angular/common';

export interface Lesson {
    lesson_name : string;
    lesson_desc : string;
    lesson_date : string;
    start_time : string;
    end_time : string;
}

@Injectable()
export class CalendarService implements Resolve<any>
{
    // localhost/api/school/course/list
    public SCHOOL_COURSEINFO_URL = `${isDevMode() && env.baseUrl || envProd.baseUrl}school/course/list`;
    public SCHOOL_COURSEUPDATE_URL = `${isDevMode() && env.baseUrl || envProd.baseUrl}school/course/update/`;

    course : any = {};
    events: any[]=[];
    onEventsUpdated: Subject<any>;

    status : string;
    id : string;

    /**
     * Constructor
     *
     * @param {HttpClient} _httpClient
     */
    constructor(
        private _httpClient: HttpClient,
        private _localSession : LocalsessionService,
        private datePipe: DatePipe
    )
    {
        // Set the defaults
        this.onEventsUpdated = new Subject();
    }

    /**
     * Resolver
     *
     * @param {ActivatedRouteSnapshot} route
     * @param {RouterStateSnapshot} state
     * @returns {Observable<any> | Promise<any> | any}
     */
    resolve(route: ActivatedRouteSnapshot, state: RouterStateSnapshot): Observable<any> | Promise<any> | any
    {
        this.id = route.params.id;
        this.status = route.params.mode;
        
        return new Promise((resolve, reject) => {
            Promise.all([
                this.getCourseContent()
            ]).then(
                ([events]: [any]) => {
                    resolve();
                },
                reject
            );
        });
    }

    lessonContent : Lesson[] = [];
    /**
     * Get events
     *
     * @returns {Promise<any>}
     */
    getCourseContent(): Promise<any>
    {
        let data : any = {};
        data['user_id'] = this._localSession.retrieveUserID();
        data['token']   = this._localSession.retrieveToken();
        data['school_id']   = this._localSession.retrieveSchoolID();
        data['status']      = this.status;
        data['id']          = this.id;

        return new Promise((resolve, reject) => {
            this._httpClient.post(this.SCHOOL_COURSEINFO_URL, data)
                .subscribe((response: any) => {
                    this.events = [];

                    let dates = '';
                    let data = response[0];
                    this.lessonContent = data.course_dates;
                    this.lessonContent = JSON.parse(data.course_dates);

                    for( let i = 0; i < this.lessonContent.length; i ++ )
                    {
                        let st = new Date(this.lessonContent[i].lesson_date);
                        let ed = new Date(this.lessonContent[i].lesson_date);
                        let event : any = {
                            start:st.toISOString(),
                            end:ed.toISOString(),
                            start_time:this.lessonContent[i].start_time,
                            end_time:this.lessonContent[i].end_time,
                            desc:this.lessonContent[i].lesson_desc,
                            title:this.lessonContent[i].lesson_name,
                            allDay:false,
                            color:{primary  : '#F44336', secondary: '#FFCDD2'},
                            resizable:{ beforeStart: true, afterEnd : true },
                            draggable:true,
                            meta:{ location: 'aa', notes   : 'aa' }
                        };

                        dates += (this.lessonContent[i].lesson_date + ",");
                        this.events.push(event);
                    }

                    if( dates.length > 1 )
                        dates = dates.substring(0, dates.length-1);

                    this.course.level_id = data.level_id;
                    this.course.course_days = data.course_days;
                    this.course.course_name = data.course_name;
                    this.course.course_desc = data.course_desc;
                    this.course.course_dates = dates;
                    this.course.trainer_first_name = data.trainer_first_name;
                    this.course.trainer_last_name = data.trainer_last_name;
                    this.course.price = data.price;
                    this.course.course_seats = data.course_seats;

                    this.onEventsUpdated.next(this.events);
                    resolve(this.events);
                }, reject);
        });
    }

    updateEvents(events): Promise<any>
    {
        let arrDates:string[] = [];
        let szDate = '';
        let linkDate : any = {};
        let lessonData = '';

        for( let i = 0; i < events.length; i ++ )
        {
            let item = events[i];
            let st = new Date(item.start);
            szDate = this.datePipe.transform(st, 'yyyy-MM-dd');

            linkDate = {"lesson_name" : item.title, "lesson_desc":item.desc, "lesson_date":szDate, "start_time":item.start_time, "end_time":item.end_time};
            lessonData += (JSON.stringify(linkDate) + ',');

        }

        if( lessonData.length > 0 )
            lessonData = lessonData.substring(0, lessonData.length-1)

        lessonData = '[' + lessonData + ']';

        let data : any = {};
        data['user_id']     = this._localSession.retrieveUserID();
        data['token']       = this._localSession.retrieveToken();
        data['school_id']   = this._localSession.retrieveSchoolID();
        data['course_dates'] = lessonData;

        return new Promise((resolve, reject) => {
            this._httpClient.post(this.SCHOOL_COURSEUPDATE_URL+this.id, data)
                .subscribe((response: any) => {
                    this.getCourseContent();
                }, reject);
        });
    }
}
