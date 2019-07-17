import { FuseNavigation } from '@fuse/types';

export const navigation: FuseNavigation[] = [
    {
        id       : 'applications',
        title    : 'Applications',
        translate: 'NAV.APPLICATIONS',
        type     : 'group',
        children : [
            // {
            //     id       : 'dashboards',
            //     title    : 'Dashboards',
            //     translate: 'NAV.DASHBOARDS',
            //     type     : 'item',
            //     icon     : 'dashboard',
            //     url      : '/dashboard'
            // },
            {
                id       : 'purchase',
                title    : 'Purchase',
                translate: 'NAV.PURCHASE',
                type     : 'item',
                icon     : 'euro_symbol',
                url      : '/purchase'
            },
            {
                id       : 'course',
                title    : 'Course',
                translate: 'NAV.COURSE',
                type     : 'collapsable',
                icon     : 'school',
                children : [
                    {
                        id   : 'qcourse',
                        title: 'Quarter course',
                        translate: 'NAV.QUART_COURSE',
                        type : 'item',
                        url  : '/maincourses'
                    },
                    {
                        id   : 'pcourse',
                        title: 'Pending course',
                        translate: 'NAV.COURSE_PENDINGLIST',
                        type : 'item',
                        url  : '/scourses/0'
                    },
                    {
                        id   : 'acourse',
                        title: 'Active course',
                        translate: 'NAV.COURSE_ACTIVELIST',
                        type : 'item',
                        url  : '/scourses/1'
                    },
                    {
                        id   : 'rcourse',
                        title: 'Closed course',
                        translate: 'NAV.COURSE_CLOSED',
                        type : 'item',
                        url  : '/scourses/2'
                    },
                ]
            },
            {
                id       : 'trainer',
                title    : 'Trainer',
                translate: 'NAV.TRAINER',
                icon     : 'accessibility_new',
                type     : 'item',
                url      : '/trainers'
            },
            {
                id       : 'athlete',
                title    : 'Athlete',
                translate: 'NAV.ATHLETE',
                icon     : 'directions_run',
                type     : 'collapsable',
                children : [
                    {
                        id   : 'pathlete',
                        title: 'Pending',
                        translate: 'NAV.PENDING_ATHLETE',
                        type : 'item',
                        url  : '/athletes/0'
                    },
                    {
                        id   : 'aathlete',
                        title: 'Active',
                        translate: 'NAV.ACTIVE_ATHLETE',
                        type : 'item',
                        url  : '/athletes/1'
                    },
                    {
                        id   : 'aathlete',
                        title: 'Closed',
                        translate: 'NAV.CLOSED_ATHLETE',
                        type : 'item',
                        url  : '/athletes/2'
                    },
                ]
            },
        ]
    }
];
