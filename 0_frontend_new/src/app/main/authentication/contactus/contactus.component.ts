import { Component, OnDestroy, OnInit, ViewEncapsulation } from '@angular/core';
import { AbstractControl, FormBuilder, FormGroup, ValidationErrors, ValidatorFn, Validators } from '@angular/forms';
import { Subject } from 'rxjs';
import { takeUntil } from 'rxjs/internal/operators';

import { FuseConfigService } from '@fuse/services/config.service';
import { fuseAnimations } from '@fuse/animations';
import { UsersService } from '../../../services/users.service';
import { GlobalService } from '../../../services/global.service';
import { LocalsessionService } from '../../../services/localsession.service'
import { Router } from '@angular/router';

import { MatDialog, MatDialogRef } from '@angular/material/dialog';
import { LoadingComponent } from '../../common/loading/loading.component';
import { ResultsComponent } from '../../common/results/results.component';

import { FuseConfirmDialogComponent } from '@fuse/components/confirm-dialog/confirm-dialog.component';

@Component({
    selector: 'contactus',
    templateUrl: './contactus.component.html',
    styleUrls: ['./contactus.component.scss'],
    encapsulation: ViewEncapsulation.None,
    animations: fuseAnimations
})

export class ContactusComponent implements OnInit, OnDestroy {
    contactusForm: FormGroup;
    model: any = {};

    retResult: String;
    spinnerDlg: MatDialogRef<LoadingComponent>;
    confirmDialogRef: MatDialogRef<FuseConfirmDialogComponent>;
    bLoading : boolean;

    // Private
    private _unsubscribeAll: Subject<any>;

    constructor(
        private dialog: MatDialog,
        private globalVars: GlobalService,
        private _fuseConfigService: FuseConfigService,
        private _formBuilder: FormBuilder,
        private usersService: UsersService,
        private session: LocalsessionService,
        private router: Router,
    ) {
        // Configure the layout
        this._fuseConfigService.config = {
            layout: {
                navbar: { hidden: true },
                toolbar: { hidden: true },
                footer: { hidden: true },
                sidepanel: { hidden: true }
            }
        };

        // Set the private defaults
        this._unsubscribeAll = new Subject();
        this.bLoading = false;
    }

    /**
     * On init
     */
    ngOnInit(): void {
        let MOBILE_PATTERN = /[0-9\+\-\ ]/;
        this.contactusForm = this._formBuilder.group({
            first_name: ['', Validators.required],
            last_name: ['', Validators.required],
            email: ['', [Validators.required, Validators.email]],
            // phone          : ['', [Validators.required, Validators.email]],
            phone: ['', Validators.pattern(MOBILE_PATTERN)],
            role: ['', Validators.required],
            comment: ['', Validators.required],
        });
    }

    /**
     * On destroy
     */
    ngOnDestroy(): void {
        // Unsubscribe from all subscriptions
        this._unsubscribeAll.next();
        this._unsubscribeAll.complete();
    }

    showSpinner() {
        this.spinnerDlg = this.dialog.open(LoadingComponent, { panelClass: 'transparent', disableClose: true });
    }

    hideSpinner() {
        this.spinnerDlg.close();
    }

    showSuccessMessage() {
        this.bLoading = true;
        // confirmDialogRef: MatDialogRef<FuseConfirmDialogComponent>;
        const dialogRef = this.dialog.open(ResultsComponent, {
            panelClass: 'transparent',
            data: { title: 'Contact us', content: this.retResult },
        });


        dialogRef.afterClosed().subscribe(result => {
        });
    }

    onSubmit() {

        this.showSpinner();

        this.usersService.contactus(this.model).subscribe(
            (data: any) => {
                /** spinner ends */
                this.hideSpinner();

                this.retResult = data['msg'];

                this.showSuccessMessage();

                var retCode = data['code'];
                if (retCode == '200') {
                    this.globalVars._gShowStage = 1;
                    this.session.saveLogicStage('contact_us');
                    this.router.navigate(['/']);
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
