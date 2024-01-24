interface CalendarEvent {
    id: number,
    title: string,
    allDay?: boolean,
    start: Date,
    end: Date,
}