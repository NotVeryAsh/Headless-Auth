"use client"

import React, {useEffect, useRef, useState} from "react";
import FullCalendar from '@fullcalendar/react'
import dayGridPlugin from '@fullcalendar/daygrid'
import interactionPlugin from '@fullcalendar/interaction'
import timeGridPlugin from '@fullcalendar/timegrid'
import PrimaryButton from "@/components/PrimaryButton";
import Modal from "@/components/Calendar/Modal";
import sendRequest from "@/lib/request";
import DangerButton from "@/components/DangerButton";
import moment from "moment";
import {Simulate} from "react-dom/test-utils";
import select = Simulate.select;

export default function CalendarBlock({calendarId}: {calendarId: any}) {

  const [showCreateEventModal, setShowCreateEventModal] = useState(false)
  const [defaultEventDates, setDefaultEventDates] = useState({
    start: "",
    end: ""
  })
  const [events, setEvents] = useState([])
  const [formErrors, setFormErrors] = useState([])
  const [selectedEvent, setSelectedEvent] = useState({})
  const [showEventModal, setShowEventModal] = useState(false)
  const [showMobileView, setShowMobileView] = useState(window.innerWidth < 768)
  const calendarRef = useRef(null)

  const getEvents = async (calendarId: number) => {
    const response = await sendRequest('GET', `/api/calendars/${calendarId}/events`);

    if(response.status !== 200) {
      throw new Error('Failed to fetch calendar items');
    }

    const json = await response.json();
    return json.events;
  }

  const handleResponse = async (status: number, response: any, event?: any) => {
    const data = await response;

    if(data.errors) {

      let errorMessages: string[]  = [];

      const errorArrays: any[] = Object.values(data.errors)

      errorArrays.map(function(errorArrays: any[]) {
        errorArrays.map(function (errorMessage: string) {
          return errorMessages.push(errorMessage);
        })
      })

      setFormErrors([
        ...errorMessages
      ])

      errorMessages = []

      return;
    }

    if(Object.keys(selectedEvent).length > 0) {

      const updatedEvents = events.map((event) => {
        if (event.id === data.event.id) {
          // Increment the clicked counter
          return data.event
        } else {
          // The rest haven't changed
          return event;
        }
      });

      setEvents(updatedEvents)
      setShowEventModal(false)
      setSelectedEvent({})
      return;
    }

    setEvents([...events, data.event])
  }

  const handleEventClick = (event: any) => {

    setDefaultEventDates({
      start: moment(event.start).format("YYYY-MM-DDTHH:mm:ss"),
      end: moment(event.end).format("YYYY-MM-DDTHH:mm:ss")
    })

    setSelectedEvent(event.event)
    setShowEventModal(true)
  }

  const handleEventResizeAndDrop = async (data) => {
    const event = data.event

    const response = await sendRequest('PATCH', `/api/events/${event.id}`, {
      start: event.start.toISOString(),
      end: event.end.toISOString()
    });

    if(response.status !== 200) {
      throw new Error('Failed to update event');
    }

    const json = await response.json();
    const updatedEvent = json.event

    const updatedEvents = events.map((event) => {
      if (event.id === updatedEvent.id) {
        // Increment the clicked counter
        return updatedEvent
      } else {
        // The rest haven't changed
        return event;
      }
    });

    setEvents(updatedEvents)
  }

  const handleWindowResize = (arg) => {
    const mobileView = window.innerWidth < 768
    setShowMobileView(mobileView)

    calendarRef.current.getApi().changeView(mobileView ? 'timeGridDay' : 'timeGridWeek')
  }

  const handleDeleteEvent = async (eventToDelete) => {
    const response = await sendRequest('DELETE', `/api/events/${eventToDelete.id}`);

    if(response.status !== 200) {
      throw new Error('Failed to delete event');
    }

    const json = await response.json();
    const deletedEvent = json.event;

    const remainingEvents = events.filter((event) => {
      return event.id !== deletedEvent.id
    });

    setEvents(remainingEvents)
    setShowEventModal(false)
    setSelectedEvent({})
    return;
  }

  const handleDateTimeSelected = (selectionInfo) => {

    setDefaultEventDates({
      start: moment(selectionInfo.start).format("YYYY-MM-DDTHH:mm:ss"),
      end: moment(selectionInfo.end).format("YYYY-MM-DDTHH:mm:ss")
    })
    setShowCreateEventModal(true);
  }

  useEffect(() => {
    getEvents(calendarId).then((events) => {setEvents(events)});
  }, [calendarId]);

  return (
    <div className="w-full mt-4">
      <CreateEventModal calendarId={calendarId} handleResponse={handleResponse} showCreateEventModal={showCreateEventModal}
                        setShowCreateEventModal={setShowCreateEventModal} setFormErrors={setFormErrors} formErrors={formErrors}
                        defaultValues={defaultEventDates} setDefaultValues={setDefaultEventDates}
      >
      </CreateEventModal>
      <EventModal event={selectedEvent} handleResponse={handleResponse} showEventModal={showEventModal}
                  setShowEventModal={setShowEventModal} setFormErrors={setFormErrors} formErrors={formErrors}
                  handleDeleteEvent={handleDeleteEvent} defaultValues={defaultEventDates} setDefaultValues={setDefaultEventDates}
      >
      </EventModal>
      <div className={"h-full flex flex-col"}>
        <div className={"flex"}>
          <PrimaryButton className={"ml-auto"} onClick={() => (setShowCreateEventModal(true))}>Create Event</PrimaryButton>
        </div>
        <FullCalendar
          ref={calendarRef}
          events={events}
          plugins={[
            dayGridPlugin,
            interactionPlugin,
            timeGridPlugin
          ]}
          headerToolbar={{
            left: showMobileView ? 'prev,next today' : 'prev,next today',
            center: showMobileView ? '' : 'title',
            right: showMobileView ? 'title' : 'timeGridDay,timeGridWeek,dayGridMonth'
          }}
          initialView={showMobileView ? 'timeGridDay' : 'timeGridWeek'}
          nowIndicator={true}
          editable={true}
          eventDurationEditable={true}
          eventResizableFromStart={true}
          selectable={true}
          selectMirror={true}
          allDaySlot={false}
          eventClick={handleEventClick}
          eventDrop={handleEventResizeAndDrop}
          eventResize={handleEventResizeAndDrop}
          windowResize={handleWindowResize}
          select={handleDateTimeSelected}
        />
      </div>
    </div>
  );
}

function CreateEventModal({calendarId, handleResponse, setShowCreateEventModal, showCreateEventModal, setFormErrors, formErrors, defaultValues, setDefaultValues}, {})
{
  return (
    <Modal title={"Create New Event"} formMethod={"POST"} formAction={`/api/calendars/${calendarId}/events`}
           formSubmitThen={(status: any, response: any) => {handleResponse(status, response)}} isOpen={showCreateEventModal}
           setIsOpen={setShowCreateEventModal} onSubmit={() => {setFormErrors([])}} submitButtonType={"submit"}>
      <ul id={"form_errors"} className={"flex flex-col space-y-2 text-red-400"}>{formErrors.map((error, index) => (
        <li key={index}>
          {error}
        </li>
      ))}</ul>
        <input
          type="datetime-local"
          name="start"
          required={true}
          className="input"
          value={defaultValues.start}
          onChange={(event) => {setDefaultValues({start: moment(event.target.value).format("YYYY-MM-DDTHH:mm:ss"), end: defaultValues.end})}}
        />
        <input
          type="datetime-local"
          name="end"
          required={true}
          className="input w-1/2"
          value={defaultValues.end}
          onChange={(event) => {setDefaultValues({start: defaultValues.start, end: moment(event.target.value).format("YYYY-MM-DDTHH:mm:ss")})}}
        />
      <input required={true} type="text" name="title" placeholder="Title" className="input" maxLength={255} />
      <div className={"flex space-x-3 mb-2"}>
        <input type="checkbox" name="all_day" /> <label htmlFor={"all_day"}>All day</label>
      </div>
    </Modal>
  )
}

function EventModal({event, handleResponse, setShowEventModal, showEventModal, setFormErrors, formErrors, handleDeleteEvent, defaultValues, setDefaultValues})
{
  return (
    <Modal title={"Edit Event"} formMethod={"PATCH"} formAction={`/api/events/${event.id}`}
           formSubmitThen={(status: any, response: any) => {handleResponse(status, response)}} isOpen={showEventModal}
           setIsOpen={setShowEventModal} onSubmit={() => {setFormErrors([])}}>
      <div className={"flex"}>
        <DangerButton className={"ml-auto"} onClick={() => (handleDeleteEvent(event))}>Delete</DangerButton>
      </div>
      <ul id={"form_errors"} className={"flex flex-col space-y-2 text-red-400"}>{formErrors.map((error, index) => (
        <li key={index}>
          {error}
        </li>
      ))}</ul>
      <input
        type="datetime-local"
        name="start"
        required={true}
        className="input"
        value={defaultValues.start}
        onChange={(event) => {setDefaultValues({start: moment(event.target.value).format("YYYY-MM-DDTHH:mm:ss"), end: defaultValues.end})}}
      />
      <input
        type="datetime-local"
        name="end"
        required={true}
        className="input w-1/2"
        value={defaultValues.end}
        onChange={(event) => {setDefaultValues({start: defaultValues.start, end: moment(event.target.value).format("YYYY-MM-DDTHH:mm:ss")})}}
      />
      <input required={true} type="text" name="title" placeholder="Title" className="input" maxLength={255} defaultValue={event.title}/>
      <div className={"flex space-x-3 mb-2"}>
        <input type="checkbox" name="all_day" defaultChecked={event.allDay} /> <label htmlFor={"all_day"}>All day</label>
      </div>
    </Modal>
  )
}