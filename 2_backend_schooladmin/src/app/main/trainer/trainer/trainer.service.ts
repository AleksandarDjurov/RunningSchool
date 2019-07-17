import { Injectable, isDevMode } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { ActivatedRouteSnapshot, Resolve, RouterStateSnapshot } from '@angular/router';
import { LocalsessionService } from '../../../services/localsession.service';

import { environment as env } from '../../../../environments/environment';
import { environment as envProd } from '../../../../environments/environment.prod';
import { GlobalService } from '../../../services/global.service';
import { BehaviorSubject, Observable, Subject } from 'rxjs';

import { throwError as observableThrowError } from 'rxjs';
import { catchError, map } from 'rxjs/operators';

@Injectable()
export class PersonalTrainerService implements Resolve<any>
{
    routeParams: any;
    personaltrainer: any;
    onTrainerChanged: BehaviorSubject<any>;

    TRAINER_URL_BYID = `${isDevMode() && env.baseUrl || envProd.baseUrl}school/personal/show/`;
    TRAINER_UPDATE_BYID = `${isDevMode() && env.baseUrl || envProd.baseUrl}school/personal/update/`;
    // localhost/api/school/personal/show/68?token=MTU2MTAzODk4OTZyTUxhbURtSURVNWYxQ3RwdjJ4&user_id=1
    
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
        this.onTrainerChanged = new BehaviorSubject({});
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
        this.globalVars.g_nTrainerID = this.routeParams;

        return new Promise((resolve, reject) => {

            Promise.all([
                this.getTrainer()
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

    // localhost/api/school/personal/show/68?token=MTU2MTAzODk4OTZyTUxhbURtSURVNWYxQ3RwdjJ4&user_id=1
    getTrainer(): Promise<any> {
        let data: any = {};
        data['token'] = this._localSession.retrieveToken();
        data['user_id'] = this._localSession.retrieveUserID();
        // data['status'] = '' + this.globalVars.g_nTrainerID;

        return new Promise((resolve, reject) => {
            this._httpClient.post(this.TRAINER_URL_BYID + this.routeParams.id, data)
                .subscribe((response: any) => {
                    this.personaltrainer = response;

                    this.onTrainerChanged.next(this.personaltrainer);
                    resolve(response);
                }, reject);
        });
    }

    approvePersonal(trainer_id, school_id) 
    {
        let data : any = {};
        data['token']   = this._localSession.retrieveToken();
        data['user_id'] = this._localSession.retrieveUserID();
        data['school_id'] = school_id;
        data['status'] = '1';

        return this.updatePersonal(data, trainer_id);
    }

    rejectPersonal(trainer_id)
    {
        let data : any = {};
        data['token']   = this._localSession.retrieveToken();
        data['user_id'] = this._localSession.retrieveUserID();
        data['status'] = '2';

        return this.updatePersonal(data, trainer_id);
    }

    updatePersonal( data, trainer_id )
    {
        return this._httpClient.post(this.TRAINER_UPDATE_BYID+trainer_id, data ).pipe(
            map(response => response),
            catchError((error: Response) => observableThrowError(error)));
    }
}
