import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import {
    MatButtonModule, MatCheckboxModule, MatDatepickerModule, MatFormFieldModule, MatIconModule, MatInputModule, MatMenuModule, MatRippleModule, MatTableModule, MatToolbarModule
} from '@angular/material';

import { FuseSharedModule } from '@fuse/shared.module';
import { FuseConfirmDialogModule, FuseSidebarModule } from '@fuse/components';
import { AthletesComponent } from 'app/main/athlete/athlete.component';

import { AthleteService } from 'app/main/athlete/athlete.service';
import { AthletesListComponent } from 'app/main/athlete/athlete-list/athlete-list.component';

import { AthletePlayerComponent } from 'app/main/athlete/athlete/athlete.component';
import { AthletePlayerService } from 'app/main/athlete/athlete/athlete.service';


import { AthleteSelectedBarComponent } from 'app/main/athlete/selected-bar/selected-bar.component';
import { AthleteMainSidebarComponent } from 'app/main/athlete/sidebars/main/main.component';
import { TranslateModule } from '@ngx-translate/core';

const routes: Routes = [
    {
        path     : 'athletes/:id',
        component: AthletesComponent,
        resolve  : { contacts: AthleteService }
    },
    {
        path     : 'athlete/:id/:cid/:rid',
        component: AthletePlayerComponent,
        resolve  : { data: AthletePlayerService },
    }
];

@NgModule({
    declarations   : [
        AthletesComponent,
        AthletePlayerComponent,
        AthletesListComponent,
        AthleteSelectedBarComponent,
        AthleteMainSidebarComponent,
    ],
    imports        : [
        RouterModule.forChild(routes),

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
        FuseSidebarModule
    ],
    providers      : [
        AthleteService,
        AthletePlayerService
    ]
})
export class AthletesModule
{
}
