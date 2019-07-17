import { NgModule } from '@angular/core';
import { RouterModule } from '@angular/router';

import { MatButtonModule } from '@angular/material/button';
import { MatFormFieldModule } from '@angular/material/form-field';
import { MatIconModule } from '@angular/material/icon';
import { MatInputModule } from '@angular/material/input';
import { MatSelectModule } from '@angular/material/select';

import { FuseSharedModule } from '@fuse/shared.module';

import { TCoursesComponent } from 'app/main/trainer/tcourse/tcourses/tcourses.component';
import { TCoursesService } from 'app/main/trainer/tcourse/tcourses.service';
import { FuseSidebarModule } from '@fuse/components';

import { MatDatepickerModule, MatToolbarModule ,MatSlideToggleModule } from '@angular/material';
import { ColorPickerModule } from 'ngx-color-picker';
import { ReactiveFormsModule } from '@angular/forms';

const routes = [
    {
        path     : 'tcourses/:id',
        component: TCoursesComponent,
        resolve  : {
            course: TCoursesService
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
        TCoursesComponent,
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
        TCoursesService,
        // SCourseService
    ]
})
export class TCourseModule
{
}
