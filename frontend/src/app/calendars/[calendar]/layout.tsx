import React from "react";

export default function CalendarLayout(props: {
    children: React.ReactNode
}) {
    return (
        <>
            <div className={"flex flex-col space-x-5"}>
                {props.children}
            </div>
        </>
    );
}