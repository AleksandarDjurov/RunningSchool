import { environment as env } from '../../../environments/environment';
import { environment as envProd } from '../../../environments/environment.prod';
import { HttpClient, HttpHeaders, HttpParams } from '@angular/common/http';
import { Injectable, isDevMode } from '@angular/core';
import { throwError as observableThrowError } from 'rxjs';
import { catchError, map } from 'rxjs/operators';

import { InterceptorSkipHeader } from '../../services/http.request.interceptor';

@Injectable({
  providedIn: 'root'
})
export class PurchaseService {
  // localhost/api/package
  // url : localhost/api/school/course/buy_package/id
  
  public PACKAGELIST_URL = `${isDevMode() && env.baseUrl || envProd.baseUrl}package`;
  public BUYPACKAGE_URL = `${isDevMode() && env.baseUrl || envProd.baseUrl}school/course/buy_package/`;
  public CONFIRM_BUY_URL = `${isDevMode() && env.baseUrl || envProd.baseUrl}school/course/confirm_package/`;

  constructor(private httpClient: HttpClient) { }

  getPackageList(data) {
    const headers = new HttpHeaders().set(InterceptorSkipHeader, '');
    return this.httpClient.post(this.PACKAGELIST_URL, data, { headers }).pipe(
      map(response => response),
      catchError((error: Response) => observableThrowError(error)));
  }

  buyPackage(data, manager_id) {
    const headers = new HttpHeaders().set(InterceptorSkipHeader, '');
    return this.httpClient.post(this.BUYPACKAGE_URL+manager_id, data, { headers }).pipe(
      map(response => response),
      catchError((error: Response) => observableThrowError(error)));
  }

  confirmPurchase(data, manager_id) {
    const headers = new HttpHeaders().set(InterceptorSkipHeader, '');
    return this.httpClient.post(this.CONFIRM_BUY_URL+manager_id, data, { headers }).pipe(
      map(response => response),
      catchError((error: Response) => observableThrowError(error)));
  }
}
