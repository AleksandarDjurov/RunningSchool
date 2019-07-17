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
  selector: 'trainer-courses',
  templateUrl: './tcourses.component.html',
  styleUrls: ['./tcourses.component.scss'],
  animations: fuseAnimations
})

export class TrainerCoursesListComponent implements OnInit {
  model: any = {};
  // courseList: any = [];

  displayedColumns: string[] = ['id', 'qcourse', 'course', 'level', 'price', 'seats', 'status', 'date', 'attend', 'feedback'];
  dataSource: any;

  urlHeader = `${isDevMode() && env.basePageUrl || envProd.basePageUrl}`;
  trainerName : string;

  private _unsubscribeAll: Subject<any>;

  @ViewChild(MatPaginator) paginator: MatPaginator;

  /**
  * Constructor
  *
  * @param {FuseTranslationLoaderService} _fuseTranslationLoaderService
  */
  constructor(
    public globalVars: GlobalService,
    private router: Router,
    private _fuseTranslationLoaderService: FuseTranslationLoaderService,
    private _Service: MySchoolTrainersService,
    private _location: Location
  ) {
    this._fuseTranslationLoaderService.loadTranslations(english, italian);
    this._unsubscribeAll = new Subject();
  }

  ngOnInit() {
    this._Service.onCoursesChanged
      .pipe(takeUntil(this._unsubscribeAll))
      .subscribe(data => {
        if( data.length > 0 )
          this.trainerName = data[0].trainer_first_name + ' ' + data[0].trainer_last_name;
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

  getDateOnly(szDate: string) {
    let arrDate = szDate.split(' ');
    return arrDate[0];
  }

  goBack()
  {
    // var url = '/schooltrainer/1/' + this.globalVars.g_nSchoolID;
    // this.router.navigate([url]);  
    this._location.back();
  }

  viewAttend(element)
  {
    // path: 'attend/:mode/:aid/:cid',
    var url = '/attend/1/0/' + element.id;
    this.router.navigate([url]);  
  }

  viewFeedback(element)
  {
    // path: 'attendsum/:mode/:aid/:cid',
    var url = '/attendsum/2/0/' + element.id;
    this.router.navigate([url]);  
  }
}
