import { Component, Inject, OnDestroy, OnInit, isDevMode } from '@angular/core';
import { Subject } from 'rxjs';
import { takeUntil } from 'rxjs/operators';
import { Router } from '@angular/router';

import { fuseAnimations } from '@fuse/animations';
import { TCoursesService } from 'app/main/trainer/tcourse/tcourses.service';
import { FuseConfigService } from '@fuse/services/config.service';

import { environment as env } from '../../../../../environments/environment';
import { environment as envProd } from '../../../../../environments/environment.prod';

@Component({
    selector: 't-courses',
    templateUrl: './tcourses.component.html',
    styleUrls: ['./tcourses.component.scss'],
    animations: fuseAnimations
})

export class TCoursesComponent implements OnInit, OnDestroy {
    dialogRef: any;

    filteredCourses: any[];

    msgContent: string;
    msgTitle: string;

    myName : string = '';

    // Private
    private _unsubscribeAll: Subject<any>;

    /**
     * Constructor
     *
     * @param {AcademyCoursesService} _academyCoursesService
     */
    constructor(
        private _academyCoursesService: TCoursesService,
        private _fuseConfigService: FuseConfigService,
        private _router: Router
    ) {
        // Set the private defaults
        this._unsubscribeAll = new Subject();

        // Configure the layout
        this._fuseConfigService.config = {
            layout: { navbar: { hidden: false }, toolbar: { hidden: false }, footer: { hidden: true }, sidepanel: { hidden: false } }
        };
    }

    // -----------------------------------------------------------------------------------------------------
    // @ Lifecycle hooks
    // -----------------------------------------------------------------------------------------------------
    ngOnInit(): void {
        // Subscribe to courses
        this._academyCoursesService.onTCoursesChanged
            .pipe(takeUntil(this._unsubscribeAll))
            .subscribe(courses => {
                this.filteredCourses = courses;
                if( this.filteredCourses.length > 0 )
                    this.myName = this.filteredCourses[0].trainer_first_name + ' ' + this.filteredCourses[0].trainer_last_name;
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

    //change background image
    changeImage(path) {
        var base_url = `${isDevMode() && env.basePageUrl || envProd.basePageUrl}`;
        var background_val = 'url(./assets/images/courses/athlete_default.jpg)';

        if (path.length > 5 && path !== null) {
            background_val = 'url(' + base_url + path + ')';
        }

        return background_val;
    }

    getDates(szDates) {
        let dates = '';
        let lessonData: any[] = [];
        lessonData = JSON.parse(szDates);
        for (let i = 0; i < lessonData.length; i++) {
            let arrDate = lessonData[i].lesson_date.split('-');
            let date = arrDate[1] + '/' + arrDate[2];

            dates += (date + ',');
        }

        if (dates.length > 0)
            dates = dates.substring(0, dates.length - 1);

        if (dates.length < 1)
            dates = 'Nothing';

        return dates;
    }

    goBack()
    {
        this._router.navigate(['/trainer/' + this._academyCoursesService.trainer_id.id]);
    }
}
