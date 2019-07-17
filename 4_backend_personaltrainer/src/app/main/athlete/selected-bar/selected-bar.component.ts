import { Component, OnDestroy, OnInit } from '@angular/core';
import { MatDialog, MatDialogRef } from '@angular/material';
import { Subject } from 'rxjs';
import { takeUntil } from 'rxjs/operators';

import { FuseConfirmDialogComponent } from '@fuse/components/confirm-dialog/confirm-dialog.component';
import { AthleteService } from 'app/main/athlete/athlete.service';

@Component({
    selector   : 'selected-bar',
    templateUrl: './selected-bar.component.html',
    styleUrls  : ['./selected-bar.component.scss']
})
export class AthleteSelectedBarComponent implements OnInit, OnDestroy
{
    confirmDialogRef: MatDialogRef<FuseConfirmDialogComponent>;
    hasSelectedContacts: boolean;
    isIndeterminate: boolean;
    selectedContacts: string[];

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
        // Set the private defaults
        this._unsubscribeAll = new Subject();
    }

    /**
     * On init
     */
    ngOnInit(): void
    {
        this._athleteService.onSelectedContactsChanged
            .pipe(takeUntil(this._unsubscribeAll))
            .subscribe(selectedContacts => {
                this.selectedContacts = selectedContacts;
                setTimeout(() => {
                    this.hasSelectedContacts = selectedContacts.length > 0;
                    this.isIndeterminate = (selectedContacts.length !== this._athleteService.athletes.length && selectedContacts.length > 0);
                }, 0);
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

    /**
     * Select all
     */
    selectAll(): void
    {
        this._athleteService.selectContacts();
    }

    /**
     * Deselect all
     */
    deselectAll(): void
    {
        this._athleteService.deselectContacts();
    }

    /**
     * Delete selected contacts
     */
    // deleteSelectedContacts(): void
    // {
    //     this.confirmDialogRef = this._matDialog.open(FuseConfirmDialogComponent, {
    //         disableClose: false
    //     });

    //     this.confirmDialogRef.componentInstance.confirmMessage = 'Are you sure you want to delete all selected contacts?';
    //     this.confirmDialogRef.afterClosed()
    //         .subscribe(result => {
    //             if ( result )
    //             {
    //                 this._athleteService.deleteSelectedContacts();
    //             }
    //             this.confirmDialogRef = null;
    //         });
    // }
}
