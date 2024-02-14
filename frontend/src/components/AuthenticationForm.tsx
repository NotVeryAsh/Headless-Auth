'use client'

import {Dispatch, ReactNode, SetStateAction, useState} from "react";
import Button from "@/components/Button";
import sendRequest from "@/lib/request";
import storeAuthToken, {redirectTo} from "@/lib/auth";

async function handleSubmit(event: any, method: string, action: string, isSubmitting: any, setIsSubmitting: any, errors: any, setErrors: any, name: string) {

    setErrors([])

    // Don't proceed if form is currently submitting
    if(isSubmitting) {
        return;
    }

    // prevent default form functionality running and set form submitting state to true
    event.preventDefault();
    setIsSubmitting(true);

    const form = event.target

    // get all data from form
    const formData = new FormData(form);
    const data = Object.fromEntries(formData);

    const response = await sendRequest(method, action, data);

    // Allow the form to be submitted again
    setIsSubmitting(false);

    return loginUser(response, name, errors, setErrors);
}

async function loginUser(response: Response | undefined, name: string, errors: any, setErrors: any) {

    setErrors([])

    if(!response) {
        return;
    }

    // Get the data from the response
    const data = await response?.json();

    if(data.errors) {

        // Get the element in which errors will be displayed
        const errorElement = document.getElementById(name + "_form_errors");
        if(!errorElement) {
            throw new Error('Form is missing error element');
        }

        // Push all of the errors into the array and set them in the errors variable

        let errorMessages: string[]  = [];

        const errorArrays: any[] = Object.values(data.errors)

        errorArrays.map(function(errorArrays: any[]) {
            errorArrays.map(function (errorMessage: string) {
                return errorMessages.push(errorMessage);
            })
        })

        setErrors([
            ...errorMessages
        ])

        errorMessages = []

        return;
    }

    if(response.status >= 200 && response.status <= 299) {

        await storeAuthToken(data.token)

        await redirectTo('calendars')
    }
}

function AuthenticationForm({method, action, name="login", buttonText, children}: {method: string, action: string, name?: string, buttonText?: string, children?: ReactNode}) {

    const [isSubmitting, setIsSubmitting] = useState(false)
    const [errors, setErrors] = useState([])

    return (
        <form className={"flex flex-col w-full text-center"} onSubmit={(event) => {handleSubmit(event, method, action, isSubmitting, setIsSubmitting, errors, setErrors, name)}}
              action={action} method={method}>
            <ul id={name + "_form_errors"} className={"flex flex-col space-y-2 text-red-400"}>{errors.map((error, index) => (
                <li key={index}>
                    {error}
                </li>
            ))}</ul>
            <div className={"w-2/12 mx-auto"}>
                {children}
                <Button disabled={isSubmitting} classNames={"w-full my-2"}>
                    {buttonText ? buttonText : 'Submit'}
                </Button>
            </div>
        </form>
    )
}

export default AuthenticationForm;