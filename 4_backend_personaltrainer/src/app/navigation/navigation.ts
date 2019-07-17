import { FuseNavigation } from '@fuse/types';

export const navigation: FuseNavigation[] = [
    {
        id: 'applications',
        title: 'Applications',
        translate: 'NAV.APPLICATIONS',
        type: 'group',
        children: [
            {
                id: 'Quarter_Course',
                title: 'Quarter Course',
                translate: 'NAV.QUARTER.TITLE',
                type: 'item',
                icon: 'account_balance',
                url: '/maincourses',
            },
            {
                id: 'School_Course',
                title: 'School Course',
                translate: 'NAV.MYSCHOOL.TITLE',
                icon: 'apps',
                type: 'item',
                url: '/schoolcourses'
            },
            {
                id: 'My_Course',
                title: 'My Course',
                translate: 'NAV.MYCOURSE.TITLE',
                icon: 'archive',
                type: 'collapsable',
                children: [
                    {
                        id: 'active',
                        title: 'Active',
                        type: 'item',
                        url: '/pcourses/1'
                    },
                    {
                        id: 'close',
                        title: 'Closed',
                        type: 'item',
                        url: '/pcourses/2'
                    },
                ]
            },
            {
                id: 'My_Athlete',
                title: 'My Athlete',
                translate: 'NAV.MYATHLETE.TITLE',
                icon: 'accessibility_new',
                type: 'collapsable',
                children: [
                    {
                        id: 'pending',
                        title: 'Pending',
                        type: 'item',
                        url  : '/athletes/0'
                    },
                    {
                        id: 'active',
                        title: 'Active',
                        type: 'item',
                        url  : '/athletes/1'
                    },
                    {
                        id: 'closed',
                        title: 'Closed',
                        type: 'item',
                        url  : '/athletes/2'
                    },
                ]
            },
        ]
    }
];
