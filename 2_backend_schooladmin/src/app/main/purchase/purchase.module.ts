import { NgModule } from '@angular/core';
import { RouterModule } from '@angular/router';
import { TranslateModule } from '@ngx-translate/core';

import { FuseSharedModule } from '@fuse/shared.module';
import { PurchaseComponent } from './purchase.component';
import { MatButtonModule, MatDividerModule } from '@angular/material';

const routes = [
    {
        path     : 'purchase',
        component: PurchaseComponent
    }
];

@NgModule({
    declarations: [
        PurchaseComponent
    ],
    imports     : [
        RouterModule.forChild(routes),
        TranslateModule,
        FuseSharedModule,
        
        MatButtonModule,
        MatDividerModule,
    ],
    exports     : [
        PurchaseComponent
    ]
})

export class PurchaseModule
{
}