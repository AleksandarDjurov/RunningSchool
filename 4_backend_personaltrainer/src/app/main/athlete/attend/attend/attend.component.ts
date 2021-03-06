import { environment as env } from '../../../../../environments/environment';
import { environment as envProd } from '../../../../../environments/environment.prod';
import { Component, ViewChild, OnInit, ViewEncapsulation, isDevMode } from '@angular/core';
import { MatPaginator, MatTableDataSource } from '@angular/material';
import { FuseTranslationLoaderService } from '@fuse/services/translation-loader.service';

import { locale as english } from './i18n/en';
import { locale as italian } from './i18n/it';

import { AttendService } from './attend.service';
import { LocalsessionService } from '../../../../services/localsession.service';
import { takeUntil } from 'rxjs/operators';
import { Subject } from 'rxjs';

/**
 * @title Table with pagination
 */

export interface Dates {
  date: string;
}

export interface Attend {
  attend: string;
}

export interface AtthendListElement {
  attend_id : string;
  athlete_id: number;
  first_name: string;
  last_name: string;
  pic: string;
  dates: Dates[];
  attend: Attend[];
}

@Component({
  selector: 'attend-list',
  templateUrl: './attend.component.html',
  styleUrls: ['./attend.component.scss'],
  encapsulation: ViewEncapsulation.None,
})

export class AttendListComponent implements OnInit {
  displayedColumns: string[] = ['id', 'avatar', 'name'];
  datesColumns: string[] = [];

  dataSource: any;
  urlHeader = `${isDevMode() && env.basePageUrl || envProd.basePageUrl}`;

  private _unsubscribeAll: Subject<any>;

  @ViewChild(MatPaginator, { static: true }) paginator: MatPaginator;

  /**
  * Constructor
  *
  * @param {FuseTranslationLoaderService} _fuseTranslationLoaderService
  */
  constructor(
    private _fuseTranslationLoaderService: FuseTranslationLoaderService,
    private _Service: AttendService,
    private localsession: LocalsessionService,
  ) {
    this._fuseTranslationLoaderService.loadTranslations(english, italian);
    this._unsubscribeAll = new Subject();
  }

  ngOnInit() {
    this._Service.onAttendListChanged
      .pipe(takeUntil(this._unsubscribeAll))
      .subscribe(attendList => {

        // console.log('attehndList', attendList);
        this.displayedColumns = ['id', 'avatar', 'name'];
        this.datesColumns = [];

        if (attendList.length > 0) {
          let dates = attendList[0].dates;
          for (let i = 0; i < dates.length; i++) {
            let dt = dates[i].split('-');
            this.displayedColumns.push(dt[1] + '/' + dt[2]);
            this.datesColumns.push(dt[1] + '/' + dt[2]);
          }
        }

        this.dataSource = new MatTableDataSource<AtthendListElement>(attendList);
        this.dataSource.paginator = this.paginator;
      });

  }

  modify(item) {
    // your delete code
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

  onClickDate(i, element) {
    // console.log('i', i, ' element', element);

    let attend = element.attend;
    let ids = element.ids;
    let athlete_id = element.athlete_id;

    let status = '1';
    if (attend[i] === '0')
      status = '1';
    else if (attend[i] === '1')
      status = '2';
    else if (attend[i] === '2')
      status = '0';

    this._Service.addAttend(athlete_id, ids[i], status)
  }
}
