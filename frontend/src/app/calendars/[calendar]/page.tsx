import CalendarBlock from "@/components/Calendar/CalendarBlock";

export default async function CalendarPage({ params }: { params: { calendar: any } }) {

    return (
        <CalendarBlock calendarId={params.calendar} />
    );
}