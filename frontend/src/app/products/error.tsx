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
            <h2>Failed to fetch products!</h2>
            <button onClick={() => reset()}>Try again</button>
        </div>
    )
}