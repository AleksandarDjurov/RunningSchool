import { environment as env } from '../../environments/environment';
import { environment as envProd } from '../../environments/environment.prod';
import { HttpClient, HttpHeaders, HttpParams } from '@angular/common/http';
import { Injectable, isDevMode } from '@angular/core';
import { throwError as observableThrowError } from 'rxjs';
import { catchError, map } from 'rxjs/operators';

import { InterceptorSkipHeader } from './http.request.interceptor';

@Injectable({
  providedIn: 'root'
})
export class GroupsService {
  public GROUPLIST_URL = `${isDevMode() && env.baseUrl || envProd.baseUrl}group`;
  
  constructor(private httpClient: HttpClient) { }

  getGroupList(data) {
    const headers = new HttpHeaders().set(InterceptorSkipHeader, '');
    return this.httpClient.post(this.GROUPLIST_URL, data, { headers }).pipe(
      map(response => response),
      catchError((error: Response) => observableThrowError(error)));
  }
}
