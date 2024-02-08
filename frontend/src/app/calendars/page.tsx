import sendRequest from "@/lib/request";
import Card from "@/components/Card";
import React from "react";
import Button from "@/components/Button";
import BackButton from "@/components/BackButton";
import Form from "@/components/Form";
import AuthenticationForm from "@/components/AuthenticationForm";
import CalendarList from "@/components/Calendar/CalendarList";
export default async function CalendarPage() {

    // TODO Drag and drop notes
    // TODO Color picker + store this color for the future
    // TODO Set background image
    // TODO Color presets
    // TODO if a dark color is selected, change color to white

    const response = await sendRequest('GET', '/api/calendars', null ,1);

    if(response.status !== 200) {
        throw new Error('Failed to fetch calendars');
    }

    const json = await response.json();

    const calendars = json.calendars;

    return (
        <div className="flex flex-col items-center justify-center space-y-10">
            <h1 className="text-6xl font-bold text-slate-900">Welcome Back, Ash</h1>
            <hr className="w-full h-1 bg-gray-200 rounded"></hr>
            <div className={"flex flex-col"}>
            <p className="text-2xl text-slate-600">Your Calendars</p>
                <CalendarList calendars={calendars} />
            </div>
        </div>
    );
}