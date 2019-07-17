import { environment as env } from '../../../environments/environment';
import { environment as envProd } from '../../../environments/environment.prod';
import { Injectable, isDevMode } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { ActivatedRouteSnapshot, Resolve, RouterStateSnapshot } from '@angular/router';
import { BehaviorSubject, Observable } from 'rxjs';
import { LocalsessionService } from '../../services/localsession.service';

@Injectable()
export class PCoursesService implements Resolve<any>
{
    public SCHOOL_COURSELIST_URL = `${isDevMode() && env.baseUrl || envProd.baseUrl}school/course/list`;
    public COURSELIST_URL = `${isDevMode() && env.baseUrl || envProd.baseUrl}course/courses`;
    public QUARTER_LEVELLIST_URL = `${isDevMode() && env.baseUrl || envProd.baseUrl}course/levels`;

    onCategoriesChanged: BehaviorSubject<any>;
    onLevelChanged: BehaviorSubject<any>;
    onCoursesChanged: BehaviorSubject<any>;

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
    }

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

    getCourses(): Promise<any> {
        let data: any = {};
        data['user_id'] = this._localSession.retrieveUserID();
        data['token'] = this._localSession.retrieveToken();
        data['school_id'] = this._localSession.retrieveSchoolID();
        data['status'] = this.status.id;
        data['trainer_id'] = this._localSession.retrieveUserID();

        return new Promise((resolve, reject) => {
            this._httpClient.post(this.SCHOOL_COURSELIST_URL, data)
                .subscribe((response: any) => {
                    this.onCoursesChanged.next(response);
                    resolve(response);
                }, reject);
        });
    }
}
