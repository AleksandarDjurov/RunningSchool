import { Component, ViewEncapsulation } from '@angular/core';
import { FuseConfigService } from '@fuse/services/config.service';
import { fuseAnimations } from '@fuse/animations';
import { LocalsessionService } from '../../../services/localsession.service';

@Component({
    selector: 'mail-confirm',
    templateUrl: './mail-confirm.component.html',
    styleUrls: ['./mail-confirm.component.scss'],
    encapsulation: ViewEncapsulation.None,
    animations: fuseAnimations
})

export class MailConfirmComponent {
    /**
     * Constructor
     *
     * @param {FuseConfigService} _fuseConfigService
     */
    mailAddress : string;

    constructor(
        private _fuseConfigService: FuseConfigService,
        private localsession : LocalsessionService
    ) {
        // Configure the layout
        this._fuseConfigService.config = { layout: { navbar: { hidden: true }, toolbar: { hidden: true }, footer: { hidden: true }, sidepanel: { hidden: true } } };
    }

    ngOnInit(): void {
        this.mailAddress = this.localsession.retrieveVerifyEmail();
    }

}
