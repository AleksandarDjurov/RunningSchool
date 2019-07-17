import { environment as env } from '../../../../environments/environment';
import { environment as envProd } from '../../../../environments/environment.prod';
import { Component, OnDestroy, OnInit, ViewEncapsulation, isDevMode } from '@angular/core';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { Location } from '@angular/common';
import { MatSnackBar } from '@angular/material';
import { Subject } from 'rxjs';
import { takeUntil } from 'rxjs/operators';
import { Router } from '@angular/router';

import { fuseAnimations } from '@fuse/animations';
import { FuseUtils } from '@fuse/utils';

import { SchoolData } from './school.model';
import { SchoolService } from './school.service';
import { GlobalService } from '../../../services/global.service';

import { NgxSpinnerService } from 'ngx-spinner';

@Component({
    selector: 'school',
    templateUrl: './school.component.html',
    styleUrls: ['./school.component.scss'],
    encapsulation: ViewEncapsulation.None,
    animations: fuseAnimations
})

export class SchoolComponent implements OnInit, OnDestroy {
    form: FormGroup;

    schoolData: SchoolData;
    school: any;
    school_manager: any;

    pageType: string;
    schoolForm: FormGroup;
    displayMode: Number;

    // urlHeader = 'http://localhost';
    urlHeader = `${isDevMode() && env.basePageUrl || envProd.basePageUrl}`;

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
        public globalVars: GlobalService,
        private spinner: NgxSpinnerService,
        private _SchoolService: SchoolService,
        private _formBuilder: FormBuilder,
        private _location: Location,
        private _matSnackBar: MatSnackBar,
        private router: Router
    ) {
        this.displayMode = globalVars.g_nDisplySchoolMode;
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
        // Subscribe to update school on changes
        this._SchoolService.onSchoolChanged
            .pipe(takeUntil(this._unsubscribeAll))
            .subscribe(retData => {

                if (retData) {
                    this.schoolData = new SchoolData(retData);
                    this.school = this.schoolData.school;
                    this.school_manager = this.schoolData.school_manager;

                    this.pageType = 'edit';
                    this.globalVars.g_nDisplySchoolMode = this.schoolData.school.status;
                    this.displayMode = this.globalVars.g_nDisplySchoolMode;
                }

                this.schoolForm = this.createschoolForm();
            });


        this.form = this._formBuilder.group({
            name: [''],
            asd: [''],
            company: [''],
            school_state: [''],
            school_region: [''],
            school_province: [''],
            school_city: [''],
            school_address: [''],
            school_postalCode: [''],
            school_membership: [''],
            first_name: [''],
            last_name: [''],
            gender: [''],
            email: [''],
            birthday: [''],
            mobile: [''],
            state: [''],
            region: [''],
            province: [''],
            city: [''],
            postalCode: [''],
            group: [''],
            vat_number: [''],
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
     * Create school form
     *
     * @returns {FormGroup}
     */
    createschoolForm(): FormGroup {
        return this._formBuilder.group({
            id: [this.schoolData.school.id],
            logourl: [this.schoolData.school.logo_path],
            imageurl: [this.schoolData.school.banner_path],
            address: [this.schoolData.school.address],
            schoolname: [this.schoolData.school.name],
            firstname: [this.schoolData.school_manager.first_name],
            lastname: [this.schoolData.school_manager.last_name],
            email: [this.schoolData.school_manager.email],
            phone: [this.schoolData.school_manager.mobile_phone],
            status: [this.schoolData.school.status],
            create: [this.schoolData.school.created_at]
        });
    }

    /**
     * Save school
     */

    viewTrainers(): void {
        var url = '/schooltrainer/1/' + this.globalVars.g_nSchoolID;
        this.router.navigate([url]);        
    }

    viewTechnicalManager(): void {
        var url = '/apps/school/techmanager/' + this.globalVars.g_nSchoolID;
        this.router.navigate([url]);
    }

    goBackToSchool(): void {
        var url = '/apps/school/schools/' + this.globalVars.g_nDisplySchoolMode;
        this.router.navigate([url]);
    }

}
