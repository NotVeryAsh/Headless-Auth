'use client'

import Button from "@/components/Button";

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
            <Button onClick={() => reset()}> Try again</Button>
        </div>
    )
}