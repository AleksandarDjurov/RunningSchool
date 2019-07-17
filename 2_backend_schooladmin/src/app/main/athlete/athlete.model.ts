import { FuseUtils } from '@fuse/utils';
import { isThisSecond } from 'date-fns';
import { containsTree } from '@angular/router/src/url_tree';

export class Athlete {
    id: number;
    school_course_id:number;
    user_id:number;
    course_id:number;
    level_id:number;
    original_amount:number;
    pay_amount:number;
    athlete_file:string;
    description:string;
    status:number;
    created_at:string;
    updated_at:string;
    deleted_at:string;
    course_name:string;
    level_name:string;
    school_course_name:string;
    school_course_desc:string;
    athlete_first_name:string;
    athlete_last_name:string;
    avatar:string;
    mail:string;
    phone:string;
    sex:string;

    /**
     * Constructor
     *
     * @param contact
     */
    constructor(contact) {

        this.id = contact.id;
        this.school_course_id = contact.school_course_id;
        this.user_id = contact.user_id;
        this.course_id = contact.course_id;
        this.level_id = contact.level_id;
        this.original_amount = contact.original_amount;
        this.pay_amount = contact.pay_amount;
        this.athlete_file = contact.athlete_file;
        this.description = contact.description;
        this.status = contact.status;
        this.created_at = contact.created_at;
        this.updated_at = contact.updated_at;
        this.deleted_at = contact.deleted_at;
        this.course_name =contact.course_name;
        this.level_name = contact.level_name;
        this.school_course_name = contact.school_course_name;
        this.school_course_desc = contact.school_course_desc;
        this.athlete_first_name = contact.athlete_first_name;
        this.athlete_last_name = contact.athlete_last_name;
        this.avatar = contact.avatar;
        this.mail = contact.mail;
        this.phone = contact.phone;
        this.sex = contact.sex;
    }
}
