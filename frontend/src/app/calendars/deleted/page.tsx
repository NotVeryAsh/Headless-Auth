import sendRequest from "@/lib/request";
import React from "react";
import CalendarList from "@/components/Calendar/CalendarList";
export default async function DeletedCalendarPage() {

    const response = await sendRequest('GET', '/api/calendars?trashed=1');

    if(response.status !== 200) {
        throw new Error('Failed to fetch calendars');
    }

    const json = await response.json();

    const calendars = json.calendars;

    return (
      <div className={"title-text"}>
        <h1 className="large-title">Welcome back, Ash</h1>
        <hr className="w-10/12 md:w-8/12  h-1 bg-gray-200 rounded"></hr>
        <CalendarList calendars={calendars} deleted={true} />
      </div>
    );
}