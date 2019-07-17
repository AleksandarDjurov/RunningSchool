import { HTTP_INTERCEPTORS, HttpClientModule } from '@angular/common/http';
import { NgModule, NO_ERRORS_SCHEMA  } from '@angular/core';
import { BrowserModule } from '@angular/platform-browser';
import { NgbModule } from '@ng-bootstrap/ng-bootstrap';
// ngx-spinner
//import { MDBBootstrapModule } from 'angular-bootstrap-md';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';

import { AppRoutingModule } from './app-routing.module';
import { AppComponent } from './app.component';
import { NavComponent } from './ui/nav/nav.component';
import { HeaderComponent } from './ui/landing/header/header.component';
import { BodyComponent } from './ui/landing/body/body.component';
import { FooterComponent } from './ui/landing/footer/footer.component';
import { LandingComponent } from './ui/landing/landing.component';
import { ContactusComponent } from './ui/contactus/contactus.component';
import { LoginComponent } from './ui/login/login.component';

import { SchooladminComponent } from './ui/register/schooladmin/schooladmin.component';
import { TechnicalmanagerComponent } from './ui/register/technicalmanager/technicalmanager.component';
import { PersonaltrainerComponent } from './ui/register/personaltrainer/personaltrainer.component';
import { AthleteComponent } from './ui/register/athlete/athlete.component';
import { RegisterComponent } from './ui/register/register.component'

import { SessionService } from './services/session.service';
import { HttpsRequestInterceptor } from './services/http.request.interceptor';
import { NgxSpinnerModule } from 'ngx-spinner';

import { AlertComponent } from './ui/alert/alert.component';
import { AlertService } from './ui/alert/alert.service';
import { DragDropDirective } from './directives/dragdrop.directive';

@NgModule({
  declarations: [
    AppComponent,
    AlertComponent,
    NavComponent,
    HeaderComponent,
    BodyComponent,
    FooterComponent,
    LandingComponent,
    ContactusComponent,
    LoginComponent,
    SchooladminComponent,
    TechnicalmanagerComponent,
    PersonaltrainerComponent,
    AthleteComponent,
    RegisterComponent,
    DragDropDirective
  ],
  imports: [
    BrowserModule,
    NgxSpinnerModule,
    FormsModule,
    ReactiveFormsModule,
    NgbModule.forRoot(),
    HttpClientModule,
    // MDBBootstrapModule.forRoot(),
    AppRoutingModule
  ],
  schemas: [ NO_ERRORS_SCHEMA ],
  providers: [
    AlertService,
    SessionService,
    {
      provide: HTTP_INTERCEPTORS,
      useClass: HttpsRequestInterceptor,
      multi: true,
    }
  ],
  bootstrap: [AppComponent]
})
export class AppModule { }
