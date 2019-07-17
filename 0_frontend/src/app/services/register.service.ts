import { environment as env } from '../../environments/environment';
import { environment as envProd } from '../../environments/environment.prod';
import { HttpClient, HttpHeaders, HttpParams } from '@angular/common/http';
import { Injectable, isDevMode } from '@angular/core';
import { throwError as observableThrowError } from 'rxjs';
import { catchError, map } from 'rxjs/operators';

// import { InterceptorSkipHeader } from './http.request.interceptor';

@Injectable({
  providedIn: 'root'
})

export class RegisterService {
  public REG_SCHOOL_URL = `${isDevMode() && env.baseUrl || envProd.baseUrl}school/create`;
  public REG_TECHNICAL_URL = `${isDevMode() && env.baseUrl || envProd.baseUrl}school/technical/create`;
  constructor(private httpClient: HttpClient) { }

  
  public register_school(data) {

    var formData = new FormData();

    // The FormData object provides a way to programmatically submit data that the
    // Browser could have natively submitted using a "<form/>" tag. Each entry here
    // represents a form-control field.

    formData.append("school_name", data.school_name);
    formData.append("reference_asd", data.reference_asd);
    formData.append("company_code", data.company_code);
    formData.append("school_state", data.school_state);
    formData.append("school_region", data.school_region);
    formData.append("school_province", data.school_province);
    formData.append("school_city", data.school_city);
    formData.append("school_address", data.school_address);
    formData.append("school_membership_type", data.school_membership_type);
    formData.append("postal_code", data.postal_code);
    formData.append("first_name", data.first_name);
    formData.append("last_name", data.last_name);
    formData.append("email", data.email);
    formData.append("dob", data.dob);
    formData.append("mobile_phone", data.mobile_phone);
    formData.append("gender", data.gender);
    formData.append("state", data.state);
    formData.append("region", data.region);
    formData.append("province", data.province);
    formData.append("city", data.city);
    formData.append("address", data.address);
    formData.append("postal", data.postal);
    formData.append("group", data.group);
    formData.append("vat_number", data.vat_number);
    formData.append("fiscal_code", data.fiscal_code);
    formData.append("membership_type", data.membership_type);
    formData.append("bio", data.bio);
    formData.append("activate", "1");

    (data.banner_file) && formData.append("banner_file", data.banner_file);
    (data.logo_file) && formData.append("logo_file", data.logo_file);
    (data.pic_file) && formData.append("pic_file", data.pic_file);

    if (data.upload_length) {

      for (let i = 0; i < data.upload_length; i++) {
        formData.append('school_upload[' + i + ']', data['school_upload[' + i + ']']);
      }
    }

    return this.httpClient.post<any>(this.REG_SCHOOL_URL, formData).pipe(
      map(response => response),
      catchError((error: Response) => observableThrowError(error)));
  }

  public register_technical(data) {

    var formData = new FormData();

    // The FormData object provides a way to programmatically submit data that the
    // Browser could have natively submitted using a "<form/>" tag. Each entry here
    // represents a form-control field.

    formData.append("school_id", data.school_id);
    (data.pic_file) && formData.append("pic_file", data.pic_file);
    formData.append("first_name", data.first_name);
    formData.append("last_name", data.last_name);
    formData.append("email", data.email);
    formData.append("dob", data.dob);
    formData.append("birth_place", data.birth_place);
    formData.append("nationality", data.nationality);
    formData.append("mobile_phone", data.mobile_phone);
    formData.append("gender", data.gender);
    formData.append("state", data.state);
    formData.append("region", data.region);
    formData.append("province", data.province);
    formData.append("city", data.city);
    formData.append("address", data.address);
    formData.append("certified_type", data.certified_type);
    formData.append("postal", data.postal);
    formData.append("group", data.group);
    formData.append("vat_number", data.vat_number);
    formData.append("fiscal_code", data.fiscal_code);
    formData.append("card_number", data.card_number);
    formData.append("membership_type", data.membership_type);
    formData.append("bio", data.bio);
    formData.append("activate", "1");


    if (data.upload_length) {

      for (let i = 0; i < data.upload_length; i++) {
        formData.append('user_upload[' + i + ']', data['school_upload[' + i + ']']);
      }
    }

    return this.httpClient.post<any>(this.REG_TECHNICAL_URL, formData).pipe(
      map(response => response),
      catchError((error: Response) => observableThrowError(error)));
  }
}
