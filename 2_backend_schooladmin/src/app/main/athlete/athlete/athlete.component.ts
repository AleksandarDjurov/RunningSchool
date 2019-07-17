import { environment as env } from '../../../../environments/environment';
import { environment as envProd } from '../../../../environments/environment.prod';
import { Component, OnDestroy, OnInit, ViewEncapsulation, isDevMode } from '@angular/core';
import { FormBuilder, FormGroup } from '@angular/forms';
import { MatDialog, MAT_DIALOG_DATA, MatDialogRef } from '@angular/material';
import { Subject } from 'rxjs';
import { takeUntil } from 'rxjs/operators';
import { Router } from '@angular/router';

import { fuseAnimations } from '@fuse/animations';
import { FuseUtils } from '@fuse/utils';

import { AthletePlayer } from './athlete.model';
import { AthletePlayerService } from './athlete.service';
import { AthleteService } from 'app/main/athlete/athlete.service';
import { GlobalService } from '../../../services/global.service';

import { ResultsComponent } from '../../common/results/results.component';
import { LoadingComponent } from '../../common/loading/loading.component';
import { FuseConfirmDialogComponent } from '@fuse/components/confirm-dialog/confirm-dialog.component';

@Component({
    selector: 'athlete',
    templateUrl: './athlete.component.html',
    styleUrls: ['./athlete.component.scss'],
    encapsulation: ViewEncapsulation.None,
    animations: fuseAnimations
})

export class AthletePlayerComponent implements OnInit, OnDestroy {
    form: FormGroup;
    dialogRef: any;
    confirmDialogRef: MatDialogRef<FuseConfirmDialogComponent>;

    // urlHeader = 'http://localhost';
    urlHeader = `${isDevMode() && env.basePageUrl || envProd.basePageUrl}`;

    athleteplayer: AthletePlayer;
    pageType: string;
    schoolForm: FormGroup;
    displayMode: Number;

    userID: string;
    courseID: string;
    reserveID : string;

    spinnerDlg: MatDialogRef<LoadingComponent>;
    msgContent: string;
    msgTitle: string;

    // Private
    private _unsubscribeAll: Subject<any>;

    constructor(
        private _matDialog: MatDialog,
        private _AthletePlayerService: AthletePlayerService,
        private _AthleteService: AthleteService,
        private _formBuilder: FormBuilder,
        public globalVars: GlobalService,
        private router: Router,
    ) {
        this.displayMode = _AthleteService.dispMode.id;
        this.courseID = _AthletePlayerService.courseId;
        this.userID = _AthletePlayerService.user_id;
        this.reserveID = _AthletePlayerService.reserve_id;

        // Set the private defaults
        this._unsubscribeAll = new Subject();
    }

    /**
     * On init
     */
    ngOnInit(): void {
        // Subscribe to update techmanager on changes
        this._AthletePlayerService.onAthleteChanged
            .pipe(takeUntil(this._unsubscribeAll))
            .subscribe(athlete => {
                if (athlete) {
                    this.athleteplayer = new AthletePlayer(athlete);
                    this.pageType = 'edit';
                }

                this.schoolForm = this.createschoolForm();
            });

        this.form = this._formBuilder.group({
            first_name: [''], last_name: [''], gender: [''], nationality: [''],
            email: [''], birthday: [''], birthplace: [''], mobile: [''], state: [''],
            region: [''], province: [''], city: [''], postalCode: [''], group: [''], vat_number: [''],
            card_number: [''], certified_type: [''], fiscalCode: [''], membership: [''], bio: [''],
            // first_name: ['', [Validators.required, Validators.maxLength(5)]],
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

    /**
     * Create techmanager form
     *
     * @returns {FormGroup}
     */
    createschoolForm(): FormGroup {
        return this._formBuilder.group({
            id: [this.athleteplayer.personal.id],
        });
    }

    viewAttendance(): void {
        // userID : string;
        // courseID : string;
        this.router.navigate(['/attend/1/' + this.userID + '/' + this.courseID]);
    }

    viewFeedback(): void {
        this.router.navigate(['/attendsum/2/' + this.userID + '/' + this.courseID]);
    }

    showSpinner() {
        this.spinnerDlg = this._matDialog.open(LoadingComponent, { panelClass: 'transparent', disableClose: true });
    }

    hideSpinner() {
        this.spinnerDlg.close();
    }

    showMessage() {
        // this.bLoading = false;
        const dialogRef = this._matDialog.open(ResultsComponent, {
            panelClass: 'transparent',
            data: { title: this.msgTitle, content: this.msgContent },
        });

        dialogRef.afterClosed().subscribe(result => { });
    }

    goback(): void {
        this.router.navigate(['/athletes/' + this.displayMode]);
    }

    onApprove()
    {
        this.confirmDialogRef = this._matDialog.open(FuseConfirmDialogComponent, {
            disableClose: false
        });

        this.confirmDialogRef.componentInstance.confirmMessage = 'Are you sure you want to approve?';
        this.confirmDialogRef.afterClosed().subscribe(result => {
            if (result)
                this.processApprove();

            this.confirmDialogRef = null;
        });
    }

    onReject()
    {
        this.confirmDialogRef = this._matDialog.open(FuseConfirmDialogComponent, {
            disableClose: false
        });

        this.confirmDialogRef.componentInstance.confirmMessage = 'Are you sure you want to reject?';
        this.confirmDialogRef.afterClosed().subscribe(result => {
            if (result)
                this.processApprove();

            this.confirmDialogRef = null;
        });
    }

    processApprove() {
        this.showSpinner();
        this._AthletePlayerService.approveReservation(this.reserveID).subscribe(
            (data: any) => {
                this.hideSpinner();

                let code = data['code']; let msg = data['msg'];
                if (code === '200' && msg === 'Completed successfully!') {
                    this.msgContent = 'Approve operation completed.';
                    this.showMessage();
                    this.router.navigate(['/athletes/1']);
                }
            },
            error => {
                this.hideSpinner();
                this.msgContent = 'Operation failed.';
                this.showMessage();
            });
    }

    processReject() {
        this.showSpinner();
        this._AthletePlayerService.rejectReservation(this.reserveID).subscribe(
            (data: any) => {
                this.hideSpinner();

                let code = data['code'];
                let msg = data['msg'];
                if (code === '200' && msg === 'Completed successfully!') {
                    this.msgContent = 'Reject operation completed.';
                    this.showMessage();
                    this.goback();
                }
            },
            error => {
                this.hideSpinner();
                this.msgContent = 'Operation failed.';
                this.showMessage();
            });
    }
}
