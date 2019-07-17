import { MatChipInputEvent } from '@angular/material';

import { FuseUtils } from '@fuse/utils';

export interface Permissions {
    school_id: number;
    type: string;
    url: string;
    size: string;
}

export interface Upload {
    school_id: number;
    type: string;
    url: string;
    size: string;
}

export interface Download {
    school_id: number;
    type: string;
    url: string;
    size: string;
}

export interface Athlete
{
    id: number;
    email: string;
    permissions: Permissions[];
    last_login: string;
    first_name: string;
    last_name: string;
    mobile_phone: string;
    gender: string;
    dob: string;
    birth_place: string;
    nationality: string;
    pic: string;
    country: string;
    state: string;
    region: string;
    province: string;
    city: string;
    address: string;
    postal: string;
    group: number;
    vat_number: string;
    fiscal_code: string;
    membership_type: number;
    bio: string;
    certified_type: string;
    card_number: string;
    token: string;
    status: number;
    created_at: string;
    updated_at: string;
    deleted_at: string;
    state_id: number;
    region_id: number;
    province_id: number;
    upload: Upload[];
    download: Download[];
}

export class AthletePlayer
{
    personal : Athlete;
    /**
     * Constructor
     *
     * @param personaltrainer
     */
    constructor(athleteplayer)
    {
        this.personal = athleteplayer.personal;

        // this.id = personaltrainer.id;
        // this.email = personaltrainer.email;
        // this.permissions = personaltrainer.permissions;
        // this.last_login = personaltrainer.last_login;
        // this.first_name = personaltrainer.first_name;
        // this.last_name = personaltrainer.last_name;
        // this.mobile_phone = personaltrainer.mobile_phone;
        // this.gender = personaltrainer.gender;
        // this.dob = personaltrainer.dob;
        // this.birth_place = personaltrainer.birth_place;
        // this.nationality = personaltrainer.nationality;
        // this.pic = personaltrainer.pic;
        // this.country = personaltrainer.country;
        // this.state = personaltrainer.state;
        // this.region = personaltrainer.region;
        // this.province = personaltrainer.province;
        // this.city = personaltrainer.city;
        // this.address = personaltrainer.address;
        // this.postal = personaltrainer.postal;
        // this.group = personaltrainer.group;
        // this.vat_number = personaltrainer.vat_number;
        // this.fiscal_code = personaltrainer.fiscal_code;
        // this.membership_type = personaltrainer.membership_type;
        // this.bio = personaltrainer.bio;
        // this.certified_type = personaltrainer.certified_type;
        // this.card_number = personaltrainer.card_number;
        // this.token = personaltrainer.token;
        // this.status = personaltrainer.status;
        // this.created_at = personaltrainer.created_at;
        // this.updated_at = personaltrainer.updated_at;
        // this.deleted_at = personaltrainer.deleted_at;
        // this.state_id = personaltrainer.state_id;
        // this.region_id = personaltrainer.region_id;
        // this.province_id = personaltrainer.province_id;
        // this.upload = personaltrainer.upload;
        // this.download = personaltrainer.download;
    }
}
