import { Component, Inject, ViewEncapsulation } from '@angular/core';
import { FormBuilder, FormGroup } from '@angular/forms';
import { MAT_DIALOG_DATA, MatDialogRef } from '@angular/material';

// import { Course } from 'app/main/academy/course/course.model';
// import { MainCourseService } from 'app/main/academy/mcourse/mcourse/mcourse.service';

@Component({
    selector: 'course-form-dialog',
    templateUrl: './course-form.component.html',
    styleUrls: ['./course-form.component.scss'],
    encapsulation: ViewEncapsulation.None
})

export class CourseFormDialogComponent {
    action: string;
    course: any = {};
    courseForm: FormGroup;
    dialogTitle: string;

    constructor(
        public matDialogRef: MatDialogRef<CourseFormDialogComponent>,
        @Inject(MAT_DIALOG_DATA) private _data: any,
        private _formBuilder: FormBuilder
    ) {
        // Set the defaults
        this.action = _data.action;
        // this.imgCourseURL = _data.imagepath;

        if (this.action === 'edit') {
            this.dialogTitle = 'Edit Course';
            this.course = _data.course;
            
            this.course.from_date = this.getDateOnly(this.course.from_date);
            this.course.to_date = this.getDateOnly(this.course.to_date);
        }
        else {
            this.dialogTitle = 'New Course';
            this.course = {};//new Course({});
        }

        this.courseForm = this.createCourseForm();
    }
    
    getDateOnly(szDate: string) {
        let arrDate = szDate.split(' ');
        return arrDate[0];
    }

    createCourseForm(): FormGroup {
        return this._formBuilder.group({
            id: [this.course.id],
            course_name: [this.course.course_name],
            course_content: [this.course.course_content],
            to_date: [this.course.to_date],
            from_date: [this.course.from_date],
            status: [this.course.status],
        });
    }

}
