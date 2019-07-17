import { NgModule } from '@angular/core';

import { LoginModule } from 'app/main/authentication/login/login.module';
import { Login2Module } from 'app/main/authentication/login-2/login-2.module';
import { RegisterModule } from 'app/main/authentication/register/register.module';
import { ContactusModule } from 'app/main/authentication/contactus/contactus.module';
import { Register2Module } from 'app/main/authentication/register-2/register-2.module';
import { ForgotPasswordModule } from 'app/main/authentication/forgot-password/forgot-password.module';
import { ForgotPassword2Module } from 'app/main/authentication/forgot-password-2/forgot-password-2.module';
import { ResetPasswordModule } from 'app/main/authentication/reset-password/reset-password.module';
import { ResetPassword2Module } from 'app/main/authentication/reset-password-2/reset-password-2.module';
// import { LockModule } from 'app/main/authentication/lock/lock.module';
import { MailConfirmModule } from 'app/main/authentication/mail-confirm/mail-confirm.module';
import { RouterModule, Routes } from '@angular/router';

import { MatProgressSpinnerModule } from '@angular/material/progress-spinner';
import { MatDialogModule } from '@angular/material/dialog';
import { LoadingComponent } from './common/loading/loading.component';
import { ResultsComponent } from './common/results/results.component';
// import { FuseConfirmDialogComponent } from '@fuse/components/confirm-dialog/confirm-dialog.component';

const appRoutes: Routes = [
    {
        path        : 'forms',
        loadChildren: 'app/main/inputforms/forms.module#MainFormsModule'
    },
    {
        path        : 'auth',
        loadChildren: 'app/main/authentication/auth.module#AuthFormsModule'
    },
];

@NgModule({
    imports: [
        // Authentication
        MatProgressSpinnerModule,
        MatDialogModule,

        LoginModule,
        Login2Module,
        RegisterModule,
        ContactusModule,
        Register2Module,
        ForgotPasswordModule,
        ForgotPassword2Module,
        ResetPasswordModule,
        ResetPassword2Module,
        
        // LockModule,
        MailConfirmModule,
        RouterModule.forRoot(appRoutes),
    ],
    declarations: [LoadingComponent, ResultsComponent],
    entryComponents: [LoadingComponent, ResultsComponent]
})
export class PagesModule {}
