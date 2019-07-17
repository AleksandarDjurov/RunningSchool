import { Injectable, isDevMode } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { ActivatedRouteSnapshot, Resolve, RouterStateSnapshot } from '@angular/router';
import { BehaviorSubject, Observable } from 'rxjs';
import { GlobalService } from '../../../services/global.service';

import { LocalsessionService } from '../../../services/localsession.service';

import { environment as env } from '../../../../environments/environment';
import { environment as envProd } from '../../../../environments/environment.prod';
import { SchoolData } from './school.model';
import { stat } from 'fs';

import { throwError as observableThrowError } from 'rxjs';
import { catchError, map } from 'rxjs/operators';

@Injectable()
export class SchoolService implements Resolve<any>
{
    public SCHOOL_PER_ID_URL = `${isDevMode() && env.baseUrl || envProd.baseUrl}school/show/`;
    public UPDATE_SCHOOL_BY_ID_URL = `${isDevMode() && env.baseUrl || envProd.baseUrl}school/update/`;
    public UPDATE_TECHNICAL_BY_ID_URL = `${isDevMode() && env.baseUrl || envProd.baseUrl}school/technical/update/`;

    routeParams: any;
    retData: SchoolData;
    onSchoolChanged: BehaviorSubject<any>;

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
        this.onSchoolChanged = new BehaviorSubject({});
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
        this.globalVars.g_nSchoolID = this.routeParams.id;

        return new Promise((resolve, reject) => {

            Promise.all([
                this.getSchool()
            ]).then(
                () => {
                    resolve();
                },
                reject
            );
        });
    }

    /**
     * Get school
     *
     * @returns {Promise<any>}
     */
    getSchool(): Promise<any> {
        let data: any = {};
        data['token'] = this._localSession.retrieveToken();
        data['user_id'] = this._localSession.retrieveUserID();

        return new Promise((resolve, reject) => {
            this._httpClient.post(this.SCHOOL_PER_ID_URL + this.routeParams.id, data)
                .subscribe((response: any) => {
                    this.retData = response;
                    this.onSchoolChanged.next(this.retData);
                    resolve(response);
                }, reject);
        });
    }

    /**
     * Update school
     *
     */
    updateSchool(status) {
        let data: any = {};
        data['token'] = this._localSession.retrieveToken();
        data['user_id'] = this._localSession.retrieveUserID();
        data['status'] = status;
        data['activate'] = status;

        return this._httpClient.post(this.UPDATE_SCHOOL_BY_ID_URL + this.routeParams.id, data).pipe(
            map(response => response),
            catchError((error: Response) => observableThrowError(error)));
    }
    
    /**
     * Update school
     *
     */
    updateTechnicalManager(user_id, status) {
        let data: any = {};
        data['token'] = this._localSession.retrieveToken();
        data['user_id'] = this._localSession.retrieveUserID();
        data['status'] = status;
        data['activate'] = status;

        return this._httpClient.post(this.UPDATE_TECHNICAL_BY_ID_URL + user_id, data).pipe(
            map(response => response),
            catchError((error: Response) => observableThrowError(error)));
    }
}
