import sendRequest from "@/lib/request";
import React from "react";
import CalendarList from "@/components/Calendar/CalendarList";
export default async function CalendarPage() {

    const response = await sendRequest('GET', '/api/calendars?trashed=1');

    if(response.status !== 200) {
        throw new Error('Failed to fetch calendars');
    }

    const json = await response.json();

    const calendars = json.calendars;

    return (
        <div className="flex flex-col items-center justify-center space-y-10">
            <h1 className="text-3xl text-slate-600">Recently Deleted Calendars</h1>
            <hr className="w-full h-1 bg-gray-200 rounded"></hr>
            <div className={"flex flex-col"}>
                <CalendarList calendars={calendars} deleted={true} />
            </div>
        </div>
    );
}