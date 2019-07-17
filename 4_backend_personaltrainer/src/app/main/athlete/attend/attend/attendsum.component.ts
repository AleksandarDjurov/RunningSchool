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

import { MatDialog, MatDialogRef } from '@angular/material/dialog';
import { FeedbackFormDialogComponent } from 'app/main/athlete/attend/feedback-form/feedback-form.component';
import { FormControl, FormGroup } from '@angular/forms';

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
  displayedColumns: string[] = ['id', 'avatar', 'name', 'total', 'present', 'late', 'absent', 'review', 'score', 'modify'];

  dataSource: any;
  urlHeader = `${isDevMode() && env.basePageUrl || envProd.basePageUrl}`;

  feedbackDlgRef: MatDialogRef<FeedbackFormDialogComponent>;

  private _unsubscribeAll: Subject<any>;

  @ViewChild(MatPaginator, { static: true }) paginator: MatPaginator;

  attendList: AtthendListElement[] = [];
  /**
  * Constructor
  *
  * @param {FuseTranslationLoaderService} _fuseTranslationLoaderService
  */
  constructor(
    private _fuseTranslationLoaderService: FuseTranslationLoaderService,
    private _Service: AttendService,
    private localsession: LocalsessionService,
    private _matDialog: MatDialog
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

  editFeedback(item) {
    // your delete code
    this.feedbackDlgRef = this._matDialog.open(FeedbackFormDialogComponent, {
      panelClass: 'feedback-form-dialog',
      data: { review: item.review, score: item.score }
    });

    this.feedbackDlgRef.afterClosed()
      .subscribe(response => {
        if (!response)
          return;

        const formData: FormGroup = response;
        this._Service.updateFeedback(item.attend_id, formData.value.review, formData.value.score);

        this.feedbackDlgRef = null;
      });
  }

  // ratingClicked: number;
  // itemIdRatingClicked: string;

  ratingComponentClick(clickObj: any): void {
    const item = this.attendList.find(((i: any) => i.id === clickObj.itemId));
    if (!!item) {
      item.score = clickObj.rating;
      // this.ratingClicked = clickObj.rating;

      this._Service.updateFeedback(item.attend_id, item.review, item.score);
    }
  }
}
