import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import {
    MatChipsModule, MatExpansionModule, MatPaginatorModule,  MatSelectModule, MatSnackBarModule, MatSortModule, MatTabsModule, MatButtonModule, MatCheckboxModule, MatDatepickerModule, MatFormFieldModule, MatIconModule, MatInputModule, MatMenuModule, MatRippleModule, MatTableModule, MatToolbarModule
} from '@angular/material';

import { FuseSharedModule } from '@fuse/shared.module';
import { FuseConfirmDialogModule, FuseSidebarModule } from '@fuse/components';

import { TrainersComponent } from 'app/main/trainers/trainers.component';
import { TrainersService } from 'app/main/trainers/trainers.service';
import { TrainersTrainerListComponent } from 'app/main/trainers/trainer-list/trainer-list.component';
import { TrainersSelectedBarComponent } from 'app/main/trainers/selected-bar/selected-trainerbar.component';
import { TrainersMainSidebarComponent } from 'app/main/trainers/sidebars/main/trainermain.component';
import { PersonalTrainerComponent } from 'app/main/trainers/personaltrainer/personaltrainer.component';
import { PersonalTrainerService } from 'app/main/trainers/personaltrainer/personaltrainer.service';
import { PendingTrainerListComponent } from 'app/main/trainers/pending-list/pending-list.component';
import { PendingTrainerListService } from 'app/main/trainers/pending-list/pending-list.service';
import { TrainerFormDialogComponent } from 'app/main/trainers/trainer-form/trainer-form.component';
import { SchoolListService } from 'app/main/school/schools/schools.service';

import { TranslateModule } from '@ngx-translate/core';
import { ReactiveFormsModule } from '@angular/forms';

const routes: Routes = [
    {
        path     : 'trainers/:id',
        component: TrainersComponent,
        resolve  : {
            data: TrainersService
        },
    },
    {
        path     : 'trainer/:id',
        component: PersonalTrainerComponent,
        resolve  : {
            data: PersonalTrainerService
        },
    },
    {
        path     : 'pending-trainers',
        component: PendingTrainerListComponent,
        resolve  : {
            data: PendingTrainerListService
        },
    }
];

@NgModule({
    declarations   : [
        TrainersComponent,
        TrainersTrainerListComponent,
        TrainersSelectedBarComponent,
        TrainersMainSidebarComponent,
        TrainerFormDialogComponent,
        PersonalTrainerComponent,
        PendingTrainerListComponent
    ],
    imports        : [
        RouterModule.forChild(routes),

        MatChipsModule,
        MatExpansionModule,
        MatPaginatorModule,
        MatSelectModule,
        MatSnackBarModule,
        MatSortModule,
        MatTabsModule,

        MatButtonModule,
        MatCheckboxModule,
        MatDatepickerModule,
        MatFormFieldModule,
        MatIconModule,
        MatInputModule,
        MatMenuModule,
        MatRippleModule,
        MatTableModule,
        MatToolbarModule,

        TranslateModule,

        FuseSharedModule,
        FuseConfirmDialogModule,
        FuseSidebarModule,
        ReactiveFormsModule.withConfig({warnOnNgModelWithFormControl: 'never'})
    ],
    providers      : [
        TrainersService,
        PersonalTrainerService,
        PendingTrainerListService,
        SchoolListService
    ],
    entryComponents: [
        TrainerFormDialogComponent
    ]
})
export class TrainersModule
{
}
