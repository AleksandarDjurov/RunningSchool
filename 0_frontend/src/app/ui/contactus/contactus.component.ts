import { Component, OnInit, ViewChild, ElementRef } from '@angular/core';
import { UsersService } from '../../services/users.service';
import { NgxSpinnerService } from 'ngx-spinner';
import { LocalsessionService } from '../../services/localsession.service'
import { GlobalService }from '../../services/global.service';
import { Router } from '@angular/router';
import { NgbModalConfig, NgbModal } from '@ng-bootstrap/ng-bootstrap';

@Component({
  selector: 'app-contactus',
  templateUrl: './contactus.component.html',
  styleUrls: ['./contactus.component.css'],
  providers: [NgbModalConfig, NgbModal]
})

export class ContactusComponent implements OnInit {
  @ViewChild("modal_result") modal_ret: ElementRef;
  
  role: String;
  retResult: String;
  model: any = {};

  userRoleList: any[] = [
    { value: '1', display: 'School Manager' },
    { value: '2', display: 'School Technical Manager' },
    { value: '3', display: 'Personal Trainer' },
    { value: '4', display: 'Athlete' }
  ];

  constructor( 
    private globalVars : GlobalService,
    private usersService: UsersService,
    private session: LocalsessionService,
    private spinner: NgxSpinnerService,
    private router: Router,
    private config: NgbModalConfig,
    private modalService: NgbModal) {
    this.role = 'School Manager';
    config.backdrop = 'static';
    config.keyboard = false;
  }

  ngOnInit() { }
  updateUI() {
    this.model['role'] = this.role;
  }

  showSuccessMessage() {
    this.modalService.open(this.modal_ret);
  }

  onSubmit() {
    /** spinner starts */
    this.spinner.show();

    this.usersService.contactus(this.model).subscribe(
      (data: any) => {
        /** spinner ends */
        this.spinner.hide();
        this.retResult = data['msg'];
        
        this.showSuccessMessage();

        var retCode = data['code'];
        if (retCode == '200') {
          this.globalVars._gShowStage = 1;
          this.session.saveLogicStage('contact_us');
          this.router.navigate(['/']);
        }
      },
      error => {
        /** spinner ends */
        this.spinner.hide();
        this.retResult = 'Operation Failed due to server down or something else.'
        this.showSuccessMessage();
      });
  }

}
