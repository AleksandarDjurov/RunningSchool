import { MatChipInputEvent } from '@angular/material';

import { FuseUtils } from '@fuse/utils';

export class TechnManager
{
    id:string;
    school_id:string;
    user_image:string;
    first_name:string;
    last_name:string;
    nationality:string;
    gender:string;
    email:string;
    dateofbirth:string;
    birthplace:string;
    mobile_phone:string;
    state:string;
    region:string;
    province:string;
    city:string;
    address:string;
    certified_type:string;
    postal_code:string;
    group:string;
    vat_number:string;
    fiscal_code:string;
    card_number:string;
    membership:string;
    bio:string;

    /**
     * Constructor
     *
     * @param technmanager
     */
    constructor(technmanager?)
    {
        technmanager = technmanager || {};

        this.id         = technmanager.id;
        this.school_id  = technmanager.school_id;
        this.user_image= technmanager.user_image;
        this.first_name= technmanager.first_name;
        this.last_name= technmanager.last_name;
        this.nationality= technmanager.nationality;
        this.gender= technmanager.gender;
        this.email= technmanager.email;
        this.dateofbirth= technmanager.dateofbirth;
        this.birthplace= technmanager.birthplace;
        this.mobile_phone= technmanager.mobile_phone;
        this.state= technmanager.state;
        this.region= technmanager.region;
        this.province= technmanager.province;
        this.city= technmanager.city;
        this.address= technmanager.address;
        this.certified_type= technmanager.certified_type;
        this.postal_code= technmanager.postal_code;
        this.group= technmanager.group;
        this.vat_number= technmanager.vat_number;
        this.fiscal_code= technmanager.fiscal_code;
        this.card_number= technmanager.card_number;
        this.membership= technmanager.membership;
        this.bio= technmanager.bio;
    }
}
