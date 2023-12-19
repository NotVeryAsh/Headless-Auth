'use client'

import {ReactNode, useState} from "react";
import Button from "@/components/Button";
import sendRequest from "@/lib/request";

async function handleSubmit(event, method, action, setIsSubmitting) {

    event.preventDefault();
    setIsSubmitting(true);

    const form = event.target

    // get all data from form
    const formData = new FormData(form);
    const data = Object.fromEntries(formData);

    const response = await sendRequest(method, action, data);

    // TODO Functionality to handle errors, successes, etc.

    setIsSubmitting(false);
}

function Form({method, action, buttonText, children}: {method: string, action: string, buttonText?: string, children?: ReactNode}) {

    const [isSubmitting, setIsSubmitting] = useState(false)

    return (
        <form onSubmit={(event) => {handleSubmit(event, method, action, setIsSubmitting)}} action={action} method={method}>
            {children}
            <Button disabled={isSubmitting}>
                {buttonText}
            </Button>
        </form>
    )
}

export default Form;