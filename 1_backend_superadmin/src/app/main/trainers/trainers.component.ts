import { Component, OnDestroy, OnInit, ViewEncapsulation } from '@angular/core';
import { FormControl, FormGroup } from '@angular/forms';
import { MatDialog } from '@angular/material';
import { Subject } from 'rxjs';
import { debounceTime, distinctUntilChanged, takeUntil } from 'rxjs/operators';

import { fuseAnimations } from '@fuse/animations';
import { FuseSidebarService } from '@fuse/components/sidebar/sidebar.service';

import { TrainersService } from 'app/main/trainers/trainers.service';
// import { TrainerFormDialogComponent } from 'app/main/trainers/trainer-form/trainer-form.component';

import { FuseTranslationLoaderService } from '@fuse/services/translation-loader.service';

import { locale as english } from './i18n/en';
import { locale as italian } from './i18n/it';

@Component({
    selector: 'trainers',
    templateUrl: './trainers.component.html',
    styleUrls: ['./trainers.component.scss'],
    encapsulation: ViewEncapsulation.None,
    animations: fuseAnimations
})

export class TrainersComponent implements OnInit, OnDestroy {
    dialogRef: any;
    hasSelectedTrainers: boolean;
    searchInput: FormControl;


    // Private
    private _unsubscribeAll: Subject<any>;

    /**
     * Constructor
     *
     * @param {TrainersService} _trainersService
     * @param {FuseSidebarService} _fuseSidebarService
     * @param {MatDialog} _matDialog
     * @param {FuseTranslationLoaderService} _fuseTranslationLoaderService
     */
    constructor(
        private _trainersService: TrainersService,
        private _fuseSidebarService: FuseSidebarService,
        private _matDialog: MatDialog,
        private _fuseTranslationLoaderService: FuseTranslationLoaderService
    ) {
        // Set the defaults
        this.searchInput = new FormControl('');

        // Set the private defaults
        this._unsubscribeAll = new Subject();

        this._fuseTranslationLoaderService.loadTranslations(english, italian);
    }

    // -----------------------------------------------------------------------------------------------------
    // @ Lifecycle hooks
    // -----------------------------------------------------------------------------------------------------

    /**
     * On init
     */
    ngOnInit(): void {
        this._trainersService.onSelectedTrainersChanged
            .pipe(takeUntil(this._unsubscribeAll))
            .subscribe(selectedContacts => {
                this.hasSelectedTrainers = selectedContacts.length > 0;
            });

        this.searchInput.valueChanges
            .pipe(
                takeUntil(this._unsubscribeAll),
                debounceTime(300),
                distinctUntilChanged()
            )
            .subscribe(searchText => {
                this._trainersService.onSearchTextChanged.next(searchText);
            });
    }

    /**
     * On destroy
     */
    ngOnDestroy(): void {
        // Unsubscribe from all subscriptions
        this._unsubscribeAll.next();
        this._unsubscribeAll.complete();
    }

    // -----------------------------------------------------------------------------------------------------
    // @ Public methods
    // -----------------------------------------------------------------------------------------------------

    /**
     * New contact
     */
    // newContact(): void {
    //     this.dialogRef = this._matDialog.open(TrainerFormDialogComponent, {
    //         panelClass: 'contact-form-dialog',
    //         data: {
    //             action: 'new'
    //         }
    //     });

    //     this.dialogRef.afterClosed()
    //         .subscribe((response: FormGroup) => {
    //             if (!response) {
    //                 return;
    //             }

    //             this._trainersService.updateContact(response.getRawValue());
    //         });
    // }

    /**
     * Toggle the sidebar
     *
     * @param name
     */
    toggleSidebar(name): void {
        this._fuseSidebarService.getSidebar(name).toggleOpen();
    }
}
