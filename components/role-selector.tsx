"use client"

import { useState, useEffect } from "react"
import { ChevronDown } from "lucide-react"
import { useRouter } from "next/navigation"

type Role = "public" | "student" | "professor" | "admin"

export default function RoleSelector() {
  const [role, setRole] = useState<Role>("public")
  const [isOpen, setIsOpen] = useState(false)
  const router = useRouter()

  useEffect(() => {
    const storedRole = localStorage.getItem("userRole") as Role | null
    if (storedRole) {
      setRole(storedRole)
    }
  }, [])

  const handleRoleChange = (newRole: Role) => {
    setRole(newRole)
    setIsOpen(false)
    localStorage.setItem("userRole", newRole)

    // Navigate based on role
    if (newRole === "public") {
      router.push("/")
    } else if (newRole === "student") {
      router.push("/student")
    }
  }

  return (
    <div className="relative inline-block text-left">
      <button
        type="button"
        className="inline-flex items-center gap-x-1 text-sm font-medium text-white hover:text-gray-200"
        onClick={() => setIsOpen(!isOpen)}
      >
        <span>View: {role.charAt(0).toUpperCase() + role.slice(1)}</span>
        <ChevronDown className="h-4 w-4" />
      </button>

      {isOpen && (
        <div className="absolute right-0 z-10 mt-2 w-40 origin-top-right rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5">
          <div className="py-1">
            <button
              onClick={() => handleRoleChange("public")}
              className={`block px-4 py-2 text-sm w-full text-left ${
                role === "public" ? "bg-gray-100 text-gray-900" : "text-gray-700"
              }`}
            >
              Public
            </button>
            <button
              onClick={() => handleRoleChange("student")}
              className={`block px-4 py-2 text-sm w-full text-left ${
                role === "student" ? "bg-gray-100 text-gray-900" : "text-gray-700"
              }`}
            >
              Student
            </button>
            <button disabled className="block px-4 py-2 text-sm w-full text-left text-gray-400">
              Professor (Coming Soon)
            </button>
            <button disabled className="block px-4 py-2 text-sm w-full text-left text-gray-400">
              Admin (Coming Soon)
            </button>
          </div>
        </div>
      )}
    </div>
  )
}
