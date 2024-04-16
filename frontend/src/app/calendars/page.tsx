import sendRequest from "@/lib/request";
import Card from "@/components/Card";
import React from "react";
import PrimaryButton from "@/components/PrimaryButton";
import BackButton from "@/components/BackButton";
import Form from "@/components/Form";
import AuthenticationForm from "@/components/AuthenticationForm";
import CalendarList from "@/components/Calendar/CalendarList";
import Link from "next/link";
export default async function CalendarPage() {

    // TODO Drag and drop notes
    // TODO Color picker + store this color for the future
    // TODO Set background image
    // TODO Color presets
    // TODO if a dark color is selected, change color to white

    const response = await sendRequest('GET', '/api/calendars');

    if(response.status !== 200) {
        throw new Error('Failed to fetch calendars');
    }

    const json = await response.json();

    const calendars = json.calendars;

    return (
        <div className={"title-text"}>
          <h1 className="large-title">Welcome back, Ash</h1>
          <hr className="w-10/12 md:w-8/12 h-1 bg-gray-200 rounded"></hr>
          <CalendarList calendars={calendars} />
        </div>
    );
}