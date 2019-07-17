import { NgModule } from '@angular/core';
import { RouterModule } from '@angular/router';

import { MatButtonModule } from '@angular/material/button';
import { MatFormFieldModule } from '@angular/material/form-field';
import { MatIconModule } from '@angular/material/icon';
import { MatInputModule } from '@angular/material/input';
import { MatSelectModule } from '@angular/material/select';

import { FuseSharedModule } from '@fuse/shared.module';

import { SCoursesComponent } from 'app/main/course/scourse/scourses/scourses.component';
// import { SCourseComponent } from 'app/main/course/scourse/scourse/scourse.component';

import { SCoursesService } from 'app/main/course/scourse/scourses.service';
import { FuseSidebarModule } from '@fuse/components';
import { SCourseFormDialogComponent } from 'app/main/course/scourse/scourse-form/scourse-form.component';
import { ActivateFormDialogComponent } from 'app/main/course/scourse/activate-form/activate-form.component';

import { MatDatepickerModule, MatToolbarModule ,MatSlideToggleModule } from '@angular/material';
import { ColorPickerModule } from 'ngx-color-picker';
import { ReactiveFormsModule } from '@angular/forms';

const routes = [
    {
        path     : 'scourses/:id',
        component: SCoursesComponent,
        resolve  : {
            course: SCoursesService
        }
    },
    // {
    //     path     : 'scourses/:scourseId/:scourseSlug',
    //     component: SCourseComponent,
    //     resolve  : {
    //         course: SCourseService
    //     }
    // }
];

@NgModule({
    declarations: [
        SCoursesComponent,
        SCourseFormDialogComponent,
        ActivateFormDialogComponent
        // SCourseComponent
    ],
    imports     : [
        RouterModule.forChild(routes),

        MatButtonModule,
        MatFormFieldModule,
        MatIconModule,
        MatInputModule,
        MatSelectModule,
        MatDatepickerModule,
        MatToolbarModule,
        MatSlideToggleModule,
        ColorPickerModule,

        FuseSharedModule,
        FuseSidebarModule,
        ReactiveFormsModule.withConfig({warnOnNgModelWithFormControl: 'never'})
    ],
    providers   : [
        SCoursesService,
        // SCourseService
    ],
    entryComponents: [
        SCourseFormDialogComponent,
        ActivateFormDialogComponent
    ]
})
export class SCourseModule
{
}
