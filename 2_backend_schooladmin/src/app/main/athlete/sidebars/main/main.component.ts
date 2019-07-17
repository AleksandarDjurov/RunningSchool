import { Component, OnDestroy, OnInit } from '@angular/core';
import { Subject } from 'rxjs';
import { takeUntil } from 'rxjs/operators';

import { AthleteService } from 'app/main/athlete/athlete.service';

import { FuseTranslationLoaderService } from '@fuse/services/translation-loader.service';
import { locale as english } from '../../i18n/en'
import { locale as italian } from '../../i18n/it';

@Component({
    selector   : 'contacts-main-sidebar',
    templateUrl: './main.component.html',
    styleUrls  : ['./main.component.scss']
})

export class AthleteMainSidebarComponent implements OnInit, OnDestroy
{
    courses: any[]=[];
    filterBy: number;

    // Private
    private _unsubscribeAll: Subject<any>;

    /**
     * Constructor
     *
     * @param {ContactsService} _athleteService
     */
    constructor(
        private _athleteService: AthleteService,
        private _fuseTranslationLoaderService: FuseTranslationLoaderService
    )
    {
        // Set the private defaults
        this._unsubscribeAll = new Subject();
        this._fuseTranslationLoaderService.loadTranslations(english, italian);
    }

    /**
     * On init
     */
    ngOnInit(): void
    {
        this._athleteService.onCourseChanged
            .pipe(takeUntil(this._unsubscribeAll))
            .subscribe(courses => {
                this.courses = courses;
                if( this.courses.length > 0 )
                {
                    this.filterBy = this.courses[0].id;
                    // set current course id to service variable.
                    this._athleteService.currentFilter = this.filterBy;
                    this._athleteService.currentCourseID = this.courses[0].id;
                    // this._athleteService.onFilterChanged.next(this.filterBy);
                    this._athleteService.getAthletesByFilter();
                }
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
     * Change the filter
     *
     * @param filter
     */
    changeFilter(filter): void
    {
        this.filterBy = filter;
        
        // set current course id to service variable.
        this._athleteService.currentFilter = filter;
        this._athleteService.currentCourseID = filter;
        // this._athleteService.onFilterChanged.next(this.filterBy);
        this._athleteService.getAthletesByFilter();
    }
}
