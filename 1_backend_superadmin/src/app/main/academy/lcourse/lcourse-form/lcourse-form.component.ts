import { Component, Inject, ViewEncapsulation } from '@angular/core';
import { FormBuilder, FormGroup } from '@angular/forms';
import { MAT_DIALOG_DATA, MatDialogRef } from '@angular/material';

// import { Course } from 'app/main/academy/course/course.model';
// import { MainCourseService } from 'app/main/academy/mcourse/mcourse/mcourse.service';

@Component({
    selector: 'level-form-dialog',
    templateUrl: './lcourse-form.component.html',
    styleUrls: ['./lcourse-form.component.scss'],
    encapsulation: ViewEncapsulation.None
})

export class LevelFormDialogComponent {
    action: string;
    level: any = {};
    levelForm: FormGroup;
    dialogTitle: string;

    constructor(
        public matDialogRef: MatDialogRef<LevelFormDialogComponent>,
        @Inject(MAT_DIALOG_DATA) private _data: any,
        private _formBuilder: FormBuilder
    ) {
        // Set the defaults
        this.action = _data.action;
        if (this.action === 'edit') {
            this.dialogTitle = 'Edit Level';
            this.level = _data.level;
        }
        else {
            this.dialogTitle = 'New Level';
            this.level = {};//new Course({});
        }

        this.levelForm = this.createLevelForm();
    }
    
    createLevelForm(): FormGroup {
        return this._formBuilder.group({
            id: [this.level.id],
            level_name: [this.level.level_name],
            level_content: [this.level.level_content],
        });
    }

}
