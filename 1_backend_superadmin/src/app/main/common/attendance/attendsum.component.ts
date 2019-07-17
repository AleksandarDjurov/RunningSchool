import { environment as env } from '../../../../environments/environment';
import { environment as envProd } from '../../../../environments/environment.prod';
import { Component, ViewChild, OnInit, ViewEncapsulation, isDevMode } from '@angular/core';
import { MatPaginator, MatTableDataSource } from '@angular/material';
import { FuseTranslationLoaderService } from '@fuse/services/translation-loader.service';

import { locale as english } from './i18n/en';
import { locale as italian } from './i18n/it';

import { AttendService } from './attend.service';
import { takeUntil } from 'rxjs/operators';
import { Subject } from 'rxjs';
import { Location } from '@angular/common';

/**
 * @title Table with pagination
 */

export interface AtthendListElement {
  attend_id: string;
  athlete_id: number;
  first_name: string;
  last_name: string;
  pic: string;
  total: number;
  present: number;
  absent: number;
  late: number;
  review: string;
  score: number;
}

@Component({
  selector: 'attendsummary-list',
  templateUrl: './attendsum.component.html',
  styleUrls: ['./attendsum.component.scss'],
  encapsulation: ViewEncapsulation.None,
})

export class AttendSummaryListComponent implements OnInit {
  displayedColumns: string[] = ['id', 'avatar', 'name', 'total', 'present', 'late', 'absent', 'review', 'score'];
  attendList: AtthendListElement[] = [];

  dataSource: any;
  urlHeader = `${isDevMode() && env.basePageUrl || envProd.basePageUrl}`;

  private _unsubscribeAll: Subject<any>;

  @ViewChild(MatPaginator) paginator: MatPaginator;

  /**
  * Constructor
  *
  * @param {FuseTranslationLoaderService} _fuseTranslationLoaderService
  */
  constructor(
    private _fuseTranslationLoaderService: FuseTranslationLoaderService,
    private _Service: AttendService,
    private _location: Location
  ) {
    this._fuseTranslationLoaderService.loadTranslations(english, italian);
    this._unsubscribeAll = new Subject();
  }

  ngOnInit() {
    this._Service.onAttendSummaryListChanged
      .pipe(takeUntil(this._unsubscribeAll))
      .subscribe(attendList => {
        this.attendList = attendList;
        this.dataSource = new MatTableDataSource<AtthendListElement>(attendList);
        this.dataSource.paginator = this.paginator;
      });
  }

  ratingComponentClick(clickObj: any): void {
    const item = this.attendList.find(((i: any) => i.id === clickObj.itemId));
    if (!!item) {
      item.score = clickObj.rating;
    }
  }

  goBack() {
    this._location.back();
  }
}
