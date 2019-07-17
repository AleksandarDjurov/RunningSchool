import { Component, OnInit, ViewChild, ElementRef } from '@angular/core';
import { RegionService } from '../../../services/region.service';
import { RegisterService } from '../../../services/register.service';
import { GlobalService } from '../../../services/global.service';
import { LocalsessionService } from '../../../services/localsession.service';
import { NgxSpinnerService } from 'ngx-spinner';
import { NgbModalConfig, NgbModal } from '@ng-bootstrap/ng-bootstrap';
import { Router } from '@angular/router';

@Component({
  selector: 'app-schooladmin',
  templateUrl: './schooladmin.component.html',
  styleUrls: ['./schooladmin.component.css'],
  providers: [NgbModalConfig, NgbModal]
})

export class SchooladminComponent implements OnInit {
  @ViewChild("modal_result") modal_ret: ElementRef;
  public model: any = {};

  public imagePath;
  public message: string;

  //-------images for upload-----------//
  public banner_file: File;
  public school_logo_file: File;
  public manager_logo_file: File;
  //---------------------------------//

  public imgBannerURL: any;
  public imgLogoURL: any;
  public imgUserURL: any;

  retResult: String;

  selectedState: string;
  selectedRegion: string;
  selectedProvince: string;
  selectedCity: string;

  selectedMState: string;
  selectedMRegion: string;
  selectedMProvince: string;
  selectedMCity: string;

  arrListRegions: any[];
  arrListProvinces: any[];
  arrListCities: any[];

  arrListMRegions: any[];
  arrListMProvinces: any[];
  arrListMCities: any[];

  uploadfile_names: any = [];

  // files to be uploaded.
  uploadfiles: File[] = [];

  constructor(
    private regionService: RegionService,
    private registerService : RegisterService,
    private localsessionService : LocalsessionService,
    private globalService : GlobalService,
    private router: Router,
    private spinner: NgxSpinnerService,
    private modalService: NgbModal) { }

  ngOnInit() { }

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

  //-------------------------------
  changeState() {
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

  changeRegion() {
    this.regionService.getProvinceList(this.model.school_region).subscribe(
      (data: any) => {
        this.arrListProvinces = data;
      },
      error => {
        this.arrListProvinces = [];
      });
  }

  changeProvince() {
    this.regionService.getCityList(this.model.school_province).subscribe(
      (data: any) => {
        this.arrListCities = data;
      },
      error => {
        this.arrListCities = [];
      });
  }

  //-------------------------------
  changeMState() {
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

  changeMRegion() {
    this.regionService.getProvinceList(this.model.region).subscribe(
      (data: any) => {
        this.arrListMProvinces = data;
      },
      error => {
        this.arrListMProvinces = [];
      });
  }

  changeMProvince() {
    this.regionService.getCityList(this.model.province).subscribe(
      (data: any) => {
        this.arrListMCities = data;
      },
      error => {
        this.arrListMCities = [];
      });
  }
  //-------------------------------

  showSuccessMessage() {
    this.modalService.open(this.modal_ret);
  }

  // trigger when user clicked on register button
  onSubmit() {
    this.spinner.show();

    this.model['group'] = '2';
    if (this.banner_file)
      this.model['banner_file'] = this.banner_file;

    if (this.school_logo_file)
      this.model['logo_file'] = this.school_logo_file;

    if (this.manager_logo_file)
      this.model['pic_file'] = this.manager_logo_file;

    if (this.uploadfile_names.length) {
      this.model['upload_length'] = this.uploadfile_names.length;

      for (let i = 0; i < this.uploadfile_names.length; i++)
        this.model['school_upload[' + i + ']'] = this.uploadfiles[i];
    }

    this.registerService.register_school(this.model).subscribe(
      (data: any) => {
        /** spinner ends */
        this.spinner.hide();
        this.retResult = data['msg'];
        
        var retCode = data['code'];
        if (retCode == '200') {
          // this.router.navigate(['/']);

          let jsonSchool = data['school'];
          this.localsessionService.saveSchoolID(jsonSchool['id']);
          this.localsessionService.saveLogicStage('school_registered');
          this.globalService._gRegisterMode = 1;
          this.router.navigate(['/register']);
        }
      },
      error => {
        /** spinner ends */
        this.spinner.hide();
        this.retResult = 'Operation Failed due to server down or something else.'
        this.showSuccessMessage();
      });
  }
}
