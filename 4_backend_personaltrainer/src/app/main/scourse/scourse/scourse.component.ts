import { Component, OnInit, ViewChild } from '@angular/core';
import { MatPaginator, MatTableDataSource } from '@angular/material';

import { FuseTranslationLoaderService } from '@fuse/services/translation-loader.service';
import { FuseConfirmDialogComponent } from '@fuse/components/confirm-dialog/confirm-dialog.component';

import { locale as english } from './i18n/en';
import { locale as italian } from './i18n/it';
import { SchoolCourseService } from './scourse.service';
import { fuseAnimations } from '@fuse/animations';
import { takeUntil } from 'rxjs/operators';
import { Subject } from 'rxjs';
import { StringNullableChain } from 'lodash';

/**
 * @title Table with pagination
 */

export interface GroupListElement {
  id: number;
  school_id: number;
  pic: string;
  course_id: number;
  level_id: number;
  course_name: string;
  course_desc: string;
  course_dates: string;
  course_days: number;
  course_seats: number;
  price: number;
  status: number;
  trainer_id: number;
  author_id: number;
  created_at: string;
  updated_at: string;
  deleted_at: string;
  quarter_name: string;
  level_name: string;
  trainer_first_name: string;
  trainer_last_name: string;
  author_first_name: string;
  author_last_name: string;
}

@Component({
  selector: 'school-course',
  templateUrl: './scourse.component.html',
  styleUrls: ['./scourse.component.scss'],
  animations: fuseAnimations
})

export class SchoolCourseListComponent implements OnInit {
  model: any = {};
  // courseList: any = [];

  displayedColumns: string[] = ['id', 'course_name', 'levels', 'price', 'status', 'trainer_name', 'course_seats', 'course_dates'];
  dataSource: any;

  private _unsubscribeAll: Subject<any>;

  @ViewChild(MatPaginator, { static: true }) paginator: MatPaginator;

  /**
  * Constructor
  *
  * @param {FuseTranslationLoaderService} _fuseTranslationLoaderService
  */
  constructor(
    private _fuseTranslationLoaderService: FuseTranslationLoaderService,
    private _Service: SchoolCourseService,
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
    if (szDesc == null)
      return '';

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
