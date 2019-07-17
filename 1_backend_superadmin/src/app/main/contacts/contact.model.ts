import { FuseUtils } from '@fuse/utils';

export class Contact {
    id: number;
    first_name: string;
    last_name: string;
    email: string;
    phone: string;
    comment: string;
    role: number;
    roleName: string;
    address: string;
    birthday: string;
    pic: string;
    status: string;
    statusName: string;
    created_at: string;
    updated_at: string;
    deleted_at: string;

    /**
     * Constructor
     *
     * @param contact
     */
    constructor(contact) {
        this.id = contact.id;
        this.first_name = contact.first_name;
        this.last_name = contact.last_name
        this.email = contact.email;
        this.phone = contact.phone;
        this.comment = contact.comment;
        this.role = contact.role;

        if (this.role == 2)
            this.roleName = 'School Administrator';
        else if (this.role == 3)
            this.roleName = 'Technical Manager';
        else if (this.role == 4)
            this.roleName = 'Personal Trainer';
        else if (this.role == 5)
            this.roleName = 'Athlete';

        this.address = contact.address;
        this.birthday = contact.brithday;
        this.pic = contact.pic;
        this.status = contact.status;

        if (this.status == '0')
            this.statusName = 'Pending';
        else if (this.status == '1')
            this.statusName = 'Approved';
        else if (this.status == '2')
            this.statusName = 'Rejected';
        else if (this.status == '3')
            this.statusName = 'Deleted';

        this.created_at = contact.created_at;
        this.updated_at = contact.updated_at;
        this.deleted_at = contact.deleted_at;
    }
}
