import { Component, OnDestroy, OnInit } from '@angular/core';
import { MatDialog, MatDialogRef } from '@angular/material';
import { Subject } from 'rxjs';
import { takeUntil } from 'rxjs/operators';

import { FuseConfirmDialogComponent } from '@fuse/components/confirm-dialog/confirm-dialog.component';
import { TrainersService } from 'app/main/trainers/trainers.service';

@Component({
    selector   : 'selected-trainerbar',
    templateUrl: './selected-trainerbar.component.html',
    styleUrls  : ['./selected-trainerbar.component.scss']
})
export class TrainersSelectedBarComponent implements OnInit, OnDestroy
{
    confirmDialogRef: MatDialogRef<FuseConfirmDialogComponent>;
    hasSelectedTrainers: boolean;
    isIndeterminate: boolean;
    selectedTrainers: string[];

    // Private
    private _unsubscribeAll: Subject<any>;

    /**
     * Constructor
     *
     * @param {TrainersService} _trainersService
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
        this._trainersService.onSelectedTrainersChanged
            .pipe(takeUntil(this._unsubscribeAll))
            .subscribe(selectedContacts => {
                this.selectedTrainers = selectedContacts;
                setTimeout(() => {
                    this.hasSelectedTrainers = selectedContacts.length > 0;
                    this.isIndeterminate = (selectedContacts.length !== this._trainersService.trainers.length && selectedContacts.length > 0);
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

    // -----------------------------------------------------------------------------------------------------
    // @ Public methods
    // -----------------------------------------------------------------------------------------------------

    /**
     * Select all
     */
    selectAll(): void
    {
        this._trainersService.selectContacts();
    }

    /**
     * Deselect all
     */
    deselectAll(): void
    {
        this._trainersService.deselectContacts();
    }

    /**
     * Delete selected contacts
     */
    deleteSelectedContacts(): void
    {
        this.confirmDialogRef = this._matDialog.open(FuseConfirmDialogComponent, {
            disableClose: false
        });

        this.confirmDialogRef.componentInstance.confirmMessage = 'Are you sure you want to delete all selected contacts?';

        this.confirmDialogRef.afterClosed()
            .subscribe(result => {
                if ( result )
                {
                    this._trainersService.deleteSelectedContacts();
                }
                this.confirmDialogRef = null;
            });
    }
}
