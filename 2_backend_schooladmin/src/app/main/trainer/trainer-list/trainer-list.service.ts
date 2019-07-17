import { Injectable, isDevMode } from '@angular/core';
import { ActivatedRouteSnapshot, Resolve, RouterStateSnapshot } from '@angular/router';
import { HttpClient } from '@angular/common/http';
import { BehaviorSubject, Observable } from 'rxjs';
import { GlobalService } from '../../../services/global.service';
import { LocalsessionService } from '../../../services/localsession.service';

import { environment as env } from '../../../../environments/environment';
import { environment as envProd } from '../../../../environments/environment.prod';

@Injectable()
export class TrainerListService implements Resolve<any>
{
    public TRAINER_LIST_URL = `${isDevMode() && env.baseUrl || envProd.baseUrl}school/personal`;

    routeParams: any;
    trainers: any[];
    onTrainersChanged: BehaviorSubject<any>;

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
        this.onTrainersChanged = new BehaviorSubject({});
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
                this.getPendingTrainers()
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

    getPendingTrainers(): Promise<any> {
        let data: any = {};
        data['token'] = this._localSession.retrieveToken();
        data['user_id'] = this._localSession.retrieveUserID();
        data['school_id'] = this._localSession.retrieveSchoolID();
        data['status'] = '1';
        
        return new Promise((resolve, reject) => {
            this._httpClient.post(this.TRAINER_LIST_URL, data)
                .subscribe((response: any) => {
                    this.trainers = response;
                    resolve(response);
                }, reject);
        }
        );
    }
    // reloadSchools(status) {
    //     this.globalVars.g_nDisplySchoolMode = status;

    //     let data: any = {};
    //     data['token'] = this._localSession.retrieveToken();
    //     data['user_id'] = this._localSession.retrieveUserID();
    //     data['status'] = '' + this.globalVars.g_nDisplySchoolMode;

    //     this._httpClient.post(this.SCHOOLS_LIST_URL, data)
    //         .subscribe((response: any) => {
    //             this.schools = response;
    //             this.onSchoolsChanged.next(this.schools);
    //         });
    // }
}
