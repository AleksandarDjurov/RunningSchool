import { NgModule, CUSTOM_ELEMENTS_SCHEMA } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import {
    MatButtonModule, MatChipsModule, MatExpansionModule, MatFormFieldModule, MatIconModule, MatInputModule, MatPaginatorModule, MatRippleModule, MatSelectModule, MatSnackBarModule,
    MatSortModule,
    MatTableModule, MatTabsModule
} from '@angular/material';
import { NgxChartsModule } from '@swimlane/ngx-charts';
// import { AgmCoreModule } from '@agm/core';

import { FuseSharedModule } from '@fuse/shared.module';
import { FuseWidgetModule } from '@fuse/components/widget/widget.module';

import { SchoolListComponent } from './schools/schools.component';
import { SchoolListService } from './schools/schools.service';
import { SchoolComponent } from './school/school.component';
import { SchoolService } from './school/school.service';
import { TechnManagerComponent } from './techmanager/techmanager.component';
// import { TechnManagerService } from './techmanager/techmanager.service';

const routes: Routes = [
    {
        path     : 'schools/:mode',
        component: SchoolListComponent,
        resolve  : {
            data: SchoolListService
        }
    },
    {
        path     : 'techmanager/:id',
        component: TechnManagerComponent,
        resolve  : {
            data: SchoolService
        }
    },
    {
        path     : 'school/:id',
        component: SchoolComponent,
        resolve  : {
            data: SchoolService
        }
    },
];

@NgModule({
    declarations: [
        SchoolListComponent,
        SchoolComponent,
        TechnManagerComponent
    ],
    imports     : [
        RouterModule.forChild(routes),

        MatButtonModule,
        MatChipsModule,
        MatExpansionModule,
        MatFormFieldModule,
        MatIconModule,
        MatInputModule,
        MatPaginatorModule,
        MatRippleModule,
        MatSelectModule,
        MatSortModule,
        MatSnackBarModule,
        MatTableModule,
        MatTabsModule,

        NgxChartsModule,

        FuseSharedModule,
        FuseWidgetModule
    ],
    providers   : [
        SchoolListService,
        SchoolService,
        // TechnManagerService
    ],
    schemas: [ CUSTOM_ELEMENTS_SCHEMA ],
})
export class EcommerceModule
{
}
