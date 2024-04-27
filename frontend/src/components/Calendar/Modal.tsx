import React, {ReactNode, useState} from "react";
import PrimaryButton from "@/components/PrimaryButton";
import DangerButton from "@/components/DangerButton";
import Card from "@/components/Card";
import Form from "@/components/Form";

export default function Modal({isOpen=false, onCloseButtonClicked, onConfirmButtonClicked, setIsOpen, title, children, formMethod, formAction, formSubmitThen, submitButtonText, closeButtonText, onSubmit, submitButtonType="button", closeButtonType="button"}: { isOpen?: boolean, onCloseButtonClicked?: any, onConfirmButtonClicked?: any, setIsOpen: any, title?: string, children?: React.ReactNode, formMethod: string, formAction?: string, formSubmitThen?: any, submitButtonText?: string, closeButtonText?: string, onSubmit?: any, submitButtonType?: string, closeButtonType?: string }) {
  return (
      <div className={isOpen ? "fixed inset-0 bg-opacity-50 z-10 bg-gray-500 flex": 'hidden'} >
        <Card title={title} className={"m-auto w-10/12 md:w-2/12"}>
          {(formAction ?
            <Form method={formMethod} action={formAction} showSubmitButton={false} formSubmitThen={formSubmitThen} onSubmit={onSubmit}>
              {ModalContent(setIsOpen, onCloseButtonClicked, onConfirmButtonClicked, submitButtonText, closeButtonText, children, submitButtonType, closeButtonType)}
            </Form>
          :
            <>
              {ModalContent(setIsOpen, onCloseButtonClicked, onConfirmButtonClicked, submitButtonText, closeButtonText, children, submitButtonType, closeButtonType)}
            </>
          )}
        </Card>
      </div>
    );
}

function ModalContent(setIsOpen: any, onCloseButtonClicked?: any, onConfirmButtonClicked?: any, submitButtonText?: string, closeButtonText?: string, children?: ReactNode, submitButtonType="button", closeButtonType="button") {
  return (
    <>
      {children}
      <div className={"flex flex-row space-x-5 my-auto"}>
        <PrimaryButton type={submitButtonType} onClick={(event) => {handleConfirmButtonClicked(setIsOpen, onConfirmButtonClicked)}}>
          {submitButtonText ?? 'Confirm' }
        </PrimaryButton>
        <DangerButton type={closeButtonType} onClick={(event) => {handleCloseButtonClicked(setIsOpen, onCloseButtonClicked)}}>
          {closeButtonText ?? 'Close' }
        </DangerButton>
      </div>
    </>
  )
}

function handleConfirmButtonClicked(setIsOpen: any, onConfirmButtonClicked?: any)
{
  if(onConfirmButtonClicked) {
    onConfirmButtonClicked().then(
      setIsOpen(false)
    )
    return
  }
  setIsOpen(false);
}

function handleCloseButtonClicked(setIsOpen: any, onCloseButtonClicked?:any)
{
  if(onCloseButtonClicked) {
    onCloseButtonClicked().then(
      setIsOpen(false)
    )
    return
  }
  setIsOpen(false);
}