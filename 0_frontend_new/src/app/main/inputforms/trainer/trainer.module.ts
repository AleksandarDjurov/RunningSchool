import { NgModule } from '@angular/core';
import { RouterModule } from '@angular/router';
import { MatButtonModule } from '@angular/material/button';
import { MatCheckboxModule } from '@angular/material/checkbox';
import { MatFormFieldModule } from '@angular/material/form-field';
import { MatIconModule } from '@angular/material/icon';
import { MatInputModule } from '@angular/material/input';
import { MatStepperModule } from '@angular/material/stepper';
import { MatOptionModule } from '@angular/material/core';
import { MatSelectModule } from '@angular/material/select';
import { MatDatepickerModule } from '@angular/material/datepicker';

import { FuseSharedModule } from '@fuse/shared.module';
import { TrainerFormComponent } from 'app/main/inputforms/trainer/trainer.component';

const routes = [
    {
        path     : 'trainer',
        component: TrainerFormComponent
    }
];

@NgModule({
    declarations: [
        TrainerFormComponent,
    ],
    imports     : [
        RouterModule.forChild(routes),

        MatButtonModule,
        MatCheckboxModule,
        MatFormFieldModule,
        MatIconModule,
        MatInputModule,
        MatStepperModule,
        MatOptionModule,
        MatSelectModule,
        MatDatepickerModule,
        FuseSharedModule,
    ],
})
export class UITrainerFormModule
{
}
