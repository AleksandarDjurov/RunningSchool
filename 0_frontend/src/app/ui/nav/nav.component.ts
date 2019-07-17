import { Component, OnInit } from '@angular/core';
import { GlobalService } from '../../services/global.service';
import { LocalsessionService } from '../../services/localsession.service'
import { Router } from '@angular/router';

@Component({
  selector: 'app-nav',
  templateUrl: './nav.component.html',
  styleUrls: ['./nav.component.css']
})
export class NavComponent implements OnInit {
  isCollapsed = false;
  constructor(
    public globalVars: GlobalService, 
    private session: LocalsessionService,
    private router: Router) { }

  ngOnInit() {
    this.globalVars._gShowStage = 0;
    var stage = this.session.retrieveLogicStage();
    if (stage == 'contact_us')
      this.globalVars._gShowStage = 1;
  }

  OnClickREG() : void {
    this.router.navigate(['/register']);
  }

  OnClickLOGIN() : void {
    this.router.navigate(['/login']);
  }
}
