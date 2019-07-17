import { Component, ViewEncapsulation } from '@angular/core';
import { FuseTranslationLoaderService } from '@fuse/services/translation-loader.service';

import { locale as english } from './i18n/en';
import { locale as italian } from './i18n/it';

import { LocalsessionService } from '../../services/localsession.service';
import { PurchaseService } from './purchase.service';

import { MatDialog, MatDialogRef } from '@angular/material/dialog';
import { LoadingComponent } from '../common/loading/loading.component';
import { ResultsComponent } from '../common/results/results.component';

@Component({
    selector: 'purchase',
    templateUrl: './purchase.component.html',
    styleUrls: ['./purchase.component.scss'],
    encapsulation: ViewEncapsulation.None
})
export class PurchaseComponent {
    /**
    * Constructor
    *
    * @param {FuseTranslationLoaderService} _fuseTranslationLoaderService
    */
    retResult: String;

    model: any = {};
    packageList: any[] = [];
    purchased : string = 'false';

    spinnerDlg: MatDialogRef<LoadingComponent>;

    constructor(
        private dialog: MatDialog,
        private _fuseTranslationLoaderService: FuseTranslationLoaderService,
        private purchaseService: PurchaseService,
        private localsession: LocalsessionService,
    ) {
        this._fuseTranslationLoaderService.loadTranslations(english, italian);
    }

    ngOnInit() {
        this.purchased = this.localsession.retrievePurchased();
        let user_id = this.localsession.retrieveUserID();

        this.model['token'] = this.localsession.retrieveToken();
        this.model['user_id'] = this.localsession.retrieveUserID();
        this.purchaseService.getPackageList(this.model).subscribe( (data: any) => { this.packageList = data; },error => {});
        this.purchaseService.confirmPurchase(this.model, user_id).subscribe( 
            (data: any) => { 
                if ( data['status'] === 0 )
                    this.purchased = 'false';
                else if ( data['status'] === 1 && data['code'] === 200 )
                    this.purchased = 'true';

                this.localsession.savePurchased(this.purchased);
            },error => {});
    }

    showSpinner() {
        this.spinnerDlg = this.dialog.open(LoadingComponent, { panelClass: 'transparent', disableClose: true });
    }

    hideSpinner() {
        this.spinnerDlg.close();
    }

    showSuccessMessage() {
        // confirmDialogRef: MatDialogRef<FuseConfirmDialogComponent>;
        const dialogRef = this.dialog.open(ResultsComponent, {
            panelClass: 'transparent',
            data: { title: 'Purchase a package', content: this.retResult },
        });

        dialogRef.afterClosed().subscribe(result => {
        });
    }

    ShowAlreadyBuy()
    {
        this.retResult = "You have already purchased this package.";
        this.showSuccessMessage();
    }

    BuyPackage() {
        let user_id = this.localsession.retrieveUserID();

        this.model['user_id'] = this.localsession.retrieveUserID();
        this.model['token'] = this.localsession.retrieveToken();
        this.model['package_id'] = '1';
        this.model['pay_amount'] = '50';
        this.model['pay_status'] = '1';

        this.showSpinner();
        this.purchaseService.buyPackage(this.model, user_id).subscribe( 
            (data: any) => 
            {
                this.hideSpinner();

                let code = data['code'];
                let msg  = data['msg'];

                if( code == '200' && msg == "Completed successfully!")
                {
                    this.retResult = "Successfully Purchased."
                    this.showSuccessMessage();

                    this.purchased = 'true';
                    this.localsession.savePurchased(this.purchased);
                }
            }
            ,error => {
                this.retResult = "Operation not completed."
                this.showSuccessMessage();
            });
    }
}
