import { Component, Inject, OnDestroy, OnInit, isDevMode } from '@angular/core';
import { Subject } from 'rxjs';
import { takeUntil } from 'rxjs/operators';

import { fuseAnimations } from '@fuse/animations';
import { PCoursesService } from 'app/main/pcourse/pcourses.service';
import { FuseConfigService } from '@fuse/services/config.service';

@Component({
    selector: 'p-courses',
    templateUrl: './pcourses.component.html',
    styleUrls: ['./pcourses.component.scss'],
    animations: fuseAnimations
})

export class PCoursesComponent implements OnInit, OnDestroy {
    categories: any[];
    levels: any[];

    courses: any[];
    coursesFilteredByCategory: any[];
    coursesFilteredByLevel: any[];

    filteredCourses: any[];
    filteredLevel: any[];

    currentCategory: string;
    currentLevel: string;

    searchTerm: string;
  
    // Private
    private _unsubscribeAll: Subject<any>;

    courseMode: any;

    /**
     * Constructor
     *
     * @param {AcademyCoursesService} _academyCoursesService
     */
    constructor(
        private _academyCoursesService: PCoursesService,
        private _fuseConfigService: FuseConfigService,
    ) {
        // Set the defaults
        this.currentCategory = 'all';
        this.searchTerm = '';

        // Set the private defaults
        this._unsubscribeAll = new Subject();

        // Configure the layout
        this._fuseConfigService.config = {
            layout: { navbar: { hidden: false }, toolbar: { hidden: false }, footer: { hidden: true }, sidepanel: { hidden: false } }
        };
    }

    /**
     * On init
     */
    ngOnInit(): void {
        // Subscribe to categories
        this._academyCoursesService.onCategoriesChanged
            .pipe(takeUntil(this._unsubscribeAll))
            .subscribe(categories => {
                this.categories = categories;
                this.courseMode = this._academyCoursesService.status;
                if (this.categories.length > 0)
                    this._academyCoursesService.getLevels(this.categories[0].id);
            });

        // Subscribe to Levels
        this._academyCoursesService.onLevelChanged
            .pipe(takeUntil(this._unsubscribeAll))
            .subscribe(levels => {
                this.levels = levels;
            });

        // Subscribe to courses
        this._academyCoursesService.onCoursesChanged
            .pipe(takeUntil(this._unsubscribeAll))
            .subscribe(courses => {
                this.filteredCourses = this.coursesFilteredByCategory = this.courses = courses;
                this.filterCoursesByCategory();
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

    /**
     * Filter courses by category
     */
    filterCoursesByCategory(): void {
        // Filter
        if (this.currentCategory === 'all') {
            this.coursesFilteredByCategory = this.courses;
            this.filteredCourses = this.courses;
        }
        else {
            let filter = this.categories[this.currentCategory];

            this.coursesFilteredByCategory = this.courses.filter((course) => {
                return course.course_id === filter.id;
            });

            this.filteredCourses = [...this.coursesFilteredByCategory];
            this._academyCoursesService.getLevels(this.categories[this.currentCategory].id);
        }
    }

    filterCoursesByLevel(): void {
        // Filter
        if (this.currentLevel === 'all') {
            this.coursesFilteredByLevel = this.filteredCourses;
            this.filteredLevel = this.filteredCourses;
        }
        else {
            this.coursesFilteredByLevel = this.filteredCourses.filter((course) => {
                return course.level_id === this.currentLevel;
            });

            this.filteredLevel = [...this.coursesFilteredByLevel];
        }
    }

    /**
     * Filter courses by term
     */
    filterCoursesByTerm(): void {
        const searchTerm = this.searchTerm.toLowerCase();

        // Search
        if (searchTerm === '') {
            this.filteredLevel = this.coursesFilteredByLevel;
        }
        else {
            this.filteredLevel = this.coursesFilteredByLevel.filter((course) => {
                return course.title.toLowerCase().includes(searchTerm);
            });
        }
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
}
