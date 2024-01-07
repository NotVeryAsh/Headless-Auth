'use client'

import {ReactNode, useState} from "react";
import Button from "@/components/Button";
import sendRequest from "@/lib/request";

async function handleSubmit(event: any, method: string, action: string, isSubmitting: any, setIsSubmitting: any, errors, setErrors) {

    setErrors([]);

    // Don't proceed if form is currently submitting
    if(isSubmitting) {
        return;
    }

    // prevent default form functionality running and set form submitting state to true
    event.preventDefault();
    setIsSubmitting(true);

    // get the form from the event
    const form = event.target

    // get all data from form
    const formData = new FormData(form);
    const data = Object.fromEntries(formData);

    const response = await sendRequest(method, action, data);

    setIsSubmitting(false);

    return response;
}

async function loginUser(response: Response | undefined, name: string, errors: any, setErrors: any) {

    setErrors([])

    if(!response) {
        return;
    }

    const data = await response?.json();

    // If response was not successful or has errors
    if(data.errors) {
        const errorElement = document.getElementById(name + "_form_errors");
        if(!errorElement) {
            throw new Error('Form is missing error element');
        }

        let errorMessages = [];

        const errorArrays = Object.values(data.errors)

        errorArrays.map(errorArrays => {
            errorArrays.map(errorMessage => {
                errorMessages.push(errorMessage)
            })
        })

        setErrors([
            ...errors,
            ...errorMessages
        ])

        errorMessages = []
    }
}

function LoginForm({method, action, buttonText, name, children}: {method: string, action: string, buttonText?: string, name: string, children?: ReactNode}) {

    const [isSubmitting, setIsSubmitting] = useState(false)
    const [errors, setErrors] = useState([])

    return (
        <form onSubmit={(event) => {handleSubmit(event, method, action, isSubmitting, setIsSubmitting, errors, setErrors)
            .then((response) => loginUser(response, name, errors, setErrors))}}
              action={action} method={method}>
            <ul id={name + "_form_errors"} className={"flex flex-col space-y-2 text-red-400"}>{errors.map((error, index) => (
                <li key={index}>
                    {error}
                </li>
            ))}</ul>
            {children}
            <Button disabled={isSubmitting}>
                {buttonText ? buttonText : 'Submit'}
            </Button>
        </form>
    )
}

export default LoginForm;