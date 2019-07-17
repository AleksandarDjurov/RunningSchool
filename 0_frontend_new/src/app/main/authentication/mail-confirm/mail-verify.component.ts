import { Component, ViewEncapsulation } from '@angular/core';
import { FuseConfigService } from '@fuse/services/config.service';
import { fuseAnimations } from '@fuse/animations';
import { MailConfirmService } from './mail-confirm.service';
import { Subject } from 'rxjs';
import { ResultsComponent } from '../../common/results/results.component';
import { MatDialog } from '@angular/material/dialog';
import { Router, ActivatedRoute } from '@angular/router';
import { LocalsessionService } from '../../../services/localsession.service';
import { GlobalService } from '../../../services/global.service';


@Component({
    selector: 'mail-verify',
    templateUrl: './mail-verify.component.html',
    styleUrls: ['./mail-verify.component.scss'],
    encapsulation: ViewEncapsulation.None,
    animations: fuseAnimations
})

export class MailVerifyComponent {
    private _unsubscribeAll: Subject<any>;
    private verify_value: any;
    mailAddress : string;

    /**
     * Constructor
     *
     * @param {FuseConfigService} _fuseConfigService
     */

    constructor(private route: ActivatedRoute,
        private dialog: MatDialog,
        private _fuseConfigService: FuseConfigService,
        private _mailConfirmService: MailConfirmService,
        private router: Router,
        private localsession : LocalsessionService,
        private globalService : GlobalService
    ) {
        // Configure the layout
        this._fuseConfigService.config = { layout: { navbar: { hidden: true }, toolbar: { hidden: true }, footer: { hidden: true }, sidepanel: { hidden: true } } };

        this.route.paramMap.subscribe(params => {
            this.verify_value = params.get('id');
        });
    }

    ngOnInit(): void {
        this.mailAddress = this.localsession.retrieveVerifyEmail();

        let data: any = {};
        data['verify_value'] = this.verify_value;

        this._mailConfirmService.verifyAthlete(data).subscribe(
            (data: any) => {
                var retCode = data['code'];
                var msg = data['msg'];
                if (retCode === "200" && msg === "This user registered scucessfully.") {
                    this.globalService._gVerifiedMode = true;
                    
                    this.showSuccessMessage();
                }
            }, error => { });
    }

    showSuccessMessage() {
        const dialogRef = this.dialog.open(ResultsComponent, {
            panelClass: 'transparent',
            data: { title: 'Mail Verification ', content: "Your account has been successfully activated." },
        });

        dialogRef.afterClosed().subscribe(result => { this.router.navigate(['auth/login']); });
    }
}
