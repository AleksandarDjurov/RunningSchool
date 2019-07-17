import { environment as env } from '../../../../environments/environment';
import { environment as envProd } from '../../../../environments/environment.prod';
import { Component, Inject, ViewEncapsulation, isDevMode } from '@angular/core';
import { FormBuilder, FormGroup } from '@angular/forms';
import { MAT_DIALOG_DATA } from '@angular/material';

//import { Course } from 'app/main/academy/course/course.model';
import { SchoolListService } from 'app/main/school/schools/schools.service';
import { PersonalTrainerService } from 'app/main/trainers/personaltrainer/personaltrainer.service';
import { LoadingComponent } from '../../common/loading/loading.component';
import { MatDialog, MatDialogRef } from '@angular/material/dialog';
import { Router } from '@angular/router';

@Component({
    selector: 'trainer-form-dialog',
    templateUrl: './trainer-form.component.html',
    styleUrls: ['./trainer-form.component.scss'],
    encapsulation: ViewEncapsulation.None
})

export class TrainerFormDialogComponent {
    urlHeader = `${isDevMode() && env.basePageUrl || envProd.basePageUrl}`;
    
    courseForm: FormGroup;
    dialogTitle: string;
    school_list: any[] = [];
    school_id: any;
    trainer_id: any;

    spinnerDlg: MatDialogRef<LoadingComponent>;
    mode : any = {};

    /**
     * Constructor
     *
     * @param {MatDialogRef<TrainerFormDialogComponent>} matDialogRef
     * @param _data
     * @param {FormBuilder} _formBuilder
     */
    constructor(
        @Inject(MAT_DIALOG_DATA) private _data: any,
        private dialog: MatDialog,
        public matDialogRef: MatDialogRef<TrainerFormDialogComponent>,
        private _formBuilder: FormBuilder,
        private _schoolService: SchoolListService,
        private _personalService: PersonalTrainerService,
        private router : Router
    ) {
        // Set the defaults
        this.dialogTitle = 'Please choose a school';
        this.courseForm = this.createCourseForm();
        this.mode = _data['action'];
        this.trainer_id = _data['personal_id'];
    }

    ngOnInit(): void {
        this._schoolService.getSchoolNames().subscribe(
            (data: any) => {
                this.school_list = data;
            }, error => { });
    }

    showSpinner() {
        this.spinnerDlg = this.dialog.open(LoadingComponent, { panelClass: 'transparent', disableClose: true });
    }

    hideSpinner() {
        this.spinnerDlg.close();
    }

    /**
     * Create contact form
     *
     * @returns {FormGroup}
     */
    createCourseForm(): FormGroup {
        return this._formBuilder.group({
            schoolList: [this.school_id],
        });
    }

    // var url = '/apps/trainer/trainers/2';
    // this.router.navigate([url]);

    onApprove() {
        this.showSpinner();
        this._personalService.approvePersonal(this.trainer_id, this.school_id).subscribe(
            (data: any) => {
                this.hideSpinner();
                this.matDialogRef.close(this.courseForm);

                var url = '/apps/trainer/trainers/1';
                this.router.navigate([url]);
            },
            error => {
                this.hideSpinner();
                this.matDialogRef.close(this.courseForm);
            });
    }


}
