import { environment as env } from '../../../../environments/environment';
import { environment as envProd } from '../../../../environments/environment.prod';
import { Injectable, isDevMode } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { ActivatedRouteSnapshot, Resolve, RouterStateSnapshot } from '@angular/router';
import { BehaviorSubject, Observable } from 'rxjs';
import { LocalsessionService } from '../../../services/localsession.service';

import { throwError as observableThrowError } from 'rxjs';
import { catchError, map } from 'rxjs/operators';

@Injectable()
export class SCoursesService implements Resolve<any>
{
    // localhost/api/school/personal
    //localhost/api/course/courses
    public SCHOOL_COURSELIST_URL = `${isDevMode() && env.baseUrl || envProd.baseUrl}school/course/list`;
    
    public QUARTER_COURSELIST_URL = `${isDevMode() && env.baseUrl || envProd.baseUrl}course/quarters`;
    public COURSELIST_URL = `${isDevMode() && env.baseUrl || envProd.baseUrl}course/courses`;
    
    public QUARTER_LEVELLIST_URL = `${isDevMode() && env.baseUrl || envProd.baseUrl}course/levels`;
    public SCHOOL_COURSE_CREATE_URL = `${isDevMode() && env.baseUrl || envProd.baseUrl}school/course/create`;
    public SCHOOL_PERSONAL_LIST_URL = `${isDevMode() && env.baseUrl || envProd.baseUrl}school/personal`;

    public ACTIVATE_SCHOOL_COURSE_URL = `${isDevMode() && env.baseUrl || envProd.baseUrl}school/course/activeupdate/`;

    onCategoriesChanged: BehaviorSubject<any>;
    onLevelChanged: BehaviorSubject<any>;
    onCoursesChanged: BehaviorSubject<any>;
    onPTListChanged: BehaviorSubject<any>;

    status: any;
    /**
     * Constructor
     *
     * @param {HttpClient} _httpClient
     */
    constructor(
        private _httpClient: HttpClient,
        private _localSession: LocalsessionService
    ) {
        // Set the defaults
        this.onCategoriesChanged = new BehaviorSubject({});
        this.onLevelChanged = new BehaviorSubject({});
        this.onCoursesChanged = new BehaviorSubject({});
        this.onPTListChanged = new BehaviorSubject({});
    }

    // -----------------------------------------------------------------------------------------------------
    // @ Public methods
    // -----------------------------------------------------------------------------------------------------

    /**
     * Resolver
     *
     * @param {ActivatedRouteSnapshot} route
     * @param {RouterStateSnapshot} state
     * @returns {Observable<any> | Promise<any> | any}
     */
    resolve(route: ActivatedRouteSnapshot, state: RouterStateSnapshot): Observable<any> | Promise<any> | any {
        this.status = route.params;

        return new Promise((resolve, reject) => {

            Promise.all([
                this.getCourses(),
                this.getCategories(),
                this.getPersonalTrainerListOfMySchool(),
            ]).then(
                () => {
                    resolve();
                },
                reject
            );
        });
    }

    /**
     * Get categories
     *
     * @returns {Promise<any>}
     */
    getCategories(): Promise<any> {
        let data: any = {};
        data['user_id'] = this._localSession.retrieveUserID();
        data['token'] = this._localSession.retrieveToken();

        return new Promise((resolve, reject) => {
            this._httpClient.post(this.COURSELIST_URL, data)
                .subscribe((response: any) => {
                    this.onCategoriesChanged.next(response);
                    resolve(response);
                }, reject);
        });
    }

    getLevels(course_id): Promise<any> {
        let data: any = {};
        data['user_id'] = this._localSession.retrieveUserID();
        data['token'] = this._localSession.retrieveToken();
        data['course_id'] = course_id;

        return new Promise((resolve, reject) => {
            this._httpClient.post(this.QUARTER_LEVELLIST_URL, data)
                .subscribe((response: any) => {
                    this.onLevelChanged.next(response);
                    resolve(response);
                }, reject);
        });
    }

    /**
     * Get courses
     *
     * @returns {Promise<any>}
     */

    createSchoolCourse(form: any): Promise<any> {
        let data: any = {};
        data['user_id'] = this._localSession.retrieveUserID();
        data['token'] = this._localSession.retrieveToken();
        data['school_id'] = this._localSession.retrieveSchoolID();
        data['course_id'] = form.course_id;
        data['level_id'] = form.level;
        data['course_name'] = form.title;
        data['course_desc'] = form.desc;
        data['course_dates'] = '[]';
        data['course_days'] = 15;
        data['course_seats'] = form.seat;
        data['price'] = form.price;
        data['trainer_id'] = 0;

        return new Promise((resolve, reject) => {
            this._httpClient.post(this.SCHOOL_COURSE_CREATE_URL, data)
                .subscribe((response: any) => {
                    resolve(response);
                    return this.getCourses();
                }, reject);
        });
    }

    actiavteSchoolCourse(data, id) {
        return this._httpClient.post(this.ACTIVATE_SCHOOL_COURSE_URL + id, data).pipe(
            map(response => response),
            catchError((error: Response) => observableThrowError(error)));
    }

    closeSchoolCourse(data, id) {
        return this._httpClient.post(this.ACTIVATE_SCHOOL_COURSE_URL + id, data).pipe(
            map(response => response),
            catchError((error: Response) => observableThrowError(error)));
    }

    getCourses(): Promise<any> {
        let data: any = {};
        data['user_id'] = this._localSession.retrieveUserID();
        data['token'] = this._localSession.retrieveToken();
        data['school_id'] = this._localSession.retrieveSchoolID();
        data['status'] = this.status.id;

        return new Promise((resolve, reject) => {
            this._httpClient.post(this.SCHOOL_COURSELIST_URL, data)
                .subscribe((response: any) => {
                    this.onCoursesChanged.next(response);
                    resolve(response);
                }, reject);
        });
    }

    getPersonalTrainerListOfMySchool(): Promise<any> {
        let data: any = {};
        data['user_id'] = this._localSession.retrieveUserID();
        data['token'] = this._localSession.retrieveToken();
        data['school_id'] = this._localSession.retrieveSchoolID();
        data['status'] = '1';

        return new Promise((resolve, reject) => {
            this._httpClient.post(this.SCHOOL_PERSONAL_LIST_URL, data)
                .subscribe((response: any) => {
                    this.onPTListChanged.next(response);
                    resolve(response);
                }, reject);
        });
    }
}
