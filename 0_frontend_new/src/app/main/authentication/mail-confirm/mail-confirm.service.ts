import { Injectable, isDevMode } from '@angular/core';
import { HttpClient } from '@angular/common/http';

import { environment as env } from '../../../../environments/environment';
import { environment as envProd } from '../../../../environments/environment.prod';

import { throwError as observableThrowError } from 'rxjs';
import { catchError, map } from 'rxjs/operators';

@Injectable()
export class MailConfirmService
{
    ATHLETE_VERIFY = `${isDevMode() && env.baseUrl || envProd.baseUrl}school/athlete/verify`;
    verify_code: string;

    /**
     * Constructor
     *
     * @param {HttpClient} _httpClient
     */
    constructor(
        private _httpClient: HttpClient,
    ) {
    }

    verifyAthlete(data) {
        return this._httpClient.post(this.ATHLETE_VERIFY, data).pipe(
            map(response => response),
            catchError((error: Response) => observableThrowError(error)));
    }

}
