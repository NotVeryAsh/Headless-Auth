"use client"

import React from "react";

import moment from 'moment'
import { Calendar, momentLocalizer } from 'react-big-calendar'
import 'react-big-calendar/lib/css/react-big-calendar.css';

const localizer = momentLocalizer(moment)

export default function CalendarBlock({events}: {events: CalendarEvent[]}) {
    return (
        <div className={"flex h-screen font-medium text-neutral-800 w-max"}>
            <Calendar
                localizer={localizer}
                events={events}
                startAccessor="start"
                endAccessor="end"
            />
        </div>
    );
}