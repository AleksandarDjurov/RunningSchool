import { Component, OnInit, ViewChild, ElementRef, Inject  } from '@angular/core';
import { UsersService } from '../../services/users.service';
import { NgxSpinnerService } from 'ngx-spinner';
import { LocalsessionService } from '../../services/localsession.service'
import { GlobalService }from '../../services/global.service';
import { NgbModalConfig, NgbModal } from '@ng-bootstrap/ng-bootstrap';
import { Router } from '@angular/router';
// import { DOCUMENT } from '@angular/common';

@Component({
  selector: 'app-login',
  templateUrl: './login.component.html',
  styleUrls: ['./login.component.css'],
  providers: [NgbModalConfig, NgbModal]
})
export class LoginComponent implements OnInit {
  @ViewChild("modal_result") modal_ret: ElementRef;
  
  model: any = {};
  retResult: String;
  token:String;
  user_id:String;
  role:String;

  reqData: any = {};
  szPublicUrl: string;

  constructor(    
    // @Inject(DOCUMENT) private document: any,
    private globalVars : GlobalService,
    private session: LocalsessionService,
    private spinner: NgxSpinnerService,
    private modalService: NgbModal,
    private userService: UsersService,
    private router: Router) { 
      this.szPublicUrl = userService.PAGE_URL;
    }
  ngOnInit() {
  }

  showSuccessMessage() {
    this.modalService.open(this.modal_ret);
  }

  onSubmit() {
    /** spinner starts */
    this.spinner.show();

    this.userService.login(this.model).subscribe(
      (data: any) => {
        /** spinner ends */
        this.spinner.hide();

        this.role       = data['role'];
        this.token      = data['token'];
        this.user_id    = data['user_id'];
        this.retResult  = data['msg'];
        
        // this.showSuccessMessage();

        var retCode = data['code'];
        if (retCode == '200') {
          this.globalVars._gShowStage = 1;
          this.session.saveLogicStage('loggedin');
          this.session.saveToken(this.token);
          this.session.saveUserID(this.user_id);

          // this.router.navigate(['/extpages']);
          this.reqChangeLocation();
        }
      },
      error => {
        /** spinner ends */
        this.spinner.hide();
        this.retResult = 'Operation Failed due to server down or something else.'
        // this.showSuccessMessage();
      });
  }

  reqChangeLocation() {
    this.reqData['user_id'] = this.session.retrieveUserID();
    this.reqData['token'] = this.session.retrieveToken();

    setTimeout(() => {
      let form: HTMLFormElement = <HTMLFormElement>document.getElementById('userForm');
      form.submit();
    }, 1);
  }
}
