import { NgModule } from '@angular/core';
import { RouterModule } from '@angular/router';
import { MatButtonModule } from '@angular/material/button';
import { MatCheckboxModule } from '@angular/material/checkbox';
import { MatFormFieldModule } from '@angular/material/form-field';
import { MatIconModule } from '@angular/material/icon';
import { MatInputModule } from '@angular/material/input';
import { MatProgressSpinnerModule } from '@angular/material/progress-spinner';

import { MatOptionModule } from '@angular/material/core';
import { MatSelectModule } from '@angular/material/select';

import { FuseSharedModule } from '@fuse/shared.module';
import { ContactusComponent } from 'app/main/authentication/contactus/contactus.component';

import { FormsModule, ReactiveFormsModule} from '@angular/forms';
import { MatDialogModule } from '@angular/material/dialog';
import { ResultsComponent } from '../../common/results/results.component';

// import { MatProgressBar } from '@angular/material/progress-bar';

const routes = [
    {
        path     : 'contactus',
        component: ContactusComponent
    }
];

@NgModule({
    declarations: [
        ContactusComponent,
    ],
    imports     : [
        RouterModule.forChild(routes),

        MatDialogModule,
        MatButtonModule,
        MatCheckboxModule,
        MatFormFieldModule,
        MatIconModule,
        MatInputModule,
        MatOptionModule,
        MatSelectModule,
        MatProgressSpinnerModule,

        FuseSharedModule,
        FormsModule,
        ReactiveFormsModule.withConfig({warnOnNgModelWithFormControl: 'never'})
    ],
})
export class ContactusModule
{
}
