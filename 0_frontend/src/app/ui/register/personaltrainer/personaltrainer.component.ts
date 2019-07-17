import { Component, OnInit, ViewChild, ElementRef } from '@angular/core';
import { RegionService } from '../../../services/region.service';
import { RegisterService } from '../../../services/register.service';
import { GlobalService } from '../../../services/global.service';
import { LocalsessionService } from '../../../services/localsession.service';
import { NgxSpinnerService } from 'ngx-spinner';
import { NgbModalConfig, NgbModal } from '@ng-bootstrap/ng-bootstrap';
import { Router } from '@angular/router';

@Component({
  selector: 'app-personaltrainer',
  templateUrl: './personaltrainer.component.html',
  styleUrls: ['./personaltrainer.component.css'],
  providers: [NgbModalConfig, NgbModal]
})
export class PersonaltrainerComponent implements OnInit {
  @ViewChild("modal_result") modal_ret: ElementRef;

  public model: any = {};

  public imagePath;
  public message: string;
  public manager_logo_file: File;
  //---------------------------------//
  public imgUserURL: any;

  arrListMRegions: any[];
  arrListMProvinces: any[];
  arrListMCities: any[];

  uploadfile_names: any = [];
  retResult: String;

  // files to be uploaded.
  uploadfiles: File[] = [];

  constructor(private regionService: RegionService,
    private registerService: RegisterService,
    private localsessionService: LocalsessionService,
    private globalService: GlobalService,
    private router: Router,
    private spinner: NgxSpinnerService,
    private modalService: NgbModal) { }

  ngOnInit() {
  }

    //---------------------------
    uploadFile(event) {
      for (let index = 0; index < event.length; index++) {
        const element = event[index];
  
        this.uploadfile_names.push(element.name);
        this.uploadfiles.push(<File>event[index]);
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
  
    onSubmit() {
      // let logicStage = this.localsessionService.retrieveLogicStage();
      // if (logicStage == 'technical_registered') {
      //   this.retResult = 'You have already sent register information, Please wait until Super Administrator reply to you. Thanks.'
      //   this.showSuccessMessage();
      //   return;
      // }
  
      this.localsessionService.saveLogicStage('technical_registered');
  
      this.spinner.show();
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
          this.spinner.hide();
          this.retResult = data['msg'];
          this.showSuccessMessage();
  
          var retCode = data['code'];
          if (retCode == '200') {
            // this.router.navigate(['/']);
  
            this.localsessionService.saveLogicStage('technical_registered');
            this.globalService._gRegisterMode = 1;
            this.router.navigate(['/']);
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
