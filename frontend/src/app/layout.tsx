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
      <body className={montserrat.className + " flex flex-col h-screen p-14 text-zinc-800"}>
          <div className={"flex flex-row w-full"}>
            <BackButton />
            <Logout/>
          </div>
          <main className="flex h-full">
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
