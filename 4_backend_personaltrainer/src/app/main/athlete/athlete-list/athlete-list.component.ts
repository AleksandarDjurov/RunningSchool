import { environment as env } from '../../../../environments/environment';
import { environment as envProd } from '../../../../environments/environment.prod';
import { Component, OnDestroy, OnInit, TemplateRef, ViewChild, ViewEncapsulation, isDevMode } from '@angular/core';
import { MatDialog, MatDialogRef } from '@angular/material';
import { DataSource } from '@angular/cdk/collections';
import { Observable, Subject } from 'rxjs';
import { takeUntil } from 'rxjs/operators';

import { fuseAnimations } from '@fuse/animations';
import { FuseConfirmDialogComponent } from '@fuse/components/confirm-dialog/confirm-dialog.component';

import { AthleteService } from 'app/main/athlete/athlete.service';
// import { ContactsContactFormDialogComponent } from 'app/main/contacts/contact-form/contact-form.component';

@Component({
    selector     : 'athlete-contact-list',
    templateUrl  : './athlete-list.component.html',
    styleUrls    : ['./athlete-list.component.scss'],
    encapsulation: ViewEncapsulation.None,
    animations   : fuseAnimations
})

export class AthletesListComponent implements OnInit, OnDestroy
{
    @ViewChild('dialogContent', {static:true})dialogContent: TemplateRef<any>;

    // urlHeader = 'http://localhost';
    urlHeader = `${isDevMode() && env.basePageUrl || envProd.basePageUrl}`;

    athletes: any;
    user: any;
    dataSource: FilesDataSource | null;
    // displayedColumns = ['checkbox', 'pic', 'name', 'role', 'email', 'phone', 'status', 'comment', 'buttons'];
    displayedColumns = ['checkbox', 'avatar', 'name', 'gender', 'mail', 'phone', 'pay_amount', 'since_date', 'buttons'];
    selectedContacts: any[];
    checkboxes: {};
    dialogRef: any;
    confirmDialogRef: MatDialogRef<FuseConfirmDialogComponent>;

    public currentCourseID : string;

    // Private
    private _unsubscribeAll: Subject<any>;
    
    /**
     * Constructor
     *
     * @param {ContactsService} _athleteService
     * @param {MatDialog} _matDialog
     */
    constructor(
        private _athleteService: AthleteService,
        public _matDialog: MatDialog
    )
    {
        this.currentCourseID = this._athleteService.currentCourseID;
        // Set the private defaults
        this._unsubscribeAll = new Subject();
    }

    /**
     * On init
     */
    ngOnInit(): void
    {
        this.dataSource = new FilesDataSource(this._athleteService);
        this._athleteService.onAthletesChanged
            .pipe(takeUntil(this._unsubscribeAll))
            .subscribe(athletes => {
                this.athletes = athletes;
                this.currentCourseID = this._athleteService.currentCourseID;
                this.checkboxes = {};
                athletes.map(athlete => {
                    this.checkboxes[athlete.id] = false;
                });
            });

        this._athleteService.onSelectedContactsChanged
            .pipe(takeUntil(this._unsubscribeAll))
            .subscribe(selectedContacts => {
                for ( const id in this.checkboxes )
                {
                    if ( !this.checkboxes.hasOwnProperty(id) )
                        continue;

                    this.checkboxes[id] = selectedContacts.includes(id);
                }
                this.selectedContacts = selectedContacts;
            });

        this._athleteService.onUserDataChanged
            .pipe(takeUntil(this._unsubscribeAll))
            .subscribe(user => {
                this.user = user;
            });

        this._athleteService.onFilterChanged
            .pipe(takeUntil(this._unsubscribeAll))
            .subscribe(() => {
                this._athleteService.deselectContacts();
            });
    }

    /**
     * On destroy
     */
    ngOnDestroy(): void
    {
        this._unsubscribeAll.next();
        this._unsubscribeAll.complete();
    }

    onSelectedChange(contactId): void
    {
        this._athleteService.toggleSelectedContact(contactId);
    }

    toggleStar(contactId): void
    {
        if ( this.user.starred.includes(contactId) )
            this.user.starred.splice(this.user.starred.indexOf(contactId), 1);
        else
            this.user.starred.push(contactId);
    }
}

export class FilesDataSource extends DataSource<any>
{
    /**
     * Constructor
     *
     * @param {ContactsService} _athleteService
     */
    constructor( private _athleteService: AthleteService ) { super(); }

    /**
     * Connect function called by the table to retrieve one stream containing the data to render.
     * @returns {Observable<any[]>}
     */
    connect(): Observable<any[]> { return this._athleteService.onAthletesChanged; }

    /**
     * Disconnect
     */
    disconnect(): void { }
}
