import { NgModule } from '@angular/core';
import { RouterModule } from '@angular/router';

import { MatButtonModule } from '@angular/material/button';
import { MatFormFieldModule } from '@angular/material/form-field';
import { MatIconModule } from '@angular/material/icon';
import { MatInputModule } from '@angular/material/input';
import { MatSelectModule } from '@angular/material/select';

import { FuseSharedModule } from '@fuse/shared.module';
import { PCoursesComponent } from 'app/main/pcourse/pcourses/pcourses.component';
import { PCoursesService } from 'app/main/pcourse/pcourses.service';
import { FuseSidebarModule } from '@fuse/components';

import { MatDatepickerModule, MatToolbarModule ,MatSlideToggleModule } from '@angular/material';
import { ColorPickerModule } from 'ngx-color-picker';
import { ReactiveFormsModule } from '@angular/forms';

const routes = [
    {
        path     : 'pcourses/:id',
        component: PCoursesComponent,
        resolve  : {
            course: PCoursesService
        }
    },
];

@NgModule({
    declarations: [
        PCoursesComponent,
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
    providers : [ PCoursesService, ],
    entryComponents: []
})
export class PersonalCourseModule
{
}
