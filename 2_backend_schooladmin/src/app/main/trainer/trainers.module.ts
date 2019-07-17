import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import {
    MatChipsModule, MatExpansionModule, MatPaginatorModule,  MatSelectModule, MatSnackBarModule, MatSortModule, MatTabsModule, MatButtonModule, MatCheckboxModule, MatDatepickerModule, MatFormFieldModule, MatIconModule, MatInputModule, MatMenuModule, MatRippleModule, MatTableModule, MatToolbarModule
} from '@angular/material';

import { FuseSharedModule } from '@fuse/shared.module';
import { FuseConfirmDialogModule, FuseSidebarModule } from '@fuse/components';

import { PersonalTrainerComponent } from 'app/main/trainer/trainer/trainer.component';
import { PersonalTrainerService } from 'app/main/trainer/trainer/trainer.service';

import { TrainerListComponent } from 'app/main/trainer/trainer-list/trainer-list.component';
import { TrainerListService } from 'app/main/trainer/trainer-list/trainer-list.service';

import { TranslateModule } from '@ngx-translate/core';
import { ReactiveFormsModule } from '@angular/forms';

const routes: Routes = [
    {
        path     : 'trainers',
        component: TrainerListComponent,
        resolve  : {
            data: TrainerListService
        },
    },
    {
        path     : 'trainer/:id',
        component: PersonalTrainerComponent,
        resolve  : {
            data: PersonalTrainerService
        },
    }
];

@NgModule({
    declarations   : [
        TrainerListComponent,
        PersonalTrainerComponent
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
        TrainerListService,
        PersonalTrainerService
    ],
    // entryComponents: [
    //     TrainerFormDialogComponent
    // ]
})
export class TrainersModule
{
}
