import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { ActivatedRouteSnapshot, Resolve, RouterStateSnapshot } from '@angular/router';
import { BehaviorSubject, Observable } from 'rxjs';

@Injectable()
export class TechnManagerService implements Resolve<any>
{
    routeParams: any;
    technicalmanager: any;
    onSchoolChanged: BehaviorSubject<any>;

    /**
     * Constructor
     *
     * @param {HttpClient} _httpClient
     */
    constructor(
        private _httpClient: HttpClient
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
        return new Promise((resolve, reject) => {
            if (this.routeParams.id === 'new') {
                this.onSchoolChanged.next(false);
                resolve(false);
            }
            else {
                this._httpClient.get('api/school-techmanager/' + this.routeParams.id)
                    .subscribe((response: any) => {
                        this.technicalmanager = response;
                        this.onSchoolChanged.next(this.technicalmanager);
                        resolve(response);
                    }, reject);
            }
        });
    }

    /**
     * Save school
     *
     * @param school
     * @returns {Promise<any>}
     */
    saveProduct(technicalmanager): Promise<any> {
        return new Promise((resolve, reject) => {
            this._httpClient.post('api/school-techmanager/' + technicalmanager.id, technicalmanager)
                .subscribe((response: any) => {
                    resolve(response);
                }, reject);
        });
    }

    /**
     * Add school
     *
     * @param school
     * @returns {Promise<any>}
     */
    addProduct(technicalmanager): Promise<any> {
        return new Promise((resolve, reject) => {
            this._httpClient.post('api/school-techmanager/', technicalmanager)
                .subscribe((response: any) => {
                    resolve(response);
                }, reject);
        });
    }
}
