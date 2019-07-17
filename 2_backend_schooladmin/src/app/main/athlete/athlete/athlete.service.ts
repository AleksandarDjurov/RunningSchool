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
export class AthletePlayerService implements Resolve<any>
{
    routeParams: any;
    athleteplayer: any;
    onAthleteChanged: BehaviorSubject<any>;

    courseId : string;
    user_id : string;
    reserve_id : string;

    // localhost/api/personal/course/athlete/reservation/id

    ATHLETE_URL_BYID = `${isDevMode() && env.baseUrl || envProd.baseUrl}school/athlete/show/`;
    UPDATE_RESERVE_BYID = `${isDevMode() && env.baseUrl || envProd.baseUrl}personal/course/athlete/reservation/`;

    constructor(
        private _httpClient: HttpClient,
        private globalVars: GlobalService,
        private _localSession: LocalsessionService
    ) {
        // Set the defaults
        this.onAthleteChanged = new BehaviorSubject({});
    }

    resolve(route: ActivatedRouteSnapshot, state: RouterStateSnapshot): Observable<any> | Promise<any> | any {
        this.routeParams = route.params;

        this.courseId = this.routeParams.cid;
        this.user_id = this.routeParams.id;
        this.reserve_id = this.routeParams.rid;
        
        return new Promise((resolve, reject) => {
            Promise.all([ this.getAthlete() ]).then( () => { resolve(); }, reject );
        });
    }

    // localhost/api/school/personal/show/68?token=MTU2MTAzODk4OTZyTUxhbURtSURVNWYxQ3RwdjJ4&user_id=1
    getAthlete(): Promise<any> {
        let data: any = {};
        data['token'] = this._localSession.retrieveToken();
        data['user_id'] = this._localSession.retrieveUserID();
        // data['status'] = '' + this.globalVars.g_nTrainerID;

        return new Promise((resolve, reject) => {
            this._httpClient.post(this.ATHLETE_URL_BYID + this.routeParams.id, data)
                .subscribe((response: any) => {
                    this.athleteplayer = response;
                    this.onAthleteChanged.next(this.athleteplayer);
                    resolve(response);
                }, reject);
        });
    }

    approveReservation(reserve_id)
    {
        let data : any = {};
        data['token']   = this._localSession.retrieveToken();
        data['user_id'] = this._localSession.retrieveUserID();
        data['status'] = '1';

        return this.processAction(data, reserve_id);
    }

    rejectReservation( reserve_id )
    {
        let data : any = {};
        data['token']   = this._localSession.retrieveToken();
        data['user_id'] = this._localSession.retrieveUserID();
        data['status'] = '0';

        return this.processAction(data, reserve_id);
    }

    processAction( data, reserve_id )
    {
        return this._httpClient.post(this.UPDATE_RESERVE_BYID+reserve_id, data ).pipe(
            map(response => response),
            catchError((error: Response) => observableThrowError(error)));
    }
}
