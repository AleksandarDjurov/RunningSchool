import { environment as env } from '../../../../../environments/environment';
import { environment as envProd } from '../../../../../environments/environment.prod';
import { HttpClient } from '@angular/common/http';
import { Injectable, isDevMode } from '@angular/core';
import { ActivatedRouteSnapshot, Resolve, RouterStateSnapshot } from '@angular/router';
import { BehaviorSubject, Observable, Subject } from 'rxjs';
import { LocalsessionService } from '../../../../services/localsession.service';

@Injectable({
  providedIn: 'root'
})

export class AttendService implements Resolve<any>
{
  public ATTEND_LIST_URL = `${isDevMode() && env.baseUrl || envProd.baseUrl}personal/course/athlete/attend/list`;
  public ATTEND_SUMMARYLIST_URL = `${isDevMode() && env.baseUrl || envProd.baseUrl}personal/course/athlete/attend/summarylist`;
  public ADD_ATTEND_URL = `${isDevMode() && env.baseUrl || envProd.baseUrl}personal/course/athlete/add_attendance`;
  public GIVE_FEEDBACK_URL = `${isDevMode() && env.baseUrl || envProd.baseUrl}personal/course/athlete/feedback/`;
  // Route::post('/personal/course/athlete/feedback/{attend_id}', 'ApiPersonalCourseController@giveFeedbackToAthlete');

  // Route::post('/personal/course/athlete/attend/summarylist', 'ApiPersonalCourseController@getSummaryAttendList');
    
  onAttendListChanged: BehaviorSubject<any>;
  onAttendSummaryListChanged: BehaviorSubject<any>;

  athlete_id: string;
  course_id: string;
  mode: string;

  attendList: any[] = [];
  attendSummaryList: any[] = [];

  constructor(
    private _httpClient: HttpClient,
    private _localSession: LocalsessionService) {
    this.onAttendListChanged = new BehaviorSubject({});
    this.onAttendSummaryListChanged = new BehaviorSubject({});
  }

  resolve(route: ActivatedRouteSnapshot, state: RouterStateSnapshot): Observable<any> | Promise<any> | any {
    this.athlete_id = route.params.aid;
    this.course_id = route.params.cid;
    this.mode = route.params.mode;

    if( this.mode === '1') // attend mode
      return new Promise((resolve, reject) => { Promise.all([ this.getAttendList()]).then( () => { resolve(); }, reject ); });
    else
      return new Promise((resolve, reject) => { Promise.all([ this.getSummaryAttendList()]).then( () => { resolve();}, reject );});
  }

  getAttendList(): Promise<any> {
    let data: any = {};
    data['user_id'] = this._localSession.retrieveUserID();
    data['token'] = this._localSession.retrieveToken();
    data['athlete_id'] = this.athlete_id;
    data['course_id'] = this.course_id;

    return new Promise((resolve, reject) => {
      this._httpClient.post(this.ATTEND_LIST_URL, data)
        .subscribe((response: any) => {
          this.attendList = response;
          this.onAttendListChanged.next(response);
          resolve(response);
        }, reject);
    });
  }

  getSummaryAttendList(): Promise<any> {
    let data: any = {};
    data['user_id'] = this._localSession.retrieveUserID();
    data['token'] = this._localSession.retrieveToken();
    data['athlete_id'] = this.athlete_id;
    data['course_id'] = this.course_id;

    return new Promise((resolve, reject) => {
      this._httpClient.post(this.ATTEND_SUMMARYLIST_URL, data)
        .subscribe((response: any) => {
          this.attendSummaryList = response;
          this.onAttendSummaryListChanged.next(response);
          resolve(response);
        }, reject);
    });
  }

  // localhost/api/personal/course/athlete/add_attendance
/**
 * 	user_id : 1
    token:  MTU2MDUxMDU4N21yclNxcVpXU3liYzI1S2Q1Z3dZ
		school_course_date_id: 25
		athlete_id: 1
		attendance_status:  1= attend  2= tardy, absent = 3		
 */
  addAttend(athlete_id, course_date_id, status)
  {
    let data: any   = {};
    data['user_id'] = this._localSession.retrieveUserID();
    data['token']   = this._localSession.retrieveToken();
    data['athlete_id']    = athlete_id;
    data['course_id']     = this.course_id;
    data['attendance_status']     = status;
    data['school_course_date_id'] = course_date_id;

    // console.log('data', data);
    return new Promise((resolve, reject) => {
      this._httpClient.post(this.ADD_ATTEND_URL, data)
        .subscribe((response: any) => {
          this.getAttendList();
          resolve(response);
        }, reject);
    });
  }

  updateFeedback(attend_id, review, score )
  {
    let data: any   = {};
    data['user_id'] = this._localSession.retrieveUserID();
    data['token']   = this._localSession.retrieveToken();
    data['review'] = review;
    data['score'] = score;

    // console.log('data', data);
    return new Promise((resolve, reject) => {
      this._httpClient.post(this.GIVE_FEEDBACK_URL + attend_id, data)
        .subscribe((response: any) => {
          this.getSummaryAttendList();
          resolve(response);
        }, reject);
    });
  }

}
