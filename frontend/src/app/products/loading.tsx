import React from "react";
import {FontAwesomeIcon} from "@fortawesome/react-fontawesome";
import { faSpinner } from '@fortawesome/free-solid-svg-icons'

export default function ProductsLoadingPage() {
    return (
        <FontAwesomeIcon icon={faSpinner} spin className={" text-4xl "} />
    );
}