import React from "react";
import Card from "@/components/Card";

export default function Stats() {
    return (
        <Card title={"Stats"}>
            <p>Last Activity: 2 Days ago</p>
            <p>Recent Activities:</p>
            <ul className={"list-disc px-4"}>
                <li>You posted 'How to make an app...' 2 days ago.</li>
                <li>You added Ash as a friend last week.</li>
            </ul>
        </Card>
    );
}