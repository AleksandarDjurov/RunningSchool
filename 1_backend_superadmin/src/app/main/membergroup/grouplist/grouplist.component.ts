import { Component, OnInit, ViewChild } from '@angular/core';
import { MatPaginator, MatTableDataSource } from '@angular/material';

import { FuseTranslationLoaderService } from '@fuse/services/translation-loader.service';

import { locale as english } from './i18n/en';
import { locale as italian } from './i18n/it';

import { GroupsService } from '../../../services/groups.service';
import { LocalsessionService } from '../../../services/localsession.service';

import { NgxSpinnerService } from 'ngx-spinner';

/**
 * @title Table with pagination
 */

export interface GroupListElement {
  id: number;
  slug:string;
  name: string;
  persons : number;
}

@Component({
  selector: 'app-grouplist',
  templateUrl: './grouplist.component.html',
  styleUrls: ['./grouplist.component.scss']
})

export class GrouplistComponent implements OnInit {
  model : any = {};
  groupList: any = [];
 
  displayedColumns: string[] = ['id', 'name', 'persons', 'actions'];
  dataSource : any;

  @ViewChild(MatPaginator) paginator: MatPaginator;

  /**
  * Constructor
  *
  * @param {FuseTranslationLoaderService} _fuseTranslationLoaderService
  */
  constructor(
    private _fuseTranslationLoaderService: FuseTranslationLoaderService,
    private groupsService : GroupsService,
    private localsession : LocalsessionService,
    private spinner: NgxSpinnerService,
  ) {
    this._fuseTranslationLoaderService.loadTranslations(english, italian);
  }

  ngOnInit() {
    this.model['token'] = this.localsession.retrieveToken();
    this.model['user_id'] = this.localsession.retrieveUserID();
    this.spinner.show();

    // this.model['token'] = 'MTU2MDUxMDU4N21yclNxcVpXU3liYzI1S2Q1Z3dZ';
    // this.model['user_id'] = '1';

    this.groupsService.getGroupList(this.model).subscribe(
      (data: any) => {
        /** spinner ends */
        this.spinner.hide();
       
        this.dataSource = new MatTableDataSource<GroupListElement>(data);
        this.dataSource.paginator = this.paginator;
      },
      error => {
        /** spinner ends */
        this.spinner.hide();
      });
  }

  modify(item) {
    // your delete code
  }
}
