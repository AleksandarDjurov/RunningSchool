import { Injectable } from '@angular/core';

@Injectable({
  providedIn: 'root'
})

export class GlobalService {
  public _gShowStage = 0;
  public _userid : String;
  public _token : String;

  public _gRegisterMode : number;

  public _gMailAddress : string;
  public _gPassword : string;
  public _gVerifiedMode : boolean;
  
  constructor() { }
}
