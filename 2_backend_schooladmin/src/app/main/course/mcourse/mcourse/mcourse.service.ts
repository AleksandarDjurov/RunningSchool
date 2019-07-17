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

export class MainCourseService implements Resolve<any> {
  public MAIN_COURSE_LIST_URL = `${isDevMode() && env.baseUrl || envProd.baseUrl}course/courses`;
  public ADD_COURSE_URL = `${isDevMode() && env.baseUrl || envProd.baseUrl}course/addcourse`;
  public UPDATE_COURSE_URL = `${isDevMode() && env.baseUrl || envProd.baseUrl}course/updatecourse`;
  public DELETE_COURSE_URL = `${isDevMode() && env.baseUrl || envProd.baseUrl}course/deletecourse`;
  
  onCoursesChanged: BehaviorSubject<any>;
  
  constructor(private httpClient: HttpClient, private _localSession: LocalsessionService) {
    this.onCoursesChanged = new BehaviorSubject({});
  }
  
  resolve(route: ActivatedRouteSnapshot, state: RouterStateSnapshot): Observable<any> | Promise<any> | any
  {
      return new Promise((resolve, reject) => {
          Promise.all([
              this.getCourses(),
              // this.getLevels(),
          ]).then(
              () => {
                  resolve();
              },
              reject
          );
      });
  }

  getCourses(): Promise<any> {
    let data: any = {};
    data['token'] = this._localSession.retrieveToken();
    data['user_id'] = this._localSession.retrieveUserID();
    data['role'] = '0';

    return new Promise((resolve, reject) => {
      this.httpClient.post(this.MAIN_COURSE_LIST_URL, data)
        .subscribe((response: any) => {
          this.onCoursesChanged.next(response);
          resolve(response);
        }, reject);
    });
  }

}
