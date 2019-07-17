import { Component, OnInit, ViewChild } from '@angular/core';
import { MatPaginator, MatTableDataSource } from '@angular/material';

import { FuseTranslationLoaderService } from '@fuse/services/translation-loader.service';

import { locale as english } from './i18n/en';
import { locale as italian } from './i18n/it';
import { MainCourseService } from './mcourse.service';
import { fuseAnimations } from '@fuse/animations';
import { takeUntil } from 'rxjs/operators';
import { Subject } from 'rxjs';

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
  selector: 'main-course',
  templateUrl: './mcourse.component.html',
  styleUrls: ['./mcourse.component.scss'],
  animations: fuseAnimations
})

export class MainCourseListComponent implements OnInit {
  model: any = {};
  // courseList: any = [];

  displayedColumns: string[] = ['id', 'course_name', 'levels', 'from_date', 'to_date', 'status', 'course_content'];
  dataSource: any;

  private _unsubscribeAll: Subject<any>;
  @ViewChild(MatPaginator, {static:true}) paginator: MatPaginator;

  /**
  * Constructor
  *
  * @param {FuseTranslationLoaderService} _fuseTranslationLoaderService
  */
  constructor(
    private _fuseTranslationLoaderService: FuseTranslationLoaderService,
    private _Service: MainCourseService,
  ) {
    this._fuseTranslationLoaderService.loadTranslations(english, italian);
    this._unsubscribeAll = new Subject();
  }

  ngOnInit() {
    this._Service.onCoursesChanged
      .pipe(takeUntil(this._unsubscribeAll))
      .subscribe(data => {
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
}
