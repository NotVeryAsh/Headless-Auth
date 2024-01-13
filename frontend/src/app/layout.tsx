import type { Metadata } from 'next'
import { Montserrat } from 'next/font/google'
import './globals.css'
import React from "react";
import BackButton from "@/components/BackButton";

const montserrat = Montserrat({ subsets: ['latin'] })

export const metadata: Metadata = {
  title: 'Headless Auth',
  description: 'A simple headless authentication app',
}

export default function RootLayout(props: {
  children: React.ReactNode,
}) {
  return (
    <html lang="en">
      <body className={montserrat.className + " flex min-h-screen flex-col items-center justify-between p-24 bg-slate-100"}>
        <main className="flex flex-col items-center justify-center space-y-10 w-full">
          <BackButton></BackButton>
          {props.children}
        </main>
      </body>
    </html>
  )
}
