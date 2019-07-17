import { Injectable, isDevMode } from '@angular/core';
import { ActivatedRouteSnapshot, Resolve, RouterStateSnapshot } from '@angular/router';
import { HttpClient } from '@angular/common/http';
import { BehaviorSubject, Observable } from 'rxjs';
import { GlobalService } from '../../../services/global.service';
import { LocalsessionService } from '../../../services/localsession.service';

import { environment as env } from '../../../../environments/environment';
import { environment as envProd } from '../../../../environments/environment.prod';
import { catchError, map } from 'rxjs/operators';
import { throwError as observableThrowError } from 'rxjs';

@Injectable()
export class SchoolListService implements Resolve<any>
{
    public SCHOOLS_LIST_URL = `${isDevMode() && env.baseUrl || envProd.baseUrl}school`;
    public SCHOOLNAME_LIST_URL = `${isDevMode() && env.baseUrl || envProd.baseUrl}school/getname`;

    public routeParams: any;
    public schools: any[];

    onSchoolsChanged: BehaviorSubject<any>;

    /**
     * Constructor
     *
     * @param {HttpClient} _httpClient
     */
    constructor(
        private _httpClient: HttpClient,
        private globalVars: GlobalService,
        private _localSession: LocalsessionService
    ) {
        // Set the defaults
        this.onSchoolsChanged = new BehaviorSubject({});
    }

    /**
     * Resolver
     *
     * @param {ActivatedRouteSnapshot} route
     * @param {RouterStateSnapshot} state
     * @returns {Observable<any> | Promise<any> | any}
     */
    resolve(route: ActivatedRouteSnapshot, state: RouterStateSnapshot): Observable<any> | Promise<any> | any {

        this.routeParams = route.params;
        this.globalVars.g_nDisplySchoolMode = this.routeParams.mode;

        return new Promise((resolve, reject) => {
            Promise.all([
                this.getSchools()
            ]).then(
                () => {
                    resolve();
                },
                reject
            );
        });
    }

    /**
     * Get schools
     *
     * @returns {Promise<any>}
     */

    getSchools(): Promise<any> {
        let data: any = {};
        data['token'] = this._localSession.retrieveToken();
        data['user_id'] = this._localSession.retrieveUserID();
        data['status'] = '' + this.globalVars.g_nDisplySchoolMode;

        return new Promise((resolve, reject) => {
            this._httpClient.post(this.SCHOOLS_LIST_URL, data)
                .subscribe((response: any) => {
                    this.schools = response;
                    this.onSchoolsChanged.next(this.schools);
                    resolve(response);
                }, reject);
        });
    }

    reloadSchools(status) {
        this.globalVars.g_nDisplySchoolMode = status;

        let data: any = {};
        data['token'] = this._localSession.retrieveToken();
        data['user_id'] = this._localSession.retrieveUserID();
        data['status'] = '' + this.globalVars.g_nDisplySchoolMode;

        this._httpClient.post(this.SCHOOLS_LIST_URL, data)
            .subscribe((response: any) => {
                this.schools = response;
                this.onSchoolsChanged.next(this.schools);
            });
    }

    getSchoolNames() {
        let data: any = {};
        data['token'] = this._localSession.retrieveToken();
        data['user_id'] = this._localSession.retrieveUserID();

        return this._httpClient.post(this.SCHOOLNAME_LIST_URL, data).pipe(
            map(response => response),
            catchError((error: Response) => observableThrowError(error)));
    }
}
