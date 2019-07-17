import { Injectable, isDevMode } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { ActivatedRouteSnapshot, Resolve, RouterStateSnapshot } from '@angular/router';
import { BehaviorSubject, Observable, Subject } from 'rxjs';

import { FuseUtils } from '@fuse/utils';
import { Athlete } from 'app/main/athlete/athlete.model';

import { environment as env } from '../../../environments/environment';
import { environment as envProd } from '../../../environments/environment.prod';

import { LocalsessionService } from '../../services/localsession.service';
// import { ContactService } from '../../services/contact.service';

@Injectable()
export class AthleteService implements Resolve<any>
{
    // Route::post('/personal/course/athlete/list/{trainer_id}', 'ApiPersonalCourseController@getPersonalAthleteList');
    public ATHLETELISTBYID_URL = `${isDevMode() && env.baseUrl || envProd.baseUrl}personal/course/athlete/list/`;
    public SCHOOL_COURSELIST_URL = `${isDevMode() && env.baseUrl || envProd.baseUrl}school/course/list`;

    //localhost/api/personal/course/list/athlete/22
    public ATHLETELIST_URL = `${isDevMode() && env.baseUrl || envProd.baseUrl}personal/course/list/athlete/`;
    // public CONTACTAPPROV_URL = `${isDevMode() && env.baseUrl || envProd.baseUrl}contact/approve`;

    onCourseChanged: BehaviorSubject<any>;
    onAthletesChanged: BehaviorSubject<any>;
    onSelectedContactsChanged: BehaviorSubject<any>;
    onUserDataChanged: BehaviorSubject<any>;
    onSearchTextChanged: Subject<any>;
    onFilterChanged: Subject<any>;

    athletes: Athlete[] = [];
    ori_athletes: Athlete[] = [];

    user: any;
    selectedContacts: number[] = [];

    searchText: string;
    courseList: any[] = [];

    //-----------------------
    public dispMode: any;
    public currentFilter: number = 0;

    public currentCourseID: string;
    //-----------------------

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
        this.onCourseChanged = new BehaviorSubject([]);
        this.onAthletesChanged = new BehaviorSubject([]);
        this.onSelectedContactsChanged = new BehaviorSubject([]);
        this.onUserDataChanged = new BehaviorSubject([]);
        this.onSearchTextChanged = new Subject();
        this.onFilterChanged = new Subject();

        this.currentFilter = -1;
    }

    /**
     * Resolver
     *
     * @param {ActivatedRouteSnapshot} route
     * @param {RouterStateSnapshot} state
     * @returns {Observable<any> | Promise<any> | any}
     */
    resolve(route: ActivatedRouteSnapshot, state: RouterStateSnapshot): Observable<any> | Promise<any> | any {
        this.dispMode = route.params;

        return new Promise((resolve, reject) => {
            Promise.all([
                this.getAthletesByTrainer(),
                this.getMyCourses()
            ]).then(
                () => {
                    // this.onSearchTextChanged.subscribe(searchText => {
                    //     this.searchText = searchText;
                    //     this.getAthletesByTrainer();
                    // });

                    // this.onFilterChanged.subscribe(filter => {
                    //     this.currentFilter = filter;
                    //     this.getAthletesByTrainer();
                    // });
                    resolve();
                },
                reject
            );
        });
    }

    getAthletesByTrainer(): Promise<any> {
        let data: any = {};
        data['token'] = this._localSession.retrieveToken();
        data['user_id'] = this._localSession.retrieveUserID();
        data['status'] = this.dispMode.id;

        let trainer_id = this._localSession.retrieveUserID();
        return new Promise((resolve, reject) => {
            this._httpClient.post(this.ATHLETELISTBYID_URL + trainer_id, data)
                .subscribe((response: any) => {
                    this.athletes = response;
                    this.ori_athletes = response;

                    if( this.currentFilter !== -1 )
                    {
                        this.athletes = this.athletes.filter(_athlete => {
                            return this.currentFilter == _athlete.school_course_id;
                        });    
                    }

                    // if (this.searchText && this.searchText !== '') {
                    //     this.athletes = FuseUtils.filterArrayByString(this.athletes, this.searchText);
                    // }

                    this.athletes = this.athletes.map(athletes => {
                        return new Athlete(athletes);
                    });

                    this.onAthletesChanged.next(this.athletes);
                    resolve(response);
                }, reject);
        });
    }
    
    getAthletesByFilter() 
    {
        this.athletes = this.ori_athletes;
        if( this.currentFilter !== -1 )
        {
            this.athletes = this.athletes.filter(_athlete => {
                return this.currentFilter == _athlete.school_course_id;
            });    
        }

        this.athletes = this.athletes.map(athletes => {
            return new Athlete(athletes);
        });
        this.onAthletesChanged.next(this.athletes);
    }

    getMyCourses(): Promise<any> {
        let data: any = {};
        data['user_id'] = this._localSession.retrieveUserID();
        data['token'] = this._localSession.retrieveToken();
        data['school_id'] = this._localSession.retrieveSchoolID();
        data['trainer_id'] = this._localSession.retrieveUserID();
        data['status'] = '1';

        if( this.dispMode.id=='2')
            data['status'] = '2';

        return new Promise((resolve, reject) => {
            this._httpClient.post(this.SCHOOL_COURSELIST_URL, data)
                .subscribe((response: any) => {
                    this.courseList = [];
                    for (let i = 0; i < response.length; i++) {
                        this.courseList.push({ 'id': response[i].id, 'name': response[i].course_name });
                    }
                    this.onCourseChanged.next(this.courseList);
                    resolve(this.courseList);
                }, reject);
        });
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
                this.onSelectedContactsChanged.next(this.selectedContacts);

                // Return
                return;
            }
        }

        // If we don't have it, push as selected
        this.selectedContacts.push(id);

        // Trigger the next event
        this.onSelectedContactsChanged.next(this.selectedContacts);
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
            this.athletes.map(contact => {
                this.selectedContacts.push(contact.id);
            });
        }

        // Trigger the next event
        this.onSelectedContactsChanged.next(this.selectedContacts);
    }

    /**
     * Update contact
     *
     * @param contact
     * @returns {Promise<any>}
     */
    // updateContact(contact): Promise<any> {
    //     return new Promise((resolve, reject) => {

    //         this._httpClient.post('api/contacts-contacts/' + contact.id, { ...contact })
    //             .subscribe(response => {
    //                 this.getContacts();
    //                 resolve(response);
    //             });
    //     });
    // }

    /**
     * Deselect contacts
     */
    deselectContacts(): void {
        this.selectedContacts = [];

        // Trigger the next event
        this.onSelectedContactsChanged.next(this.selectedContacts);
    }

    /**
     * Delete contact
     *
     * @param contact
     */
    // deleteContact(contact): void {
    //     const contactIndex = this.athletes.indexOf(contact);
    //     this.athletes.splice(contactIndex, 1);
    //     this.onatChanged.next(this.athletes);
    // }

    /**
     * Delete selected contacts
     */
    // deleteSelectedContacts(): void {
    //     for (const contactId of this.selectedContacts) {
    //         const contact = this.contacts.find(_contact => {
    //             return _contact.id === contactId;
    //         });
    //         const contactIndex = this.contacts.indexOf(contact);
    //         this.contacts.splice(contactIndex, 1);
    //     }
    //     this.onContactsChanged.next(this.contacts);
    //     this.deselectContacts();
    // }

}
