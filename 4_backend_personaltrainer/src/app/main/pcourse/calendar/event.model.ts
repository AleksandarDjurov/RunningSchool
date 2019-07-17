import { CalendarEventAction } from 'angular-calendar';
import { startOfDay, endOfDay } from 'date-fns';

export class CalendarEventModel
{
    start: Date;
    end?: Date;
    start_time: string;
    end_time: string;
    title: string;
    desc: string;
    price: string;
    seat: string;
    maincourse: string;
    color: {
        primary: string;
        secondary: string;
    };
    actions?: CalendarEventAction[];
    allDay?: boolean;
    cssClass?: string;
    resizable?: {
        beforeStart?: boolean;
        afterEnd?: boolean;
    };
    draggable?: boolean;
    meta?: {
        location: string,
        notes: string
    };

    /**
     * Constructor
     *
     * @param data
     */
    constructor(data?)
    {
        data = data || {};
        this.start = new Date(data.start) || startOfDay(new Date());
        this.end = new Date(data.end) || endOfDay(new Date());

        this.start_time = data.start_time || '';
        this.end_time  =  data.end_time || '';
    
        this.title = data.title || '';
        this.desc  = data.desc || '';
        this.price  = data.price || '';
        this.seat  = data.seat || '';
        this.maincourse  = data.maincourse || '';

        this.color = {
            primary  : data.color && data.color.primary || '#1e90ff',
            secondary: data.color && data.color.secondary || '#D1E8FF'
        };
        this.draggable = data.draggable;
        this.resizable = {
            beforeStart: data.resizable && data.resizable.beforeStart || true,
            afterEnd   : data.resizable && data.resizable.afterEnd || true
        };
        this.actions = data.actions || [];
        this.allDay = data.allDay || false;
        this.cssClass = data.cssClass || '';
        this.meta = {
            location: data.meta && data.meta.location || '',
            notes   : data.meta && data.meta.notes || ''
        };
    }
}
