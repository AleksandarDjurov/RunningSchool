import { NgModule } from '@angular/core';
import { BrowserModule } from '@angular/platform-browser';
import { HttpClientModule } from '@angular/common/http';
import { BrowserAnimationsModule } from '@angular/platform-browser/animations';
import { RouterModule, Routes } from '@angular/router';
import { MatMomentDateModule } from '@angular/material-moment-adapter';
import { MatButtonModule, MatIconModule } from '@angular/material';
import { TranslateModule } from '@ngx-translate/core';
import 'hammerjs';

import { FuseModule } from '@fuse/fuse.module';
import { FuseSharedModule } from '@fuse/shared.module';
import { FuseProgressBarModule, FuseSidebarModule, FuseThemeOptionsModule } from '@fuse/components';

import { fuseConfig } from 'app/fuse-config';

import { AppComponent } from 'app/app.component';
import { LayoutModule } from 'app/layout/layout.module';
import { SampleModule } from 'app/main/sample/sample.module';
import { LoginModule } from 'app/main/authentication/login/login.module';
import { PurchaseModule } from 'app/main/purchase/purchase.module';
import { DashboardModule } from 'app/main/dashboard/dashboard.module'
import { SCourseModule } from 'app/main/course/scourse/scourse.module';
import { TCourseModule } from 'app/main/trainer/tcourse/tcourse.module';
import { AthletesModule } from 'app/main/athlete/athlete.module';

import { CalendarModule } from 'app/main/course/scourse/calendar/calendar.module';

import { LoadingComponent } from 'app/main/common/loading/loading.component';
import { ResultsComponent } from 'app/main/common/results/results.component';
import { TrainersModule } from 'app/main/trainer/trainers.module';
import { MainCourseModule } from 'app/main/course/mcourse/mcourse.module';
import { AttendModule } from 'app/main/common/attendance/attend.module';

import { InMemoryWebApiModule } from 'angular-in-memory-web-api';
import { FakeDbService } from 'app/fake-db/fake-db.service';
import { MatProgressSpinnerModule } from '@angular/material/progress-spinner';
import { DatePipe } from '@angular/common';

const appRoutes: Routes = [
    {
        path      : '**',
        redirectTo: 'purchase'
    }
];

@NgModule({
    declarations: [
        AppComponent, LoadingComponent, ResultsComponent
    ],
    imports     : [
        BrowserModule,
        BrowserAnimationsModule,
        HttpClientModule,
        RouterModule.forRoot(appRoutes),

        TranslateModule.forRoot(),

        // Material moment date module
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
        MatProgressSpinnerModule,
        
        // App modules
        LayoutModule,
        SampleModule,
        LoginModule,
        PurchaseModule,
        DashboardModule,
        SCourseModule,
        TCourseModule,

        CalendarModule,
        TrainersModule,
        MainCourseModule,
        AthletesModule,
        AttendModule,
        
        InMemoryWebApiModule.forRoot(FakeDbService, {
            delay             : 0,
            passThruUnknownUrl: true
        }),
    ],
    bootstrap   : [
        AppComponent
    ],
    entryComponents: [LoadingComponent, ResultsComponent],
    providers : [ DatePipe ]
})

export class AppModule{}
