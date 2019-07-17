import { NgModule } from '@angular/core';
import { BrowserModule } from '@angular/platform-browser';
import { HttpClientModule } from '@angular/common/http';
import { BrowserAnimationsModule } from '@angular/platform-browser/animations';
import { RouterModule, Routes } from '@angular/router';
import { MatMomentDateModule } from '@angular/material-moment-adapter';
import { MatButtonModule } from '@angular/material/button';
import { MatIconModule } from '@angular/material/icon';
import { TranslateModule } from '@ngx-translate/core';
import 'hammerjs';

import { FuseModule } from '@fuse/fuse.module';
import { FuseSharedModule } from '@fuse/shared.module';
import { FuseProgressBarModule, FuseSidebarModule, FuseThemeOptionsModule } from '@fuse/components';

import { fuseConfig } from 'app/fuse-config';

import { AppComponent } from 'app/app.component';
import { LayoutModule } from 'app/layout/layout.module';
import { MainCourseModule } from 'app/main/mcourse/mcourse.module';
import { SchoolCourseModule } from 'app/main/scourse/scourse.module';
import { PersonalCourseModule } from 'app/main/pcourse/pcourse.module';
import { CalendarModule } from 'app/main/pcourse/calendar/calendar.module';
import { DatePipe } from '@angular/common';
import { AthletesModule } from 'app/main/athlete/athlete.module';
import { AttendModule } from 'app/main/athlete/attend/attend.module';

import { LoadingComponent } from 'app/main/common/loading/loading.component';
import { ResultsComponent } from 'app/main/common/results/results.component';

const appRoutes: Routes = [
    {
        path      : '**',
        redirectTo: 'maincourses'
    }
];

@NgModule({
    declarations: [
        AppComponent,
        LoadingComponent, 
        ResultsComponent,
    ],
    imports     : [
        BrowserModule,
        BrowserAnimationsModule,
        HttpClientModule,
        RouterModule.forRoot(appRoutes),

        TranslateModule.forRoot(),
        MatMomentDateModule,

        // Material 
        MatButtonModule,
        MatIconModule,

        // Fuse modules
        FuseModule.forRoot(fuseConfig),
        FuseProgressBarModule,
        FuseSharedModule,
        FuseSidebarModule,
        FuseThemeOptionsModule,
        MainCourseModule,
        SchoolCourseModule,
        PersonalCourseModule,
        CalendarModule,
        AthletesModule,
        AttendModule,

        // App modules
        LayoutModule,

    ],
    bootstrap   : [
        AppComponent
    ],
    entryComponents: [LoadingComponent, ResultsComponent],
    providers : [ DatePipe ]
})
export class AppModule
{
}