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
import { PagesModule } from 'app/main/main.module';

// import { SampleModule } from 'app/main/sample/sample.module';
import { Injectable } from '@angular/core';
import { DefaultUrlSerializer, UrlSegment, UrlSerializer, UrlTree } from '@angular/router';
import { LocationStrategy, Location, PathLocationStrategy } from '@angular/common';

@Injectable()
export class CustomUrlSerializer implements UrlSerializer {
    /** Parses a url into a {@link UrlTree} */
    private defaultSerializer: DefaultUrlSerializer = new DefaultUrlSerializer();

    /** Parses a url into a {@link UrlTree} */
    parse(url: string): UrlTree {

        // This is the custom patch where you'll collect segment containing '='
        const lastSlashIndex = url.lastIndexOf('/'), equalSignIndex = url.indexOf('=', lastSlashIndex);
        if (equalSignIndex > -1) { // url contians '=', apply patch
            const keyValArr = url.substr(lastSlashIndex + 1).split('=');
            const urlTree = this.defaultSerializer.parse(url);

            // Once you have serialized urlTree, you have two options to capture '=' part
            // Method 1. replace desired segment with whole "key=val" as segment
            urlTree.root.children['primary'].segments.forEach((segment: UrlSegment) => {
                if (segment.path === keyValArr[0]) {
                    segment.path = keyValArr.join('='); // Suggestion: you can use other unique set of characters here too e.g. '$$$'
                }
            });

            // Method 2. This is the second method, insert a custom query parameter
            // urlTree.queryParams[keyValArr[0]] = keyValArr[1];
            return urlTree;
        } else {
            // return as usual
            return this.defaultSerializer.parse(url);
        }
    }

    /** Converts a {@link UrlTree} into a url */
    serialize(tree: UrlTree): string {
        return this.defaultSerializer.serialize(tree);
    }
}

const appRoutes: Routes = [
    { path: "", pathMatch: "full", redirectTo: "/auth/login" },
    {
        path: '**',
        redirectTo: '/auth/login'
    },
];

@NgModule({
    declarations: [
        AppComponent
    ],
    imports: [
        BrowserModule,
        BrowserAnimationsModule,
        HttpClientModule,
        RouterModule.forRoot(appRoutes, {useHash:true}),
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

        // App modules
        LayoutModule,
        PagesModule,
    ],
    providers : [
        {
            provide: UrlSerializer,
            useClass: CustomUrlSerializer
        },
        Location,
        { provide: LocationStrategy, useClass: PathLocationStrategy }
    ],
    bootstrap: [
        AppComponent
    ],
})
export class AppModule {
}
