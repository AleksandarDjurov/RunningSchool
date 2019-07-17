import { MatChipInputEvent } from '@angular/material';

import { FuseUtils } from '@fuse/utils';

export interface State {
    id: number;
    state_name: string;
    created_at: string;
    updated_at: string;
    deleted_at: string;
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

export interface Permissions {
    school_id: number;
    type: string;
    url: string;
    size: string;
}

export interface School {
    id: number;
    name: string;
    logo_path: string;
    banner_path: string;
    reference_asd: string;
    company_code: string;
    state: string;
    region: string;
    province: string;
    city: string;
    address: string;
    postal_code: string;
    user_id: number;
    status: number;
    membership_type: number;
    created_at: string;
    updated_at: string;
    deleted_at: string;
    upload : Upload[];
    download: Download[];
}

export interface School_Manager {
    id: number;
    email: string;
    permissions: Permissions[],
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
    membership_type: string;
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
    upload : Upload[];
    ownload : Download[];
}
export interface Technical_Manager {
    id: number;
    email: string;
    permissions: Permissions[],
    last_login: string,
    first_name: string,
    last_name: string,
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
    province_id: null;
    upload : Upload[]
    download : Download[]
}

export class SchoolData {
    school : School;
    school_manager : School_Manager;
    technical_person : Technical_Manager;
    states: State;

    /**
     * Constructor
     *
     * @param ret_Data
     */
    constructor(ret_Data) {
        this.school             = ret_Data.school;
        this.school_manager     = ret_Data.school_manager;
        this.technical_person   = ret_Data.technical_person;
        this.states             = ret_Data.states;
    }
}
