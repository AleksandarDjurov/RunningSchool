import { Injectable } from '@angular/core';

@Injectable({
  providedIn: 'root'
})
export class LocalsessionService {
  private logicStage  = 'logic_stage';
  private token       = 'token';
  private user_id     = 'user_id';

  private school_id   = 'school_id';
 
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
    const token: string = localStorage.getItem(this.user_id);
    return token;
  }

  public saveToken(token) {
    localStorage.setItem(this.token, token);
  }

  public retrieveToken() {
    const token: string = localStorage.getItem(this.token);
    return token;
  }

  public saveSchoolID(id) {
    localStorage.setItem(this.school_id, id);
  }

  public retrieveSchoolID() {
    return localStorage.getItem(this.school_id);
  }
}
