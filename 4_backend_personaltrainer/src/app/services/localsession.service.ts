import { Injectable } from '@angular/core';

@Injectable({
  providedIn: 'root'
})
export class LocalsessionService {
  private logicStage  = 'logic_stage';
  private token       = 'token';
  private user_id     = 'user_id';

  private school_id   = 'school_id';
  private purchase    = 'purchase';
  private verify_mail = 'verify_mail';

  constructor() { }
  
  public saveLogicStage(stage) {
    localStorage.setItem(this.logicStage, stage);
  }

  public retrieveLogicStage() {
    const storedLogic: string = localStorage.getItem(this.logicStage);
    return storedLogic;
  }

  public saveUserID(token) {
    localStorage.setItem(this.user_id, token);
  }

  public retrieveUserID() {
    const _id: string = localStorage.getItem(this.user_id);
    return _id;
    // return '82';
  }

  public saveToken(token) {
    localStorage.setItem(this.token, token);
  }

  public retrieveToken() {
    const token: string = localStorage.getItem(this.token);
    return token;
    // return 'MTU2MzEyNzYzMEhnUEFuR1JPejZKcEhjR1B2bVcx';
  }

  public saveSchoolID(id) {
    localStorage.setItem(this.school_id, id);
  }

  public retrieveSchoolID() {
    return localStorage.getItem(this.school_id);
  }

  public savePurchased(state : string) {
    localStorage.setItem(this.purchase, state);
  }

  public retrievePurchased() {
    return localStorage.getItem(this.purchase);
  }

  public retrieveVerifyEmail() {
    return localStorage.getItem(this.verify_mail);
  }
}
