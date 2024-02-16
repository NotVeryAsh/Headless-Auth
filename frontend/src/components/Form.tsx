'use client'

import {ReactNode, useState} from "react";
import PrimaryButton from "@/components/PrimaryButton";
import sendRequest from "@/lib/request";

/*

TODO - Form to call handleSubmit when it gets submitted
TODO - This will gather the data and fire off the request
TODO - We will then return the response and call the provided function formSubmitThen
TODO - This function will be a server action

 */

async function handleSubmit(event: any, method: string, action: string, isSubmitting: any, setIsSubmitting: any) {

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

function Form({method, action, buttonText, formSubmitThen, children, className, showSubmitButton=true}: {method: string, action: string, buttonText?: string, formSubmitThen?: any, className?: string, children?: ReactNode, showSubmitButton?: boolean}) {

    const [isSubmitting, setIsSubmitting] = useState(false)

    return (
        <form className={className} onSubmit={(event) => {handleSubmit(event, method, action, isSubmitting, setIsSubmitting).then(
            (response) => {formSubmitThen(response?.status, response?.json())}
        )}} action={action} method={method}>
            {children}
            {showSubmitButton && (
                <PrimaryButton className={"w-full"} disabled={isSubmitting}>
                    {buttonText ? buttonText : 'Submit'}
                </PrimaryButton>
            )}
        </form>
    )
}

export default Form;