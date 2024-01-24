import type { Metadata } from 'next'
import { Montserrat } from 'next/font/google'
import './globals.css'
import React from "react";
import BackButton from "@/components/BackButton";
import LogoutButton from "@/components/LogoutButton";
import {getAuthToken} from "@/lib/auth";
import { config } from '@fortawesome/fontawesome-svg-core'
import '@fortawesome/fontawesome-svg-core/styles.css'
config.autoAddCss = false

const montserrat = Montserrat({ subsets: ['latin'] })

export const metadata: Metadata = {
  title: 'Headless Auth',
  description: 'A simple headless authentication app',
}

export default async function RootLayout(props: {
  children: React.ReactNode,
}) {

  return (
    <html lang="en">
      <body className={montserrat.className + " flex min-h-screen flex-col items-center justify-between px-24 mt-10 bg-slate-100"}>
        <main className="flex flex-col items-center justify-center space-y-10 w-full">
          <div className={"flex flex-row w-full"}>
            <BackButton />
            <Logout/>
          </div>
          {props.children}
        </main>
      </body>
    </html>
  )
}

async function Logout() {

  const authenticated = await getAuthToken() !== undefined

  if(!authenticated) {
    return null
  }

  return <LogoutButton />
}
