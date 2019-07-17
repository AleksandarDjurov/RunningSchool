import { FuseUtils } from '@fuse/utils';

export class Trainer
{
    id: string;
    email: string;
    password: string;
    permissions: string;
    last_login: string;
    first_name : string;
    last_name :string;
    mobile_phone:string;
    gender:string;
    dob:string;
    birth_place:string;
    nationality:string;
    pic:string;
    country:string;
    state:string;
    region:string;
    province:string;
    city:string;
    address:string;
    postal:string;
    group:number;
    vat_number:string;
    fiscal_code:string;
    membership_type:string;
    bio:string;
    certified_type:string;
    card_number:string;
    token:string;
    status:number;
    created_at:string;
    updated_at:string;
    deleted_at:string;
    school_id:number;
    school_name:string;
    role_id:number;
    role_name:string;
    /**
     * Constructor
     *
     * @param trainer
     */
    constructor(trainer)
    {
        this.id = trainer.id;
        this.email = trainer.email;
        this.password = trainer.password;
        this.permissions = trainer.permissions;
        this.last_login = trainer.last_login;
        this.first_name = trainer.first_name;
        this.last_name = trainer.last_name;
        this.mobile_phone = trainer.mobile_phone;
        this.gender = trainer.gender;
        this.dob = trainer.dob;
        this.birth_place = trainer.birth_place;
        this.nationality = trainer.nationality;
        this.pic = trainer.pic;
        this.country = trainer.country;
        this.state = trainer.state;
        this.region = trainer.region;
        this.province = trainer.province;
        this.city = trainer.city;
        this.address = trainer.address;
        this.postal = trainer.postal;
        this.group = trainer.group;
        this.vat_number = trainer.vat_number;
        this.fiscal_code = trainer.fiscal_code;
        this.membership_type = trainer.membership_type;
        this.bio = trainer.bio;
        this.certified_type = trainer.certified_type;
        this.card_number = trainer.card_number;
        this.token = trainer.token;
        this.status = trainer.status;
        this.created_at = trainer.created_at;
        this.updated_at = trainer.updated_at;
        this.deleted_at = trainer.deleted_at;
        this.school_id = trainer.school_id;
        this.school_name = trainer.school_name;
        this.role_id = trainer.role_id;
        this.role_name = trainer.role_name;
    }
}
