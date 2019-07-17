import { environment as env } from '../../../../environments/environment';
import { environment as envProd } from '../../../../environments/environment.prod';
import { Component, OnDestroy, OnInit, ViewEncapsulation, isDevMode } from '@angular/core';
import { FormBuilder, FormGroup } from '@angular/forms';
import { Location } from '@angular/common';
import { MatSnackBar } from '@angular/material';
import { Subject } from 'rxjs';
import { takeUntil } from 'rxjs/operators';
import { Router } from '@angular/router';

import { fuseAnimations } from '@fuse/animations';
import { FuseUtils } from '@fuse/utils';

import { PersonalTrainer } from './personaltrainer.model';
import { PersonalTrainerService } from './personaltrainer.service';
import { GlobalService } from '../../../services/global.service';
import { TrainerFormDialogComponent } from 'app/main/trainers/trainer-form/trainer-form.component';
import { FuseConfirmDialogComponent } from '@fuse/components/confirm-dialog/confirm-dialog.component';
import { MatDialog, MatDialogRef } from '@angular/material/dialog';

@Component({
    selector: 'personaltrainer',
    templateUrl: './personaltrainer.component.html',
    styleUrls: ['./personaltrainer.component.scss'],
    encapsulation: ViewEncapsulation.None,
    animations: fuseAnimations
})

export class PersonalTrainerComponent implements OnInit, OnDestroy {
    form: FormGroup;
    dialogRef: any;
    // urlHeader = 'http://localhost';
    urlHeader = `${isDevMode() && env.basePageUrl || envProd.basePageUrl}`;

    personaltrainer: PersonalTrainer;
    pageType: string;
    schoolForm: FormGroup;
    displayMode: Number;

    nTrainerNumber: Number;

    // Private
    private _unsubscribeAll: Subject<any>;

    confirmDialogRef: MatDialogRef<FuseConfirmDialogComponent>;
    /**
     * Constructor
     *
     * @param {PersonalTrainerService} _PersonalTrainerService
     * @param {FormBuilder} _formBuilder
     * @param {Location} _location
     * @param {MatSnackBar} _matSnackBar
     */
    constructor(
        private _PersonalTrainerService: PersonalTrainerService,
        private _formBuilder: FormBuilder,
        private _location: Location,
        private _matSnackBar: MatSnackBar,
        public  globalVars: GlobalService,
        private router: Router,
        private _matDialog: MatDialog
    ) {
        this.nTrainerNumber = globalVars.g_nTrainerID;
        this.displayMode = this.globalVars.g_nDisplyTrainerMode;

        // Set the default
        // this.personaltrainer = new PersonalTrainer();

        // Set the private defaults
        this._unsubscribeAll = new Subject();
    }

    /**
     * On init
     */
    ngOnInit(): void {
        // Subscribe to update techmanager on changes
        this._PersonalTrainerService.onTrainerChanged
            .pipe(takeUntil(this._unsubscribeAll))
            .subscribe(personaltrainer => {
                if (personaltrainer) {
                    this.personaltrainer = new PersonalTrainer(personaltrainer);
                    this.pageType = 'edit';
                }

                this.schoolForm = this.createschoolForm();
            });

        this.form = this._formBuilder.group({
            first_name: [''],
            last_name: [''],
            gender: [''],
            nationality: [''],
            email: [''],
            birthday: [''],
            birthplace: [''],
            mobile: [''],
            state: [''],
            region: [''],
            province: [''],
            city: [''],
            postalCode: [''],
            group: [''],
            vat_number: [''],
            card_number: [''],
            certified_type: [''],
            fiscalCode: [''],
            membership: [''],
            bio: [''],
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

    // -----------------------------------------------------------------------------------------------------
    // @ Public methods
    // -----------------------------------------------------------------------------------------------------

    /**
     * Create techmanager form
     *
     * @returns {FormGroup}
     */
    createschoolForm(): FormGroup {
        return this._formBuilder.group({
            id: [this.personaltrainer.personal.id],
        });
    }

    acceptTrainer(): void {
        this.dialogRef = this._matDialog.open(TrainerFormDialogComponent, {
            panelClass: 'contact-form-dialog',
            data: {
                action: '1',
                personal_id:this.personaltrainer.personal.id
            }
        });
    }

    rejectTrainer(): void
    {
        this.confirmDialogRef = this._matDialog.open(FuseConfirmDialogComponent, {
            disableClose: false
        });

        this.confirmDialogRef.componentInstance.confirmMessage = 'Are you sure you want to reject?';
        this.confirmDialogRef.afterClosed().subscribe(result => {
            if ( result )
            {
                this._PersonalTrainerService.rejectPersonal(this.personaltrainer.personal.id).subscribe((data: any) => { }, error => { });
                var url = '/apps/trainer/trainers/2';
                this.router.navigate([url]);
            }
            this.confirmDialogRef = null;
        });
    }

    goback(): void {
        var url = '/apps/trainer/trainers/' + this.globalVars.g_nDisplyTrainerMode;
        if (this.globalVars.g_nDisplyTrainerMode == 0)
            url = '/apps/trainer/pending-trainers';
        this.router.navigate([url]);
    }

    viewCourses(): void {
        var url = '/trainercourse/2/0/' + this.personaltrainer.personal.id;
        this.router.navigate([url]);  
    }
}
