import { environment as env } from '../../../../../environments/environment';
import { environment as envProd } from '../../../../../environments/environment.prod';
import { HttpClient } from '@angular/common/http';
import { Injectable, isDevMode } from '@angular/core';
import { LocalsessionService } from '../../../../services/localsession.service';
import { ActivatedRouteSnapshot, Resolve, RouterStateSnapshot } from '@angular/router';
import { BehaviorSubject, Observable } from 'rxjs';

@Injectable({
  providedIn: 'root'
})

export class LevelsService implements Resolve<any> {
  // localhost/api/course/levels
  public LEVEL_LIST_URL = `${isDevMode() && env.baseUrl || envProd.baseUrl}course/levels`;
  public ADD_LEVEL_URL = `${isDevMode() && env.baseUrl || envProd.baseUrl}course/addlevel`;
  public UPDATE_LEVEL_URL = `${isDevMode() && env.baseUrl || envProd.baseUrl}course/updatelevel`;
  public DELETE_LEVEL_URL = `${isDevMode() && env.baseUrl || envProd.baseUrl}course/deletelevel`;

  onLevelsChanged: BehaviorSubject<any>;
  course_id: string;
  course_name: string;

  constructor(private httpClient: HttpClient, private _localSession: LocalsessionService) {
    this.onLevelsChanged = new BehaviorSubject({});
  }

  resolve(route: ActivatedRouteSnapshot, state: RouterStateSnapshot): Observable<any> | Promise<any> | any {
    this.course_id = route.params.id;
    this.course_name = route.params.name;

    return new Promise((resolve, reject) => {
      Promise.all([
        this.getLevels(),
      ]).then(
        () => {
          resolve();
        },
        reject
      );
    });
  }

  getLevels(): Promise<any> {
    let data: any = {};
    data['token'] = this._localSession.retrieveToken();
    data['user_id'] = this._localSession.retrieveUserID();
    data['course_id'] = this.course_id;

    return new Promise((resolve, reject) => {
      this.httpClient.post(this.LEVEL_LIST_URL, data)
        .subscribe((response: any) => {
          this.onLevelsChanged.next(response);
          resolve(response);
        }, reject);
    });
  }

  addLevels(level): Promise<any> {
    let data: any = {};
    data['token'] = this._localSession.retrieveToken();
    data['user_id'] = this._localSession.retrieveUserID();
    data['course_id'] = this.course_id;
    data['level_name'] = level.level_name;
    data['level_content'] = level.level_content;

    return new Promise((resolve, reject) => {
      this.httpClient.post(this.ADD_LEVEL_URL, data)
        .subscribe((response: any) => {
          this.getLevels();
          resolve(response);
        }, reject);
    });
  }

  updateLevels(level): Promise<any> {
    let data: any = {};
    data['token'] = this._localSession.retrieveToken();
    data['user_id'] = this._localSession.retrieveUserID();
    data['level_id'] = level.id;
    data['level_name'] = level.level_name;
    data['level_content'] = level.level_content;

    return new Promise((resolve, reject) => {
      this.httpClient.post(this.UPDATE_LEVEL_URL, data)
        .subscribe((response: any) => {
          this.getLevels();
          resolve(response);
        }, reject);
    });
  }

  deleteLevels(level_id): Promise<any> {
    let data: any = {};
    data['token'] = this._localSession.retrieveToken();
    data['user_id'] = this._localSession.retrieveUserID();
    data['level_id'] = level_id;

    return new Promise((resolve, reject) => {
      this.httpClient.post(this.DELETE_LEVEL_URL, data)
        .subscribe((response: any) => {
          this.getLevels();
          resolve(response);
        }, reject);
    });
  }

}
