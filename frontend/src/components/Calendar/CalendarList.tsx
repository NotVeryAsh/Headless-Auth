"use client"

import React, {useState} from "react";

import 'react-big-calendar/lib/css/react-big-calendar.css';
import Form from "@/components/Form";
import Card from "@/components/Card";

export default function CalendarList({calendars}: {calendars: Calendar[]}) {

    const [calendarList, setCalendars] = useState<Calendar[]>(calendars)

    return (
        <>
            <Form method={"POST"} action={"/api/calendars"} buttonText={"Create New"} formSubmitThen={(status?: number | undefined, response?: Promise<any>) => { handleResponse(setCalendars, calendars, status, response)}}>
                <input type="text" name="title" placeholder="Name"
                       className="w-full p-2 my-2 border border-gray-300 rounded" maxLength={255}/>
            </Form>
            {calendarList.map((calendar) => (
                <Card key={calendar.id} title={calendar.title}>
                </Card>
            ))}
        </>
    );
}

async function handleResponse(setCalendars: any, calendars: Calendar[], status?: number, response?: Promise<any>) {

    if(!status || !response) {
        return;
    }

    const calendar = (await response).calendar

    if(status !== 201) {
        throw new Error("Failed to create calendar")
    }

    setCalendars(
        [
            ...calendars,
            { id: calendar.id, title: calendar.title }
        ]
    );
}