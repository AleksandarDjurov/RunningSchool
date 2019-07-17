import { Component, OnInit, ViewChild } from '@angular/core';
import { MatPaginator, MatTableDataSource } from '@angular/material';

import { FuseTranslationLoaderService } from '@fuse/services/translation-loader.service';
import { FuseConfirmDialogComponent } from '@fuse/components/confirm-dialog/confirm-dialog.component';

import { locale as english } from './i18n/en';
import { locale as italian } from './i18n/it';
import { FormControl, FormGroup } from '@angular/forms';
import { LevelsService } from './levels.service';
import { LocalsessionService } from '../../../../services/localsession.service';
import { MatDialog, MatDialogRef } from '@angular/material';
import { LevelFormDialogComponent } from 'app/main/academy/lcourse/lcourse-form/lcourse-form.component';
import { fuseAnimations } from '@fuse/animations';
import { takeUntil } from 'rxjs/operators';
import { Subject } from 'rxjs';

/**
 * @title Table with pagination
 */

export interface LevelListElement {
  id: number;
  level_name: string;
  level_content: string;
  level_pic: string;
  status: number;
  course_id: number;
  created_at: string;
  updated_at: string;
  deleted_at: string;
  course_name: string;
}

@Component({
  selector: 'levels',
  templateUrl: './levels.component.html',
  styleUrls: ['./levels.component.scss'],
  animations: fuseAnimations
})

export class LevelsListComponent implements OnInit {
  model: any = {};
  // courseList: any = [];

  dialogRef: any;
  confirmDialogRef: MatDialogRef<FuseConfirmDialogComponent>;

  displayedColumns: string[] = ['id', 'level_name', 'created_at', 'level_content', 'modify', 'remove'];
  dataSource: any;

  private _unsubscribeAll: Subject<any>;

  @ViewChild(MatPaginator) paginator: MatPaginator;

  /**
  * Constructor
  *
  * @param {FuseTranslationLoaderService} _fuseTranslationLoaderService
  */
  constructor(
    public _Service: LevelsService,
    private _fuseTranslationLoaderService: FuseTranslationLoaderService,
    private localsession: LocalsessionService,
    private _matDialog: MatDialog,
  ) {
    this._fuseTranslationLoaderService.loadTranslations(english, italian);
    this._unsubscribeAll = new Subject();
  }

  ngOnInit() {
    this._Service.onLevelsChanged
      .pipe(takeUntil(this._unsubscribeAll))
      .subscribe(data => {
        this.dataSource = new MatTableDataSource<LevelListElement>(data);
        this.dataSource.paginator = this.paginator;
      });
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

  newLevel(): void {
    this.dialogRef = this._matDialog.open(LevelFormDialogComponent, {
      panelClass: 'levels-form-dialog',
      data: {
        action: 'new'
      }
    });

    this.dialogRef.afterClosed()
      .subscribe((response: FormGroup) => {
        if (!response) {
          return;
        }
        const actionType: string = response[0];
        const formData: FormGroup = response[1];

        switch (actionType) {
          case 'new':
            this._Service.addLevels(formData.getRawValue());
            break;
        }
      });
  }

  editLevel(item): void {

    this.dialogRef = this._matDialog.open(LevelFormDialogComponent, {
      panelClass: 'levels-form-dialog',
      data: {
        level: item,
        action: 'edit',
      }
    });

    this.dialogRef.afterClosed()
      .subscribe(response => {
        if (!response) {
          return;
        }
        const actionType: string = response[0];
        const formData: FormGroup = response[1];
        switch (actionType) {
          case 'save':
            this._Service.updateLevels(formData.getRawValue());
            break;
        }
      });
  }

  deleteLevel(level): void
  {
      this.confirmDialogRef = this._matDialog.open(FuseConfirmDialogComponent, {
          disableClose: false
      });

      this.confirmDialogRef.componentInstance.confirmMessage = 'Are you sure you want to delete?';
      this.confirmDialogRef.afterClosed().subscribe(result => {
          if ( result )
          {
            this._Service.deleteLevels(level.id);
          }
          this.confirmDialogRef = null;
      });

  }
}
