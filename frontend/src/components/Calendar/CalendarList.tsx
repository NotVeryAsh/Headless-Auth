"use client"

import React, {useState} from "react";

import 'react-big-calendar/lib/css/react-big-calendar.css';
import Form from "@/components/Form";
import Card from "@/components/Card";
import Link from "next/link";
import Button from "@/components/Button";
import sendRequest from "@/lib/request";

export default function CalendarList({calendars, deleted=false}: {calendars: Calendar[], deleted?: boolean}) {

    const [calendarList, setCalendars] = useState<Calendar[]>(calendars)

    return (
        <>
            {!deleted ? (
                <>
                    <Form method={"POST"} action={"/api/calendars"} buttonText={"Create New"} formSubmitThen={(status?: number | undefined, response?: Promise<any>) => { handleResponse(setCalendars, calendars, status, response)}}>
                        <input type="text" name="title" placeholder="Name"
                               className="w-full p-2 my-2 border border-gray-300 rounded" maxLength={255}/>
                    </Form>
                    <Link href={"calendars/deleted"} className="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded">
                        Recently Deleted
                    </Link>
                </>
            ): null}
            {calendarList.map((calendar: Calendar) => (
                <Card key={calendar.id} title={calendar.title}>
                    { deleted ? (
                        <>
                            <Button onClick={() => {restoreCalendar(calendar, calendars, setCalendars)}}>Recover</Button>
                            <p>Deleted on {calendar.deleted_at}</p>
                        </>
                    ): (
                        <Button onClick={() => {deleteCalendar(calendar, calendars, setCalendars)}}>Delete</Button>
                    )}
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

async function deleteCalendar(calendar: Calendar, calendars: Calendar[], setCalendars: any) {
    const response = await sendRequest('DELETE', `/api/calendars/${calendar.id}`);

    if(response.status !== 200) {
        throw new Error('Failed to delete calendar');
    }

    setCalendars([
        calendars.filter(item => item.id !== calendar.id)
    ])
}

async function restoreCalendar(calendar: Calendar, calendars: Calendar[], setCalendars: any) {
    const response = await sendRequest('PATCH', `/api/calendars/${calendar.id}/restore`);

    if(response.status !== 200) {
        throw new Error('Failed to restore calendar');
    }

    setCalendars([
        calendars.filter(item => item.id !== calendar.id)
    ])
}