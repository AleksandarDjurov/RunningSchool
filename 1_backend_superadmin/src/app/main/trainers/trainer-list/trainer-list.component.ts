import { environment as env } from '../../../../environments/environment';
import { environment as envProd } from '../../../../environments/environment.prod';
import { Component, OnDestroy, OnInit, TemplateRef, ViewChild, ViewEncapsulation, isDevMode } from '@angular/core';
import { FormGroup } from '@angular/forms';
import { MatDialog, MatDialogRef } from '@angular/material';
import { DataSource } from '@angular/cdk/collections';
import { Observable, Subject } from 'rxjs';
import { takeUntil } from 'rxjs/operators';

import { fuseAnimations } from '@fuse/animations';
import { FuseConfirmDialogComponent } from '@fuse/components/confirm-dialog/confirm-dialog.component';

import { TrainersService } from 'app/main/trainers/trainers.service';
// import { TrainerFormDialogComponent } from 'app/main/trainers/trainer-form/trainer-form.component';

@Component({
    selector     : 'trainers-trainer-list',
    templateUrl  : './trainer-list.component.html',
    styleUrls    : ['./trainer-list.component.scss'],
    encapsulation: ViewEncapsulation.None,
    animations   : fuseAnimations
})

export class TrainersTrainerListComponent implements OnInit, OnDestroy
{
    @ViewChild('dialogContent')
    dialogContent: TemplateRef<any>;

    // urlHeader = 'http://localhost';
    urlHeader = `${isDevMode() && env.basePageUrl || envProd.basePageUrl}`;

    trainers: any;
    user: any;
    dataSource: FilesDataSource | null;
    displayedColumns = ['checkbox', 'avatar', 'name', 'school', 'role', 'email', 'phone', 'comment', 'buttons'];
    selectedContacts: any[];
    checkboxes: {};
    dialogRef: any;
    confirmDialogRef: MatDialogRef<FuseConfirmDialogComponent>;

    // Private
    private _unsubscribeAll: Subject<any>;

    /**
     * Constructor
     *
     * @param {ContactsService} _contactsService
     * @param {MatDialog} _matDialog
     */
    constructor(
        private _trainersService: TrainersService,
        public _matDialog: MatDialog
    )
    {
        // Set the private defaults
        this._unsubscribeAll = new Subject();
    }

    // -----------------------------------------------------------------------------------------------------
    // @ Lifecycle hooks
    // -----------------------------------------------------------------------------------------------------

    /**
     * On init
     */
    ngOnInit(): void
    {
        this.dataSource = new FilesDataSource(this._trainersService);

        this._trainersService.onTrainersChanged
            .pipe(takeUntil(this._unsubscribeAll))
            .subscribe(trainers => {
                this.trainers = trainers;

                this.checkboxes = {};
                trainers.map(contact => {
                    this.checkboxes[contact.id] = false;
                });
            });

        this._trainersService.onSelectedTrainersChanged
            .pipe(takeUntil(this._unsubscribeAll))
            .subscribe(selectedContacts => {
                for ( const id in this.checkboxes )
                {
                    if ( !this.checkboxes.hasOwnProperty(id) )
                    {
                        continue;
                    }

                    this.checkboxes[id] = selectedContacts.includes(id);
                }
                this.selectedContacts = selectedContacts;
            });

        this._trainersService.onUserDataChanged
            .pipe(takeUntil(this._unsubscribeAll))
            .subscribe(user => {
                this.user = user;
            });

        this._trainersService.onFilterChanged
            .pipe(takeUntil(this._unsubscribeAll))
            .subscribe(() => {
                this._trainersService.deselectContacts();
            });
    }

    /**
     * On destroy
     */
    ngOnDestroy(): void
    {
        // Unsubscribe from all subscriptions
        this._unsubscribeAll.next();
        this._unsubscribeAll.complete();
    }

    // /**
    //  * Delete Contact
    //  */
    deleteTrainer(contact): void
    {
        this.confirmDialogRef = this._matDialog.open(FuseConfirmDialogComponent, {
            disableClose: false
        });

        this.confirmDialogRef.componentInstance.confirmMessage = 'Are you sure you want to delete?';

        this.confirmDialogRef.afterClosed().subscribe(result => {
            if ( result )
            {
                this._trainersService.deleteContact(contact);
            }
            this.confirmDialogRef = null;
        });

    }

    // /**
    //  * On selected change
    //  *
    //  * @param contactId
    //  */
    onSelectedChange(contactId): void
    {
        this._trainersService.toggleSelectedContact(contactId);
    }

    // /**
    //  * Toggle star
    //  *
    //  * @param contactId
    //  */
    // toggleStar(contactId): void
    // {
    //     if ( this.user.starred.includes(contactId) )
    //     {
    //         this.user.starred.splice(this.user.starred.indexOf(contactId), 1);
    //     }
    //     else
    //     {
    //         this.user.starred.push(contactId);
    //     }

    //     this._trainersService.updateUserData(this.user);
    // }
}

export class FilesDataSource extends DataSource<any>
{
    /**
     * Constructor
     *
     * @param {ContactsService} _trainersService
     */
    constructor(
        private _trainersService: TrainersService
    )
    {
        super();
    }

    /**
     * Connect function called by the table to retrieve one stream containing the data to render.
     * @returns {Observable<any[]>}
     */
    connect(): Observable<any[]>
    {
        return this._trainersService.onTrainersChanged;
    }

    /**
     * Disconnect
     */
    disconnect(): void
    {
    }
}
