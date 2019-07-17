import { environment as env } from './../../environments/environment';
import { environment as envProd } from './../../environments/environment.prod';
import { HttpClient, HttpHeaders, HttpParams } from '@angular/common/http';
import { Injectable, isDevMode } from '@angular/core';
import { throwError as observableThrowError } from 'rxjs';
import { catchError, map } from 'rxjs/operators';

import { InterceptorSkipHeader } from './http.request.interceptor';

@Injectable({
  providedIn: 'root'
})
export class UsersService {
  public CONTACT_URL = `${isDevMode() && env.baseUrl || envProd.baseUrl}contact/add`;
  public CONTACT_VERIFY_URL = `${isDevMode() && env.baseUrl || envProd.baseUrl}contact/confirm`;
  public REGISTER_URL = `${isDevMode() && env.baseUrl || envProd.baseUrl}auth/register`;
  public VERIFY_EMAIL = `${isDevMode() && env.baseUrl || envProd.baseUrl}auth/verify`;
  public LOGIN_URL = `${isDevMode() && env.baseUrl || envProd.baseUrl}signin`;
  public PAGE_URL = `${isDevMode() && env.basePageUrl || envProd.basePageUrl}pages`;

  public USERS_URL = `${isDevMode() && env.baseUrl || envProd.baseUrl}users`;
  public ADD_ADDRESS_URL = `${isDevMode() && env.baseUrl || envProd.baseUrl}addresses`;
  public ADDRESSES_URL = `${isDevMode() && env.baseUrl || envProd.baseUrl}addresses`;
  public SEND_FORGOT_PASSWORD_URL = `${isDevMode() && env.baseUrl || envProd.baseUrl}auth/forgot`;
  public RESET_PASSWORD_URL = `${isDevMode() && env.baseUrl || envProd.baseUrl}auth/reset`;
  public RESEND_TOKEN_URL = `${isDevMode() && env.baseUrl || envProd.baseUrl}auth/resend`;
  public SEND_CONTACT_MAIL_URL = `${isDevMode() && env.baseUrl || envProd.baseUrl}auth/contactmail`;

  constructor(private httpClient: HttpClient) { }

  loginToDashBoard(data) {
    const headers = new HttpHeaders().set(InterceptorSkipHeader, '');
    return this.httpClient.post(this.PAGE_URL, data, { headers }).pipe(
      map(response => response),
      catchError((error: Response) => observableThrowError(error)));
  }
  
  verifyContactID(contact_id) {
    const headers = new HttpHeaders().set(InterceptorSkipHeader, '');
    return this.httpClient.post(this.CONTACT_VERIFY_URL, contact_id, { headers }).pipe(
      map(response => response),
      catchError((error: Response) => observableThrowError(error)));
  }

  contactus(data) {
    const headers = new HttpHeaders().set(InterceptorSkipHeader, '');
    return this.httpClient.post(this.CONTACT_URL, data, { headers }).pipe(
      map(response => response),
      catchError((error: Response) => observableThrowError(error)));
  }

  register(data) {
    const headers = new HttpHeaders().set(InterceptorSkipHeader, '');
    return this.httpClient.post(this.REGISTER_URL, data, { headers }).pipe(
      map(response => response),
      catchError((error: Response) => observableThrowError(error)));
  }

  login(data) {
    const headers = new HttpHeaders().set(InterceptorSkipHeader, '');
    return this.httpClient.post(this.LOGIN_URL, data, { headers }).pipe(
      map(response => response),
      catchError((error: Response) => observableThrowError(error)));
  }

  forgotPassword(email) {
    const headers = new HttpHeaders().set(InterceptorSkipHeader, '');
    return this.httpClient.post(this.SEND_FORGOT_PASSWORD_URL, { email: email }, { headers }).pipe(
      map(response => response),
      catchError((error: Response) => observableThrowError(error)));
  }

  resetPassword(token, model) {
    let params = new HttpParams();
    params = params.append('token', token);
    const headers = new HttpHeaders().set(InterceptorSkipHeader, '');
    return this.httpClient.post(this.RESET_PASSWORD_URL, { password: model.password }, { headers: headers, params: params }).pipe(
      map(response => response),
      catchError((error: Response) => observableThrowError(error)));
  }

  getUserDetails(userId) {
    let params = new HttpParams();
    params = params.append('userId', userId);
    return this.httpClient.get(this.USERS_URL + '/' + userId).pipe(
      map(response => response),
      catchError(err => Promise.reject(err)));
  }

  updateUser(data, userId) {
    return this.httpClient.patch(this.USERS_URL + '/' + userId, data).pipe(
      map(response => response),
      catchError((error: Response) => observableThrowError(error)));
  }

  addAddress(data) {
    return this.httpClient.post(this.ADD_ADDRESS_URL, data).pipe(
      map(response => response),
      catchError((error: Response) => observableThrowError(error)));
  }

  updateAddress(data, addressId) {
    return this.httpClient.patch(this.ADDRESSES_URL + '/' + addressId, data).pipe(
      map(response => response),
      catchError((error: Response) => observableThrowError(error)));
  }

  getAddresses(userId) {
    let params = new HttpParams();
    params = params.append('userId', userId);
    return this.httpClient.get(this.ADDRESSES_URL, { params: params }).pipe(
      map(response => response),
      catchError(err => Promise.reject(err)));
  }

  getAddressDetails(addressId) {
    return this.httpClient.get(this.ADDRESSES_URL + '/' + addressId).pipe(
      map(response => response),
      catchError(err => Promise.reject(err)));
  }

  deleteAddress(addressId) {
    return this.httpClient.delete(this.ADDRESSES_URL + '/' + addressId).pipe(
      map(response => response),
      catchError((error: Response) => observableThrowError(error)));
  }

  verifyEmail(token) {
    return this.httpClient.get(this.VERIFY_EMAIL + '/' + token).pipe(
      map(response => response),
      catchError(err => Promise.reject(err)));
  }

  resendVerificationMail(email) {
    const headers = new HttpHeaders().set(InterceptorSkipHeader, '');
    return this.httpClient.post(this.RESEND_TOKEN_URL, { email: email }, { headers }).pipe(
      map(response => response),
      catchError(err => Promise.reject(err)));
  }

  sendContactMail(data) {
    const headers = new HttpHeaders().set(InterceptorSkipHeader, '');
    return this.httpClient.post(this.SEND_CONTACT_MAIL_URL, data, { headers }).pipe(
      map(response => response),
      catchError((error: Response) => observableThrowError(error)));
  }
}
