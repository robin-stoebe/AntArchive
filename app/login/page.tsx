"use client"

import { useRouter } from "next/navigation"
import Link from "next/link"
import { ArrowLeft } from "lucide-react"

export default function LoginPage() {
  const router = useRouter()

  const handleRoleSelect = (role: "professor" | "admin") => {
    // Set user role in localStorage
    localStorage.setItem("userRole", role)
    localStorage.setItem("isLoggedIn", "true")

    // Redirect based on role
    if (role === "professor") {
      router.push("/professor")
    } else if (role === "admin") {
      router.push("/admin")
    }
  }

  return (
    <div className="min-h-screen flex flex-col bg-gray-50">
      {/* Back to public view button */}
      <div className="absolute top-4 left-4">
        <Link
          href="/"
          className="flex items-center px-4 py-2 bg-white rounded-md shadow-sm text-[#0064A4] hover:bg-gray-100 transition-colors"
        >
          <ArrowLeft className="h-4 w-4 mr-2" />
          Return to public view
        </Link>
      </div>

      {/* UCI Logo */}
      <div className="flex justify-center pt-16 pb-8">
        <div className="text-center">
          <div className="flex items-center justify-center">
            <span className="text-[#0064A4] text-6xl font-bold">UCI</span>
            <div className="ml-4 text-left">
              <div className="text-[#0064A4] text-lg font-medium">University of</div>
              <div className="text-[#0064A4] text-lg font-medium">California, Irvine</div>
            </div>
          </div>
        </div>
      </div>

      {/* Login Card */}
      <div className="w-full max-w-md mx-auto">
        <div className="bg-white rounded-md shadow-lg p-8">
          <h2 className="text-2xl font-medium text-center text-gray-800 mb-6">Staff Login</h2>
          <p className="text-center text-gray-600 mb-8">Select your role to continue</p>

          <div className="space-y-4">
            <button
              onClick={() => handleRoleSelect("professor")}
              className="w-full py-3 px-4 bg-[#4b84c7] text-white font-medium rounded-md hover:bg-[#3b6ba0] transition-colors flex items-center justify-center"
            >
              Login as Professor
            </button>

            <button
              onClick={() => handleRoleSelect("admin")}
              className="w-full py-3 px-4 bg-[#FFD200] text-[#0064A4] font-medium rounded-md hover:bg-[#FFDA33] transition-colors flex items-center justify-center"
            >
              Login as Admin
            </button>
          </div>

          <div className="mt-8 text-center text-sm text-gray-500">
            This is a demonstration version with simplified login.
            <br />
            No credentials required.
          </div>
        </div>
      </div>

      {/* Footer */}
      <div className="mt-auto py-6 text-center text-sm">
        <Link href="#" className="text-[#0064A4] hover:underline">
          Privacy Policy
        </Link>
        {" • "}
        <Link href="#" className="text-[#0064A4] hover:underline">
          OIT
        </Link>
      </div>
    </div>
  )
}
