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

export class RegionService {
  public REGION_URL = `${isDevMode() && env.baseUrl || envProd.baseUrl}region`;
  public PROVINCE_URL = `${isDevMode() && env.baseUrl || envProd.baseUrl}province`;
  public CITY_URL = `${isDevMode() && env.baseUrl || envProd.baseUrl}city`;

  constructor(private httpClient: HttpClient) { }

  // region list
  getRegionList(state_id) {
    let params = new HttpParams();
    params = params.append('state_name', state_id);
    return this.httpClient.get(this.REGION_URL, { params: params }).pipe(
      map(response => response),
      catchError(err => Promise.reject(err)));
  }

  // region list
  getProvinceList(region_id) {
    let params = new HttpParams();
    params = params.append('region_name', region_id);
    return this.httpClient.get(this.PROVINCE_URL, { params: params }).pipe(
      map(response => response),
      catchError(err => Promise.reject(err)));
  }

  // city list
  getCityList(province_id) {
    let params = new HttpParams();
    params = params.append('province_name', province_id);
    return this.httpClient.get(this.CITY_URL, { params: params }).pipe(
      map(response => response),
      catchError(err => Promise.reject(err)));
  }
}
