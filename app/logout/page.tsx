"use client"

import { useEffect } from "react"
import { useRouter } from "next/navigation"

export default function LogoutPage() {
  const router = useRouter()

  useEffect(() => {
    // Clear user data
    localStorage.removeItem("userRole")
    localStorage.removeItem("isLoggedIn")

    // Redirect to home page
    router.push("/")
  }, [router])

  return (
    <div className="min-h-screen flex items-center justify-center">
      <p>Logging out...</p>
    </div>
  )
}
