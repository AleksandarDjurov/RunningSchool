import { NgModule } from '@angular/core';
import { RouterModule } from '@angular/router';
import { MatIconModule } from '@angular/material/icon';

import { FuseSharedModule } from '@fuse/shared.module';
import { MailConfirmComponent } from 'app/main/authentication/mail-confirm/mail-confirm.component';
import { MailVerifyComponent } from 'app/main/authentication/mail-confirm/mail-verify.component';
import { MailConfirmService } from 'app/main/authentication/mail-confirm/mail-confirm.service';


const routes = [
    {
        path     : 'mail-confirm',
        component: MailConfirmComponent
    },
    {
        path     : 'verify/:id',
        component: MailVerifyComponent,
        // resolve  : {
        //     data: MailConfirmService
        // },
    }
];

@NgModule({
    declarations: [
        MailConfirmComponent,
        MailVerifyComponent
    ],
    imports     : [
        RouterModule.forChild(routes),
        MatIconModule,
        FuseSharedModule
    ],
    providers : [
        MailConfirmService,

    ]
})
export class MailConfirmModule
{
}
