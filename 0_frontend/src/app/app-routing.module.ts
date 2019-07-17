import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';
import { LandingComponent } from './ui/landing/landing.component'
import { ContactusComponent } from './ui/contactus/contactus.component'
import { LoginComponent } from './ui/login/login.component'
import { RegisterComponent } from './ui/register/register.component'

const routes: Routes = [
  { path:"", pathMatch:"full", redirectTo:"landing" },
  { path:"landing", component:LandingComponent },
  { path:"contactus", component:ContactusComponent },
  { path:"login", component:LoginComponent },
  { path:"register", component:RegisterComponent },
  { path: '**', component: LandingComponent },
];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})
export class AppRoutingModule { }
