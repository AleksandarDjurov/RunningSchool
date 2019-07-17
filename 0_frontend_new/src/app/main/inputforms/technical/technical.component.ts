import { Component, OnInit, ViewEncapsulation } from '@angular/core';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { FuseConfigService } from '@fuse/services/config.service';
import { fuseAnimations } from '@fuse/animations';
import { RegionService } from '../../../services/region.service';
import { Router } from '@angular/router';

import { RegisterService } from '../../../services/register.service';
import { GlobalService } from '../../../services/global.service';
import { LocalsessionService } from '../../../services/localsession.service';

import { MatDialog, MatDialogRef } from '@angular/material/dialog';
import { LoadingComponent } from '../../common/loading/loading.component';
import { ResultsComponent } from '../../common/results/results.component';

@Component({
    selector: 'technical',
    templateUrl: './technical.component.html',
    styleUrls: ['./technical.component.scss'],
    encapsulation: ViewEncapsulation.None,
    animations: fuseAnimations
})

export class TechnicalFormComponent implements OnInit {
    /**
     * Constructor
     *
     * @param {FuseConfigService} _fuseConfigService
     * @param {FormBuilder} _formBuilder
     */

    spinnerDlg: MatDialogRef<LoadingComponent>;
    retResult: String;

    //-------------------
    uploadfile_names: any = [];
    // files to be uploaded.
    uploadfiles: File[] = [];

    //---------------------------
    public model: any = {};

    //---------------------------
    public imagePath;

    public imgBannerURL: any;
    public imgLogoURL: any;
    public imgUserURL: any;

    public message: string;

    //---------------------------
    public banner_file: File;
    public school_logo_file: File;
    public manager_logo_file: File;
    //---------------------------

    //---------------------------
    verticalStepperStep1: FormGroup;
    verticalStepperStep2: FormGroup;
    verticalStepperStep3: FormGroup;
    verticalStepperStep4: FormGroup;
    //-------------------------

    arrListRegions: any[];
    arrListProvinces: any[];
    arrListCities: any[];
    //-------------------------
    arrListMRegions: any[];
    arrListMProvinces: any[];
    arrListMCities: any[];
    //-------------------------

    // email = new FormControl('', [Validators.required, Validators.email]);

    constructor(
        private dialog: MatDialog,
        private registerService: RegisterService,
        private localsessionService: LocalsessionService,
        private globalService: GlobalService,

        private regionService: RegionService,
        private _fuseConfigService: FuseConfigService,
        private _formBuilder: FormBuilder,
        private router: Router
        // private _formBuilder: FormBuilder
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
    }

    // -----------------------------------------------------------------------------------------------------
    // @ Lifecycle hooks
    // -----------------------------------------------------------------------------------------------------

    email_messages: [
        { type: 'required', message: 'Email is required' },
        { type: 'pattern', message: 'Enter a valid email' }
    ];

    /**
     * On init
     */
    ngOnInit(): void {

        let MOBILE_PATTERN = /[0-9\+\-\ ]/;
        let EMAIL_PATTERN = /[a-zA-Z0-9.-_]{1,}@[a-zA-Z.-]{2,}[.]{1}[a-zA-Z]{2,}/;
        this.verticalStepperStep1 = this._formBuilder.group({});
        this.verticalStepperStep2 = this._formBuilder.group({
            first_name: ['', Validators.required],
            last_name: ['', Validators.required],
            gender: ['', Validators.required],
            nationality: ['', Validators.required],
            birth_date: ['', Validators.required],
            // email: ['', Validators.required, Validators.email],
            // email: ['', Validators.required, Validators.pattern('^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+.[a-zA-Z0-9-.]+$')],
            email: ['', Validators.pattern('^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+.[a-zA-Z0-9-.]+$')],
            phone: ['', Validators.pattern(MOBILE_PATTERN)],
            birth_place: ['', Validators.required],
            fiscalCode: ['', Validators.required],
            postal: ['', Validators.required],
            vat_number: ['', Validators.required],
            card_number: ['', Validators.required],
        });

        this.verticalStepperStep3 = this._formBuilder.group({
            state: ['', Validators.required],
            region: ['', Validators.required],
            province: ['', Validators.required],
            city: ['', Validators.required],
            address: ['', Validators.required],
        });

        this.verticalStepperStep4 = this._formBuilder.group({
            membership_type: ['', Validators.required],
            certified_type: ['', Validators.required],
            bio: ['', Validators.required],
        });
    }

    previewBanner(files) {
        if (files.length === 0) {
            this.imgBannerURL = '';
            return;
        }

        var mimeType = files[0].type;
        if (mimeType.match(/image\/*/) == null) {
            this.message = "Only images are supported.";
            return;
        }

        this.banner_file = <File>files[0];

        var reader = new FileReader();
        this.imagePath = files;
        reader.readAsDataURL(files[0]);
        reader.onload = (_event) => {
            this.imgBannerURL = reader.result;
        }
    }

    previewLogo(files) {
        if (files.length === 0) {
            this.imgLogoURL = '';
            return;
        }

        var mimeType = files[0].type;
        if (mimeType.match(/image\/*/) == null) {
            this.message = "Only images are supported.";
            return;
        }

        this.school_logo_file = <File>files[0];

        var reader = new FileReader();
        this.imagePath = files;
        reader.readAsDataURL(files[0]);
        reader.onload = (_event) => {
            this.imgLogoURL = reader.result;
        }
    }

    //-------------------------------
    school_partChanged(event) {
        this.regionService.getRegionList(this.model.school_state).subscribe(
            (data: any) => {
                this.arrListRegions = data;

                if (data.length < 1) {
                    this.arrListProvinces = [];
                    this.arrListCities = [];
                }
            },
            error => {
                this.arrListRegions = [];
                this.arrListProvinces = [];
                this.arrListCities = [];
            });
    }

    school_regionChanged(event) {
        this.regionService.getProvinceList(this.model.school_region).subscribe(
            (data: any) => {
                this.arrListProvinces = data;
            },
            error => {
                this.arrListProvinces = [];
            });
    }

    school_provinceChanged(event) {
        this.regionService.getCityList(this.model.school_province).subscribe(
            (data: any) => {
                this.arrListCities = data;
            },
            error => {
                this.arrListCities = [];
            });
    }
    //-----------------------------------------

    //-------------------------------
    partChanged(event) {
        this.regionService.getRegionList(this.model.state).subscribe(
            (data: any) => {
                this.arrListMRegions = data;

                if (data.length < 1) {
                    this.arrListMProvinces = [];
                    this.arrListMCities = [];
                }
            },
            error => {
                this.arrListMRegions = [];
                this.arrListMProvinces = [];
                this.arrListMCities = [];
            });
    }

    regionChanged(event) {
        this.regionService.getProvinceList(this.model.region).subscribe(
            (data: any) => {
                this.arrListMProvinces = data;
            },
            error => {
                this.arrListMProvinces = [];
            });
    }

    provinceChanged(event) {
        this.regionService.getCityList(this.model.province).subscribe(
            (data: any) => {
                this.arrListMCities = data;
            },
            error => {
                this.arrListMCities = [];
            });
    }
    //-----------------------------------------

    previewUserImage(files) {
        if (files.length === 0) {
            this.imgUserURL = '';
            return;
        }

        var mimeType = files[0].type;
        if (mimeType.match(/image\/*/) == null) {
            this.message = "Only images are supported.";
            return;
        }

        this.manager_logo_file = <File>files[0];

        var reader = new FileReader();
        this.imagePath = files;
        reader.readAsDataURL(files[0]);
        reader.onload = (_event) => {
            this.imgUserURL = reader.result;
        }
    }

    //---------------------------
    uploadFile(event) {
        for (let index = 0; index < event.length; index++) {
            const element = event[index];

            this.uploadfile_names.push(element.name);
            this.uploadfiles.push(<File>event[index]);
        }
    }

    deleteAttachment(index) {
        this.uploadfile_names.splice(index, 1)
        this.uploadfiles.splice(index, 1)
    }
    //---------------------------

    goBack() {
        this.router.navigate(['forms/schoolform']);
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
            data: { title: 'School Manager ( Register )', content: this.retResult },
        });

        dialogRef.afterClosed().subscribe(result => {});
    }
    
    sendRegister() {
        // let logicStage = this.localsessionService.retrieveLogicStage();
        // if (logicStage == 'technical_registered') {
        //   this.retResult = 'You have already sent register information, Please wait until Super Administrator reply to you. Thanks.'
        //   this.showSuccessMessage();
        //   return;
        // }

        this.showSpinner();
        let school_id = this.localsessionService.retrieveSchoolID();

        this.model['school_id'] = school_id;
        this.model['group'] = '3';
        if (this.manager_logo_file)
            this.model['pic_file'] = this.manager_logo_file;

        if (this.uploadfile_names.length) {
            this.model['upload_length'] = this.uploadfile_names.length;

            for (let i = 0; i < this.uploadfile_names.length; i++)
                this.model['school_upload[' + i + ']'] = this.uploadfiles[i];
        }

        this.registerService.register_technical(this.model).subscribe(
            (data: any) => {
                /** spinner ends */
                this.hideSpinner();

                this.retResult = data['msg'];
                this.showSuccessMessage();

                var retCode = data['code'];
                if (retCode == '200') {
                    // this.router.navigate(['/']);

                    this.localsessionService.saveLogicStage('technical_registered');
                    this.globalService._gRegisterMode = 1;
                    this.router.navigate(['auth/login']);
                }
            },
            error => {
                /** spinner ends */
                this.hideSpinner();
                this.retResult = 'Operation Failed due to server down or something else.'
                this.showSuccessMessage();
            });
    }
}
