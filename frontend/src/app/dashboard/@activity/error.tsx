'use client'

export default function Error({
                                  error,
                                  reset,
                              }: {
    error: Error & { digest?: string }
    reset: () => void
}) {
    return (
        <div>
            <h2>Failed to fetch activity!</h2>
            <PrimaryButton onClick={() => reset()}>Try again</PrimaryButton>
        </div>
    )
}