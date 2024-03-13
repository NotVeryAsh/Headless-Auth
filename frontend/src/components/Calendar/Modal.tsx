import React, {ReactNode, useState} from "react";
import PrimaryButton from "@/components/PrimaryButton";
import DangerButton from "@/components/DangerButton";
import Card from "@/components/Card";
import Form from "@/components/Form";

export default function Modal({isOpen=false, onCloseButtonClicked, onConfirmButtonClicked, setIsOpen, title, children, formMethod, formAction, formSubmitThen, submitButtonText, closeButtonText, onSubmit}: { isOpen?: boolean, onCloseButtonClicked?: any, onConfirmButtonClicked?: any, setIsOpen: any, title?: string, children?: React.ReactNode, formMethod: string, formAction?: string, formSubmitThen?: any, submitButtonText?: string, closeButtonText?: string, onSubmit?: any }) {

    return (
      <div className={isOpen ? "fixed inset-0 bg-opacity-50 z-10 bg-gray-500 flex": 'hidden'} >
        <Card title={title} className={"m-auto w-2/12"}>
          {(formAction ?
            <Form method={formMethod} action={formAction} showSubmitButton={false} formSubmitThen={formSubmitThen} onSubmit={onSubmit}>
              {ModalContent(setIsOpen, onCloseButtonClicked, onConfirmButtonClicked, submitButtonText, closeButtonText, children)}
            </Form>
          :
            <>
              {ModalContent(setIsOpen, onCloseButtonClicked, onConfirmButtonClicked, submitButtonText, closeButtonText, children)}
            </>
          )}
        </Card>
      </div>
    );
}

function ModalContent(setIsOpen: any, onCloseButtonClicked?: any, onConfirmButtonClicked?: any, submitButtonText?: string, closeButtonText?: string, children?: ReactNode) {
  return (
    <>
      {children}
      <div className={"flex flex-row space-x-5 my-auto"}>
        <PrimaryButton type={"button"} onClick={(event) => {handleConfirmButtonClicked(setIsOpen); onConfirmButtonClicked();}}>
          {submitButtonText ?? 'Confirm' }
        </PrimaryButton>
        <DangerButton type={"button"} onClick={(event) => {event.preventDefault();  handleCloseButtonClicked(setIsOpen); { onCloseButtonClicked && onCloseButtonClicked() }}}>
          {closeButtonText ?? 'Close' }
        </DangerButton>
      </div>
    </>
  )
}

function handleConfirmButtonClicked(setIsOpen: any)
{
  // setIsOpen(false);
}

function handleCloseButtonClicked(setIsOpen: any)
{
  setIsOpen(false);
}