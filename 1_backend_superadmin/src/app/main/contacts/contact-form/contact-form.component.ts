import { environment as env } from '../../../../environments/environment';
import { environment as envProd } from '../../../../environments/environment.prod';
import { Component, Inject, ViewEncapsulation, isDevMode } from '@angular/core';
import { FormBuilder, FormGroup } from '@angular/forms';
import { MAT_DIALOG_DATA, MatDialogRef } from '@angular/material';

import { Contact } from 'app/main/contacts/contact.model';
import { isThisSecond } from 'date-fns';

@Component({
    selector     : 'contacts-contact-form-dialog',
    templateUrl  : './contact-form.component.html',
    styleUrls    : ['./contact-form.component.scss'],
    encapsulation: ViewEncapsulation.None
})

export class ContactsContactFormDialogComponent
{
    action: string;
    contact: Contact;
    contactForm: FormGroup;
    dialogTitle: string;

    urlHeader = `${isDevMode() && env.basePageUrl || envProd.basePageUrl}`;

    /**
     * Constructor
     *
     * @param {MatDialogRef<ContactsContactFormDialogComponent>} matDialogRef
     * @param _data
     * @param {FormBuilder} _formBuilder
     */
    constructor(
        public matDialogRef: MatDialogRef<ContactsContactFormDialogComponent>,
        @Inject(MAT_DIALOG_DATA) private _data: any,
        private _formBuilder: FormBuilder
    )
    {
        // Set the defaults
        this.action = _data.action;

        if ( this.action === 'edit' )
        {
            this.dialogTitle = 'View Contact';
            this.contact = _data.contact;
        }
        else
        {
            this.dialogTitle = 'New Contact';
            this.contact = new Contact({});
        }

        this.contactForm = this.createContactForm();
    }

    // -----------------------------------------------------------------------------------------------------
    // @ Public methods
    // -----------------------------------------------------------------------------------------------------

    /**
     * Create contact form
     *
     * @returns {FormGroup}
     */
    createContactForm(): FormGroup
    {
        let roleName = "School Administrator";
        if ( this.contact.role == 2 )
            roleName = "Technical Manager";
        else if( this.contact.role == 3 )
            roleName = "Personal Trainer";
        else if( this.contact.role == 4 )
            roleName = "Athlete";

        return this._formBuilder.group({
            id      : [this.contact.id],
            name    : [this.contact.first_name],
            lastName: [this.contact.last_name],
            avatar  : [this.urlHeader + this.contact.pic],
            nickname: [""],
            // role    : [this.contact.role],
            role    : [roleName],
            company : [""],
            comment : [this.contact.comment],
            email   : [this.contact.email],
            phone   : [this.contact.phone],
            // address : [this.contact.address],
            birthday: [this.contact.birthday],
            notes   : [""]
        });
    }
}
