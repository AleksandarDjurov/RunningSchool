import { environment as env } from '../../../../environments/environment';
import { environment as envProd } from '../../../../environments/environment.prod';
import { HttpClient } from '@angular/common/http';
import { Injectable, isDevMode } from '@angular/core';
import { ActivatedRouteSnapshot, Resolve, RouterStateSnapshot } from '@angular/router';
import { BehaviorSubject, Observable, Subject } from 'rxjs';
import { LocalsessionService } from '../../../services/localsession.service';

@Injectable({
  providedIn: 'root'
})

export class AttendService implements Resolve<any>
{
  public ATTEND_LIST_URL = `${isDevMode() && env.baseUrl || envProd.baseUrl}personal/course/athlete/attend/list`;
  public ATTEND_SUMMARYLIST_URL = `${isDevMode() && env.baseUrl || envProd.baseUrl}personal/course/athlete/attend/summarylist`;
    
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


}
