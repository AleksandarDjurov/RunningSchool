import { Component, OnInit, ViewEncapsulation } from '@angular/core';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';

import { FuseConfigService } from '@fuse/services/config.service';
import { fuseAnimations } from '@fuse/animations';
import { Router } from '@angular/router';

import { GlobalService } from '../../../services/global.service';
import { UsersService } from '../../../services/users.service';
import { MatDialog, MatDialogRef } from '@angular/material/dialog';
import { LocalsessionService } from '../../../services/localsession.service';
import { LoadingComponent } from '../../common/loading/loading.component';
import { ResultsComponent } from '../../common/results/results.component';

@Component({
    selector: 'forms',
    templateUrl: './forms.component.html',
    styleUrls: ['./forms.component.scss'],
    encapsulation: ViewEncapsulation.None,
    animations: fuseAnimations
})

export class FormsComponent implements OnInit {
    /**
     * Constructor
     *
     * @param {FuseConfigService} _fuseConfigService
     * @param {FormBuilder} _formBuilder
     */
    inputMode: number;
    verticalStepperStep1: FormGroup;

    bIsVerified: boolean;
    spinnerDlg: MatDialogRef<LoadingComponent>;
    model: any = {};

    constructor(
        private dialog: MatDialog,
        private userService: UsersService,
        private localsessionService: LocalsessionService,
        private globalService: GlobalService,

        private _fuseConfigService: FuseConfigService,
        private _formBuilder: FormBuilder,
        private router: Router
    ) {
        this.bIsVerified = false;

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
        this.verticalStepperStep1 = this._formBuilder.group({
            role: ['', Validators.required],
            contact_key: ['', Validators.required],
        });
    }

    roleChanged(event) {
        if (event.value == '2')
            this.inputMode = 2;
        else if (event.value == '4')
            this.inputMode = 4;
        else if (event.value == '5')
            this.inputMode = 5;
    }

    showSpinner() {
        this.spinnerDlg = this.dialog.open(LoadingComponent, { panelClass: 'transparent', disableClose: true });
    }

    hideSpinner() {
        this.spinnerDlg.close();
    }
    
    showSuccessMessage() {
        // this.bLoading = false;
        const dialogRef = this.dialog.open(ResultsComponent, {
            panelClass: 'transparent',
            data: { title: 'Contact Key', content: "Please input correct key. You can get it from your mailbox." },
        });

        dialogRef.afterClosed().subscribe(result => {});
    }

    done() {
        
        // if (this.inputMode == 1)
        // this.router.navigate(['forms/schoolform']);
        //     else if (this.inputMode == 3)
        // this.router.navigate(['forms/trainer']);
        // return;

        if (this.inputMode == 5)
        {
            this.router.navigate(['forms/ahtlete']);
            return;
        }

        this.showSpinner();

        this.userService.verifyContactID(this.model).subscribe(
            (data: any) => {
                /** spinner ends */
                this.hideSpinner();
                // this.retResult = data['msg'];

                var retCode = data['code'];
                var confirm = data['confirm'];
                if (retCode === 200 && confirm === "1") {

                    if (this.inputMode == 2)
                        this.router.navigate(['forms/schoolform']);
                    else if (this.inputMode == 4)
                        this.router.navigate(['forms/trainer']);
                }
                else
                    this.showSuccessMessage();
            },
            error => {
                this.hideSpinner();
                this.showSuccessMessage();
            });
    }

    goBack() {
        this.router.navigate(['auth/login']);
    }
}
