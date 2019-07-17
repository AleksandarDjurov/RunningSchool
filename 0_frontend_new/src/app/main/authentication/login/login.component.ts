import { Component, OnInit, ViewChild, ElementRef, Inject } from '@angular/core';
import { UsersService } from '../../../services/users.service';
import { LocalsessionService } from '../../../services/localsession.service'
import { GlobalService } from '../../../services/global.service';
import { Router } from '@angular/router';
import { ViewEncapsulation } from '@angular/core';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';

import { FuseConfigService } from '@fuse/services/config.service';
import { fuseAnimations } from '@fuse/animations';
import { MatDialog, MatDialogRef } from '@angular/material/dialog';
import { LoadingComponent } from '../../common/loading/loading.component';

@Component({
    selector: 'login',
    templateUrl: './login.component.html',
    styleUrls: ['./login.component.scss'],
    encapsulation: ViewEncapsulation.None,
    animations: fuseAnimations
})
export class LoginComponent implements OnInit {
    loginForm: FormGroup;
    model: any = {};

    token: String;
    user_id: String;
    role: String;
    retResult: String;

    reqData: any = {};
    szPublicUrl: string;

    spinnerDlg: MatDialogRef<LoadingComponent>;

    /**
     * Constructor
     *
     * @param {FuseConfigService} _fuseConfigService
     * @param {FormBuilder} _formBuilder
     */
    constructor(
        private dialog: MatDialog,
        private globalVars: GlobalService,
        private session: LocalsessionService,
        private userService: UsersService,

        private _fuseConfigService: FuseConfigService,
        private _formBuilder: FormBuilder
    ) {
        this.szPublicUrl = userService.PAGE_URL;

        // Configure the layout
        this._fuseConfigService.config = {
            layout: {
                navbar: { hidden: true },
                toolbar: { hidden: true },
                footer: { hidden: true },
                sidepanel: { hidden: true }
            }
        };
    }

    // -----------------------------------------------------------------------------------------------------
    // @ Lifecycle hooks
    // -----------------------------------------------------------------------------------------------------

    /**
     * On init
     */
    ngOnInit(): void {
        this.loginForm = this._formBuilder.group({
            email: ['', [Validators.required, Validators.email]],
            password: ['', Validators.required]
        });

        if ( this.globalVars._gVerifiedMode == true )
        {
            this.globalVars._gVerifiedMode = false;

            this.model.email    = this.session.retrieveVerifyEmail();
            this.model.password = this.session.retrieveVerifyPassword();

            this.onSubmit();
        }
    }

    showSpinner() {
        this.spinnerDlg = this.dialog.open(LoadingComponent, { panelClass: 'transparent', disableClose: true });
    }

    hideSpinner() {
        this.spinnerDlg.close();
    }

    reqChangeLocation() {
        this.reqData['user_id'] = this.session.retrieveUserID();
        this.reqData['token'] = this.session.retrieveToken();

        setTimeout(() => {
            let form: HTMLFormElement = <HTMLFormElement>document.getElementById('userForm');
            form.submit();
        }, 1);
    }

    onSubmit() {
        /** spinner starts */
        this.showSpinner();

        this.userService.login(this.model).subscribe(
            (data: any) => {
                /** spinner ends */
                this.hideSpinner();

                this.role = data['role'];
                this.token = data['token'];
                this.user_id = data['user_id'];
                this.retResult = data['msg'];

                // this.showSuccessMessage();

                var retCode = data['code'];
                if (retCode == '200') {
                    this.globalVars._gShowStage = 1;
                    this.session.saveLogicStage('loggedin');
                    this.session.saveToken(this.token);
                    this.session.saveUserID(this.user_id);
                    this.session.saveVerifyEmail(this.model.email);
        
                    // this.router.navigate(['/extpages']);
                    this.reqChangeLocation();
                }
            },
            error => {
                /** spinner ends */
                this.hideSpinner();
                this.retResult = 'Operation Failed due to server down or something else.'
                // this.showSuccessMessage();
            });
    }
}
