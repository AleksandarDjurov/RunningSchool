import { environment as env } from '../../../../../environments/environment';
import { environment as envProd } from '../../../../../environments/environment.prod';
import { HttpClient } from '@angular/common/http';
import { Injectable, isDevMode } from '@angular/core';
import { LocalsessionService } from '../../../../services/localsession.service';
import { ActivatedRouteSnapshot, Resolve, RouterStateSnapshot } from '@angular/router';
import { BehaviorSubject, Observable } from 'rxjs';
import { Trainer } from 'app/main/trainers/trainer.model';

@Injectable({
  providedIn: 'root'
})

export class MySchoolTrainersService implements Resolve<any> {
  public TRAINERS_LIST_URL = `${isDevMode() && env.baseUrl || envProd.baseUrl}school/personal`;
  public SCHOOL_COURSELIST_URL = `${isDevMode() && env.baseUrl || envProd.baseUrl}school/course/list`;

  onAllTrainersChanged: BehaviorSubject<any>;
  onTrainersChanged: BehaviorSubject<any>;
  onCoursesChanged: BehaviorSubject<any>;

  trainers: Trainer[];
  school_id: string;
  trainer_id: string;
  mode: string;

  constructor(private httpClient: HttpClient, private _localSession: LocalsessionService) {
    this.onAllTrainersChanged = new BehaviorSubject({});
    this.onTrainersChanged = new BehaviorSubject({});
    this.onCoursesChanged = new BehaviorSubject({});
  }

  resolve(route: ActivatedRouteSnapshot, state: RouterStateSnapshot): Observable<any> | Promise<any> | any {
    this.mode = route.params.mode;
    if (this.mode === '1')
      this.school_id = route.params.sch_id;
    else if (this.mode == '2') {
      this.trainer_id = route.params.tr_id;
      this.school_id = route.params.sch_id;
    }

    if (this.mode === '1')
      return new Promise((resolve, reject) => { Promise.all([this.getTrainers(),]).then(() => { resolve(); }, reject); });
    else if (this.mode == '2')
      return new Promise((resolve, reject) => { Promise.all([this.getCourses(),]).then(() => { resolve(); }, reject); });
    else if (this.mode == '3')
      return new Promise((resolve, reject) => { Promise.all([this.getAllTrainers(),]).then(() => { resolve(); }, reject); });
  }

  getAllTrainers(): Promise<any> {
    let data: any = {};
    data['token'] = this._localSession.retrieveToken();
    data['user_id'] = this._localSession.retrieveUserID();
    data['status'] = '1';

    return new Promise((resolve, reject) => {
      this.httpClient.post(this.TRAINERS_LIST_URL, data)
        .subscribe((response: any) => {
          this.trainers = response;
          this.onAllTrainersChanged.next(this.trainers);
          resolve(this.trainers);
        }, reject);
    }
    );
  }

  getTrainers(): Promise<any> {
    let data: any = {};
    data['token'] = this._localSession.retrieveToken();
    data['user_id'] = this._localSession.retrieveUserID();
    data['school_id'] = this.school_id;
    data['status'] = '1';

    return new Promise((resolve, reject) => {
      this.httpClient.post(this.TRAINERS_LIST_URL, data)
        .subscribe((response: any) => {
          this.trainers = response;
          this.onTrainersChanged.next(this.trainers);
          resolve(this.trainers);
        }, reject);
    }
    );
  }

  getCourses(): Promise<any> {
    let data: any = {};
    data['user_id'] = this._localSession.retrieveUserID();
    data['token'] = this._localSession.retrieveToken();
    data['trainer_id'] = this.trainer_id;
    data['school_id'] = this.school_id;
    data['status'] = '5';

    return new Promise((resolve, reject) => {
      this.httpClient.post(this.SCHOOL_COURSELIST_URL, data)
        .subscribe((response: any) => {
          this.onCoursesChanged.next(response);
          resolve(response);
        }, reject);
    });
  }


}
