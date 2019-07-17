import { Component, OnDestroy, OnInit } from '@angular/core';
import { Subject } from 'rxjs';
import { takeUntil } from 'rxjs/operators';

import { TrainersService } from 'app/main/trainers/trainers.service';

import { FuseTranslationLoaderService } from '@fuse/services/translation-loader.service';
import { locale as english } from '../../i18n/en'
import { locale as italian } from '../../i18n/it';

@Component({
    selector   : 'trainers-main-sidebar',
    templateUrl: './trainermain.component.html',
    styleUrls  : ['./trainermain.component.scss']
})

export class TrainersMainSidebarComponent implements OnInit, OnDestroy
{
    user: any;
    filterBy: string;
    arrListSchoolName : String[];

    // Private
    private _unsubscribeAll: Subject<any>;

    /**
     * Constructor
     *
     * @param {TrainersService} _trainersService
     */
    constructor(
        private _trainersService: TrainersService,
        private _fuseTranslationLoaderService: FuseTranslationLoaderService
    )
    {
        // Set the private defaults
        this._unsubscribeAll = new Subject();
        this._fuseTranslationLoaderService.loadTranslations(english, italian);
        this.arrListSchoolName = _trainersService.schoolnames;
    }

    // -----------------------------------------------------------------------------------------------------
    // @ Lifecycle hooks
    // -----------------------------------------------------------------------------------------------------

    /**
     * On init
     */
    ngOnInit(): void
    {
        this.filterBy = this._trainersService.filterBy || 'all';

        this._trainersService.onUserDataChanged
            .pipe(takeUntil(this._unsubscribeAll))
            .subscribe(user => {
                this.user = user;
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
     * Change the filter
     *
     * @param filter
     */
    changeFilter(filter): void
    {
        this.filterBy = filter;
        this._trainersService.onFilterChanged.next(this.filterBy);
    }
}
