import { Component, OnInit } from '@angular/core';
import { GlobalService } from '../../services/global.service';
import { LocalsessionService } from '../../services/localsession.service';

@Component({
  selector: 'app-register',
  templateUrl: './register.component.html',
  styleUrls: ['./register.component.css']
})

export class RegisterComponent implements OnInit {
  // displayMode: Number;
  userRole: String;

  userRoleList: any[] = [
    { value: '0', display: 'School Manager' },
    // { value: '1', display: 'School Technical Manager' },
    { value: '2', display: 'Personal Trainer' },
    { value: '3', display: 'Athlete' }
  ];

  constructor( public globalService : GlobalService,
    public localsession : LocalsessionService ) { this.userRole = '0'; }

  checkIfTechnicalManager()
  {
    let logicStage = this.localsession.retrieveLogicStage();
    if( logicStage == 'school_registered' || logicStage =='technical_registered')
      this.globalService._gRegisterMode = 1;
  }

  ngOnInit() 
  {
    this.globalService._gRegisterMode = 0;
    this.checkIfTechnicalManager();
  }

  updateUI() 
  {
    if (this.userRole == '0')
    {
      this.globalService._gRegisterMode = 0;

      this.checkIfTechnicalManager();
    }
    else if (this.userRole == '1')
      this.globalService._gRegisterMode = 1;
    else if (this.userRole == '2')
      this.globalService._gRegisterMode = 2;
    else if (this.userRole == '3')
      this.globalService._gRegisterMode = 3;
  }
}
