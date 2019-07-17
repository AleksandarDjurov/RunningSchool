import { Component, OnInit, ViewChild, isDevMode } from '@angular/core';
import { MatPaginator, MatTableDataSource } from '@angular/material';

import { FuseTranslationLoaderService } from '@fuse/services/translation-loader.service';
import { FuseConfirmDialogComponent } from '@fuse/components/confirm-dialog/confirm-dialog.component';

import { locale as english } from './i18n/en';
import { locale as italian } from './i18n/it';
import { MySchoolTrainersService } from './mytrainers.service';
// import { CourseFormDialogComponent } from 'app/main/academy/mcourse/course-form/course-form.component';
import { fuseAnimations } from '@fuse/animations';
import { takeUntil } from 'rxjs/operators';
import { Subject } from 'rxjs';

import { environment as env } from '../../../../../environments/environment';
import { environment as envProd } from '../../../../../environments/environment.prod';

import { Router } from '@angular/router';
import { GlobalService } from '../../../../services/global.service';

import { Location } from '@angular/common';

/**
 * @title Table with pagination
 */

export interface GroupListElement {
  id: number;
  course_name: string;
  levels: string;
  from_date: string;
  to_date: string;
  status: string;
  course_content: string;
}

@Component({
  selector: 'myschool-trainers',
  templateUrl: './mytrainers.component.html',
  styleUrls: ['./mytrainers.component.scss'],
  animations: fuseAnimations
})

export class MySchoolTrainerListComponent implements OnInit {
  model: any = {};
  // courseList: any = [];

  displayedColumns: string[] = ['id', 'avatar', 'name', 'email', 'phone', 'address', 'date', 'courseview'];
  dataSource: any;

  urlHeader = `${isDevMode() && env.basePageUrl || envProd.basePageUrl}`;
  schoolName : string;

  private _unsubscribeAll: Subject<any>;

  @ViewChild(MatPaginator) paginator: MatPaginator;

  /**
  * Constructor
  *
  * @param {FuseTranslationLoaderService} _fuseTranslationLoaderService
  */
  constructor(
    public globalVars: GlobalService,
    private _fuseTranslationLoaderService: FuseTranslationLoaderService,
    private _Service: MySchoolTrainersService,
    private router: Router,
    private _location: Location
  ) {
    this._fuseTranslationLoaderService.loadTranslations(english, italian);
    this._unsubscribeAll = new Subject();
  }

  ngOnInit() {
    this._Service.onTrainersChanged
      .pipe(takeUntil(this._unsubscribeAll))
      .subscribe(data => {

        if( data.length > 0 )
          this.schoolName = data[0].school_name;          

        this.dataSource = new MatTableDataSource<GroupListElement>(data);
        this.dataSource.paginator = this.paginator;
      });
  }

  getStatusName(status: number) {
    if (status == 0)
      return "Pending";
    else if (status == 1)
      return "Activated";
    else if (status == 2)
      return "Closed";
  }

  getDescriptionBy50(szDesc: string) {
    if (szDesc.length < 50)
      return szDesc;
    else {
      szDesc = szDesc.substring(0, 50);
      szDesc += '...';

      return szDesc;
    }
  }

  getDateOnly(szDate: string) {
    let arrDate = szDate.split(' ');
    return arrDate[0];
  }

  getDashDate(date: any) {
    let dt: Date;
    dt = new Date(date);
    return dt.getFullYear() + '-' + (dt.getMonth() + 1) + '-' + dt.getDate();
  }

  viewCourse(element)
  {
    // console.log(element);
    var url = '/trainercourse/2/' + element.school_id + '/' + element.id;
    this.router.navigate([url]);    
  }

  goBack()
  {
    // this._location.back();
    var url = '/apps/school/school/'+ this.globalVars.g_nSchoolID;
    this.router.navigate([url]);
  }
}
