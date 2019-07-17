import { NgModule } from '@angular/core';

import { FormsModule, ReactiveFormsModule} from '@angular/forms';
import { ContactusModule } from 'app/main/authentication/contactus/contactus.module';
import { LoginModule } from 'app/main/authentication/login/login.module';
import { MailConfirmModule } from 'app/main/authentication/mail-confirm/mail-confirm.module';

@NgModule({
    imports: [
        ContactusModule,
        LoginModule,
        FormsModule,
        MailConfirmModule,
        ReactiveFormsModule.withConfig({warnOnNgModelWithFormControl: 'never'})
    ]
})
export class AuthFormsModule {}
