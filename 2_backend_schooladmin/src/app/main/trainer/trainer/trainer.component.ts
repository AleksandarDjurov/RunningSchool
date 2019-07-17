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

import { PersonalTrainer } from './trainer.model';
import { PersonalTrainerService } from './trainer.service';
import { GlobalService } from '../../../services/global.service';
// import { TrainerFormDialogComponent } from 'app/main/trainers/trainer-form/trainer-form.component';
import { FuseConfirmDialogComponent } from '@fuse/components/confirm-dialog/confirm-dialog.component';
import { MatDialog, MatDialogRef } from '@angular/material/dialog';

@Component({
    selector: 'trainer',
    templateUrl: './trainer.component.html',
    styleUrls: ['./trainer.component.scss'],
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

    viewActivity() : void {
        this.router.navigate(['/tcourses/' + this.personaltrainer.personal.id]);
    }

    goback(): void {
        this.router.navigate(['/trainers']);
    }
}
