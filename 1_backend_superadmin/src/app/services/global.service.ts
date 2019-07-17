import { environment as env } from '../../environments/environment';
import { environment as envProd } from '../../environments/environment.prod';
import { HttpClient, HttpHeaders, HttpParams } from '@angular/common/http';
import { Injectable, isDevMode } from '@angular/core';
import { throwError as observableThrowError } from 'rxjs';
import { catchError, map } from 'rxjs/operators';

import { LocalsessionService } from '../services/localsession.service';

@Injectable({
  providedIn: 'root'
})

export class GlobalService {
  public LOGOUT_URL = `${isDevMode() && env.baseUrl || envProd.baseUrl}logout`;

  public g_nDisplySchoolMode : Number;
  public g_nDisplyTrainerMode : Number;
  public g_nTrainerID : Number;
  public g_nSchoolID : Number;

  constructor( private httpClient: HttpClient, private _localSession : LocalsessionService ) { 
    this.g_nDisplySchoolMode = 0; // 0: list, 1 : add
    this.g_nDisplyTrainerMode = 0; // 0: list, 1 : add

    this.g_nTrainerID = 0;
    this.g_nSchoolID = 0;
  }

  logOut() {
    let data: any = {};
    data['user_id'] = this._localSession.retrieveUserID();
    data['token'] = this._localSession.retrieveToken();
    
    return this.httpClient.post(this.LOGOUT_URL, data ).pipe(
      map(response => response),
      catchError((error: Response) => observableThrowError(error)));
  }
}
