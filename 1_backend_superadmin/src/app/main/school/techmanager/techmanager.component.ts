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

import { SchoolData } from '../school/school.model';
import { SchoolService } from '../school/school.service';
import { SchoolListService } from '../schools/schools.service';
import { GlobalService } from '../../../services/global.service';

import { MatDialog, MatDialogRef } from '@angular/material/dialog';
import { FuseConfirmDialogComponent } from '@fuse/components/confirm-dialog/confirm-dialog.component';

@Component({
    selector: 'technmanager',
    templateUrl: './techmanager.component.html',
    styleUrls: ['./techmanager.component.scss'],
    encapsulation: ViewEncapsulation.None,
    animations: fuseAnimations
})

export class TechnManagerComponent implements OnInit, OnDestroy {
    form: FormGroup;
    dialogRef: any;

    _schoolData: SchoolData;
    techmanager: any;

    pageType: string;
    schoolForm: FormGroup;
    displayMode: Number;

    nSchoolNumber: Number;
    // urlHeader = 'http://localhost';
    urlHeader = `${isDevMode() && env.basePageUrl || envProd.basePageUrl}`;
    
    confirmDialogRef: MatDialogRef<FuseConfirmDialogComponent>;

    // Private
    private _unsubscribeAll: Subject<any>;

    /**
     * Constructor
     *
     * @param {SchoolService} _SchoolService
     * @param {FormBuilder} _formBuilder
     * @param {Location} _location
     * @param {MatSnackBar} _matSnackBar
     */
    constructor(
        private _SchoolService: SchoolService,
        private _SchoolListService: SchoolListService,
        private _formBuilder: FormBuilder,
        private _location: Location,
        private _matSnackBar: MatSnackBar,
        private globalVars: GlobalService,
        private router: Router,
        public _matDialog: MatDialog
    ) {
        this.displayMode = globalVars.g_nDisplySchoolMode;
        this.nSchoolNumber = globalVars.g_nSchoolID;

        // Set the default
        // this.schoolData = new SchoolData();

        // Set the private defaults
        this._unsubscribeAll = new Subject();
    }

    // -----------------------------------------------------------------------------------------------------
    // @ Lifecycle hooks
    // -----------------------------------------------------------------------------------------------------

    /**
     * On init
     */
    ngOnInit(): void {
        // Subscribe to update techmanager on changes
        this._SchoolService.onSchoolChanged
            .pipe(takeUntil(this._unsubscribeAll))
            .subscribe(data => {
                if (data) {
                    this._schoolData = new SchoolData(data);
                    this.techmanager = this._schoolData.technical_person;
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
            id: [this._schoolData.technical_person],
        });
    }

    viewSchoolManager(): void {
        var url = '/apps/school/school/' + this.globalVars.g_nSchoolID;
        this.router.navigate([url]);
    }

    gobackToSchool(): void {
        var url = '/apps/school/schools/' + this.globalVars.g_nDisplySchoolMode;
        this.router.navigate([url]);
    }

    updateSchool(status) {
        this._SchoolService.updateSchool(status).subscribe((data: any) => { }, error => { });
    }

    updateTechnicalManager(user_id, status) {
        this._SchoolService.updateTechnicalManager(user_id, status).subscribe((data: any) => { }, error => { });
    }

    takeAction(mode): void
    {
        this.confirmDialogRef = this._matDialog.open(FuseConfirmDialogComponent, {
            disableClose: false
        });

        this.confirmDialogRef.componentInstance.confirmMessage = 'Are you sure you want to accept?';
        if( mode == 2)
            this.confirmDialogRef.componentInstance.confirmMessage = 'Are you sure you want to reject?';
            
        this.confirmDialogRef.afterClosed().subscribe(result => {
            if ( result )
            {
                this.updateSchool(mode);

                if ( mode == 1 ) // accpet
                    this.updateTechnicalManager(this._schoolData.technical_person.id, 1);
                else if( mode == 2) // reject
                    this.updateTechnicalManager(this._schoolData.technical_person.id, 0);

                this._SchoolListService.reloadSchools(mode);
                var url = '/apps/school/schools/'+ mode;
                this.router.navigate([url]);
            }
            this.confirmDialogRef = null;
        });
    }

    acceptSchool(): void {
        this.takeAction(1);
    }

    rejectSchool(): void {
        this.takeAction(2);
    }

    // view course contents.
    viewCourses() : void {
    // console.log(element);
        var url = '/trainercourse/2/' + this.nSchoolNumber + '/' + this.techmanager.id;
        this.router.navigate([url]);
    }
}
