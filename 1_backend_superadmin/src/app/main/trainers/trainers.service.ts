import { Injectable, isDevMode } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { ActivatedRouteSnapshot, Resolve, RouterStateSnapshot } from '@angular/router';
import { BehaviorSubject, Observable, Subject } from 'rxjs';

import { FuseUtils } from '@fuse/utils';
import { Trainer } from 'app/main/trainers/trainer.model';
import { GlobalService } from '../../services/global.service';

import { LocalsessionService } from '../../services/localsession.service';

import { environment as env } from '../../../environments/environment';
import { environment as envProd } from '../../../environments/environment.prod';


@Injectable()
export class TrainersService implements Resolve<any>
{
    schools: any[] = [];

    public TRAINERS_LIST_URL = `${isDevMode() && env.baseUrl || envProd.baseUrl}school/personal`;
    public SCHOOLS_LIST_URL = `${isDevMode() && env.baseUrl || envProd.baseUrl}school`;
    public SCHOOLNAME_LIST_URL = `${isDevMode() && env.baseUrl || envProd.baseUrl}school/getname`;

    routeParams: any;

    onTrainersChanged: BehaviorSubject<any>;
    onSchoolNamesChanged: BehaviorSubject<any>;
    onSelectedTrainersChanged: BehaviorSubject<any>;
    onUserDataChanged: BehaviorSubject<any>;
    onSearchTextChanged: Subject<any>;
    onFilterChanged: Subject<any>;

    trainers: Trainer[];
    schoolnames: String[];

    user: any;
    selectedContacts: string[] = [];

    searchText: string;
    filterBy = 'all';

    /**
     * Constructor
     *
     * @param {HttpClient} _httpClient
     */
    constructor(
        private _httpClient: HttpClient,
        private _localSession: LocalsessionService,
        private globalVars : GlobalService
    ) {
        // Set the defaults
        this.onTrainersChanged = new BehaviorSubject([]);
        this.onSchoolNamesChanged = new BehaviorSubject([]);
        this.onSelectedTrainersChanged = new BehaviorSubject([]);
        this.onUserDataChanged = new BehaviorSubject([]);
        this.onSearchTextChanged = new Subject();
        this.onFilterChanged = new Subject();
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
        
        this.routeParams = route.params;
        this.globalVars.g_nDisplyTrainerMode = this.routeParams.id;
        
        return new Promise((resolve, reject) => {
            Promise.all([
                this.getSchoolNames(),
                // this.getUserData(),
                this.getTrainers()
            ]).then(
                ([files]) => {

                    this.onSearchTextChanged.subscribe(searchText => {
                        this.searchText = searchText;
                        this.getTrainers();
                    });

                    this.onFilterChanged.subscribe(filter => {
                        this.filterBy = filter;
                        this.getTrainers();
                    });

                    resolve();

                },
                reject
            );
        });
    }
    /**
     * Get contacts
     *
     * @returns {Promise<any>}
     */
   
    // getSchoolNames() {
    //     let data: any = {};
    //     data['token'] = this._localSession.retrieveToken();
    //     data['user_id'] = this._localSession.retrieveUserID();

    //     return this._httpClient.post(this.SCHOOLNAME_LIST_URL, data).pipe(
    //         map(response => response),
    //         catchError((error: Response) => observableThrowError(error)));
    // }

    getSchoolNames(): Promise<any> {
        let data: any = {};
        data['token'] = this._localSession.retrieveToken();
        data['user_id'] = this._localSession.retrieveUserID();

        return new Promise((resolve, reject) => {
            this._httpClient.post(this.SCHOOLNAME_LIST_URL, data)
                .subscribe((response: any) => {
                    this.schools = response;

                    this.schoolnames = [];
                    for ( let i = 0 ; i < this.schools.length; i ++)
                    {
                        this.schoolnames.push(this.schools[i].school_name);
                    }
                    
                    this.schoolnames = this.schoolnames.filter((el, i, a) => i === a.indexOf(el))
                    this.onSchoolNamesChanged.next(this.schools);
                    resolve(response);
                }, reject);
        });
    }

    /**
     * Get contacts
     *
     * @returns {Promise<any>}
     */
    getTrainers(): Promise<any> {
        let data: any = {};
        data['token'] = this._localSession.retrieveToken();
        data['user_id'] = this._localSession.retrieveUserID();
        data['status'] = '' + this.globalVars.g_nDisplyTrainerMode;
        
        return new Promise((resolve, reject) => {
            this._httpClient.post(this.TRAINERS_LIST_URL, data)
                .subscribe((response: any) => {
                    this.trainers = response;
                    
                    if (this.filterBy !== 'all') {
                        this.trainers = this.trainers.filter(_trainer => {
                            return this.filterBy ==_trainer.school_name;
                        });
                    }

                    if (this.searchText && this.searchText !== '') {
                        this.trainers = FuseUtils.filterArrayByString(this.trainers, this.searchText);
                    }

                    this.trainers = this.trainers.map(contact => {
                        return new Trainer(contact);
                    });

                    this.onTrainersChanged.next(this.trainers);
                    resolve(this.trainers);
                }, reject);
        }
        );
    }

    /**
     * Get user data
     *
     * @returns {Promise<any>}
     */
    getUserData(): Promise<any> {
        return new Promise((resolve, reject) => {
            this._httpClient.get('api/contacts-user/5725a6802d10e277a0f35724')
                .subscribe((response: any) => {
                    this.user = response;
                    this.onUserDataChanged.next(this.user);
                    resolve(this.user);
                }, reject);
        }
        );
    }

    /**
     * Toggle selected contact by id
     *
     * @param id
     */
    toggleSelectedContact(id): void {
        // First, check if we already have that contact as selected...
        if (this.selectedContacts.length > 0) {
            const index = this.selectedContacts.indexOf(id);

            if (index !== -1) {
                this.selectedContacts.splice(index, 1);

                // Trigger the next event
                this.onSelectedTrainersChanged.next(this.selectedContacts);

                // Return
                return;
            }
        }

        // If we don't have it, push as selected
        this.selectedContacts.push(id);

        // Trigger the next event
        this.onSelectedTrainersChanged.next(this.selectedContacts);
    }

    /**
     * Toggle select all
     */
    toggleSelectAll(): void {
        if (this.selectedContacts.length > 0) {
            this.deselectContacts();
        }
        else {
            this.selectContacts();
        }
    }

    /**
     * Select contacts
     *
     * @param filterParameter
     * @param filterValue
     */
    selectContacts(filterParameter?, filterValue?): void {
        this.selectedContacts = [];

        // If there is no filter, select all contacts
        if (filterParameter === undefined || filterValue === undefined) {
            this.selectedContacts = [];
            this.trainers.map(contact => {
                this.selectedContacts.push(contact.id);
            });
        }

        // Trigger the next event
        this.onSelectedTrainersChanged.next(this.selectedContacts);
    }

    /**
     * Update contact
     *
     * @param contact
     * @returns {Promise<any>}
     */
    updateContact(contact): Promise<any> {
        return new Promise((resolve, reject) => {

            this._httpClient.post('api/contacts-contacts/' + contact.id, { ...contact })
                .subscribe(response => {
                    this.getTrainers();
                    resolve(response);
                });
        });
    }

    /**
     * Update user data
     *
     * @param userData
     * @returns {Promise<any>}
     */
    updateUserData(userData): Promise<any> {
        return new Promise((resolve, reject) => {
            this._httpClient.post('api/contacts-user/' + this.user.id, { ...userData })
                .subscribe(response => {
                    // this.getUserData();
                    this.getTrainers();
                    resolve(response);
                });
        });
    }

    /**
     * Deselect contacts
     */
    deselectContacts(): void {
        this.selectedContacts = [];

        // Trigger the next event
        this.onSelectedTrainersChanged.next(this.selectedContacts);
    }

    /**
     * Delete contact
     *
     * @param contact
     */
    deleteContact(contact): void {
        const contactIndex = this.trainers.indexOf(contact);
        this.trainers.splice(contactIndex, 1);
        this.onTrainersChanged.next(this.trainers);
    }

    /**
     * Delete selected contacts
     */
    deleteSelectedContacts(): void {
        for (const contactId of this.selectedContacts) {
            const contact = this.trainers.find(_contact => {
                return _contact.id === contactId;
            });
            const contactIndex = this.trainers.indexOf(contact);
            this.trainers.splice(contactIndex, 1);
        }
        this.onTrainersChanged.next(this.trainers);
        this.deselectContacts();
    }
}
