"use client"

import React, {useState} from "react";

import 'react-big-calendar/lib/css/react-big-calendar.css';
import Form from "@/components/Form";
import Card from "@/components/Card";
import PrimaryButton from "@/components/PrimaryButton";
import sendRequest from "@/lib/request";
import DangerButton from "@/components/DangerButton";
import Link from "next/link";

export default function CalendarList({calendars, deleted=false}: {calendars: Calendar[], deleted?: boolean}) {

    const [calendarList, setCalendars] = useState<Calendar[]>(calendars)
    const [showCreateDropdown, setShowCreateDropdown] = useState(false)
    const [isSubmitting, setIsSubmitting] = useState(false)

    return (
        <>
            {!deleted && (
                <div className={"flex flex-col w-8/12"}>
                    <p className="text-4xl text-slate-600 text-center">Your Calendars</p>
                    <Form className={"w-full"} method={"POST"} action={"/api/calendars"} buttonText={"Create New"} showSubmitButton={false} formSubmitThen={(status: any, response: any) => {handleResponse(setCalendars, calendarList, setIsSubmitting, status, response)}}>
                        <div className={"flex flex-row ml-auto w-3/12 space-x-4"}>
                            <div className={"w-full flex ml-auto justify-center"}>
                                <DangerButton className={(!showCreateDropdown ? "hidden" : "") + " w-full flex ml-auto justify-center"} onClick={() => {setShowCreateDropdown(!showCreateDropdown)}}>
                                    Close
                                </DangerButton>
                            </div>
                            <PrimaryButton className={"w-full flex ml-auto justify-center bg-red-200"} onClick={(event) => { setShowCreateDropdown(!showCreateDropdown); showCreateDropdown ? setIsSubmitting(true) : event.preventDefault() }}>
                                {(showCreateDropdown ? "Save" : "Create New")}
                            </PrimaryButton>
                        </div>
                        <div className={"w-3/12 mt-2 ml-auto relative"}>
                            <div className={(!showCreateDropdown ? "hidden" : "") + " z-10 w-full absolute bg-white border-2 border-zinc-100 rounded drop-shadow-xl"}>
                                <input required={true} type="text" name="title" placeholder="Name"
                                       className={(!showCreateDropdown ? "hidden" : "") + " w-full p-2 rounded outline-none"} maxLength={255}/>
                            </div>
                        </div>
                    </Form>
                </div>
            )}
            <div className={"md:grid md:grid-cols-2 md:w-8/12 md:space-x-0 md:gap-4 md:overflow-visible space-x-8 flex flex-nowrap flex-row overflow-x-scroll px-8 scrollbar-hidden overflow-scroll w-screen"}>
                {calendarList.map((calendar: Calendar) => (
                    <Card key={calendar.id} title={calendar.title}>
                        { deleted ? (
                            <>
                                <PrimaryButton onClick={() => {restoreCalendar(calendar, calendars, setCalendars)}}>Recover</PrimaryButton>
                                <p>Deleted on {calendar.deleted_at}</p>
                            </>
                        ): (
                          <div className={"flex flex-col w-2/12 ml-auto space-y-4"}>
                            <Link href={`/calendars/${calendar.id}`} className={"button-primary w-full"}>View</Link>
                            <DangerButton className={"w-full"} onClick={() => {deleteCalendar(calendar, calendars, setCalendars)}}>Delete</DangerButton>
                          </div>
                        )}
                    </Card>
                ))}
            </div>
        </>
    );
}

async function handleResponse(setCalendars: any, calendarList: Calendar[], setIsSubmitting: any, status?: number, response?: Promise<any>) {

    if(!status || !response) {
        return;
    }

    const calendar = (await response).calendar

    setIsSubmitting(false)

    if(status !== 201) {
        throw new Error("Failed to create calendar")
    }

    setCalendars(
        [
            ...calendarList,
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