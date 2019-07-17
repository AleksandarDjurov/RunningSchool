import { environment as env } from '../../../../environments/environment';
import { environment as envProd } from '../../../../environments/environment.prod';
import { HttpClient } from '@angular/common/http';
import { Injectable, isDevMode } from '@angular/core';
import { LocalsessionService } from '../../../services/localsession.service';
import { ActivatedRouteSnapshot, Resolve, RouterStateSnapshot } from '@angular/router';
import { BehaviorSubject, Observable } from 'rxjs';

export interface Lesson {
  lesson_name : string;
  lesson_desc : string;
  lesson_date : string;
  start_time : string;
  end_time : string;
}

@Injectable({
  providedIn: 'root'
})

export class SchoolCourseService implements Resolve<any> {
  public SCHOOL_COURSE_LIST_URL = `${isDevMode() && env.baseUrl || envProd.baseUrl}school/course/list`;
  
  //localhost/api/school/course/list
  onCoursesChanged: BehaviorSubject<any>;
  
  constructor(private httpClient: HttpClient, private _localSession: LocalsessionService) {
    this.onCoursesChanged = new BehaviorSubject({});
  }
  
  resolve(route: ActivatedRouteSnapshot, state: RouterStateSnapshot): Observable<any> | Promise<any> | any
  {
      return new Promise((resolve, reject) => {
          Promise.all([
              this.getCourses()]).then( () => { resolve(); }, reject );
      });
  }

  getDashDate(date) {
    if( date == null )
      return '';
    let dt = date.split('-');
    return dt[1] + '/' + dt[2];
  }

  retData :any[]= [];
  getCourses(): Promise<any> {
    let data: any = {};
    data['user_id'] = this._localSession.retrieveUserID();
    data['token'] = this._localSession.retrieveToken();
    data['school_id'] = this._localSession.retrieveSchoolID();

    return new Promise((resolve, reject) => {
      this.httpClient.post(this.SCHOOL_COURSE_LIST_URL, data)
        .subscribe((response: any) => {

          this.retData = response;
          for( let i = 0 ; i < this.retData.length; i ++ )
          {
            let lessonContent : Lesson[] = [];
            let szDate = '';
            lessonContent = JSON.parse(this.retData[i].course_dates);
            for( let j = 0 ; j < lessonContent.length; j ++ )
            {
              if( szDate === '')
                  szDate = this.getDashDate(lessonContent[j].lesson_date);
                else
                  szDate = (szDate + ', ' + this.getDashDate(lessonContent[j].lesson_date));
            }

            this.retData[i].course_dates = szDate;
          }

          this.onCoursesChanged.next(this.retData);
          resolve(this.retData);
        }, reject);
    });
  }

}
