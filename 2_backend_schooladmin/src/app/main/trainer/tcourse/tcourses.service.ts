import { environment as env } from '../../../../environments/environment';
import { environment as envProd } from '../../../../environments/environment.prod';
import { Injectable, isDevMode } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { ActivatedRouteSnapshot, Resolve, RouterStateSnapshot } from '@angular/router';
import { BehaviorSubject, Observable } from 'rxjs';
import { LocalsessionService } from '../../../services/localsession.service';

@Injectable()
export class TCoursesService implements Resolve<any>
{
    public SCHOOL_COURSELIST_URL = `${isDevMode() && env.baseUrl || envProd.baseUrl}school/course/list`;

    onTCoursesChanged: BehaviorSubject<any>;
    trainer_id: any;
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
        this.onTCoursesChanged = new BehaviorSubject({});
    }

    /**
     * Resolver
     *
     * @param {ActivatedRouteSnapshot} route
     * @param {RouterStateSnapshot} state
     * @returns {Observable<any> | Promise<any> | any}
     */
    resolve(route: ActivatedRouteSnapshot, state: RouterStateSnapshot): Observable<any> | Promise<any> | any {
        this.trainer_id = route.params;

        return new Promise((resolve, reject) => { 
            Promise.all([ this.getCourses(), ]).then( () => { resolve(); }, reject ); 
        });
    }

    getCourses(): Promise<any> {
        let data: any = {};
        data['user_id'] = this._localSession.retrieveUserID();
        data['token'] = this._localSession.retrieveToken();
        data['school_id'] = this._localSession.retrieveSchoolID();
        data['trainer_id'] = this.trainer_id.id;

        return new Promise((resolve, reject) => {
            this._httpClient.post(this.SCHOOL_COURSELIST_URL, data)
                .subscribe((response: any) => {
                    this.onTCoursesChanged.next(response);
                    resolve(response);
                }, reject);
        });
    }
}
