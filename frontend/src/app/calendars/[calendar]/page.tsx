import CalendarBlock from "@/components/Calendar/CalendarBlock";
import sendRequest from "@/lib/request";
export default async function CalendarPage({ params }: { params: { calendar: any } }) {

    // TODO Drag and drop notes
    // TODO Color picker + store this color for the future
    // TODO Set background image
    // TODO Color presets
    // TODO if a dark color is selected, change color to white

    const response = await sendRequest('GET', '/api/calendar/events');

    if(response.status !== 200) {
        throw new Error('Failed to fetch calendar items');
    }

    const json = await response.json();
    const events = json.events;

    return (
        <CalendarBlock events={events} />
    );
}