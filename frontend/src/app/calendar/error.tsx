'use client'

import PrimaryButton from "@/components/PrimaryButton";

export default function Error({
  error,
  reset,
}: {
    error: Error & { digest?: string }
    reset: () => void
}) {
    return (
        <div>
            <h2>Failed to fetch calendar events!</h2>
            <PrimaryButton onClick={() => reset()}> Try again</PrimaryButton>
        </div>
    )
}