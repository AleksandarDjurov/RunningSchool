import { Component, Inject, OnDestroy, OnInit, isDevMode } from '@angular/core';
import { Subject } from 'rxjs';
import { takeUntil } from 'rxjs/operators';
import { MatDialog, MAT_DIALOG_DATA, MatDialogRef } from '@angular/material';
import { Router } from '@angular/router';

import { fuseAnimations } from '@fuse/animations';
import { SCoursesService } from 'app/main/course/scourse/scourses.service';
import { FuseConfigService } from '@fuse/services/config.service';
import { FormControl, FormGroup } from '@angular/forms';

import { environment as env } from '../../../../../environments/environment';
import { environment as envProd } from '../../../../../environments/environment.prod';

import { SCourseFormDialogComponent } from 'app/main/course/scourse/scourse-form/scourse-form.component';
import { ActivateFormDialogComponent } from 'app/main/course/scourse/activate-form/activate-form.component';

import { ResultsComponent } from '../../../common/results/results.component';
import { LoadingComponent } from '../../../common/loading/loading.component';

import { LocalsessionService } from '../../../../services/localsession.service';
import { FuseConfirmDialogComponent } from '@fuse/components/confirm-dialog/confirm-dialog.component';

@Component({
    selector: 's-courses',
    templateUrl: './scourses.component.html',
    styleUrls: ['./scourses.component.scss'],
    animations: fuseAnimations
})

export class SCoursesComponent implements OnInit, OnDestroy {
    dialogRef: any;

    confirmDialogRef: MatDialogRef<FuseConfirmDialogComponent>;

    categories: any[];
    levels: any[];

    courses: any[];
    coursesFilteredByCategory: any[];
    coursesFilteredByLevel: any[];

    filteredCourses: any[];
    filteredLevel: any[];

    ptListOfMySchool: any[];

    currentCategory: string;
    currentLevel: string;

    searchTerm: string;
    msgContent: string;
    msgTitle: string;

    spinnerDlg: MatDialogRef<LoadingComponent>;

    // Private
    private _unsubscribeAll: Subject<any>;

    courseMode: any;

    /**
     * Constructor
     *
     * @param {AcademyCoursesService} _academyCoursesService
     */
    constructor(
        private _matDialog: MatDialog,
        private _academyCoursesService: SCoursesService,
        private _fuseConfigService: FuseConfigService,
        private _localSession: LocalsessionService,
        private _router: Router
        // public  matDialogRef: MatDialogRef<SCourseFormDialogComponent>,
        // @Inject(MAT_DIALOG_DATA) private _data: any,
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

    showSpinner() {
        this.spinnerDlg = this._matDialog.open(LoadingComponent, { panelClass: 'transparent', disableClose: true });
    }

    hideSpinner() {
        this.spinnerDlg.close();
    }

    // -----------------------------------------------------------------------------------------------------
    // @ Lifecycle hooks
    // -----------------------------------------------------------------------------------------------------
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

        // Personal Trainer List
        this._academyCoursesService.onPTListChanged
            .pipe(takeUntil(this._unsubscribeAll))
            .subscribe(ptLists => {
                this.ptListOfMySchool = ptLists;
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

    showMessage() {
        // this.bLoading = false;
        const dialogRef = this._matDialog.open(ResultsComponent, {
            panelClass: 'transparent',
            data: { title: this.msgTitle, content: this.msgContent },
        });

        dialogRef.afterClosed().subscribe(result => { });
    }

    showNotSelectedQuarterCourseMSG()
    {
        this.msgTitle = 'Course Activation';
        this.msgContent = "Please select a proper quarter course.";
        this.showMessage();
    }

    showNotActivatedQuarterCourseMSG()
    {
        this.msgTitle = 'Course Activation';
        this.msgContent = "Select quarter course is not activated yet.";
        this.showMessage();
    }

    showPurchasePacketMSG()
    {
        this.msgTitle = 'Purchase Package';
        this.msgContent = "You have to purchase a package in order to open course!";
        this.showMessage();
    }

    newCourse() {
        let purchased = this._localSession.retrievePurchased();
        if( purchased=='false')
        {
            this.showPurchasePacketMSG();
            return;
        }

        if (this.categories.length < 1 || this.currentCategory === 'all') {
            this.showNotSelectedQuarterCourseMSG();
            return;
        }

        let category = this.categories[this.currentCategory];
        if (category.status === 0) {
            this.showNotActivatedQuarterCourseMSG();
            return;
        }

        this.dialogRef = this._matDialog.open(SCourseFormDialogComponent, {
            panelClass: 'calendar-scourse-form-dialog',
            data: { quarter: category, levels: this.levels }
        });

        this.dialogRef.afterClosed()
            .subscribe(response => {
                if (!response)
                    return;

                const formData: FormGroup = response;
                this._academyCoursesService.createSchoolCourse(formData.value);
            });
    }

    activateCourse(courseData: any) {
        let purchased = this._localSession.retrievePurchased();
        if( purchased=='false')
        {
            this.showPurchasePacketMSG();
            return;
        }

        if (courseData.course_dates.length < 5) {
            this.msgContent = "Period not confirmed.";
            this.showMessage();
            return;
        }

        this.dialogRef = this._matDialog.open(ActivateFormDialogComponent, {
            panelClass: 'calendar-activate-form-dialog',
            data: { personal: this.ptListOfMySchool, course: courseData }
        });

        this.dialogRef.afterClosed()
            .subscribe(response => {
                if (!response)
                    return;

                const formData: FormGroup = response;
                if( formData.value.trainer == null )
                {
                    this.msgContent = "Trainer not selected.";
                    this.showMessage();
                    return;
                }

                this.doActivate(courseData.id, formData.value.trainer);
            });
    }

    doActivate(course_id : string, trainer_id: string) {
        let model: any = {};
        model['user_id'] = this._localSession.retrieveUserID();
        model['token'] = this._localSession.retrieveToken();
        model['trainer_id'] = trainer_id;
        model['active'] = '1';

        this.showSpinner();
        this._academyCoursesService.actiavteSchoolCourse(model, course_id).subscribe(
            (data: any) => {
                this.hideSpinner();

                let code = data['code'];
                let msg = data['msg'];

                if (code === '200' && msg === 'Completed successfully!') {
                    this.msgContent = 'Successfully Activated';
                    this.showMessage();

                    this._router.navigate(['scourses/1']);
                }
            },
            error => {
                this.hideSpinner();
                this.msgContent = 'Operation failed.';
                this.showMessage();
            });
    }

    closeCourse(courseData: any)
    {
        this.confirmDialogRef = this._matDialog.open(FuseConfirmDialogComponent, {
            disableClose: false
        });

        this.confirmDialogRef.componentInstance.confirmMessage = 'Are you sure you want to close?';
        this.confirmDialogRef.afterClosed().subscribe(result => {
            if (result) {
                this.doClose(courseData.id);
            }
            this.confirmDialogRef = null;
        });

    }

    doClose(course_id : string) {
        let model: any = {};
        model['user_id'] = this._localSession.retrieveUserID();
        model['token'] = this._localSession.retrieveToken();
        model['active'] = '2';

        this.showSpinner();
        this._academyCoursesService.closeSchoolCourse(model, course_id).subscribe(
            (data: any) => {
                this.hideSpinner();

                let code = data['code'];
                let msg = data['msg'];

                if (code === '200' && msg === 'Completed successfully!') {
                    this.msgContent = 'Successfully Closed';
                    this.showMessage();

                    this._router.navigate(['scourses/2']);
                }
            },
            error => {
                this.hideSpinner();
                this.msgContent = 'Operation failed.';
                this.showMessage();
            });
    }
}
